<?php

namespace Tracking\Repository;

use Tracking\Dtos\ConfigDto;
use Tracking\Dtos\ConfigDtoStub;

/**
 * @author  <fatal.error.27@gmail.com>
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
