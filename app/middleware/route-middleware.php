<?php
	$FBAuth = function() use ($app){
		return function() use ($app) {
			if( ! $app->user) $app->util->fail('Not logged in');
		};
	}
?>