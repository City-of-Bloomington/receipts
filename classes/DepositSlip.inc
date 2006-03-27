<?php
	require_once(APPLICATION_HOME."/classes/ReceiptList.inc");

	class DepositSlip
	{
		private $date;
		private $receiptList;

		public function __construct($date=null)
		{
			if ($date)
			{
				# Dates used in the contructor must refer to already existing deposits slips.
				# Otherwise, you should call gatherNonDepositedReceipts()
				$sql = "select distinct depositSlipDate from receipts where depositSlipDate='$date'";
				$result = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($result))
				{
					# This date is in the system, go ahead and load it
					$this->date = $date;
					$this->receiptList = new ReceiptList();
					$this->receiptList->find(array("depositSlipDate"=>$this->date));
				}
				else { throw new Exception("noDepositSlipFound"); }
			}
			else
			{
				# Make sure we haven't already generated a depositSlip for today
				$sql = "select distinct depositSlipDate from receipts where depositSlipDate=now()";
				$result = mysql_query($sql) or die($sql.mysql_error());
				if (!mysql_num_rows($result))
				{
					# Create a new deposit slip and grab all the non-deposited receipts
					$this->receiptList = new ReceiptList();
					$this->receiptList->find(array("depositSlipDate"=>"null"));
				}
				else { throw new Exception("depositSlipAlreadyExists"); }
			}
		}

		public function save()
		{
			$now = getdate();
			$this->date = "$now[year]-$now[mon]-$now[mday]";

			foreach($this->receiptList as $receipt)
			{
				$receipt->setDepositSlipDate($this->date);
				$receipt->save();
			}
		}

		public function delete()
		{
			$now = getdate();
			if ($this->date != "$now[year]-$now[mon]-$now[mday]") { throw new Exception("receiptsAlreadyDeposited"); }

			# Set all the receipts for this deposit slip back to undeposited
			$sql = "update receipts set depositSlipDate=null where depositSlipDate='{$this->date}'";
			mysql_query($sql) or die($sql.mysql_error());

			unset($this->receiptList);
		}



		public function getDate() { return $this->date; }
		public function getReceipts($feeID=null)
		{
			if (!$feeID) { return $this->receiptList; }
			else
			{
				$receiptList = new ReceiptList();
				$receiptList->find( array("feeID"=>$feeID,"depositSlipDate"=>$this->date) );
				return $receiptList;
			}
		}
	}
?>