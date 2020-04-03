<?php

namespace Tracking\Dtos;

/**
 * @codeCoverageIgnore
 * @author  <fatal.error.27@gmail.com>
 */
class WorkingWeekDto
{
    /** @var int */
    public $weekNr;

    /** @var string */
    public $difference;

    /** @var int */
    public $differenceIsBalanced;

    /** @var WorkingDayDto[] */
    public $workingDays = [];
}
