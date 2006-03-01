<?php
	require_once(APPLICATION_HOME."/classes/Fee.inc");
	require_once(GLOBAL_INCLUDES."/classes/MySQLResultIterator.inc");

	class FeeList extends MySQLResultIterator
	{
		public function find($fields=null,$sortby = "name")
		{
			if ($fields)
			{
				$options = array();
				$sql = "select feeID from fees";
				if (isset($fields['feeID'])) { $options[] = "feeID=$fields[feeID]"; }
				if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
				if (isset($fields['accountID'])) { $options[] = "accountID=$fields[accountID]"; }
				if (isset($fields['amount']))
				{
					if ($fields['amount']!="null") { $options[] = "amount='$fields[amount]'"; }
					else { $options[] = "amount is null"; }
				}
				$where = " where ".implode(" and ",$options);
				$sql.= "$where order by $sortby";
			}
			else { $sql = "select feeID from fees order by $sortby"; }

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
