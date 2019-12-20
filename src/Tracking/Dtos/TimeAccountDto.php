<?php

namespace Tracking\Dtos;

/**
 * @codeCoverageIgnore
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class TimeAccountDto
{
    /** @var string */
    public $overtimeLeft;

    /** @var int */
    public $overtimeIsBalanced;

    /** @var OvertimeDto[] */
    public $overtimeDto = [];
}
