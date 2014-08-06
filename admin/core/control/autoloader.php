<?php  
	function _autoloader ($pClassName, $callback= true) {
		$xpl = explode('_model', $pClassName);

		$DIR_NAME = $xpl[0] ;
		
		if (file_exists(ADMIN."$DIR_NAME/model/$DIR_NAME.class.php")) {
			
			require(ADMIN."$DIR_NAME/model/$DIR_NAME.class.php");return;
		}

		if (file_exists(ROOT. 'plugins/'.$DIR_NAME . "/$DIR_NAME.class.php")){
			require(ROOT. 'plugins/'.$DIR_NAME . "/$DIR_NAME.class.php");return;
		} 

		if (file_exists(ADMIN. "core/model/$DIR_NAME.class.php")) {
			require(ADMIN. "core/model/$DIR_NAME.class.php");return;
		}

		if (file_exists(ADMIN. "core/model/$DIR_NAME.class.php")) {
			require(ADMIN. "core/model/$DIR_NAME.class.php");return;
		}
		
		if (file_exists(ADMIN. "core/util/$DIR_NAME.class.php")) {
			require(ADMIN. "core/util/$DIR_NAME.class.php");return;
		}

		$aux = explode('_', $pClassName);
		$DIR_NAME = $aux[0];

		if (file_exists(ADMIN. "$DIR_NAME/model/{$xpl[0]}.class.php")) {
			require(ADMIN. "$DIR_NAME/model/{$xpl[0]}.class.php");return;
		}

		$aux = explode('-', $pClassName);
		$DIR_NAME = $aux[0];
		if (file_exists(ADMIN. "$DIR_NAME/model/{$xpl[0]}.class.php")) {
			require(ADMIN. "$DIR_NAME/model/{$xpl[0]}.class.php");return;
		}

		if ($callback) 
			return ( _autoloader(strtolower($pClassName),false) );
	}

	spl_autoload_register("_autoloader");