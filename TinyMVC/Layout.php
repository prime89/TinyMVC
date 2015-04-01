<?php
/**
* 
*/
class Layout
{
	protected static $_instance;

	protected $_layout;

	protected $_content;

	protected $_controller;

	public function getContent() 
	{
		$view = $this->_controller->getView();
		$this->_content = $view->output();
		return $this->_content;
	}

	public function setContent($content)
	{
		$this->_content = $content;
	}

	public function setLayout($layout = '')
	{
		$this->_layout = $layout;
	}

	public function output()
	{
		$view = $this->_controller->getView();
		if ($view->isNoRender()) {
			return;
		}

		$controllerClassName = get_class($this->_controller);
		$controllerName = substr($controllerClassName, 0, strripos($controllerClassName, 'Controller'));

		$path = APPLICATION_PATH . '/layout';
		$layout = strtolower($this->_layout) ;
		
		$incPath = false;
		if(!empty($path) && is_string($path)) {
			$incPath = get_include_path();
			set_include_path($path . PATH_SEPARATOR . $incPath);
		}
		//ob_start();

		$content = include_once($layout . '.phtml');

		if($incPath) {
			set_include_path($incPath);
		}
		//ob_end_flush();
		return $content;
	}

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
		$this->_controller = $controller;
	}
}
