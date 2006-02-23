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

		public function findByDate($date,$sorting="receiptID")
		{
			$sql = "select receiptID from receipts where date='$date' order by $sorting";
			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		public function search($month=null,$day=null,$year=null,
								$firstname=null,$lastname=null,$paymentMethod=null,
								$lineItemName=null,$lineItemNotes=null,$receiptNotes=null)
		{
			$firstname = sanitizeString($firstname);
			$lastname = sanitizeString($lastname);
			$lineItemNotes = sanitizeString($lineItemNotes);
			$receiptNotes = sanitizeString($receiptNotes);

			$options = array();
			if ($month && $day && $year) { $options[] = "date='$year-$month-$day'"; }
			else
			{
				if ($month) { $options[] = "month(date)=$month"; }
				if ($day) { $options[] = "dayofmonth(date)=$day"; }
				if ($year) { $options[] = "year(date)=$year"; }
			}

			if ($firstname) { $options[] = "firstname like '$firstname%'"; }
			if ($lastname) { $options[] = "lastname like '$lastname%'"; }
			if ($paymentMethod) { $options[] = "paymentMethod='$paymentMethod'"; }
			if ($lineItemName) { $options[] = "name='$lineItemName'"; }
			if ($lineItemNotes) { $options[] = "match (lineItems.notes) against ('$lineItemNotes')"; }
			if ($receiptNotes) { $options[] = "match (receipts.notes) against ('$receiptNotes')"; }


			if (count($options))
			{
				$options = implode(" and ",$options);
				$sql = "select receiptID from receipts left join lineItems using (receiptID)
						left join fees using (feeID)
						where $options";
				$this->result = mysql_query($sql) or die($sql.mysql_error());
			}
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

