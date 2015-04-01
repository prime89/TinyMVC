<?php
abstract class Bootstrap_Abstract
{
	protected $_application;

	protected $_classResources = array();

	protected $_started = array();

	protected $_return = array();

	protected $_defaultOption = array();

	protected $_resources = array();

	function __construct($application)
	{
		$this->_application = $application;
	}

	public function bootstrap()
	{
		$this->_bootstrap();
		return $this;
	}

	protected function _bootstrap()
	{
		foreach ($this->_getClassResources() as $key => $resource) {
			$this->_excuteResource(array('name'=>$key,'method'=>$resource));
		}
	}

	protected function _getClassResources() {
		$methods     = get_class_methods($this);
		foreach ($methods as $method) {
			if(5 < strlen($method) && '_init' == substr($method, 0, 5)) {
				$this->_classResources[strtolower(substr($method, 5))] = $method;
			}
		}
		return $this->_classResources;
	}

	protected function _excuteResource($resource) {
		$this->_started[$resource['name']] = true;
		$return = $this->$resource['method']();
		unset($this->_started[$resource['name']]);
		$this->_return[$resource['name']] = $return;
	}
}
