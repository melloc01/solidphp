<?php   

	date_default_timezone_set('America/Sao_Paulo');

	$aux = explode('/',$_SERVER['REQUEST_URI']);
	$project = $aux[1];

	define ('PROJECT_NAME',$project);
	define ('ON_PRODUCTION',false);
		

	define ('BASE_URL','http://'.$_SERVER['HTTP_HOST'].'/'.PROJECT_NAME.'/');
	if (strpos(getcwd(), 'admin')) {
		define("ADMIN", "./");
		define("ROOT", "../");
		define("CURRENT_BASE",ADMIN);
		define("ON_ADMIN",TRUE);
	} else {
		define("ADMIN", "./admin/");
		define("ROOT", "./");
		define("CURRENT_BASE",ROOT);
		define("ON_ADMIN",FALSE);
	}