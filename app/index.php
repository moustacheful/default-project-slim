<?php
require_once 'bootstrap.php';


// Middlewares
$app->add(new JSONMiddleware()); // Responds as JSON for AJAX requests

$app->add(new CORSMiddleware(array(
    'allowed_origins' => array('*')
))); // Allow cross origin requests

$app->add(new FBUserMiddleware()); // Fill user data, if any, use along with $FBAuth() route middleware to require a user.


// Facebook auth routes
$app->get('/login','UserController::loginForm');
$app->map('/api/login(/:cb)','UserController::login')->via('GET','POST');
$app->get('/api/isloggedin',$FBAuth(),'UserController::isloggedin');


// Error handling and CORS
$app->map('/:x+', function($x) {
    // CORS
    http_response_code(200);
})->via('OPTIONS');

$app->notFound(function()use($app){
    $app->render('404.php');
});
$app->error(function (\Exception $e) use ($app) {
    $app->util->fail($e->getMessage(),0,500);
});

$app->run();