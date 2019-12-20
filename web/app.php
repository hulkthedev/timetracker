<?php

use Tracking\Controller\RecalculationController;
use Tracking\Controller\ConfigController;
use Tracking\Controller\TimeAccountController;
use Tracking\Controller\WorkingTimeController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$workingController = new WorkingTimeController();
$recalculationController = new RecalculationController();
$configController = new ConfigController();
$timeAccountController = new TimeAccountController();

$app = new Silex\Application();
$app->after(function (Request $request, Response $response) {
    $response->headers->addCacheControlDirective('no-cache');
    $response->headers->addCacheControlDirective('max-age', 0);
    $response->headers->addCacheControlDirective('must-revalidate');
    $response->headers->addCacheControlDirective('no-store');
    $response->headers->add(['Access-Control-Allow-Origin' => '*']);
});

/**
 * Working Time
 */
$app->get('/working/listing', function() use ($workingController) {
    return $workingController->listing();
});

$app->post('/working/start', function(Request $request) use ($workingController) {
    return $workingController->start($request);
});

$app->put('/working/end', function(Request $request) use ($workingController) {
    return $workingController->end($request);
});

$app->put('/working/update', function (Request $request) use ($workingController) {
    return $workingController->update($request);
});

$app->get('/working/get/{date}', function(Request $request) use ($workingController) {
    return $workingController->get($request);
});

/**
 * Recalculation
 */
$app->put('/recalculate/time/difference', function () use ($recalculationController) {
    return $recalculationController->recalculateTimeDifference();
});

$app->post('/recalculate/time/difference/realtime', function (Request $request) use ($recalculationController) {
    return $recalculationController->recalculateTimeDifferenceInRealTime($request);
});

$app->put('/recalculate/account', function () use ($recalculationController) {
    return $recalculationController->recalculateTimeAccount();
});

/**
 * Config
 */
$app->get('/config/get', function () use ($configController) {
    return $configController->get();
});

$app->put('/config/update', function (Request $request) use ($configController) {
    return $configController->update($request);
});

/**
 * Time Account
 */
$app->get('/timeaccount/get', function () use ($timeAccountController) {
    return $timeAccountController->get();
});

$app->put('/timeaccount/add', function (Request $request) use ($timeAccountController) {
    return $timeAccountController->add($request);
});

$app->run();
