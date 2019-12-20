<?php

namespace Tracking\Repository;

use Tracking\Dtos\TimeAccountDto;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
interface TimeAccountRepository
{
    /**
     * @return TimeAccountDto
     */
    public function getAll(): TimeAccountDto;

    /**
     * @param array $data
     * @param int   $id
     *
     * @return TimeAccountDto
     */
    public function add(array $data, int $id): TimeAccountDto;
}
