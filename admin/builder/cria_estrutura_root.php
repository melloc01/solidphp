<?php
	$root = '../..';
	$admin = '..';
	$table = $_GET['table'];
	$static = ( isset($_GET['static']))? "true" : "false" ;
	mkdir("$root/$table");
	mkdir("$root/$table/control");
	mkdir("$root/$table/view");
	mkdir("$root/$table/css");
	mkdir("$root/$table/js");
	mkdir("$root/$table/uploads");

	$data_control = 
'<?php
/**
 * ROOT CONTROL
 */  
class '.$table.'_control extends LoopControl
{
	function __construct()
	{
		parent::__construct();
	}

	public function home()
	{
		require(ROOT."'.$table.'/view/home.php");
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

$home =
"
<link rel='stylesheet'  href='./$table/css/$table.css'>
<script src='./$table/js/$table.js'></script>
home de $table
";

	file_put_contents("$root/$table/control/".$table."_control.php", $data_control);
	file_put_contents("$root/$table/css/$table.css","");
	file_put_contents("$root/$table/js/$table.js","");
	file_put_contents("$root/$table/view/home.php",$home);
