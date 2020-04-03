<?php

namespace Tracking\Dtos;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class ConfigDtoStub extends ConfigDto
{
    public function __construct()
    {
        $this->BREAKING_TIME_PER_DAY_1 = '00:30';
        $this->BREAKING_TIME_PER_DAY_2 = '00:00';
        $this->WORKING_TIME_PER_DAY = '08:00';
        $this->VACATION_DAYS_PER_YEAR = 30;
        $this->TIME_ACCOUNT_BALANCE = '00:00';
        $this->TIME_ACCOUNT_BALANCE_IS_BALANCED = 0;
    }
}
