<?php

namespace Tracking\Usecase\Config;

use Tracking\Usecase\ResultCodes;

/**
 * @author <fatal.error.27@gmail.com>
 */
class GetConfigInteractor extends ConfigBasic
{
    /**
     * @return ConfigBasicResponse
     */
    public function execute(): ConfigBasicResponse
    {
        try {
            $configDto = $this->configRepository->getAll();
            return $this->getSuccessfullyResponse($configDto);
        } catch (\PDOException $exception) {
            return new ConfigBasicResponse(ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND);
        } catch (\Exception $exception) {
            return new ConfigBasicResponse($exception->getCode());
        }
    }
}
