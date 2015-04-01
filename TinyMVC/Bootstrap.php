<?php
require_once 'Bootstrap_Abstract.php';
class TINY_Bootstrap extends Bootstrap_Abstract
{
	function __construct($application)
	{	
		parent::__construct($application);
	}

	public function run()
	{
		$pattern = '~^(/)([^?#]*)(\?([^#]*))?(#(.*))?$~';
		$status  = @preg_match($pattern, $_SERVER['REQUEST_URI'], $matches);
		if ($status === false) {
			throw new Exception('Internal error: scheme specific parsed error', 1);
		}
		$path     = isset($matches[2]) === true ? $matches[2] : '';
		$query    = isset($matches[4]) === true ? $matches[4] : '';
		$fragment = isset($matches[6]) === true ? $matches[6] : '';

		$uri = trim($path, '/');
		$controller = 'Index';
		$action     = 'Index';
		if (strlen($uri) > 0) {
			$str = explode('/', $uri);
			if (isset($str[0]) && !empty($str[0])) {
				$controller = $str[0];
			}
			if (isset($str[1]) && !empty($str[1])) {
				$action = $str[1];
			}
		}

		$classname  = $controller . 'Controller';
		$actionName = $action . 'Action';
		$filePath   = APPLICATION_PATH . '/controllers/'; 

		try {
			Loader::loadClass($classname, $filePath);
		} catch (Exception $e) {
			header('Location: /404.php');
		}

		$instance = new $classname();

		if (! $instance instanceof TINY_Controller) {
			throw new Exception("\"$classname\" was not an instance of TINY_Controller", 1);
		}

		$methods  = array_keys(get_class_methods($instance));

		if (!in_array($action, $methods)) {
			header('Location: /404.php');
		}

		$handler = $instance->excute($action)->getHandler();

		$handler->output();
	}
}
