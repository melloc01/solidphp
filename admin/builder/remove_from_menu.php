<?
	include_once '../core/config/Conexao.class.php';
	$db = Conexao::getInstance();
	$sql2 = "DELETE FROM menul where id = '{$_GET['id']}'";

	$dbStatment = $db->prepare($sql2);
	$dbStatment->execute();
?>