<?php

// Middlewares

$app->add(new CORSMiddleware(array(
	'allowed_origins' => array('*')
)));

$app->get('/',"PublicController:index");
$app->get('/user',"UserController:index");
$app->get('/user/{id}',"UserController:get");

$app->group('/api', function() use ($app){
	$app->get('/user',"PublicController:listing");
});