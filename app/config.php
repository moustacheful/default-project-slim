<?php
require 'util.php';
use Facebook\FacebookSession;
session_start();
date_default_timezone_set('America/Santiago');

// Common config

$app->config(array(
    'templates.path' => getenv('TEMPLATES_PATH')
));

FacebookSession::setDefaultApplication(getenv('FB_APP_ID'),getenv('FB_APP_SECRET'));

if(getenv('CLEARDB_DATABASE_URL')){
    $db_config = parse_url(getenv('CLEARDB_DATABASE_URL'));
    $db_config['path'] = str_replace('/','',$db_config['path']);

}else{
    $db_config = array(
        'host' => getenv('DB_HOST'),
        'path' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'pass' => getenv('DB_PASSWORD')
    );
}

define('SITE_URL',getenv('SITE_URL'));
define('TEMPLATE_URL',getenv('TEMPLATE_URL'));

R::setup('mysql:host='.$db_config['host'].';dbname='.$db_config['path'],$db_config['user'],$db_config['pass']);


// App modes
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enabled' => FALSE,
        'debug' => TRUE,
    ));

    R::freeze( FALSE );
});

$app->configureMode('staging', function () use ($app) {
    $app->config(array(
        'log.enabled' => FALSE,
        'debug' => TRUE,
    ));

    R::freeze( TRUE );
});

$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enabled' => TRUE,
        'debug' => FALSE,
    ));

    R::freeze( TRUE );
});

// DI
$app->container->singleton('util',function(){
    return new \Util();
});

?>