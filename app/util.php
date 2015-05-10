<?php
class Util extends StdClass {

	public function fail($message,$status=0,$http_status=400){
		$app = \Slim\Slim::getInstance();
        $response = new StdClass();
        $response->message = $message;
        $response->status = 0;

	    if($app->request->isXhr()){
	        $app->halt($http_status,json_encode($response));
	    }else{
	        $app->render('error.php',array(
	        	'response' => $response	
	        ));
	    }
	}

}
?>