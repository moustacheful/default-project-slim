<?php
require '../vendor/autoload.php';
if(class_exists('Dotenv')) Dotenv::load('../');

// Instantiate app
$app = new \Slim\Slim();

// Configuration
require 'config.php';

// Load route middleware
require 'middleware/route-middleware.php';