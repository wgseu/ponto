<?php
class Session
{
	static private $_instance = null;

	static public function Init()
	{
		self::$_instance = new Session();
		session_start();
	}

	static public function Set($name, $v) 
	{
		$_SESSION[$name] = $v;
	}

	static public function Get($name, $once=false)
	{
		$v = null;
		if ( isset($_SESSION[$name]) )
		{
			$v = $_SESSION[$name];
			if ( $once ) unset( $_SESSION[$name] );
		}
		return $v;
	}
}
