<?php
class View
{
	protected $_params;

	protected $_controller;

	protected $_view;

	protected $_noRender = false;

	protected static $_instance;

	public static function getInstance($controller)
	{
		if (null === self::$_instance) {
			self::$_instance = new self($controller);
		}
		else {
			self::$_instance->setController($controller);
		}
		return self::$_instance;
	}

	function __construct($controller)
	{
		$this->_params = array();
		$this->_controller = $controller;
	}

	public function setController($controller)
	{
		$this->_controller = $controller;
	}

	public function cleanParam() 
	{
		$this->_params = array();
	}

	public function __set($name, $value)
	{
		$this->$name = $value;

		$this->_params[$name] = $value;
	}

	public function getParams()
	{
		return $this->_params;
	}

	public function setView($view) 
	{	
		$this->_view = $view;
	}

	public function render($view)
	{
		$this->_view = $view;
	}

	public function stopRender()
	{
		$this->_noRender = true;
	}

	public function isNoRender()
	{
		return $this->_noRender;
	}

	public function output()
	{
		if ($this->_noRender) {
			return;
		}

		$controllerClassName = get_class($this->_controller);
		$controllerName = substr($controllerClassName, 0, strripos($controllerClassName, 'Controller'));

		$path = APPLICATION_PATH . '/views/' . strtolower($controllerName) ;
		$view = strtolower($this->_view) ;

		//Loader::loadFile($view . '.phtml', $path, true);
		$incPath = false;
		if(!empty($path) && is_string($path)) {
			$incPath = get_include_path();
			set_include_path($path . PATH_SEPARATOR . $incPath);
		}
		$content = include_once($view . '.phtml');

		if($incPath) {
			set_include_path($incPath);
		}
		return $content;
	}
}
