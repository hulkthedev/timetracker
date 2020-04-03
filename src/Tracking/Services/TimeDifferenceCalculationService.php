<?php

namespace Tracking\Services;

use DateTime;
use Tracking\Dtos\ConfigDto;
use Tracking\Usecase\Working\WorkingBasic;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class TimeDifferenceCalculationService
{
    const WORKING_DAY_WITHOUT_BREAK_TIME_IN_HOURS = 6;

    /** @var int */
    private $workingTimePerDayInMinutes;

    /** @var int */
    private $brakingTimePerDayInMinutes1;

    /** @var int */
    private $brakingTimePerDayInMinutes2;

    /**
     * @param ConfigDto $config
     */
    public function __construct(ConfigDto $config)
    {
        $this->workingTimePerDayInMinutes = $this->getMinutesFromTime($config->WORKING_TIME_PER_DAY);
        $this->brakingTimePerDayInMinutes1 = $this->getMinutesFromTime($config->BREAKING_TIME_PER_DAY_1);
        $this->brakingTimePerDayInMinutes2 = $this->getMinutesFromTime($config->BREAKING_TIME_PER_DAY_2);
    }

    /**
     * @param string $date
     * @param string $startTime
     * @param string $endTime
     * @return array
     */
    public function calculate(string $date, string $startTime, string $endTime): array
    {
        $workingStartTime = new DateTime($date . $startTime);
        $workingEndTime = new DateTime($date . $endTime);

        $isWorkingTimeInMinutes = abs($workingStartTime->getTimestamp() - $workingEndTime->getTimestamp()) / 60;

        /**
         * if short day, calculate with short break time
         */
        if ($this->isShortWorkingDay($isWorkingTimeInMinutes)) {
            $brakingTimePerDayInMinutes = $this->brakingTimePerDayInMinutes2;
        } else {
            $brakingTimePerDayInMinutes = $this->brakingTimePerDayInMinutes1;
        }

        $shouldWorkingTimeInMinutes = $this->workingTimePerDayInMinutes + $brakingTimePerDayInMinutes;
        $differenceInMinutes = $isWorkingTimeInMinutes - $shouldWorkingTimeInMinutes;

        if ($differenceInMinutes < 0) {
            $differenceInMinutes *= -1;
        }

        $isBalanced = $isWorkingTimeInMinutes > $shouldWorkingTimeInMinutes ? 1 : -1;
        if ($differenceInMinutes === 0) {
            $isBalanced = 0;
        }

        return [
            'date' => $workingEndTime->format(WorkingBasic::DEFAULT_DATE_FORMAT),
            'endTime' => $workingEndTime->format(WorkingBasic::DEFAULT_TIME_FORMAT),
            'startTime' => $workingStartTime->format(WorkingBasic::DEFAULT_TIME_FORMAT),
            'difference' => date('H:i', mktime(0, $differenceInMinutes)),
            'isBalanced' => $isBalanced
        ];
    }

    /**
     * @param string $time
     * @return int
     */
    private function getMinutesFromTime(string $time): int
    {
        $timeCalculationService = new TimeCalculationService();
        $timeCalculationService->add($time);

        return $timeCalculationService->getMinutes();
    }

    /**
     * @param int $workingTimeInMinutes
     * @return bool
     */
    private function isShortWorkingDay(int $workingTimeInMinutes): bool
    {
        $shortWorkingTimeInMinutes = self::WORKING_DAY_WITHOUT_BREAK_TIME_IN_HOURS * TimeCalculationService::MINUTES_PER_HOUR;
        return $workingTimeInMinutes <= $shortWorkingTimeInMinutes;
    }
}
