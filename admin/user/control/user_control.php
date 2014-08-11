<?php

class user_control extends LoopControl
{
	public 	$registros,
			$Form, 
			$Model, 
			$no_controls_lista,
			$list_headers,
			$list_cells;

	public function __construct($tool="_use")
	{
		parent::__construct($tool);
		$this->Form->setMasks(array("fkaccess_level" => "Nível de acesso", 'password' => 'Senha','login' => 'Login', 'img_user' => 'Imagem de Perfil')) ;

	}

	public function submit()
	{
		if (isset($_POST['user:password'])) 
			$_POST['user:password'] = crypt($_POST['user:password']);
		
		if (isset($_POST['del'])) 
			if ($_POST['del'] == 1) {
				$_SESSION['system_danger'] = "Esse usuário não pode ser removido por esta ferramenta";
				$this->movePermanently('./');
			}
		
		parent::submit();
	}

	public function home()
	{		
		// $this->Form->setInputOrder(array("column1","column2"));
		// $this->Form->setRotuloFk("fkuser","name");
		// $this->Form->defineInput("","last_access");
		// $this->Form->setDefaultValues("field",array("optionValue" => "optionMask","optionValue"=> "optionMask"));


		$this->list_headers = array("Login");
		$this->list_cells = array("{{login}}");	

		$this->no_controls_lista = array(); //inicializa 
		$this->registros =  $this->Model->getRegistros(" id <> 1 ");

		$this->setPageTitle("Usuários");
		$this->render(ADMIN."core/view/lista.php",get_defined_vars());

	}


	public function edit()
	{
		$id = $this->httpRequest->getActionValue();
		$registro = $this->Model->getRegistro($id);

		$this->setPageTitle("Editar user");
		$this->Form->setInputs($registro);
		$this->Form->hideInput("last_access");
		$this->Form->hideInput("type");
		$this->Form->hideInput("password");

		$this->render(ADMIN."core/view/editar.php",get_defined_vars());
	}

	public function create()
	{
		$this->setPageTitle("Novo Usuário");
		
		$this->Form->setInputs();
		$this->Form->hideInput("last_access");
		$this->Form->hideInput("type");


		$this->render(ADMIN."core/view/novo.php",get_defined_vars());
	}
}