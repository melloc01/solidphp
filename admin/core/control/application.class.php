<?php   

	/**
	* Class Application
	* Routers and Starts the Application
	*/

require 'autoloader.php';

class Application
{
	public $httpRequest ;

	function __construct()
	{
		$request  = new HttpRequest();
		if (ON_ADMIN)
			$request->setBaseUrl('/'.PROJECT_NAME.'/admin/');
		else 
			$request->setBaseUrl('/'.PROJECT_NAME.'/');

		$this->httpRequest = $request->createRequest();
		$this->route();

		Kint::dump($this->httpRequest);
	}

	public function route()
	{
		
		$controllerName = $this->httpRequest->getControllerClassName();
		$action = $this->httpRequest->getActionName();

		if (!ON_ADMIN || isset($_SESSION['admin'])  ) {
			if ($controllerName != 'default'){ 
				if (file_exists($controllerName."/control/".$controllerName."_control.php")){
					include_once($controllerName."/control/".$controllerName."_control.php"); 
					$control = $controllerName."_control";
					$control_object = new $control; // instancia o control de 'l'
					$control_object->httpRequest = $this->httpRequest;
					$control_object->route();
				} else {
					//make default_control possibilty to be called by /project/method 
					$this->httpRequest->setActionName($controllerName);
					$control = $this->newDefaultControl();
					$control->httpRequest = $this->httpRequest;
					$control->route();
				}
			} else {
				$control = $this->newDefaultControl();
				$control->httpRequest = $this->httpRequest;
				$control->route();
			}
		} else {
			$control = $this->newLoginControl();
			$control->httpRequest = $this->httpRequest;
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
