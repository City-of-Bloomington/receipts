<?php
	require_once(APPLICATION_HOME."/classes/LineItem.inc");
	require_once(GLOBAL_INCLUDES."/classes/MySQLResultIterator.inc");

	class LineItemList extends MySQLResultIterator
	{
		public function find($fields=null,$sorting = "lineItemID")
		{
			if ($fields)
			{
				$options = array();
				$sql = "select lineItemID from lineItems";

				if (isset($fields['lineItemID'])) { $options[] = "lineItemID=$fields[lineItemID]"; }
				if (isset($fields['receiptID'])) { $options[] = "receiptID=$fields[receiptID]"; }
				if (isset($fields['feeID'])) { $ptions[] = "feeID=$fields[feeID]"; }
				if (isset($fields['quantity'])) { $options[] = "quantity=$fields[quantity]"; }
				if (isset($fields['amount'])) { $options[] = "amount='$fields[amount]'"; }
				if (isset($fields['notes']))
				{
					if ($fields['notes'] != "null") { $optionsp[] = "notes='$fields[notes]'"; }
					else { $options[] = "notes is null"; }
				}

				$where = " where ".implode(" and ",$options);
				$sql.= "$where order by $sorting";
			}
			else { $sql = "select lineItemID from lineItems order by $sorting"; }

			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		protected function loadCurrent()
		{
			if (mysql_num_rows($this->result))
			{
				list($lineItemID) = mysql_fetch_array($this->result);
				$this->current = new LineItem($lineItemID);
			}
		}
	}
?>

