<?php

namespace Tracking\Repository;

use Tracking\Dtos\ConfigDto;

/**
 * @author  <fatal.error.27@gmail.com>
 */
interface ConfigRepository
{
    /**
     * @return ConfigDto
     */
    public function getAll(): ConfigDto;

    /**
     * @param array $data
     * @return ConfigDto
     */
    public function update(array $data): ConfigDto;
}
