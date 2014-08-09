<?php  
	if (!defined('ADMIN'))
		if ( !file_exists('../config/system_constants.php') || !file_exists('../config/database_constants.php')){
			require 'core/view/no_constants.html';
			exit;
		}
		else {
			require '../config/system_constants.php';
			require ROOT.'config/database_constants.php';
		}
	require ADMIN."core/config/inicia.php";
	require ADMIN.'core/control/LoopControl.php';
	require ADMIN.'core/control/application.class.php';
	
	new Application();	
?>