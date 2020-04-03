<?php

namespace Tracking\Usecase\Config;

use ReflectionException;
use Tracking\Dtos\ConfigDto;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\WorkingModes;
use Tracking\Usecase\ResultCodes;

/**
 * @author <fatal.error.27@gmail.com>
 */
class ConfigBasic
{
    /** @var ConfigRepository */
    protected $configRepository;

    /**
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @param ConfigDto $config
     * @return ConfigBasicResponse
     * @throws ReflectionException
     */
    protected function getSuccessfullyResponse(ConfigDto $config): ConfigBasicResponse
    {
        $workingModes = $this->getWorkingModes();

        $response = new ConfigBasicResponse(ResultCodes::CODE_SUCCESS, $config);
        $response->setWorkingModes($workingModes);

        return $response;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getWorkingModes(): array
    {
        $modes = [];

        $class = new \ReflectionClass(new WorkingModes());
        foreach ($class->getConstants() as $name => $value) {
            $modes[$name] = $value;
        }

        return $modes;
    }
}
