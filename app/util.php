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
			exit();
		}
	}

	public static function json($data,$status = FALSE){
		$app = \Slim\Slim::getInstance();
		
		if($status)
			$app->repsonse->setStatus($status);
		
		$app->response->headers->set('Content-Type','application/json');
		echo json_encode($data);
	}


	public static function slugify($str,$delimiter){
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		return $clean;
	}

}
?>