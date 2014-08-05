<?php
/**
 * ROOT CONTROL
 */  
class wiki_control extends LoopControl
{
	var $registros;
	var $Form;
	var $Model;
	var $static = true;
	var $hasHead = true;
	var $hasFooter = false;
	 
	function __construct()
	{
		parent::__construct();
		$this->submit();
		$this->route();
	}

	public function init($tool="")
	{
		if (!$this->static) {
			$this->addModels(array("wiki"));
		}
		parent::init();
		
		if (!$this->static) {
			$this->Model = new wiki_model;
			$this->Form = new Form("wiki");
		}
	}
	public function home()
	{
		include_once(ROOT."wiki/view/home.php");
	}
}
?>

