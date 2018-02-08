<?php
/**
 * Created by PhpStorm.
 * User: snizhok
 * Date: 06.02.18
 * Time: 0:34
 */

require __DIR__.'/../vendor/autoload.php';

$app = \App\Core\Application::run();
$response = $app->handleRequest();

echo $response;