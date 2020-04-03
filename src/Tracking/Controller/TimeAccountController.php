<?php

namespace Tracking\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\ConfigSQLiteRepository;
use Tracking\Repository\TimeAccountRepository;
use Tracking\Repository\TimeAccountSQLiteRepository;
use Tracking\Repository\WorkingTimeRepository;
use Tracking\Repository\WorkingTimeSQLiteRepository;
use Tracking\Usecase\TimeAccount\GetTimeAccountInteractor;
use Tracking\Usecase\TimeAccount\AddOverTimeInteractor;

/**
 * @author <fatal.error.27@gmail.com>
 */
class TimeAccountController
{
    /** @var WorkingTimeRepository */
    private $workingTimeRepository;

    /** @var TimeAccountRepository */
    private $timeAccountRepository;

    /** @var ConfigRepository */
    private $configRepository;

    public function __construct()
    {
        $this->workingTimeRepository = new WorkingTimeSQLiteRepository();
        $this->timeAccountRepository = new TimeAccountSQLiteRepository();
        $this->configRepository = new ConfigSQLiteRepository();
    }

    /**
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        $interactor = new GetTimeAccountInteractor($this->timeAccountRepository, $this->configRepository);
        $response = $interactor->execute();

        return new JsonResponse([
            'code' => $response->code,
            'timeAccount' => $response->timeAccount,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $interactor = new AddOverTimeInteractor(
            $this->timeAccountRepository,
            $this->configRepository,
            $this->workingTimeRepository
        );

        $response = $interactor->execute($request);

        return new JsonResponse([
            'code' => $response->code,
            'timeAccount' => $response->timeAccount
        ]);
    }
}
