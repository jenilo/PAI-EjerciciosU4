<?php
	//errores
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	//sesion 
	if(!isset($_SESSION)){
		session_start();
	}

?>