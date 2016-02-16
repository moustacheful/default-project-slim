<?php

class UserController extends CRUDController
{
	public function __construct(){
		$this->model = 'user';
		parent::__construct('user');
	}
}