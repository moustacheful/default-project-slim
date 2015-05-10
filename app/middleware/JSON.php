<?php
class JSONMiddleware extends \Slim\Middleware
{
	public function call(){
		$app = $this->app;
		if($app->request->isXhr())
			 $app->response->headers['Content-Type']="text/json";

		$this->next->call();
	}	
}

