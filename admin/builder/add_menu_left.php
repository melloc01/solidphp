<?
	include_once '../core/config/Conexao.class.php';
	$db = Conexao::getInstance();
	$table = $_GET['table'];

	$sql = "SELECT * FROM  access_tool where table_name = '$table'";
	$dbStatment = $db->prepare($sql);
	$dbStatment->execute();
	$linha = $dbStatment->fetch();
	
	$ferramentaID = $linha['id'];

	$sql2 = "INSERT INTO menul (mask,link,fkaccess_tool) VALUES ('$table','$table',$ferramentaID);";


	$dbStatment = $db->prepare($sql2);
	$dbStatment->execute();

?>