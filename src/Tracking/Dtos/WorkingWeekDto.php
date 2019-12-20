<?php

namespace Tracking\Dtos;

/**
 * @codeCoverageIgnore
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
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
