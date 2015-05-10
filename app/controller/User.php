<?php
use \Slim\Slim;
use \Facebook\FacebookSession;
use \Facebook\FacebookRequest;
use \Facebook\FacebookJavaScriptLoginHelper;
use \Facebook\FacebookRedirectLoginHelper;
use \Facebook\GraphUser;

class UserController {

	static function loginForm(){
		$app = Slim::getInstance();
		$redirection_helper =  new FacebookRedirectLoginHelper(
			getenv('FB_LOGIN_CALLBACK'),
			getenv('FB_APP_ID'), 
			getenv('FB_APP_SECRET')
		);
		$app->render('logintest.php',array(
			'redirection_login_url' => $redirection_helper->getLoginUrl()
		));
	}

	static function login($from_redirection=FALSE){
		
		// Get app instance
		$app = Slim::getInstance();
		
		if($from_redirection){
			// Handle login requests from facebook via redirect ( Chrome in iOS)
			$redirection_helper = new FacebookRedirectLoginHelper(getenv('FB_LOGIN_CALLBACK'));
			$session = $redirection_helper->getSessionFromRedirect();

		}else{
			// Handle login requests via AJAX using a token
			$token = $app->request->post('token');
			$session = new FacebookSession($token);
			$session->validate();

		}

		//Get user id from session
		$user_id = $session->getSessionInfo()->getId();			
		
		//Attempt to find a user with said user id
		$user = R::findOne('user','fbid = ?',[$user_id]);

		if(!$user){
			$fb_user = (new FacebookRequest($session,'GET','/me'))
				->execute()
				->getGraphObject(GraphUser::className());

			$user = R::dispense( 'user' );
			$user->setMeta("buildcommand.unique" , array(array('fbid')));
			$user->fbid = (string)$fb_user->getId();
			$user->firstName = $fb_user->getFirstName();
			$user->lastName = $fb_user->getLastName();
			$user->name = $fb_user->getName();
			$user->gender = $fb_user->getGender();
			$user->email = $fb_user->getEmail();
			$user->registeredAt = R::isoDateTime();
			$user->lastLogin = R::isoDateTime();

		}else {
		    $user->lastLogin = R::isoDateTime();
		}
		R::store($user);

		$_SESSION['fb_token'] = $session->getAccessToken();
		
		if($from_redirection){
			$app->redirect(SITE_URL.'/api/isloggedin');
		}else{
			echo json_encode($user->export());
		}	
	}
	

	static function isloggedin(){
		$app = Slim::getInstance();
		var_dump($app->user);
	}

}
?>
