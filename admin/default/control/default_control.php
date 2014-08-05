<?
/**
 * ADMIN CONTROL
 */  
class default_control extends LoopControl
{
	var $registros;

	public function home()
	{
		$this->setPageTitle("CasaControle");
		$this->render(ADMIN."default/view/home.php", get_defined_vars());
	}

}
?>