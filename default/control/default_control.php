<?php
/**
 *
 *   default_control class
 *
 *		This class handles all calls by not existing,		
 *		ideal for static pages or that are not interactive ( such as one that needs parameters on URL )
 *			/project/contact
 *		The Controller will treate contact as a controller
 *			so /contact/action/actionValue
 *				/controller/action/actionValue/param1/param1.val/param2/param2.val
 *
 */

class default_control extends LoopControl
{

	public function __construct()
	{
		parent::__construct('');
	}

	public function home()
	{
		$this->render(ROOT."default/view/home.php",get_defined_vars());
	}

	public function contact()
	{

	}

	public function oitenta($value='')
	{
		$this->renderPartials = false;
		$this->render(ROOT."default/view/home.php",get_defined_vars());
	}

}