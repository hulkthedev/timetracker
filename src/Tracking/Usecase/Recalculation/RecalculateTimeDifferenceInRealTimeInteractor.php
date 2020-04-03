<?php

namespace Tracking\Usecase\Recalculation;

use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigRepository;
use Tracking\Services\TimeDifferenceCalculationService;
use Tracking\Usecase\ResultCodes;

/**
 * @author <fatal.error.27@gmail.com>
 */
class RecalculateTimeDifferenceInRealTimeInteractor
{
    /** @var ConfigRepository */
    private $configRepository;

    /**
     * @param ConfigRepository      $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @param Request $request
     * @return RecalculationBasicResponse
     */
    public function execute(Request $request): RecalculationBasicResponse
    {
        try {
            $config = $this->configRepository->getAll();
            $timeCalculationService = new TimeDifferenceCalculationService($config);

            $result = $timeCalculationService->calculate(
                $request->get('workingDate'),
                $request->get('workingStartTime'),
                $request->get('workingEndTime')
            );

            $response = new RecalculationBasicResponse(ResultCodes::CODE_SUCCESS);
            $response->setDifference($result['difference']);
            $response->setIsDifferenceBalanced($result['isBalanced']);

            return $response;
        } catch (\Exception $exception) {
            return new RecalculationBasicResponse(ResultCodes::CODE_ERROR_CAN_NOT_RECALCULATION_TIME_DIFFERENCE);
        }
    }
}
