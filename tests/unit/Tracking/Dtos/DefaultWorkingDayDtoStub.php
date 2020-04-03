<?php

namespace Tracking\Dtos;

use Tracking\Repository\WorkingModes;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class DefaultWorkingDayDtoStub extends WorkingDayDto
{
    public function __construct()
    {
        $this->id = random_int(0, 1000);
        $this->weekday = 'Monday';
        $this->date = '11.06.2018';
        $this->workingStart = '10:00';
        $this->workingEnd = '18:00';
        $this->workingMode = WorkingModes::WORKING_MODE_DEFAULT;
        $this->timeDifference = '00:00';
        $this->timeDifferenceIsBalanced = 0;
    }
}
