<?
use RedBeanPHP\R;

class PublicController extends BaseController {
	public function index($req,$res){
		return $this->view->render($res, 'home.twig', array(
			'test' => "hello world"
		));
	}
}