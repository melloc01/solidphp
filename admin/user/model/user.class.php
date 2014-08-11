<?php
class user_model extends LoopModel
{

	function __construct()
	{
		parent::__construct();
	}


	public function getTools($levelID)
	{
		$sql = "SELECT act.code, acc.id IS NOT NULL as has_access
					FROM 
						access_tool act 
						LEFT JOIN access acc on ( acc.fkaccess_tool = act.id  AND acc.fkaccess_level = $levelID ) 

		";
		return $this->runSQL($sql);
	}

	public function submit_insert($historico=false, $uploaddir = null )
	{
		return parent::submit_insert(false,"./user/uploads/");
	}

	public function submit_update($historico=false,$chave="id",$id = 0, $uploaddir = null)
	{
		return parent::submit_update($historico,$chave,$id,'./user/uploads/');
	}

	
}