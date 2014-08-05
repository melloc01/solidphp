<?php  
	$staticPages  = array(
		'x' => 'x', 
		'y' => 'y'
	);

	if (isset($_GET["l"]) && $_GET["l"])
	{
		
		if (in_array($_GET["l"], $staticPages))
		{
			//$this->getDefaultBase();	
			include_once("templateHead.php");
			
			include_once("{$_GET["l"]}.php");

			include_once("templateRodape.php");	
		}
		else
		{
			if (file_exists(ROOT.$_GET["l"]."/control/".$_GET["l"]."_control.php"))
			{
				include_once(ROOT.$_GET["l"]."/control/".$_GET["l"]."_control.php"); 
				$control = $_GET["l"]."_control";
				$pagina = new $control; // instancia o control de 'l'
			}
			else
			{
				//echo "<h1>sorry! bad request.<br>'".$_GET["l"]."' doesnt exists</h1>";
				//// CUSTOMIZACAO
				$pagina = new LoopControl();
			}
		}
	}
	else // if not, call default behavior 
	{
		$pagina = new LoopControl();
	}
	include_once './core/util/util.class.php';

	class LoopControl extends Util
	{

		function __construct()
		{
			parent::__construct();
		}

	}
	$aux = explode("control/", __FILE__);
	$class = explode(".", $aux[1]);
	$tico = new $class[0]();

	?>