<?php  
	require 'config/system_constants.php';
	if (!defined('DB_SCHEMA_NAME'))
		if (  !file_exists('config/database_constants.php')){
			require 'admin/core/view/no_constants.html';
			exit;
		}
		else {
			require ROOT.'config/database_constants.php';
		}
	require ADMIN."core/config/inicia.php";
	require ADMIN.'core/control/LoopControl.php';
	require ADMIN.'core/control/application.class.php';
	
	new Application();	
?>