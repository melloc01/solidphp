
<?php   

	date_default_timezone_set('America/Sao_Paulo');

	define ('ON_PRODUCTION',false);
		

	if (strpos(getcwd(), 'admin')) {
		define ('BASE_URL','http://localhost/solidphp/admin/');
		define("ADMIN", "./");
		define("ROOT", "../");
		define("CURRENT_BASE",ADMIN);
		define("ON_ADMIN",TRUE);
	} else {
		define ('BASE_URL','http://localhost/solidphp/');
		define("ADMIN", "./admin/");
		define("ROOT", "./");
		define("CURRENT_BASE",ROOT);
		define("ON_ADMIN",FALSE);
	}
	
	if (php_sapi_name() == 'cli')
		define('RENDER_LAYOUT',false);

	if (isset($_GET['sl']) && substr($_GET['sl'], 0, 4)== 'AJAX' ) {
		if(!defined('RENDER_LAYOUT')) define('RENDER_LAYOUT', false);	
	} else {
		if(!defined('RENDER_LAYOUT')) define('RENDER_LAYOUT', true);
	}

	if (!defined('ENABLE_ROUTE')) define('ENABLE_ROUTE', true);