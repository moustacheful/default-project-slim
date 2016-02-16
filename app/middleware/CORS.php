<?php
class CORSMiddleware
{
	private $options = array(
		'allowed_origins' => array('*'),
	);

	public function __construct($incoming_options=NULL){
		if($incoming_options)
			$this->options = array_merge($this->options,$incoming_options);

	}

	public function __invoke($req,$res,$next){
		$current_origin = @$_SERVER['HTTP_ORIGIN'];

		if( 
			in_array( '*', $this->options['allowed_origins'] ) ||
			in_array($current_origin,$this->options['allowed_origins']) 
		){
			$res = $res->withHeader('Access-Control-Allow-Origin',$current_origin);
		}

		$res = $next($req,$res);
		return $res;
	}	
}

