<?php
	require_once(APPLICATION_HOME."/classes/Fee.inc");
	require_once(GLOBAL_INCLUDES."/classes/MySQLResultIterator.inc");

	class FeeList extends MySQLResultIterator
	{
		public function findAll($sortby = "name")
		{
			$sql = "select feeID from fees order by $sortby";
			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		public function findByAccountID($accountID,$sortby="name")
		{
			$sql = "select feeID from fees where accountID=$accountID order by $sortby";
			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		protected function loadCurrent()
		{
			if (mysql_num_rows($this->result))
			{
				list($feeID) = mysql_fetch_array($this->result);
				$this->current = new Fee($feeID);
			}
		}
	}
?>
