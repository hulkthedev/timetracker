<?php

namespace Tracking\Services;

use Tracking\Dtos\WorkingWeekDto;
use Tracking\Repository\WorkingModes;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class AnalyticsCalculationService
{
    /** @var WorkingWeekDto[] */
    private $dtoList;

    /**
     * @param WorkingWeekDto[] $dtoList
     */
    public function __construct(array $dtoList)
    {
        $this->dtoList = $dtoList;
    }

    /**
     * @return int
     */
    public function countWorkingDays(): int
    {
        return $this->count(WorkingModes::WORKING_MODE_DEFAULT);
    }

    /**
     * @return int
     */
    public function countSickDays(): int
    {
        return $this->count(WorkingModes::WORKING_MODE_SICK);
    }

    /**
     * @return int
     */
    public function countVacationDays(): int
    {
        return $this->count(WorkingModes::WORKING_MODE_VACATION);
    }

    /**
     * @return int
     */
    public function countOvertimeDays(): int
    {
        return $this->count(WorkingModes::WORKING_MODE_OVERTIME);
    }

    /**
     * @return int
     */
    public function countHolidays(): int
    {
        return $this->count(WorkingModes::WORKING_MODE_HOLIDAY);
    }

    /**
     * @param int $criteria
     * @return int
     */
    private function count(int $criteria): int
    {
        $counter = 0;

        foreach ($this->dtoList as $workingWeekDto) {
            foreach ($workingWeekDto->workingDays as $workingDayDto) {
                if ($workingDayDto->workingMode === $criteria) {
                    $counter++;
                }
            }
        }

        return $counter;
    }
}
