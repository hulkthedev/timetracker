<?php

namespace Tracking\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\ConfigSQLiteRepository;
use Tracking\Repository\WorkingTimeRepository;
use Tracking\Repository\WorkingTimeSQLiteRepository;
use Tracking\Usecase\Recalculation\RecalculateTimeDifferenceInRealTimeInteractor;
use  Tracking\Usecase\Recalculation\RecalculateTimeDifferenceInteractor;
use Tracking\Usecase\Recalculation\RecalculationBasicResponse;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class RecalculationController
{
    /** @var WorkingTimeRepository */
    private $workingTimeRepository;

    /** @var ConfigRepository */
    private $configRepository;

    public function __construct()
    {
        $this->workingTimeRepository = new WorkingTimeSQLiteRepository();
        $this->configRepository =  new ConfigSQLiteRepository();
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function recalculateTimeDifferenceInRealTime(Request $request): JsonResponse
    {
        $interactor = new RecalculateTimeDifferenceInRealTimeInteractor($this->configRepository);
        $response = $interactor->execute($request);

        return new JsonResponse([
            'code' => $response->code,
            'timeDifference' => $response->difference,
            'timeDifferenceIsBalanced' => $response->differenceIsBalanced
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function recalculateTimeDifference(): JsonResponse
    {
        $interactor = new RecalculateTimeDifferenceInteractor($this->workingTimeRepository, $this->configRepository);
        $response = $interactor->execute();

        return $this->getResponse($response);
    }

    /**
     * @return JsonResponse
     */
    public function recalculateTimeAccount(): JsonResponse
    {
        /**
         * @todo add logic
         */
    }

    /**
     * @param RecalculationBasicResponse $response
     *
     * @return JsonResponse
     */
    private function getResponse(RecalculationBasicResponse $response): JsonResponse
    {
        return new JsonResponse([
            'code' => $response->code
        ]);
    }
}
