<?php  
	if (!defined('ADMIN')){	
		require 'admin/core/util/system_constants.php';
		require ADMIN.'core/util/database_constants.php';
	}
	require ADMIN."core/config/inicia.php";
	require ADMIN.'core/control/LoopControl.php';
	require ADMIN.'core/control/application.class.php';
	
	new Application();	
?>