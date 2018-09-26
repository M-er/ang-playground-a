<?php
namespace App;
/**
*
Clase Session para retornar, crear o eliminar sessiones
*
**/
class Session
{
	function __construct($logger)
	{
		$this->logger = $logger;
	}
	public static function destroySession(){
		if(isSet($_SESSION['id']))
		{
			unset($_SESSION['id']);
			unset($_SESSION['username']);
			$info='info';
			if(isSet($_COOKIE[$info]))
			{
				setcookie ($info, '', time() - $cookie_time);
			}
		}
	}
	public static function createSession($id, $name, $type){
		if (!isset($_SESSION)) {
			session_start();
		}
		$sess = array();
		$sess["id"] = $id;
		$sess["username"] = $name;
		$sess["type"] = $type;
		return $sess;
	}
	function loggedInfo(){
		if (!isset($_SESSION)) {
			session_start();
		}
		$sess = array();
		if(isset($_SESSION['id']))
		{
			$sess["id"] = $_SESSION['id'];
			$sess["username"] = $_SESSION['username'];
		}
		else
		{
			$sess["id"] = '';
			$sess["username"] = 'Invitado';
		}
		return $sess;
	}
	function getSession(){
		if (!isset($_SESSION)) {
			session_start();
		}
		$sess = array();
		if(isset($_SESSION['id']))
		{
			$sess["id"] = $_SESSION['id'];
			$sess["username"] = $_SESSION['username'];
		}
		else
		{
			$sess["id"] = '';
			$sess["username"] = 'Invitado';
		}
		return $sess;
	}

}
?>
