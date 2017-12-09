<?php

$config = require __DIR__ . '/console.php';

$config['components']['db'] = readConfig(__DIR__ . '/test-db.php');
$config['components']['queue'] = readConfig(__DIR__ . '/test-queue.php');

$config = mergeConfig($config, __DIR__ . '/local/test.php');

return $config;