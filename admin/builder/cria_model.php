<?php
	include_once '../core/config/Conexao.class.php';
	$db = Conexao::getInstance();

	$root = '../..';
	$admin = '..';
	$table = $_GET['table'];
	
	mkdir("$admin/$table");
	mkdir("$admin/$table/model");

	$ferramenta = isset($_GET['ferramenta'])?$_GET['ferramenta']:"";

	if ($ferramenta!="") {
		$sql = "INSERT into ferramenta (nome,cod,descricao,table_name) values ('$table','$ferramenta','Permissão para Cadastrar/Alterar/Remover cadastros de $table. ','$table') ";	
		$dbStatment = $db->prepare($sql);
		$dbStatment->execute();
		$ferramenta_id = $db->lastInsertId();
		
		// Add access to  ADMIN/ADMIN
		$sql2 = "INSERT into permissao (fkNivel,fkFerramenta) values(1,'$ferramenta_id')";
		$dbStatment = $db->prepare($sql2);
		$dbStatment->execute();
	}
	$dbStatment = $db->prepare("SHOW COLUMNS FROM  $table ");
	$dbStatment->execute();
 
	$colunas = $dbStatment->fetchAll();
	$mask_list = $colunas[1]['Field'];



$data_model =
'<?php
class '.$table.'_model extends LoopModel
{

	function __construct()
	{
		parent::__construct();
	}
}
?>';

file_put_contents("$admin/$table/model/$table.class.php", $data_model);

?>