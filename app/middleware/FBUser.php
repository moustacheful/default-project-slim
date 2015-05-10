<?php
use Facebook\FacebookSession;
class FBUserMiddleware extends \Slim\Middleware
{
	public $accessToken = FALSE;
	public function __construct(){
		if(array_key_exists('fb_token',$_SESSION))
			$this->accessToken = $_SESSION['fb_token'];
	}
	public function call(){
		$user = FALSE;

		if($this->accessToken){
			$session = new FacebookSession($this->accessToken);
			try{
				$session->validate();
				//Get user id from session
				$user_id = $session->getSessionInfo()->getId();		
				$user = R::findOne('user','fbid = ?',[$user_id]);
			
			} catch(Exception $e){
				unset($_SESSION['fb_token']);
			}
		}
		// Fill view data
		$this->app->view->appendData(array(
			'user' => $user
		));
		// Set
		$this->app->container->set('user',function() use($user){
			return $user;
		});

		$this->next->call();
	}	
}

