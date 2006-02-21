<?php
	class LineItem
	{
		private $lineItemID;
		private $receiptID;
		private $feeID;
		private $quantity;
		private $amount;

		public function __construct($id=null)
		{
			if ($id)
			{
				$sql = "select * from lineItems where lineItemID={$this->lineItemID}";
				$result = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($result))
				{
					$lineItem = mysql_fetch_array($result);
					$this->lineItemID = $id;
					$this->receiptID = $lineItem['receiptID'];
					$this->feeID = $lineItem['feeID'];
					$this->quantity = $lineItem['quantity'];
					$this->amount = $lineItem['amount'];
				}
				else { throw new Exception($sql); }
			}
		}

		public function save()
		{
			if ($this->lineItemID) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			$sql = "update lineItems set receiptID={$this->receiptID},feeID={$this->feeID},quantity={$this->quantity},amount='{$this->amount}' where lineItemID={$this->lineItemID}";
			mysql_query($sql) or die($sql.mysql_error());
		}

		private function insert()
		{
			$sql = "insert lineItems set receiptID={$this->receiptID},feeID={$this->feeID},quantity={$this->quantity},amount='{$this->amount}'";
			mysql_query($sql) or die($sql.mysql_error());
			$this->lineItemID = mysql_insert_id();
		}

		public function getLineItemID() { return $this->lineItemID; }
		public function getReceiptID() { return $this->receiptID; }
		public function getFeeID() { return $this->feeID; }
		public function getQuantity() { return $this->quantity; }
		public function getAmount() { return $this->amount; }

		public function getFee() { return new Fee($this->feeID); }

		public function setLineItemID($lineItemID) { $this->lineItemID = $lineItemID; }
		public function setReceiptID($receiptID) { $this->receiptID = $receiptID; }
		public function setFeeID($feeID) { $this->feeID = $feeID; }
		public function setQuantity($quantity) { $this->quantity = $quantity; }
		public function setAmount($amount) { $this->amount = $amount; }
	}
?>
