<?php

require_once '../app/bootstrap.php';
require_once '../app/routes.php';

$app->add('CommonMiddleware:stripTrailingSlash');
$app->run();

?>