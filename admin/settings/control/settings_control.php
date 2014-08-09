<?php

class settings_control extends LoopControl
{

	public function __construct($tool="")
	{
		parent::__construct($tool);
	}

	public function home()
	{		
		$this->setPageTitle("Configurações");
		$this->render(ADMIN."settings/view/lista.php",get_defined_vars());

	}
	
}