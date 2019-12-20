<?php

namespace Tracking\Dtos;

/**
 * @codeCoverageIgnore
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class WorkingDayDto
{
    /** @var int */
    public $id;

    /** @var string */
    public $weekday;

    /** @var string */
    public $date;

    /** @var string */
    public $workingStart;

    /** @var string */
    public $workingEnd;

    /** @var int */
    public $workingMode;

    /** @var string */
    public $timeDifference;

    /** @var int */
    public $timeDifferenceIsBalanced;
}
