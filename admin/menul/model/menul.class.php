<?php  

class menul_model extends LoopModel
{

	function __construct()
	{
		parent::__construct();
	}

	public function getMenu()
	{
		$db = Conexao::getInstance();
		$nivel = $_SESSION['admin']["user"]["fkaccess_level"];
		$sql = "SELECT * 
					FROM  menul men  
					LEFT JOIN  access_tool too on (men.fkaccess_tool = too.id )
					
					ORDER BY men.ordem

					";
		return $this->runSQL($sql);
	}

	public function print_menu()
	{
		include_once './core/view/menu_left.php';	
	}

}

?>
