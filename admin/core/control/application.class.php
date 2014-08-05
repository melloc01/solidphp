<?php   

	/**
	* Class Application
	* Routers and Starts the Application
	*/

require 'autoloader.php';

class Application
{
	function __construct()
	{
		$this->route();
	}

	public function route()
	{
		if (!ON_ADMIN || isset($_SESSION['admin'])  ) {
			if (isset($_GET["l"]) && $_GET["l"] != ''){ 
				if (file_exists($_GET["l"]."/control/".$_GET["l"]."_control.php")){
					include_once($_GET["l"]."/control/".$_GET["l"]."_control.php"); 
					$control = $_GET["l"]."_control";
					$control_object = new $control; // instancia o control de 'l'
					$control_object->route();
				} else {
					$_GET['sl'] = $_GET['l'];
					$control = $this->newDefaultControl();
					$control->route();
				}
			} else {
				$control = $this->newDefaultControl();
				$control->route();
			}
		} else {
			$control = $this->newLoginControl();
			$control->route();
		}
	}

	public function newDefaultControl()
	{
		if (ON_ADMIN) {
			require ADMIN."default/control/default_control.php";
		} else {
			require ROOT."default/control/default_control.php";
		}

		$control = "default_control";
		return new $control;
	}

	public function newLoginControl()
	{
		if (ON_ADMIN){
			require ADMIN."login/control/login_control.php";
			$control = "login_control";
			return new $control;
		} 

	}
}
