<?php

namespace Tracking\Dtos;

/**
 * @codeCoverageIgnore
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class OvertimeDto
{
    /** @var int */
    public $id;

    /** @var int */
    public $workingDayId;

    /** @var string */
    public $overtime;
}
