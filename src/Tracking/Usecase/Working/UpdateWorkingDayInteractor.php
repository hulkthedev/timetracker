<?php

namespace Tracking\Usecase\Working;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Services\TimeDifferenceCalculationService;
use Tracking\Usecase\ResultCodes;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class UpdateWorkingDayInteractor extends WorkingBasic
{
    /**
     * @param Request $request
     *
     * @return WorkingBasicResponse
     */
    public function execute(Request $request): WorkingBasicResponse
    {
        try {
            $this->validateRequest($request);

            $params = $this->getParamsFromRequest($request);
            $list = $this->workingTimeRepository->updateWorkingDayByDate($params);

            return $this->getSuccessfullyResponse($list);
        } catch (InvalidArgumentException $exception) {
            return new WorkingBasicResponse(ResultCodes::CODE_ERROR_INVALID_ARGUMENTS);
        }  catch (\PDOException $exception) {
            return new WorkingBasicResponse(ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND);
        } catch (\Exception $exception) {
            return new WorkingBasicResponse($exception->getCode());
        }
    }

    /**
     * @param Request $request
     *
     * @throws InvalidArgumentException
     */
    private function validateRequest(Request $request): void
    {
        if (null == $request->get('workingDate') || null == $request->get('workingStartTime') || null == $request->get('workingEndTime')) {
            throw new InvalidArgumentException('Required parameters are missing');
        }
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getParamsFromRequest(Request $request): array
    {
        $config = $this->configRepository->getAll();

        $timeCalculationService = new TimeDifferenceCalculationService($config);
        $result = $timeCalculationService->calculate(
            $request->get('workingDate'),
            $request->get('workingStartTime'),
            $request->get('workingEndTime')
        );

        return [
            'date' => $request->get('workingDate'),
            'workingEnd' => $result['endTime'],
            'workingStart' => $result['startTime'],
            'timeDifference' => $result['difference'],
            'timeDifferenceIsBalanced' => $result['isBalanced']
        ];
    }
}
