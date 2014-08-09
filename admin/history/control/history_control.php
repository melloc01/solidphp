<?php

class history_control extends LoopControl
{
	public 	$registros,
	$Form, 
	$Model, 
	$no_controls_lista,
	$list_headers,
	$list_cells;

	public function __construct($tool="_his")
	{
		parent::__construct($tool);
	}

	public function home()
	{		
		// $this->Form->setInputOrder(array("column1","column2"));
		// $this->Form->setRotuloFk("fkuser","name");
		// $this->Form->setMasks(array("title" => "Title"));
		// $this->Form->defineInput("<input />","field");
		// $this->Form->setDefaultValues("field",array("optionValue" => "optionMask","optionValue"=> "optionMask"));


		$this->list_headers = array("Título");
		$this->list_cells = array("{{title}}");	

		$this->no_controls_lista = array('novo','editar','remover'); //inicializa 
		$this->registros =  $this->Model->getRegistros();

		$this->setPageTitle("Histórico");
		$this->render(ADMIN."core/view/lista.php",get_defined_vars());

	}
	
}