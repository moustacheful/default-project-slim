<?php

$config = array();


$app->configureMode('development', function () use ($app,&$config) {
    $app->config(array(
        'log.enabled' => FALSE,
        'debug' => TRUE,
        'templates.path' => './templates_dev'
    ));

    $config['fb'] = array(
        'appId' => '',
        'secret' => '',
        'fileUpload' => FALSE,
        'allowSignedRequest' => FALSE, 
    );

    define('SITE_URL','http://localhost/default-project-slim/public');
    define('TEMPLATE_URL','http://localhost/default-project-slim/public/templates_dev');
    
    R::setup('mysql:host=DB_HOST;dbname=DB_NAME','DB_USER','DB_PASSWORD');
    R::freeze( FALSE );

});

$app->configureMode('staging', function () use ($app,&$config) {

    $app->config(array(
        'log.enabled' => TRUE,
        'debug' => FALSE,
        'templates.path' => './templates_dev'
    ));

    $config['fb'] = array(
        'appId' => '',
        'secret' => '',
        'fileUpload' => FALSE,
        'allowSignedRequest' => FALSE, 
    );

    define('SITE_URL','http://localhost/default-project-slim/public');
    define('TEMPLATE_URL','http://localhost/default-project-slim/public/templates');
    
    R::setup('mysql:host=DB_HOST;dbname=DB_NAME','DB_USER','DB_PASSWORD');
    R::freeze( TRUE );
});

$app->configureMode('production', function () use ($app,&$config) {

    $app->config(array(
        'log.enabled' => TRUE,
        'debug' => FALSE,
        'templates.path' => './templates_dev'
    ));

    $config['fb'] = array(
        'appId' => '',
        'secret' => '',
        'fileUpload' => FALSE,
        'allowSignedRequest' => FALSE, 
    );

    define('SITE_URL','http://localhost/default-project-slim/public');
    define('TEMPLATE_URL','http://localhost/default-project-slim/public/templates');
    
    R::setup('mysql:host=DB_HOST;dbname=DB_NAME','DB_USER','DB_PASSWORD');
    R::freeze( TRUE );
});

$fb = new Facebook($config['fb']);


?>