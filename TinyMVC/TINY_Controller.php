<?php
require_once 'View.php';
require_once 'Layout.php';

class TINY_Controller
{
	public $view;

	protected $layout;

	protected $_layoutName = 'layout';

	public $viewPath;
	
	function __construct()
	{
		$this->view = View::getInstance($this);
		$this->layout = Layout::getInstance($this);

		$this->view->cleanParam();
	}

	public function preDispatch() {
		$this->startLayout();
	}

	public function excute($action)
	{
		$this->view->setView($action);

		$this->preDispatch();

		$action .= 'Action';
		$this->$action();
		return $this;
	}

	public function startLayout($layout = 'layout')
	{
		$this->_layoutName = $layout;
		$this->layout->setLayout($layout);
		return $this;
	}

	public function stopLayout() 
	{
		$this->_layoutName = '';
		$this->layout->setLayout('');
		return $this;
	}

	public function getHandler()
	{
		if (empty($this->_layoutName)) {
			return $this->view;
		}
		return $this->layout;
	}

	public function getView()
	{	
		return $this->view;
	}

	protected function _getUri() 
	{
		return $_SERVER['REQUEST_URI'];
	}

	protected function _getQueryParam()
	{
		return $_SERVER['QUERY_STRING'];
	}

	protected function _redirect($url = '/') 
	{
		header('Location: ' . $url);
	}
}
