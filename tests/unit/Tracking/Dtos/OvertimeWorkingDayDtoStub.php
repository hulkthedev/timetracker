<?php

namespace Tracking\Dtos;

use Tracking\Repository\WorkingModes;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class OvertimeWorkingDayDtoStub extends WorkingDayDto
{
    public function __construct()
    {
        $this->id = random_int(0, 1000);
        $this->weekday = 'Wednesday';
        $this->date = '13.06.2018';
        $this->workingStart = '00:00';
        $this->workingEnd = '00:00';
        $this->workingMode = WorkingModes::WORKING_MODE_OVERTIME;
        $this->timeDifference = '00:00';
        $this->timeDifferenceIsBalanced = 0;
    }
}
