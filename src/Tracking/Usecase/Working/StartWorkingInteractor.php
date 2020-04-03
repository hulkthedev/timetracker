<?php

namespace Tracking\Usecase\Working;

use DateTime;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\WorkingModes;
use Tracking\Usecase\ResultCodes;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class StartWorkingInteractor extends WorkingBasic
{
    /**
     * @param Request $request
     * @return WorkingBasicResponse
     */
    public function execute(Request $request): WorkingBasicResponse
    {
        try {
            $this->validateRequest($request);

            if ((int)$request->get('workingMode') === WorkingModes::WORKING_MODE_DEFAULT) {
                $list = $this->startWorking($request);
            } else {
                $list = $this->startNonWorking($request);
            }

            return $this->getSuccessfullyResponse($list);
        } catch (InvalidArgumentException $exception) {
            return new WorkingBasicResponse(ResultCodes::CODE_ERROR_INVALID_ARGUMENTS);
        } catch (\PDOException $exception) {
            return new WorkingBasicResponse(ResultCodes::CODE_ERROR_DATABASE_DOUBLE_ENTRY);
        } catch (Exception $exception) {
            return new WorkingBasicResponse($exception->getCode());
        }
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     */
    private function validateRequest(Request $request): void
    {
        if ((int)$request->get('workingMode') === WorkingModes::WORKING_MODE_DEFAULT) {
            $this->validateDateTimeRequest($request);
        } else {
            $this->validateDateOnlyRequest($request);
        }
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     */
    private function validateDateTimeRequest(Request $request): void
    {
        if (null == $request->get('workingDate') || null == $request->get('workingTime')) {
            throw new InvalidArgumentException('Required parameters are missing');
        }
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     */
    private function validateDateOnlyRequest(Request $request): void
    {
        if (null == $request->get('from') || null == $request->get('to')) {
            throw new InvalidArgumentException('Required parameters are missing');
        }
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    private function startNonWorking(Request $request): array
    {
        $mode = (int)$request->get('workingMode');
        $days = $this->getRangeOfDays($request->get('from'), $request->get('to'));

        return $this->workingTimeRepository->setNonWorkingNote($days, $mode);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function startWorking(Request $request): array
    {
        $dateTime = $request->get('workingDate') . $request->get('workingTime');
        $date = new DateTime($dateTime);

        return $this->workingTimeRepository->startWorking([
            'weekNr'        => (int)$date->format('W'),
            'weekday'       => $date->format('l'),
            'date'          => $date->format(self::DEFAULT_DATE_FORMAT),
            'workingStart'  => $date->format(self::DEFAULT_TIME_FORMAT)
        ]);
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     * @throws Exception
     */
    private function getRangeOfDays(string $from, string $to): array
    {
        $toDate = new DateTime($to);
        $toDate = $toDate->modify('+1 day');

        $range = new \DatePeriod(new DateTime($from), new \DateInterval('P1D'), $toDate);
        $days = [];

        /** @var $date DateTime */
        foreach($range as $date){
            $days[] = [
                'weekNr'    => (int)$date->format('W'),
                'weekday'   => $date->format('l'),
                'date'      => $date->format(self::DEFAULT_DATE_FORMAT)
            ];
        }

        return $days;
    }
}
