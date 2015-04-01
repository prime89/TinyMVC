<?php
require_once 'Loader.php';

class TINY_Application 
{
	protected $_bootstrap;

	protected $_bootstrapClassName = 'Bootstrap';

	protected $_option;

	public function __construct($option)
	{
		$this->setOption($option);
	}

	public function setOption($option)
	{
		if (isset($option['bootstrap'])) {
			$bootstrap = $option['bootstrap'];

			//require_once $bootstrap['directory'];
			$name = isset($bootstrap['classname']) ? $bootstrap['classname'] : $this->_bootstrapClassName;

			Loader::loadClass($name, $bootstrap['directory']);
			$this->_bootstrap = new $name($this);

			if (! $this->_bootstrap instanceof Bootstrap_Abstract) {
				throw new Exception('bootstrap privided for TINY_Application was not an instance of Bootstrap_Abstract', 1);
			}
			return;
		}
		throw new Exception('no bootstrap provided for TINY_Application', 1);
	}

	public function getOption($key = null)
	{
		if (!isset($key) || empty($key)) {
			return $this->_option;
		}

		if(isset($this->_option[$key])) {
			return $this->_option[$key];
		}
		return null;
	}

	public function getBootstrap()
	{
		return $this->_bootstrap;
	}

	public function bootstrap()
	{
		return $this->_bootstrap->bootstrap();
	}
}
