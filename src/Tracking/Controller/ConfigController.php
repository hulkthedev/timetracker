<?php

namespace Tracking\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\ConfigSQLiteRepository;
use Tracking\Usecase\Config\GetConfigInteractor;
use Tracking\Usecase\Config\UpdateConfigInteractor;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class ConfigController
{
    /** @var ConfigRepository */
    private $configRepository;

    public function __construct()
    {
        $this->configRepository = new ConfigSQLiteRepository();
    }

    /**
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        $interactor = new GetConfigInteractor($this->configRepository);
        $response = $interactor->execute();

        return new JsonResponse([
            'code' => $response->code,
            'config' => $response->config,
            'workingModes' => $response->workingModes
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $interactor = new UpdateConfigInteractor($this->configRepository);
        $response = $interactor->execute($request);

        return new JsonResponse([
            'code' => $response->code,
            'config' => $response->config,
            'workingModes' => $response->workingModes
        ]);
    }
}
