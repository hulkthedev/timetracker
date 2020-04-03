<?php

namespace Tracking\Usecase\Working;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Services\TimeDifferenceCalculationService;
use Tracking\Usecase\ResultCodes;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class EndWorkingInteractor extends WorkingBasic
{
    /**
     * @param Request $request
     * @return WorkingBasicResponse
     */
    public function execute(Request $request): WorkingBasicResponse
    {
        try {
            $this->validateRequest($request);

            $params = $this->getParamsFromRequest($request);
            $list = $this->workingTimeRepository->endWorking($params);

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
     * @throws InvalidArgumentException
     */
    private function validateRequest(Request $request): void
    {
        if (null == $request->get('workingDate') || null == $request->get('workingTime')) {
            throw new InvalidArgumentException('Required parameters are missing');
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getParamsFromRequest(Request $request): array
    {
        $config = $this->configRepository->getAll();
        $workingDay = $this->workingTimeRepository->getWorkingDayByDate($request->get('workingDate'));

        $timeCalculationService = new TimeDifferenceCalculationService($config);
        $calculationResult = $timeCalculationService->calculate(
            $workingDay->date,
            $workingDay->workingStart,
            $request->get('workingTime')
        );

        return [
            'date' => $calculationResult['date'],
            'workingEnd' => $calculationResult['endTime'],
            'workingStart' => $calculationResult['startTime'],
            'timeDifference' => $calculationResult['difference'],
            'timeDifferenceIsBalanced' => $calculationResult['isBalanced']
        ];
    }
}
