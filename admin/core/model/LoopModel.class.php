<?php  

class LoopModel 
{
	public $db;
	public $table;
	private $limit;
	private $Util;

	function __construct()
	{		
		$aux = explode('_model', get_class($this));
		$this->table = $aux[0];
		$this->Util = new Util();
		$this->setLimit();
	}
	
	public function connect()
	{
		if (!is_object($this->db)) {
			$this->db = Conexao::getInstance();
		}
	}

	public function getLastInsertId()
	{	
		$this->connect();
		return $this->db->lastInsertId();
	}

	public function getRegistroPlusFKROWS($idRow, $table = false)
	{
		$table =  ($table) ? $table : $this->table; 

		$colunas = $this->Util->getColumnsFromTable($table);
		$i=1;

		$sql = "SELECT * FROM  $table tbl ";
		foreach ($colunas as $key => $coluna) {
			$fkTable = $this->Util->getFkTable($coluna['Field']);
			if ($fkTable) {
				$sql .= "join $fkTable tbl_$i on ( tbl.{$coluna['Field']} = tbl_$i.id) where tbl.id=$idRow";
				$i++;
			}
		}
		return $this->runSQL_unique($sql);
	}

	public function runSQL_unique($sql)
	{
		$this->connect();
		$dbStatment = $this->db->prepare($sql);

		if($dbStatment->execute() && $dbStatment->rowCount() != 0)
			return $dbStatment->fetch();

		return null;
	}

	public function runSQL($sql)
	{
		$this->connect();
		$dbStatment = $this->db->prepare($sql);
		$dbStatment->execute();

		if($dbStatment->errorCode() == 0) {
			return $dbStatment->fetchAll();
		}
		else {
			$errors = $dbStatment->errorInfo();
			$_SESSION['mysql_error'] = $errors;
			require ADMIN.'core/view/system_messages.php';
		}
		return false;
	}

	/**
	 * 		function runQuery
	 *			@param String $sql SQL string to run on database 
	 *
	 */
	public function runQuery($sql)
	{
		$this->connect();
		$dbStatment = $this->db->prepare($sql);
		if ($dbStatment->execute()){
			return true;
		} else {
			$error = $dbStatment->errorInfo();
			$_SESSION['mysql_error'] = $error;
			return false;
		}
	}
	

	/**
	 * 		function runPDOQuery
	 *			@param String $sql SQL string to run on database 
	 *
	 *		Example : 	$sql = "SELECT * FROM table WHERE  id=? AND name=?"
	 *					$param_array = array(1, 'john');
	 *
	 */
	public function runPDOQuery($sql, $param_array)
	{
		$this->connect();
		$dbStatment = $this->db->prepare($sql);
		if ($dbStatment->execute($param_array)){  //PDO method
			return true;
		} else {
			$error = $dbStatment->errorInfo();
			$_SESSION['mysql_error'] = $error;
			return false;
		}
	}


	/**
	 *  startTransaction();
	*/
	public function startTransaction()
	{
		$this->connect();
		$this->db->beginTransaction();
	}

	/**
	 *  COMMIT();
	*/
	public function commit()
	{
		$this->connect();
		$this->db->commit();
	}
	/**
	 *  COMMIT();
	*/
	public function rollback()
	{
		$this->connect();
		$this->db->rollBack();
	}

	public function getRegistros($search="",$order="", $attributes="*")
	{
		$sql = " select $attributes from $this->table";
		if ($search!=""){
			$sql .=" where  $search ";
		}
		if ($order!="") {
			$sql .=" order by $order";
		}

		$sql .= " LIMIT 0, $this->limit";

		return $this->runSQL($sql);
	
	}

	public function getRegistro($value,$key="id",$and_search="")
	{
		$sql = " select * from $this->table where $key = '$value' $and_search";
		return $this->runSQL_unique($sql);
	}

	public function getMaxVal($col="id")
	{
		$sql = " select max($col) from $this->table";

		$dbStatment = $this->db->prepare($sql);

		if($dbStatment->execute()){
			$row =  $dbStatment->fetch();
			return intval($row[0]);
		}
		return null;
	}

	public function getNextID()
	{
		$sql = "select auto_increment from INFORMATION_SCHEMA.tables where table_name = '$this->table'";
		
		$row = $this->runSQL_unique($sql);

		if($row){
			return intval($row[0]);
		}
		return null;
	}

	/* trata insercao de quando existe _GET['cad']*/
	
		public function submit_insert($historico=false, $uploaddir = null )
	{
		$tabelas[] = array();
		$control_variables = array("l","sl","ins");
		$uploaddir = $uploaddir == null ? "../$this->table/uploads/" : $uploaddir;

		if ($_FILES) {
			if ( $uploaddir == "../$this->table/uploads/" ) 
				if ( !is_dir("../$this->table/uploads/") ){
					mkdir("../$this->table");
					mkdir("../$this->table/uploads");
				}
			$i=1;
			foreach ($_FILES as $key => $FILE) {
				if ( ($FILE['name'] != "") && ($FILE['name'] != array("")) ) { //foi upado
					$extensao = explode('.', $FILE['name']) ;
					$extensao = $extensao[1];
					$nome_arquivo = $this->Util->hashNameGenerator().".$extensao";
					$_POST[$key] = $nome_arquivo;
					$move = move_uploaded_file($FILE['tmp_name'], "{$uploaddir}{$nome_arquivo}");
					// $i++;
				}
			}
		}
		 //getting all tables involved in the submit action
		foreach ($_POST as $key => $value) {
			if (!in_array($key, $control_variables)) {
				$tabela_1 = $this->FORM_getTableName($key);
				if (!in_array($key, $control_variables)) {
					if (!in_array($tabela_1, $tabelas)) {
						$tabelas[] = $this->FORM_getTableName($key); 
					}
				}
			}
		}

	foreach ($tabelas as $key => $tabela) { //foreach table it prepares the insert info
		$next_id = $this->getNextID();
		if ($tabela) {
			if ($_FILES) {
				foreach ($_FILES as $key => $FILE) {
					if ($FILE['name'] == "files") {
						$extensao = explode('.', $FILE['name']) ;
						$extensao = $extensao[1];
						$nome_arquivo = $this->table."_".$next_id."_img_".$i.".".$extensao;							
						$_POST[$key] = $nome_arquivo;
					}
				}
			}
			$insert_into = "insert into $tabela ( ";
				$values = "VALUES ( ";
					foreach ($_POST as $key => $value) {
						if (!in_array($key, $control_variables)) {
							$aux = explode(":", $key);
							$tabela_atual = $this->FORM_getTableName($key);
							$campo_atual = end($aux);
							if ($tabela_atual == $tabela) {
								$insert_into .= " $campo_atual, ";
								$values .= " '$value', ";				
							}
						}
					}
					$insert_into.= ")";
			$values .= ")";

			$sql = $insert_into." ".$values.";";
			$sql = str_replace(", )", ")", $sql);


			return $this->runQuery($sql);
			
		}
	
	}
	if ($historico) {
		$this->addHistorico('Inserção',$this->table,$next_id,$sql);
	}
	return true;
}


public function submit_remove($historico=false)
{
	$id = $_POST['del'];
	$sql = " delete from $this->table where id = $id";
	$texto ="";
	$registro = $this->getRegistro($id);
		// checking if unlink is needed
	foreach ($registro as $coluna => $valor) {
		if (stripos($coluna, "_arquivo") || stripos($coluna, "_file") || stripos($coluna, "_img")  ) {
			if (file_exists("./$this->table/uploads/$valor")) {
				unlink("./$this->table/uploads/$valor");
			}
		}
	}

	$str="|";
	$i=0;
	foreach ($registro as $key => $value) {
		if ($i%2==0) {
			$str .= "$key = $value |";
		}
		$i++;
	}
	$str .= "
	sql = $sql";

	$dbStatment = $this->db->prepare($sql);
	if($dbStatment->execute()){
		if ($historico) {
			$this->addHistorico('Remoção',$this->table,$id,$str);
		} 
		return true;
	} else{
		$errors = $dbStatment->errorInfo();
			// exit(var_dump($errors));
		$_SESSION['mysql_error'] = $errors;
	}
	return false;
}

public function submit_publicado($historico=false)
{
	$id = $_POST['id'];
	$publicado = (intval($_POST['pub'])== 0)? 1 : 0;


	$sql = " update $this->table set publicado = $publicado  where id = $id";

	

	if($this->runQuery($sql)){
		if ($historico) {
			$this->addHistorico('Remoção',$this->table,$id,$str);
			$registro = $this->getRegistro($id);
			$str="|";
			$i=0;
			foreach ($registro as $key => $value) {
				if ($i%2==0) {
					$str .= "$key = $value |";
				}
				$i++;
			}
			$str .= "
			sql = $sql";
		} 
		return true;
	}
	return false;
}

public function checkUnlinkFile($id,$campo)
{
	$registro = $this->getRegistro($id);
	$aux = explode(":", $campo);
	$campo = end($aux);
			// exit($registro[$campo]);
			// if ($registro[$campo] != nu\ll) {
			// 	$path = "./$this->table/uploads/{$registro[$campo]}";
			// 	if (file_exists($path)) {
			// 		unlink("$path");
			// 	}
			// }
}

/**
 *  function insert()
 *
 *	@param $array_insert ( 'column' => 'value')
 *
 *
 */
public function insert($array_insert)
{
	$sql_statement = "INSERT INTO $this->table ( ";
	$values_statement = ") VALUES (";
	$param_array  = array();

	foreach ($array_insert as $key => $value){
		array_push($param_array, $value);
		$value = mysql_real_escape_string($value);
		$sql_statement .= " $key,";
		$values_statement .= " ?,";
	}
	//delete last comas
	$sql_statement = substr($sql_statement, 0, -1);
	$values_statement = substr($values_statement, 0, -1);
	$values_statement .= " ) ";

	$sql = $sql_statement.$values_statement;


	return $this->runPDOQuery($sql,$param_array) ? $this->db->lastInsertId() : false;
}
/**
 *  function insert()
 *
 *	@param $array_insert ( 'column' => 'value')
 *
 *
 */
public function update($id,$array_insert,$search_key = 'id')
{
	$sql_statement = "UPDATE $this->table SET  ";
	$values_statement = ") VALUES (";
	$param_array  = array();

	foreach ($array_insert as $key => $value){
		array_push($param_array, $value);
		$sql_statement .= " $key = ?,";
	}
	//delete last comas
	$sql_statement = substr($sql_statement, 0, -1);
	$sql_statement .= " WHERE $search_key = '$id' ";
	echo "$sql_statement";
	return $this->runPDOQuery($sql_statement,$param_array);
}

public function submit_update($historico=false,$chave="id",$id = 0)
{		
	$id = $_POST["upd"];
	$update = "update  $this->table SET ";
	$values = "";
	$where = "where $chave = $id";
	$control_variables = array("l","sl","upd","id");
	$i=0;
			//arrumo o $_POST para ter também os $_FILES
	if ($_FILES) {
		$hash_rand = rand(0,9999);
		foreach ($_FILES as $key => $file) {
			if ($file['name'] == "files") {
				var_dump($_FILES);
				$i++;
				$extensao = explode('.', $file['name']) ;
				$extensao = $extensao[1];
				$nome = $hash_rand."_$this->table"."_".$i;
				$nome_arquivo = $nome.'.'.$extensao;			
						// if (!isset($_POST[$key])) {
				$_POST[$key] = $nome_arquivo;
						// }			
				$uploaddir= "./$this->table/uploads/";
						// $this->checkUnlinkFile($id,$key);
				move_uploaded_file($file['tmp_name'], $uploaddir . $nome_arquivo);
			}
		}
	}

			// monto o SQL
			// exit(var_dump($_POST)."nome = $nome_arquivo");
	foreach ($_POST as $key => $value) {
		if (!in_array($key, $control_variables)) {
			$aux = explode(":", $key);
			$campo = end($aux);
			$update .= " $campo= '$value' ,";
		}
	}

	$sql = $update.$where.";";
	$sql = str_replace(",w", "w", $sql);

	if($this->runQuery($sql)){
		if ($historico) {
			$registro = $this->getRegistro($id);
			$str="|";
			$i=0;
			foreach ($registro as $key => $value) {
				if ($i%2==0) {
					$str .= "$key = $value |";
				}
				$i++;
			}
			$str .= "
			sql = $sql";
			$this->addHistorico('Edição',$this->table,$id,$str);
		}
			// exit("nome : $nome_arquivo <br>".var_dump($_POST)."<br>".$sql);
		return true;
	}
	return false;
}

public function addHistorico($operacao, $tabela,$idTupla,$sql="")
{
	if (isset($_SESSION["user"])) {
		$user = $_SESSION["user"]["email"];
		$sql = "INSERT INTO historico(titulo,data,sql_backup) VALUES (CONCAT('$operacao em $tabela :: ref.: $idTupla, por : $user, em : ',CURRENT_TIMESTAMP),CURRENT_TIMESTAMP,\"$sql\")";
	}
	else{
		$sql = "INSERT INTO historico(titulo,data,sql_backup) VALUES (CONCAT('$operacao em $tabela :: ref.: $idTupla ',CURRENT_TIMESTAMP),CURRENT_TIMESTAMP,\"$sql\")";
	}
	// echo "$sql";


	$dbStatment = $this->db->prepare($sql);
	if($dbStatment->execute())
		return true;
	return false;

}

	public function updateRegistro($attr,$value,$cond="0")
	{
		$sql = " UPDATE $this->table SET $attr = $value where $cond";
		$dbStatment = $this->db->prepare($sql);
		return $sql;
	}

		
	public function FORM_getTableName($post_key)
	{
		$aux = explode(":", $post_key);
		return $aux[0];
	}
	
	public function deleteRegistro($cond)
	{
		$this->connect();
		$sql = " DELETE FROM $this->table where $cond";

		if ($this->runQuery($sql)) {
			return true;
		}
		return false;
	}
	public function setLimit($value = 20)
	{
		$this->limit = 20;
	}


}

?>