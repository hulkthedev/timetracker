<?php

namespace Tracking\Usecase\Config;

use Tracking\Dtos\ConfigDto;

/**
 * @author <fatal.error.27@gmail.com>
 */
class ConfigBasicResponse
{
    /** @var int */
    public $code;

    /** @var ConfigDto */
    public $config;

    /** @var array */
    public $workingModes;

    /**
     * @param int $code
     * @param ConfigDto|null $dto
     */
    public function __construct(int $code, ConfigDto $dto = null)
    {
        $this->code = $code;
        $this->config = $dto->data ?? new ConfigDto();
        $this->workingModes = [];
    }

    /**
     * @param array $modes
     */
    public function setWorkingModes(array $modes): void
    {
        $this->workingModes = $modes;
    }
}
