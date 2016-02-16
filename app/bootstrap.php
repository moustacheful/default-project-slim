<?php

require '../vendor/autoload.php';

# Load .env if on dev
if(class_exists('Dotenv')) Dotenv::load('../');


# Load all app classes
$paths = [
    "/classes/*.php",
    "/model/*.php",
    "/controller/*.php",
    "/middleware/*.php"
];


foreach ($paths as $path) {
    foreach(glob(dirname(__FILE__).$path) as $filename){   
        require $filename;
    }
}


# Instantiate app
$app = new \Slim\App(array(
	'settings' => array(
		'displayErrorDetails' => getenv('DEBUG') || FALSE
	)
));

# Load configuration
require 'config.php';