<?php
class test_control extends LoopControl
{
	public function __construct($tool="")
	{
		parent::__construct($tool);
	}

	public function home()
	{	
		$this->render(ADMIN."test/view/home.php");	
	}


}