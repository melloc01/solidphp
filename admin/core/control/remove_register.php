<?
	$table = $_GET["table"];
	$id = $_GET["id"];

	$chave = "SHOW INDEX FROM post WHERE Key_name = 'PRIMARY'"; 	
	
	$chave = "Column_name";
	$sql = "DELETE FROM $table WHERE id = $id";

?>