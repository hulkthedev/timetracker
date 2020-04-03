<?php
namespace Tracking\Usecase\TimeAccount;

use DateTime;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\TimeAccountRepository;
use Tracking\Repository\WorkingModes;
use Tracking\Repository\WorkingTimeRepository;
use Tracking\Services\TimeCalculationService;
use Tracking\Usecase\ResultCodes;
use Tracking\Usecase\Working\WorkingBasic;

/**
 * @author <fatal.error.27@gmail.com>
 */
class AddOverTimeInteractor
{
    const OVERTIME_VALUE_MANUAL = 'manual';
    const OVERTIME_VALUE_FULL = 'full';
    const OVERTIME_VALUE_HALF = 'half';

    /** @var TimeAccountRepository */
    private $timeAccountRepository;

    /** @var ConfigRepository */
    private $configRepository;

    /** @var WorkingTimeRepository */
    private $workingTimeRepository;

    /**
     * @param TimeAccountRepository $timeAccountRepository
     * @param ConfigRepository $configRepository
     * @param WorkingTimeRepository $workingTimeRepository
     */
    public function __construct(
        TimeAccountRepository $timeAccountRepository,
        ConfigRepository $configRepository,
        WorkingTimeRepository $workingTimeRepository
    ) {
        $this->timeAccountRepository = $timeAccountRepository;
        $this->configRepository = $configRepository;
        $this->workingTimeRepository = $workingTimeRepository;
    }

    /**
     * @param Request $request
     * @return TimeAccountBasicResponse
     */
    public function execute(Request $request): TimeAccountBasicResponse
    {
        try {
            $this->validateRequest($request);

            $day = $this->getParamsFromRequest($request);
            $list = $this->workingTimeRepository->setNonWorkingNote([$day], WorkingModes::WORKING_MODE_OVERTIME);
            $timeAccountDto = $this->timeAccountRepository->add($day, $this->getDatabaseIdFromList($list));

            return new TimeAccountBasicResponse(ResultCodes::CODE_SUCCESS, $timeAccountDto);
        } catch (InvalidArgumentException $exception) {
            return new TimeAccountBasicResponse(ResultCodes::CODE_ERROR_INVALID_ARGUMENTS);
        } catch (\PDOException $exception) {
            return new TimeAccountBasicResponse(ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND);
        } catch (\Exception $exception) {
            return new TimeAccountBasicResponse($exception->getCode());
        }
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     */
    private function validateRequest(Request $request): void
    {
        if (null == $request->get('overtimeValue')) {
            throw new InvalidArgumentException('Required parameters are missing');
        }

        if (self::OVERTIME_VALUE_MANUAL === $request->get('overtimeValue')) {
            if (null == $request->get('overtimeTime') || null == $request->get('overtimeDate') ) {
                throw new InvalidArgumentException('Required parameters are missing');
            }
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getParamsFromRequest(Request $request): array
    {
        $config = $this->configRepository->getAll();
        $date = new DateTime();

        switch ($request->get('overtimeValue')) {
            case self::OVERTIME_VALUE_MANUAL:
                $date = new DateTime($request->get('overtimeDate'));
                $overtime = $request->get('overtimeTime');
                break;
            case self::OVERTIME_VALUE_HALF:
                $overtime = $this->calculateHalfWorkingTimeBasedOnDefaultWorkingTimePerDay($config->WORKING_TIME_PER_DAY);
                break;
            case self::OVERTIME_VALUE_FULL:
            default:
                $overtime = $config->WORKING_TIME_PER_DAY;
        }

        return [
            'overtime' => $overtime,
            'weekNr' => (int)$date->format('W'),
            'weekday' => $date->format('l'),
            'date' => '21.05.2018',
//            'date' => $date->format(WorkingBasic::DEFAULT_DATE_FORMAT)
        ];
    }

    /**
     * @param string $fullWorkingTimePerDay
     * @return string
     */
    private function calculateHalfWorkingTimeBasedOnDefaultWorkingTimePerDay(string $fullWorkingTimePerDay): string
    {
        $timeCalculationService = new TimeCalculationService();

        $workingTimePerDayInMinutes = $timeCalculationService->getMinutesFromTime($fullWorkingTimePerDay);
        $halfWorkingTimePerDayInMinutes = $workingTimePerDayInMinutes / 2;

        return $timeCalculationService->getTimeFromMinutes($halfWorkingTimePerDayInMinutes);
    }

    /**
     * @param array $list
     * @return int
     */
    private function getDatabaseIdFromList(array $list): int
    {
        $week = end($list);
        return end($week->workingDays)->id;
    }
}
