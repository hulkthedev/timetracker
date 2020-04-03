<?php

namespace Tracking\Usecase\Config;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Usecase\ResultCodes;

/**
 * @author <fatal.error.27@gmail.com>
 */
class UpdateConfigInteractor extends ConfigBasic
{
    /**
     * @param Request $request
     * @return ConfigBasicResponse
     */
    public function execute(Request $request): ConfigBasicResponse
    {
        try {
            $this->validateRequest($request);

            $params = $this->getParamsFromRequest($request);
            $configDto = $this->configRepository->update($params);

            return $this->getSuccessfullyResponse($configDto);
        } catch (InvalidArgumentException $exception) {
            return new ConfigBasicResponse(ResultCodes::CODE_ERROR_INVALID_ARGUMENTS);
        } catch (\PDOException $exception) {
            return new ConfigBasicResponse(ResultCodes::CODE_ERROR_CAN_NOT_UPDATE_CONFIG);
        } catch (\Exception $exception) {
            return new ConfigBasicResponse($exception->getCode());
        }
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     */
    private function validateRequest(Request $request): void
    {
        if (null == $request->get('breakTimePerDay1') || null == $request->get('breakTimePerDay2') ||
            null == $request->get('workingTimePerDay') || null == $request->get('vacationDaysPerYear')
        ) {
            throw new InvalidArgumentException('Required parameters are missing');
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getParamsFromRequest(Request $request): array
    {
        return [
            'BREAKING_TIME_PER_DAY_1' => $request->get('breakTimePerDay1'),
            'BREAKING_TIME_PER_DAY_2' => $request->get('breakTimePerDay2'),
            'WORKING_TIME_PER_DAY' => $request->get('workingTimePerDay'),
            'VACATION_DAYS_PER_YEAR' => (int)$request->get('vacationDaysPerYear')
        ];
    }
}
