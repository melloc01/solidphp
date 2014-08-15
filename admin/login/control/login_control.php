<?php
class login_control extends LoopControl
{
	public function __construct()
	{
		$this->hasHead = false;
		$this->hasFooter = false;

		parent::__construct();
	}

	public function setNotifications()
	{
		$this->info_notification = "Registro Editado com succeso.";	
		$this->success_notification = "Registro criado com succeso.";	
		$this->warning_notification = "Registro atualizado com successo.";	
		$this->danger_notification = "Senha ou E-mail incorretos, tente novamente.";
	}

	public function submit()
	{

		if (isset($_POST["user"])){
			if (!$this->auth()) {
				$this->actionForms = './';
				$_SESSION['system_danger'] = $this->danger_notification;
			}
			$this->movePermanently('/admin/');
		}
	}

	public function home()
	{
		$this->renderPure(ADMIN.'login/view/home.php',get_defined_vars());		
	}
	public function login()
	{
		$this->home();
	}

	public function auth()
	{
		$user_model = new user_model();

		$registro = $user_model->getRegistro($_POST["user"],'login');

		if( !empty($registro) && $this->checkCryptPassword($_POST["pass"],$registro['password']) ){
			$this->buildSession($registro);
			return true;
		}
		else{
			return false;
		}
	}

	public function buildSession($registro)
	{
		$_SESSION["admin"]['user'] = $registro;

		$user_model = new user_model();
		$access_tool_model = new access_tool_model();
		
		$user_tools = $user_model->getTools($registro['fkaccess_level']);	

		$access = array();
		foreach ($user_tools as $key => $tool) {
			$access[$tool['code']] = (bool) $tool['has_access'];
		}

		//updates last_access
		$update_user = array(
			'last_access' => date('Y-m-d H:i:s')
		);

		$bool = $user_model->update($registro['id'],$update_user);
		$_SESSION['admin']['access'] = $access;

	}

	public function logout()
	{
		session_destroy();
		$this->movePermanently('./');
	}
}