<?php

namespace Tracking\Usecase\Working;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class WorkingBasicResponse
{
    /** @var int */
    public $code;

    /** @var array */
    public $list = [];

    /** @var array */
    public $statistics = [];

    /**
     * @param int $code
     */
    public function __construct(int $code)
    {
        $this->code = $code;
    }

    /**
     * @param array $list
     *
     * @return WorkingBasicResponse
     */
    public function setList(array $list): WorkingBasicResponse
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @param string        $key
     * @param int|string    $value
     *
     * @return WorkingBasicResponse
     */
    public function addToStatistics(string $key, $value): WorkingBasicResponse
    {
        $this->statistics[$key] = $value;
        return $this;
    }
}
