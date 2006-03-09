<?php
	require_once(APPLICATION_HOME."/classes/Account.inc");
	require_once(GLOBAL_INCLUDES."/classes/MySQLResultIterator.inc");

	class AccountList extends MySQLResultIterator
	{
		public function find($fields=null,$sortby = "name")
		{
			if ($fields)
			{
				$options = array();
				$sql = "select accountID from accounts";
				if (isset($fields['accountID'])) { $options[] = "accountID=$fields[accountID]"; }
				if (isset($fields['accountNumber'])) { $options[] = "accountNumber='$fields[accountNumber]'"; }
				if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }

				$where = " where ".implode(" and ",$options);
				$sql.= "$where order by $sortby";
			}
			else { $sql = "select accountID from accounts order by $sortby"; }

			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		protected function loadCurrent()
		{
			if (mysql_num_rows($this->result))
			{
				list($accountID) = mysql_fetch_array($this->result);
				$this->current = new Account($accountID);
			}
		}
	}
?>