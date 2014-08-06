<?php
/**
 * ROOT CONTROL
 */  
class wiki_control extends LoopControl
{
	public $registros;
	public $Form;
	public $Model;
	public $static = true;
	public $hasHead = true;
	public $hasFooter = false;
	 
	function __construct()
	{
		parent::__construct();
	}
	public function home()
	{
		$this->render(ROOT."wiki/view/home.php");
	}
}
?>

