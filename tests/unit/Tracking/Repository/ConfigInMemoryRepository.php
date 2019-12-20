<?php

namespace Tracking\Repository;

use Tracking\Dtos\ConfigDto;
use Tracking\Dtos\ConfigDtoStub;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class ConfigInMemoryRepository extends Spy implements ConfigRepository
{
    /**
     * @inheritdoc
     */
    public function getAll(): ConfigDto
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        return new ConfigDtoStub();
    }

    /**
     * @inheritdoc
     */
    public function update(array $data): ConfigDto
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        return new ConfigDtoStub();
    }
}
