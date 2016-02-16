<?php
use RedBeanPHP\R;

class CRUDController extends BaseController
{
	private $model;

	public function __construct($model){
		if(empty($model))
			throw new Exception('No model defined for CRUD Controller');

		$this->model = $model;
	}
	public function index($req,$res){
		$result = R::find($this->model);
		$res->withJson(R::exportAll($result));
	}
	public function get($req,$res,$args){
		$id = $args['id'];
		$result = R::findOne($this->model,'id=?',array(
			$id
		));
		if($result)
			$res->withJson($result->export());
		else
			$res->withStatus(404)->write('404');
	}
}