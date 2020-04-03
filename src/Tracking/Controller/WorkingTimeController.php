<?php

namespace Tracking\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\ConfigSQLiteRepository;
use Tracking\Repository\WorkingTimeRepository;
use Tracking\Repository\WorkingTimeSQLiteRepository;
use Tracking\Usecase\Working\EndWorkingInteractor;
use Tracking\Usecase\Working\GetWorkingDayInteractor;
use Tracking\Usecase\Working\GetWorkingListInteractor;
use Tracking\Usecase\Working\StartWorkingInteractor;
use Tracking\Usecase\Working\UpdateWorkingDayInteractor;
use Tracking\Usecase\Working\WorkingBasicResponse;

/**
 * @author <fatal.error.27@gmail.com>
 */
class WorkingTimeController
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
     * @return JsonResponse
     */
    public function listing(): JsonResponse
    {
        $interactor = new GetWorkingListInteractor($this->workingTimeRepository, $this->configRepository);
        $response = $interactor->execute();
        return $this->getResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function start(Request $request): JsonResponse
    {
        $interactor = new StartWorkingInteractor($this->workingTimeRepository, $this->configRepository);
        $response = $interactor->execute($request);
        return $this->getResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function end(Request $request): JsonResponse
    {
        $interactor = new EndWorkingInteractor($this->workingTimeRepository, $this->configRepository);
        $response = $interactor->execute($request);
        return $this->getResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $interactor = new UpdateWorkingDayInteractor($this->workingTimeRepository, $this->configRepository);
        $response = $interactor->execute($request);
        return $this->getResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $interactor = new GetWorkingDayInteractor($this->workingTimeRepository, $this->configRepository);
        $response = $interactor->execute($request);

        return new JsonResponse([
            'code' => $response->code,
            'workingDay' => $response->workingDay,
        ]);
    }

    /**
     * @param WorkingBasicResponse $response
     * @return JsonResponse
     */
    private function getResponse(WorkingBasicResponse $response): JsonResponse
    {
        return new JsonResponse([
            'code' => $response->code,
            'list' => $response->list,
            'statistics' => $response->statistics
        ]);
    }
}
