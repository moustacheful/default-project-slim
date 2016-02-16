<?php
use RedBeanPHP\R;

session_start();
date_default_timezone_set('America/Santiago');

# Define stuff
define('SITE_URL',getenv('SITE_URL'));

# DB Config
if(getenv('CLEARDB_DATABASE_URL')){
	$db_config = parse_url(getenv('CLEARDB_DATABASE_URL'));
	$db_config['path'] = str_replace('/','',$db_config['path']);

}else{
	$db_config = array(
		'host' => getenv('DB_HOST'),
		'path' => getenv('DB_NAME'),
		'user' => getenv('DB_USER'),
		'pass' => getenv('DB_PASSWORD')
	);
}
R::setup(
	'mysql:host='.$db_config['host'].';dbname='.$db_config['path'],
	$db_config['user'],
	$db_config['pass']
);

# Twig config
$config = array(
	'twig' => array(
		'cache' => '../.cache',
		'debug' => FALSE
	)
);
# App modes
switch (getenv('ENVIRONMENT')) {
	case 'local':
		$config['twig']['debug'] = TRUE;
		R::freeze(FALSE);
		break;
	
	default:
		R::freeze(TRUE);
		break;
}

# DI
$container = $app->getContainer();

$container['util'] = function(){
	return new Util();
};

$container['view'] = function ($container) {
	global $config;
	$view = new \Slim\Views\Twig('../views', $config['twig']);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container['router'],
		$container['request']->getUri()
	));

	$view->addExtension(new TwigFilters());
	$environment = $view->getEnvironment();
	$environment->setLexer(new Twig_Lexer($environment, array(
		'tag_comment'   => array('{#', '#}'),
		'tag_block'     => array('{%', '%}'),
		'tag_variable'  => array('${', '}'),
		'interpolation' => array('#{', '}'),
	)));
	return $view;
};

$container['errorHandler'] = function($container){
	return function($req,$res,$exception) use ($container){
		$result = new StdClass();
		$result->message = $exception->getMessage();

		if(getenv('DEBUG') === TRUE)
			$result->stack = $exception->getTraceAsString();

		if($req->isXhr()){
			return $container['response']
				->withJson($result)
				->withStatus(500);
		}else{
			return $container['view']
				->render($res,'error.twig',array(
					'response' => $result
				))->withStatus(500);
		}
	};
};

$container['notFoundHandler'] = function($container){  
	return function ($req,$res) use ($container){
		$res = $res->withStatus(404);
		if($req->isXhr()){
			return $res->withJson(array(
				'error' => 'Not found'
			));
		}else{
			return $container['view']
				->render($res,'404.twig');
		}
	};
};


?>