<?php
	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	require_once(GLOBAL_INCLUDES."/classes/MySQLResultIterator.inc");

	class ReceiptList extends MySQLResultIterator
	{
		public function findAll($sorting="receiptID")
		{
			$sql = "select receiptID from receipts order by $sorting";
			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		protected function loadCurrent()
		{
			if (mysql_num_rows($this->result))
			{
				list($receiptID) = mysql_fetch_array($this->result);
				$this->current = new Receipt($receiptID);
			}
		}
	}
?>

