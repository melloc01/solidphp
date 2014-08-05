<?php
	include_once '../core/config/Conexao.class.php';

	$db = Conexao::getInstance();

	$root = '../..';
	$admin = '..';
	$table = $_GET['table'];

	mkdir("$admin/$table");
	mkdir("$admin/$table/control");
	mkdir("$admin/$table/model");
	mkdir("$admin/$table/view");
	mkdir("$admin/$table/uploads");


	$ferramenta = isset($_GET['ferramenta'])?$_GET['ferramenta']:"";

	if ($ferramenta!="") {
		$sql = "INSERT into ferramenta (nome,cod,descricao,table_name) values ('$table','$ferramenta','Permissão para Cadastrar/Alterar/Remover cadastros de $table. ','$table') ";	
		$dbStatment = $db->prepare($sql);
		$dbStatment->execute();
		$ferramenta_id = $db->lastInsertId();
		
		// Add access to  ADMIN/ADMIN
		$sql2 = "INSERT into permissao (fkNivel,fkFerramenta) values(1,'$ferramenta_id')";
		echo "$sql2";
		$dbStatment = $db->prepare($sql2);
		$dbStatment->execute();
	}
	$dbStatment = $db->prepare("SHOW COLUMNS FROM  $table ");
	$dbStatment->execute();
 
	$colunas = $dbStatment->fetchAll();
	$mask_list = $colunas[1]['Field'];


$data_control = 
'<?php
/**
 * ADMIN CONTROL
 */  

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
		render(ADMIN."core/view/lista.php");

	}


	public function editar()
	{
		$registro = $this->Model->getRegistro($_GET["id"],"id");
		$this->setPageTitle("Editar '.$table.'");
		$this->Form->setInputs($registro);

		require(ADMIN."core/view/titulo_pagina.php");
		require(ADMIN."core/view/editar.php");
	}

	public function novo()
	{
		$this->setPageTitle("Novo '.$table.'");
		$this->Form->setInputs();
		require(ADMIN."core/view/titulo_pagina.php");
		require(ADMIN."core/view/novo.php");
	}
}';


$data_model =
'<?php
class '.$table.'_model extends LoopModel
{

	function __construct()
	{
		parent::__construct();
	}
}';


file_put_contents("$admin/$table/control/".$table."_control.php", $data_control);
file_put_contents("$admin/$table/model/$table.class.php", $data_model);