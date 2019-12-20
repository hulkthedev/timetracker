<?php

namespace Tracking\Repository;

use Tracking\Dtos\ConfigDto;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
interface ConfigRepository
{
    /**
     * @return ConfigDto
     */
    public function getAll(): ConfigDto;

    /**
     * @param array $data
     *
     * @return ConfigDto
     */
    public function update(array $data): ConfigDto;
}
