<?php

namespace Tracking\Repository;

use Tracking\Dtos\WorkingDayDto;
use Tracking\Dtos\WorkingWeekDto;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
interface WorkingTimeRepository
{
    /**
     * @return WorkingWeekDto[]
     */
    public function getWorkingTimeList(): array;

    /**
     * @param array $day
     *
     * @return WorkingWeekDto[]
     */
    public function startWorking(array $day): array;

    /**
     * @param array $day
     *
     * @return WorkingWeekDto[]
     */
    public function endWorking(array $day): array;

    /**
     * @param array $days
     * @param int   $mode
     *
     * @return WorkingWeekDto[]
     */
    public function setNonWorkingNote(array $days, int $mode): array;

    /**
     * @param string $date
     *
     * @return WorkingDayDto
     */
    public function getWorkingDayByDate(string $date): WorkingDayDto;

    /**
     * @param array $day
     *
     * @return array
     */
    public function updateWorkingDayByDate(array $day): array;

    /**
     * @param array $day
     */
    public function updateTimeDifferenceByDate(array $day): void;
}
