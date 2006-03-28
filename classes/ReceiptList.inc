<?php
	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	require_once(GLOBAL_INCLUDES."/classes/MySQLResultIterator.inc");

	class ReceiptList extends MySQLResultIterator
	{
		private $where = "";
		private $joins = "";

		public function find($fields=null,$sorting="receiptID")
		{
			if ($fields)
			{
				$options = array();
				if (isset($fields['receiptID'])) { $options[] = "receiptID=$fields[receiptID]"; }
				if (isset($fields['date'])) { $options[] = "date='$fields[date]"; }
				if (isset($fields['enteredBy'])) { $options[] = "enteredBy=$fields[enteredBy]"; }
				if (isset($fields['firstname'])) { $options[] = "firstname='$fields[firstname]'"; }
				if (isset($fields['lastname'])) { $options[] = "lastname='$fields[lastname]'"; }
				if (isset($fields['paymentMethod'])) { $options[] = "paymentMethod='$fields[paymentMethod]'"; }
				if (isset($fields['status'])) { $options[] = "status='$fields[status]'"; }

				if (isset($fields['depositSlipDate']))
				{
					if ($fields['depositSlipDate'] != "null") { $options[] = "depositSlipDate='$fields[depositSlipDate]'"; }
					else { $options[] = "depositSlipDate is null"; }
				}
				if (isset($fields['notes']))
				{
					if ($fields['notes'] != "null") { $options[] = "match (receipts.notes) against ('$fields[notes]')"; }
					else { $options[] = "receipt.notes is null"; }
				}

				# If we want to find on these fields, we have to join the lineItems and fees tables.
				if (isset($fields['accountID']) || isset($fields['feeID']))
				{
					$this->joins = " left join lineItems using (receiptID) left join fees using (feeID)";
					if (isset($fields['accountID'])) { $options[] = "accountID=$fields[accountID]"; }
					if (isset($fields['feeID'])) { $options[] = "lineItems.feeID=$fields[feeID]"; }
				}

				$this->where = "where ".implode(" and ",$options);
				$sql = "select distinct receiptID from receipts {$this->joins} {$this->where} order by $sorting";
			}
			else
			{
				$sql = "select receiptID from receipts order by $sorting";
			}

			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		public function search($month=null,$day=null,$year=null,
								$firstname=null,$lastname=null,$paymentMethod=null,
								$depositSlipMonth=null,$depositSlipDay=null,$depositSlipYear=null,
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

			if ($depositSlipMonth && $depositSlipDay && $depositSlipYear) { $options[] = "depositSlipDate='$depositSlipYear-$depositSlipMonth-$depositSlipDay'"; }
			else
			{
				if ($depositSlipMonth) { $options[] = "month(depositSlipDate)=$depositSlipMonth"; }
				if ($depositSlipDay) { $options[] = "dayofmonth(depositSlipDate)=$depositSlipDay"; }
				if ($depositSlipYear) { $options[] = "year(depositSlipDate)=$depositSlipYear"; }
			}

			if ($firstname) { $options[] = "firstname like '$firstname%'"; }
			if ($lastname) { $options[] = "lastname like '$lastname%'"; }
			if ($paymentMethod) { $options[] = "paymentMethod='$paymentMethod'"; }
			if ($receiptNotes) { $options[] = "match (receipts.notes) against ('$receiptNotes')"; }

			# If we want to find on these fields, we have to join the lineItems and fees tables.
			if ($lineItemName || $lineItemNotes)
			{
				$this->joins = " left join lineItems using (receiptID) left join fees using (feeID)";
				if ($lineItemName) { $options[] = "name='$lineItemName'"; }
				if ($lineItemNotes) { $options[] = "match (lineItems.notes) against ('$lineItemNotes')"; }
			}


			if (count($options))
			{
				$this->where = "where ".implode(" and ",$options);
				$sql = "select distinct receiptID from receipts {$this->joins} {$this->where}";
			}
			else { $sql = "select receiptID from receipts"; }
			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}


		public function getTotalAmount($paymentMethod=null)
		{
			if ($paymentMethod)
			{
				if ($this->where) { $where = $this->where." and paymentMethod='$paymentMethod' and status='valid'"; }
				else { $where = "where paymentMethod='$paymentMethod' and status='valid'"; }
			}
			else { $where = $this->where." and status='valid'"; }

			# Total amounts will always have to join lineItems and fees, no matter what.
			$sql = "select sum(amount) from receipts left join lineItems using (receiptID) left join fees using (feeID) $where";
			$temp = mysql_query($sql) or die($sql.mysql_error());
			list($amount) = mysql_fetch_array($temp);
			return $amount;
		}

		public function getMinReceiptID()
		{
			$sql = "select min(receiptID) from receipts {$this->joins} {$this->where}";
			$temp = mysql_query($sql) or die($sql.mysql_error());
			list($receiptID) = mysql_fetch_array($temp);
			return $receiptID;
		}

		public function getMaxReceiptID()
		{
			$sql = "select max(receiptID) from receipts {$this->joins} {$this->where}";
			$temp = mysql_query($sql) or die($sql.mysql_error());
			list($receiptID) = mysql_fetch_array($temp);
			return $receiptID;
		}

		public function getMinYear()
		{
			$sql = "select min(year(date)) from receipts {$this->joins} {$this->where}";
			$temp = mysql_query($sql) or die($sql.mysql_error());
			list($year) = mysql_fetch_array($temp);
			return $year;
		}

		public function getMaxYear()
		{
			$sql = "select max(year(date)) from receipts {$this->joins} {$this->where}";
			$temp = mysql_query($sql) or die($sql.mysql_error());
			list($year) = mysql_fetch_array($temp);
			return $year;
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