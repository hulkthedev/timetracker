<?php

namespace Tracking\Dtos;

/**
 * @property string BREAKING_TIME_PER_DAY_1
 * @property string BREAKING_TIME_PER_DAY_2
 * @property string WORKING_TIME_PER_DAY
 * @property int    VACATION_DAYS_PER_YEAR
 * @property string TIME_ACCOUNT_BALANCE
 * @property int    TIME_ACCOUNT_BALANCE_IS_BALANCED
 *
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class ConfigDto
{
    /** @var array */
    public $data = [];

    /**
     * @param string    $key
     * @param mixed     $value
     */
    public function __set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function __get(string $key): ?string
    {
        return $this->data[$key] ?? null;
    }
}
