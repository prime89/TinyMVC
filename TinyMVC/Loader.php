<?php
class Loader
{
	
	public static function loadClass($class, $dir = null)
	{
		if(class_exists($class, false) || interface_exists($class, false)) {
			return;
		}

		if(null !== $dir && !is_string($dir) && !is_array($dir)) {
			throw new Exception('dir is not validate', 1);
		}

		$className = ltrim($class);
		$file 	   = '';
		$namespace = '';

		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos+1);
			$file 	   = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}
		$file .= $className . '.php';

		self::loadFile($file, $dir, true);

		if(!class_exists($class, false) && !interface_exists($class, false)) {
			echo 'error';
			throw new Exception("File \"$file\" does not exist or class \"$class\" was not found in the file", 1);
		}
	}

	public static function loadFile($filename, $dir = null, $once = false)
	{
		$incPath = false;
		if(!empty($dir) && is_string($dir)) {
			$incPath = get_include_path();
			set_include_path($dir . PATH_SEPARATOR . $incPath);
		}
		if($once) {
			include_once $filename;
		}
		else {
			include $filename;
		}

		//reset include path
		if($incPath) {
			set_include_path($incPath);
		}
		return true;
	}

	function __construct()
	{
		
	}
}
