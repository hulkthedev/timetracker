<?php

namespace Tracking\Dtos;

use Tracking\Repository\WorkingModes;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class HolidayWorkingDayDtoStub extends WorkingDayDto
{
    public function __construct()
    {
        $this->id = random_int(0, 1000);
        $this->weekday = 'Thursday';
        $this->date = '14.06.2018';
        $this->workingStart = '00:00';
        $this->workingEnd = '00:00';
        $this->workingMode = WorkingModes::WORKING_MODE_HOLIDAY;
        $this->timeDifference = '00:00';
        $this->timeDifferenceIsBalanced = 0;
    }
}
