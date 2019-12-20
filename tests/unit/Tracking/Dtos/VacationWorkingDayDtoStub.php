<?php

namespace Tracking\Dtos;

use Tracking\Repository\WorkingModes;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class VacationWorkingDayDtoStub extends WorkingDayDto
{
    public function __construct()
    {
        $this->id = random_int(0, 1000);
        $this->weekday = 'Friday';
        $this->date = '15.06.2018';
        $this->workingStart = '00:00';
        $this->workingEnd = '00:00';
        $this->workingMode = WorkingModes::WORKING_MODE_VACATION;
        $this->timeDifference = '00:00';
        $this->timeDifferenceIsBalanced = 0;
    }
}
