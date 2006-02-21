<?php
	require_once(APPLICATION_HOME."/classes/LineItem.inc");
	require_once(GLOBAL_INCLUDES."/classes/MySQLResultIterator.inc");

	class LineItemList extends MySQLResultIterator
	{
		public function findAll($sorting = "lineItemID")
		{
			$sql = "select lineItemID from lineItems order by $sorting";
			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		public function findByReceiptID($receiptID,$sorting="lineItemID")
		{
			$sql = "select lineItemID from lineItems where receiptID=$receiptID order by $sorting";
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

