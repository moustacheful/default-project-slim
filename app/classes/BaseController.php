<?php

class BaseController {
	public function __construct($container){
		$this->view = $container['view'];
		$this->util = $container['util'];
	}
}