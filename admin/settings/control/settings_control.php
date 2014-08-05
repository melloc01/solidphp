<?php

class settings_control extends LoopControl
{
	public 	$registros,
	$Form, 
	$Model, 
	$no_controls_lista,
	$list_headers,
	$list_cells;

	public function __construct($tool="")
	{
		parent::__construct($tool);
	}

	public function home()
	{		
		$this->setPageTitle("Configurações");
		$this->render(ADMIN."settings/view/lista.php",get_defined_vars());

	}


	public function editar()
	{
		$registro = $this->Model->getRegistro($_GET["id"],"id");
		$this->setPageTitle("Editar settings");
		$this->Form->setInputs($registro);

		$this->render(ADMIN."core/view/editar.php",get_defined_vars());
	}

	public function novo()
	{
		$this->setPageTitle("Novo settings");
		$this->Form->setInputs();
		$this->render(ADMIN."core/view/novo.php",get_defined_vars());
	}
}