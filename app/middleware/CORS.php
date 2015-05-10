<?php
class CORSMiddleware extends \Slim\Middleware
{
	private $options = array(
		'allowed_origins' => array('*'),
	);
	public function __construct($incoming_options=NULL){
		if($incoming_options)
			$this->options = array_merge($this->options,$incoming_options);

	}

	public function call(){
		$app = $this->app;

		$current_origin = @$_SERVER['HTTP_ORIGIN'];

		if( 
			in_array( '*', $this->options['allowed_origins'] ) ||
			in_array($current_origin,$this->options['allowed_origins']) 
		){
			$app->response->headers['Access-Control-Allow-Origin']=$current_origin;
		}

		$this->next->call();
	}	
}

