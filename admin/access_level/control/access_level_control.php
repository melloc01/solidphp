<?php

class access_level_control extends LoopControl
{
	public 	$registros,
			$Form, 
			$Model, 
			$no_controls_lista,
			$list_headers,
			$list_cells;

	public function __construct($tool="_lev")
	{
		$this->icon = 'fa-users';
		parent::__construct($tool);
	}

	public function submit()
	{
		if (isset($_POST['upd'])) {
			Kint::dump($_POST);
			$tools = "";
	
		}

		if (isset($_POST['ins'])) {
			if ($this->insertAccessLevel($_POST))
				$_SESSION['system_success'] = "Nível de acesso criado com sucesso.";
			else 
				$this->dispatchErrors();
			$this->movePermanently('./');
		}

		if (isset($_POST['del'])) {
			parent::submit();
		}
	}

	public function home()
	{	

		$this->list_headers = array("Nome");
		$this->list_cells = array("{{name}}");	

		$this->no_controls_lista = array(); //inicializa 
		$this->registros =  $this->Model->getRegistros();

		$this->setPageTitle("Níveis de Acesso");
		$this->render(ADMIN."core/view/lista.php",get_defined_vars());

	}


	public function edit()
	{
		$id = $this->httpRequest->getActionValue();
		$access_tool_model = new access_tool_model();

		$registro = $this->Model->getRegistro($id);
		$tools = $access_tool_model->getRegistros();


		$this->setPageTitle("Editar Nível de Acesso - {$registro['name']}");

		$this->render(ADMIN."access_level/view/editar.php",get_defined_vars());
	}

	public function create()
	{
		$access_tool_model = new access_tool_model();
		$tools = $access_tool_model->getRegistros();

		$this->setPageTitle("Cadastrar Nível de Acesso ");

		$this->render(ADMIN."access_level/view/novo.php",get_defined_vars());
	}

	public function insertAccessLevel($post_data)
	{
		$access_model =  new access_model;
		$access_level_model = new access_level_model;

		$access_level_model->startTransaction();
		$insert_level =  array(
			'name' => $post_data['name']
		);

		$bool = true;
		$newLevelID =  $access_level_model->insert($insert_level);
		$bool &= $newLevelID > 0 ;


		foreach ($post_data['tools'] as $key => $value) {
			$insert_array = array (
				'fkaccess_tool' => $value,
				'fkaccess_level' => $newLevelID
			);
			$bool &= $access_model->insert($insert_array) > 0;
		}

		if ($bool) {
			$access_level_model->commit();
			return true;
		} else {
			$access_level_model->rollback();
			return false;
		}
	}
}