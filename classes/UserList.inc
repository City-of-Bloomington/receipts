<?php
	require_once(GLOBAL_INCLUDES."/classes/SystemUserList.inc");

	class UserList extends SystemUserList
	{
		public function findAll($sort = "username")
		{
			$sql = "select userID from users order by $sort";
			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}
	}
?>
