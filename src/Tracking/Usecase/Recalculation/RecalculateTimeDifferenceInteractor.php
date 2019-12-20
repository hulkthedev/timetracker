<?php

namespace Tracking\Usecase\Recalculation;

use Tracking\Dtos\WorkingDayDto;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\WorkingModes;
use Tracking\Repository\WorkingTimeRepository;
use Tracking\Services\TimeDifferenceCalculationService;
use Tracking\Usecase\ResultCodes;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class RecalculateTimeDifferenceInteractor
{
    /** @var WorkingTimeRepository */
    private $workingTimeRepository;

    /** @var ConfigRepository */
    private $configRepository;

    /**
     * @param WorkingTimeRepository $workingTimeRepository
     * @param ConfigRepository      $configRepository
     */
    public function __construct(WorkingTimeRepository $workingTimeRepository, ConfigRepository $configRepository)
    {
        $this->workingTimeRepository = $workingTimeRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @return RecalculationBasicResponse
     */
    public function execute(): RecalculationBasicResponse
    {
        try {
            $config = $this->configRepository->getAll();
            $timeCalculationService = new TimeDifferenceCalculationService($config);

            foreach ($this->workingTimeRepository->getWorkingTimeList() as $workingWeekDto) {
                foreach ($workingWeekDto->workingDays as $workingDayDto) {
                    if ($this->isAllowed($workingDayDto)) {
                        $result = $timeCalculationService->calculate(
                            $workingDayDto->date,
                            $workingDayDto->workingStart,
                            $workingDayDto->workingEnd
                        );

                        $this->workingTimeRepository->updateTimeDifferenceByDate($result);
                    }
                }
            }

            return new RecalculationBasicResponse(ResultCodes::CODE_SUCCESS);
        } catch (\PDOException $exception) {
            return new RecalculationBasicResponse(ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND);
        } catch (\Exception $exception) {
            return new RecalculationBasicResponse(ResultCodes::CODE_ERROR_CAN_NOT_RECALCULATION_TIME_DIFFERENCE);
        }
    }

    /**
     * @param WorkingDayDto $workingDay
     *
     * @return bool
     */
    private function isAllowed(WorkingDayDto $workingDay): bool
    {
        return WorkingModes::WORKING_MODE_DEFAULT === $workingDay->workingMode;
    }
}
