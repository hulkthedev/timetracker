<?php

error_reporting(-1);

/** @var Composer\Autoload\ClassLoader $autoLoader */
$autoLoader = require __DIR__ . '/../../vendor/autoload.php';
$autoLoader->addPsr4('Tracking\\', realpath(__DIR__) . '/Tracking');
