<?php
class access_tool_model extends LoopModel
{

	function __construct()
	{
		parent::__construct();
	}

	public function getToolLevel($levelID)
	{
		$sql = "SELECT act.*, ac.id  as hasAccess FROM access_tool act
				LEFT JOIN access ac ON (act.id = ac.fkaccess_tool )";
		return $this->runSQL($sql);
	}
}