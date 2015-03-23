<?php
require '../vendor/autoload.php';
session_start();
date_default_timezone_set('America/Santiago');

switch($_SERVER['SERVER_NAME']){
    case "localhost":
        $env = "development";
    break;

    case "x.herokuapp.com":
        $env = "staging";
    break;

    default:
        $env = "production";
    break;
}

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array(
    'mode'=> $env
));

//$app->response->headers['Access-Control-Allow-Origin']="*";

// Configuration
require 'config.php';
require 'functions.php';

$JSON = function() use ($app){
    return function() use ($app){
        $app->response->headers['Content-Type']="text/json";
    };
};

$loggedInUser = FALSE;
$FBAuth = function() use(&$loggedInUser,$app){
    return function() use(&$loggedInUser,$app) {
        global $fb;
        if(!$fb->getUser()) fail("Not logged in");
        $user = R::findOne('user','fbid = ?',[$fb->getUser()]);
        if(!$user) fail("User doesn't exist");
       $loggedInUser = $user;

    };
};

$app->get('/',function() use ($app,&$config){
    $app->render('index.php',array(
        'url' => SITE_URL,
        'fb_app_id' => $config['fb']['appId']
    ));
});

$app->post('/login',$JSON(), function()use($app,$fb){

    @$token = $app->request->post('token');
    $fb->setAccessToken($token);
    // do login
    $user = R::findOne('user','fbid = ?',[$fb->getUser()]);
    if(!$user){
        $fb_user = $fb->api("/me",'GET');
        $user = R::dispense( 'user' );
        $user->setMeta("buildcommand.unique" , array(array('fbid')));
        $user->fbid = (string)$fb_user['id'];
        $user->firstName = $fb_user['first_name'];
        $user->lastName = $fb_user['last_name'];
        $user->name = $fb_user['name'];
        $user->gender = $fb_user['gender'];
        $user->email = $fb_user['email'];
        $user->registeredAt = R::isoDateTime();
        $user->lastLogin = R::isoDateTime();

    }else {
        $user->lastLogin = R::isoDateTime();
    }
    R::store($user);

    echo json_encode($user->export());
});

$app->get('/is-logged-in',$FBAuth(), function()use($app,&$loggedInUser){
    // FB Auth route example
    echo 'User logged in!';
    var_dump($loggedInUser);
});

$app->map('/:x+', function($x) {
    // CORS
    http_response_code(200);
})->via('OPTIONS');

$app->error(function (\Exception $e) use ($app) {
    fail($e->getMessage(),0,500);
});

function fail($message = '', $status = 0, $http_status = 400){
	global $app;
	$response = new StdClass();
	$response->message = $message;
	$response->status = $status;
	$app->halt($http_status, json_encode($response));
};

$app->run();
