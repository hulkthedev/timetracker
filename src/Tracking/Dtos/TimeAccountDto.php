<?php

namespace Tracking\Dtos;

/**
 * @codeCoverageIgnore
 * @author  <fatal.error.27@gmail.com>
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
