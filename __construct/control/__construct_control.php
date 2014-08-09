<?php
class __construct_control extends LoopControl
{
	public $system_tables, $db_tables;

	function __construct()
	{
		$this->hasHeader = false;
		$this->hasFooter = false;
		$this->system_tables = array("access",'access_tool','user','access_level','history','menul','album','foto_album');

		parent::__construct();
	}

	public function home()
	{
		if ($this->testDatabase()) {
			$dummy_model = new access_model();
			try {
				$_tables = $dummy_model->runSQL("SHOW TABLES");	
				$this->db_tables = array();
				foreach ($_tables as $key => $table) 
					if (!in_array($table[0], $this->system_tables)) 
						array_push($this->db_tables, $table[0]);
				$this->setSiteTitle('Solid - __construct ');
				$this->render(ROOT."__construct/view/home.php",get_defined_vars());
				
			} catch (Exception $e) {
				$this->noDatabase();
			}

		}
	}

	public function testDatabase()
	{
		try {
			$test = new access_model();

			if ( $test->table == 'no_db' ){
				$this->noDatabase();
				return false;
			}
			else 
				return true;

		} catch (Exception $e) {
			switch ($e->getCode()) {
				case 1044:
				$this->accessDenied();
				return false;
				break;
				default:
				throw new Exception($e, 1);
				break;
			}
		}
	}

	public function noDatabase()
	{

		$this->render(ROOT.'__construct/view/no_database.php',get_defined_vars());
	}

	public function createSchema()
	{
		$schema_name = $_POST['db_name'];
		$db = Conexao::getInstanceNoDB();
		$sql = "CREATE SCHEMA `$schema_name` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci" ;
		
		$dbStatment = $db->prepare($sql);
		if ($dbStatment->execute()){
			$_SESSION['system_success'] = "Database created successfully.";
			require ADMIN.'core/config/SQL_SKELETON.php';
			$dbStatment = $db->prepare($sql_base);
			$dbStatment->execute();
		} else {
			$error = $dbStatment->errorInfo();
			$_SESSION['mysql_error'] = $error;
			return false;
		}

		$this->movePermanently('./');
	}

	public function addToMenuLeft()
	{
		$table = $this->httpRequest->getActionValue();
		$access_model = new access_model();
		$access_tool_model = new access_tool_model();
		$menul_model = new menul_model();

		$linha = $access_tool_model->getRegistro("$table",'table_name');

		$ferramentaID = $linha['id'];

		$insert_menul = array(
				'mask' => $table,
				'link' => "./$table",
				'fkaccess_tool' => $ferramentaID
		);

		if ($menul_model->insert($insert_menul)){
			$_SESSION['system_success'] = "Adicionado ao menu com sucesso";
		}
		$this->movePermanently('../');
	}

	public function buildAdminStructure()
	{
		$access_model = new access_model();
		$access_tool_model = new access_tool_model();

		$table = $_POST['table'];
		$_success = true;	
		$ferramenta = isset($_POST['tool'])? $_POST['tool']:"";
		
		if ($_POST['createTool'] == 'true' && $ferramenta != '') {

			$insert_access_tool = array(
				'name' => $table,
				'code' => $ferramenta,
				'table_name' => $table,
				'description' => "Permissão para Cadastrar/Alterar/Remover cadastros de {$table}s.) "
			);

			$access_model->startTransaction();
			$_success &= $access_tool_model->insert($insert_access_tool);
			$ferramenta_id = $access_tool_model->getLastInsertId();

			$insert_access = array(
				'fkaccess_level' => 1,
				'fkaccess_tool' => $ferramenta_id
			);

			$_success &= $access_model->insert($insert_access);
			
			if ($_success) {
				$access_model->commit();
				mkdir(ADMIN."$table");
				mkdir(ADMIN."$table/control");
				mkdir(ADMIN."$table/model");
				mkdir(ADMIN."$table/view");
				mkdir(ADMIN."$table/css");
				mkdir(ADMIN."$table/js");
				mkdir(ADMIN."$table/uploads");
			}
			else
				$access_model->rollback();
				
		}

		if ($_POST['createTool'] == 'true' ) {
			$colunas = $access_model->runSQL("SHOW COLUMNS FROM  $table ");
			$mask_list = $colunas[1]['Field'];
		} else {
			$mask_list = $table;
		}
			$data_control = '<?php

class '.$table.'_control extends LoopControl
{
	public 	$registros,
			$Form, 
			$Model, 
			$no_controls_lista,
			$list_headers,
			$list_cells;

	public function __construct($tool="'.$ferramenta.'")
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


		$this->list_headers = array("'.$mask_list.'");
		$this->list_cells = array("{{'.$mask_list.'}}");	

		$this->no_controls_lista = array(); //inicializa 
		$this->registros =  $this->Model->getRegistros();

		$this->setPageTitle("'.$table.'s");
		$this->render(ADMIN."core/view/lista.php",get_defined_vars());

	}


	public function edit()
	{
		$id = $this->httpRequest->getActionValue();
		$registro = $this->Model->getRegistro($id);
		$this->setPageTitle("Editar '.$table.'");
		$this->Form->setInputs($registro);

		$this->render(ADMIN."core/view/editar.php",get_defined_vars());
	}

	public function create()
	{
		$this->setPageTitle("Novo '.$table.'");
		$this->Form->setInputs();
		$this->render(ADMIN."core/view/novo.php",get_defined_vars());
	}
}';


$data_model ='<?php
class '.$table.'_model extends LoopModel
{

	function __construct()
	{
		parent::__construct();
	}
}';

	if ($_success) {
		if ($_POST['createTool'] == 'true') {
			file_put_contents(ADMIN."$table/model/$table.class.php", $data_model);
		} else {
			mkdir(ADMIN."$table");
			mkdir(ADMIN."$table/control");
		}
		file_put_contents(ADMIN."$table/control/".$table."_control.php", $data_control);
		file_put_contents(ADMIN."$table/css/".$table.".css",'');
		file_put_contents(ADMIN."$table/js/".$table.".js",'');
	}

	$this->movePermanently('./');
	}

	public function buildStaticAdminStructure()
	{
		$table = $_POST['table'];
		$data_control = '<?php
class '.$table.'_control extends LoopControl
{
	public function __construct($tool="'.$ferramenta.'")
	{
		parent::__construct($tool);
	}

	public function home()
	{	
		$this->render(ADMIN."'.$table.'/view/home.php");	
	}


}';


$data_view =' home de '.$table.'';

		mkdir(ADMIN."$table");
		mkdir(ADMIN."$table/control");
		mkdir(ADMIN."$table/view");
		mkdir(ADMIN."$table/css");
		mkdir(ADMIN."$table/js");
		file_put_contents(ADMIN."$table/control/".$table."_control.php", $data_control);
		file_put_contents(ADMIN."$table/css/".$table.".css", '');
		file_put_contents(ADMIN."$table/js/".$table.".js", '');
		file_put_contents(ADMIN."$table/view/home.php", $data_view);

	$this->movePermanently('./');
	}

	public function buildRootStructure()
	{
		$access_model = new access_model();
		$access_tool_model = new access_tool_model();

		$table = $_POST['table'];
		
		mkdir(ROOT."$table");
		mkdir(ROOT."$table/control");
		mkdir(ROOT."$table/view");
		mkdir(ROOT."$table/css");
		mkdir(ROOT."$table/js");


	$data_control = '<?php
class '.$table.'_control extends LoopControl
{
	function __construct()
	{
		parent::__construct();
	}

	public function home()
	{
		$this->render(ROOT."'.$table.'/view/home.php", get_defined_vars());
	}
}';

$home =
"
<link rel='stylesheet'  href='./$table/css/$table.css'>
<script src='./$table/js/$table.js'></script>
home de $table
";

	file_put_contents(ROOT."$table/control/".$table."_control.php", $data_control);
	file_put_contents(ROOT."$table/css/$table.css","");
	file_put_contents(ROOT."$table/js/$table.js","");
	file_put_contents(ROOT."$table/view/home.php",$home);

	}

	public function accessDenied($value='')
	{
		
	}

}