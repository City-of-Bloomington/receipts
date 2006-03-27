<?php
	require_once(APPLICATION_HOME."/classes/User.inc");
	require_once(APPLICATION_HOME."/classes/LineItemList.inc");
	class Receipt
	{
		private $receiptID;
		private $date;
		private $enteredBy;
		private $firstname;
		private $lastname;
		private $paymentMethod;
		private $depositSlipDate;
		private $notes;

		private $status;
		private $voidedDate;
		private $voidedReason;
		private $voidedBy;

		private $lineItems = array();

		public function __construct($id = null)
		{
			if ($id)
			{
				$sql = "select * from receipts where receiptID=$id";
				$result = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($result))
				{
					$receipt = mysql_fetch_array($result);
					$this->receiptID = $id;
					$this->date = $receipt['date'];
					$this->enteredBy = $receipt['enteredBy'];
					$this->firstname = $receipt['firstname'];
					$this->lastname = $receipt['lastname'];
					$this->paymentMethod = $receipt['paymentMethod'];
					$this->status = $receipt['status'];
					if ($receipt['depositSlipDate']) { $this->depositSlipDate = $receipt['depositSlipDate']; }
					if ($receipt['notes']) { $this->notes = $receipt['notes']; }

					$this->loadLineItems();

					# Load any voided data
					if ($this->status == 'void')
					{
						$sql = "select * from voidedReceipts where receiptID={$this->receiptID}";
						$result = mysql_query($sql) or die($sql.mysql_error());
						if (mysql_num_rows($result))
						{
							$void = mysql_fetch_array($result);
							$this->voidedDate = $void['voidedDate'];
							$this->voidedReason = $void['voidedReason'];
							$this->voidedBy = $void['voidedBy'];
						}
					}

				}
				else { throw new Exception($sql); }
			}
			else
			{
				# Create a new empty receipt
				$now = getdate();
				$this->date = "$now[year]-$now[mon]-$now[mday]";
				$this->enteredBy = $_SESSION['USER']->getUserID();
				$this->status = 'valid';
			}
		}

		public function void($voidedBy,$voidedReason)
		{
			if ($this->depositSlipDate) { throw new Exception("receiptAlreadyDeposited"); }

			$now = getdate();
			$this->status = 'void';
			$this->voidedDate = "$now[year]-$now[mon]-$now[mday]";
			$this->voidedReason = sanitizeString($voidedReason);
			$this->voidedBy = $voidedBy;
		}


		public function save()
		{
			if ($this->receiptID) { $this->update(); }
			else { $this->insert(); }

			foreach($this->lineItems as $lineItem) { $lineItem->save(); }
			if ($this->status == 'void') { $this->saveVoid(); }
		}

		private function update()
		{
			$sql = "update receipts set date='{$this->date}',enteredBy={$this->enteredBy},firstname='{$this->firstname}',lastname='{$this->lastname}',
					paymentMethod='{$this->paymentMethod}',status='{$this->status}'";
			if ($this->depositSlipDate) { $sql.=",depositSlipDate='{$this->depositSlipDate}'"; } else { $sql.=",depositSlipDate=null"; }
			if ($this->notes) { $sql.=",notes='{$this->notes}'"; } else { $sql.=",notes=null"; }
			$sql.=" where receiptID={$this->receiptID}";
			mysql_query($sql) or die($sql.mysql_error());
		}

		private function insert()
		{
			$sql = "insert receipts set date='{$this->date}',enteredBy={$this->enteredBy},firstname='{$this->firstname}',lastname='{$this->lastname}',
					paymentMethod='{$this->paymentMethod}',status='{$this->status}'";
			if ($this->depositSlipDate) { $sql.=",depositSlipDate='{$this->depositSlipDate}'"; }
			if ($this->notes) { $sql.=",notes='{$this->notes}'"; }
			mysql_query($sql) or die($sql);
			$this->receiptID = mysql_insert_id();

			foreach($this->lineItems as $lineItem) { $lineItem->setReceiptID($this->receiptID); }
		}

		private function saveVoid()
		{
			$sql = "select receiptID from voidedReceipts where receiptID={$this->receiptID}";
			$result = mysql_query($sql) or die($sql.mysql_error());
			if (mysql_num_rows($result)) { $sql = "update voidedReceipts set voidedDate='{$this->voidedDate}',voidedReason='{$this->voidedReason}',voidedBy='{$this->voidedBy}' where receiptID={$this->receiptID}"; }
			else { $sql = "insert voidedReceipts set receiptID={$this->receiptID},voidedDate='{$this->voidedDate}',voidedReason='{$this->voidedReason}',voidedBy='{$this->voidedBy}'"; }
			mysql_query($sql) or die($sql.mysql_error());
		}

		private function loadLineItems()
		{
			$lineItemList = new LineItemList();
			$lineItemList->find(array("receiptID"=>$this->receiptID));
			foreach($lineItemList as $lineItem)
			{
				$this->lineItems[] = $lineItem;
			}
		}

		public function getReceiptID() { return $this->receiptID; }
		public function getDate() { return $this->date; }
		public function getEnteredBy() { return $this->enteredBy; }
		public function getFirstname() { return $this->firstname; }
		public function getLastname() { return $this->lastname; }
		public function getPaymentMethod() { return $this->paymentMethod; }
		public function getDepositSlipDate() { return $this->depositSlipDate; }
		public function getNotes() { return $this->notes; }
		public function getLineItems() { return $this->lineItems; }
		public function getAmount()
		{
			$amount = 0;
			foreach($this->lineItems as $lineItem) { $amount+= $lineItem->getAmount(); }
			return $amount;
		}

		public function getStatus() { return $this->status; }
		public function getVoidedDate() { return $this->voidedDate; }
		public function getVoidedReason() { return $this->voidedReason; }
		public function getVoidedByUser() { if ($this->voidedBy) { return new User($this->voidedBy); } else { return false; } }

		public function getEnteredByUser() { return new User($this->enteredBy); }


		public function setReceiptID($receiptID) { $this->receiptID = $receiptID; }
		public function setDate($date) { $this->date = $date; }
		public function setEnteredBy($userID) { $this->enteredBy = $userID; }
		public function setFirstname($firstname) { $this->firstname = sanitizeString($firstname); }
		public function setLastname($lastname) { $this->lastname = sanitizeString($lastname); }
		public function setPaymentMethod($paymentMethod) { $this->paymentMethod = $paymentMethod; }
		public function setDepositSlipDate($depositSlipDate) { $this->depositSlipDate = $depositSlipDate; }
		public function setNotes($notes) { $this->notes = sanitizeString($notes); }

		public function addLineItem($lineItem) { $this->lineItems[] = $lineItem; }
	}
?>