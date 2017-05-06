<?php
/* for rewrite or iis rewrite */
if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
	$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
} else if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
	$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
}
/* end */

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
define('DIR_ROOT', str_replace('\\', '/', dirname(__FILE__)));
define('DIR_LIBRARY', DIR_ROOT . '/library');
define('DIR_CLASSES', DIR_ROOT . '/classes');
define('DIR_COMPILED', DIR_ROOT . '/compiled');
define('DIR_TEMPLATE', DIR_ROOT . '/template');
define('DIR_FUNCTION', DIR_ROOT . '/function');
define('DIR_CONFIGURE', DIR_ROOT . '/configure');
define('WWW_ROOT', dirname(DIR_ROOT));
define('IMG_ROOT', WWW_ROOT . '/static/img');
define('DOC_ROOT', WWW_ROOT . '/static/doc');

/* carrega os arquivos de classes automaticamente */
function __classLoad($class_name) {
	$file_name = trim(str_replace('_','/',$class_name),'/').'.class.php';
	$file_path = DIR_LIBRARY. '/' . $file_name;
	if ( file_exists( $file_path ) ) {
		return require_once( $file_path );
	}
	$file_path = DIR_CLASSES. '/' . $file_name;
	if ( file_exists( $file_path ) ) {
		return require_once( $file_path );
	} else {
		$splnames = split_camel_case($class_name);
		$fistname = 'Z'.array_shift($splnames);
		$file_path = DIR_CLASSES. '/' . $fistname.'.class.php';
		if ( file_exists( $file_path ) ) {
			return require_once( $file_path );
		}
		foreach ($splnames as $key => $value) {
			$fistname = $fistname.$value;
			$file_path = DIR_CLASSES. '/' . $fistname.'.class.php';
			if ( file_exists( $file_path ) ) {
				return require_once( $file_path );
			}
		}
	}
	return false;
}

function import($funcpre) {
	$file_path = DIR_FUNCTION. '/' . $funcpre . '.php';
	if (file_exists($file_path) ) {
		require_once( $file_path );
	}
}

spl_autoload_register('__classLoad');
import('common');
import('template');