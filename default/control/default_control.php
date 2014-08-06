<?php
class default_control extends LoopControl
{

	public function __construct()
	{
		Kint::dump($_GET);
	}

	public function home()
	{
		$this->render(ROOT."default/view/home.php",get_defined_vars());
	}

	public function hello()
	{
		echo "Hello World";
	}

}