<?php
	require_once(APPLICATION_HOME."/classes/User.inc");
	class Receipt
	{
		private $receiptID;
		private $date;
		private $enteredBy;
		private $firstname;
		private $lastname;
		private $paymentMethod;

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
					$this->paymentAmount = $receipt['paymentAmount'];

					$this->loadLineItems();
				}
				else { throw new Exception($sql); }
			}
			else
			{
				# Create a new empty receipt
				$now = getdate();
				$this->date = "$now[year]-$now[mon]-$now[mday]";
				$this->enteredBy = $_SESSION['USER']->getUserID();
			}
		}


		public function save()
		{
			if ($this->receiptID) { $this->update(); }
			else { $this->insert(); }

			foreach($this->lineItems as $lineItem) { $lineItem->save(); }
		}

		private function update()
		{
			$sql = "update receipts set date='{$this->date}',enteredBy={$this->enteredBy},firstname='{$this->firstname}',lastname='{$this->lastname}',
					paymentMethod='{$this->paymentMethod}' where receiptID={$this->receiptID}";
			mysql_query($sql) or die($sql.mysql_error());
		}

		private function insert()
		{
			$sql = "insert receipts set date='{$this->date}',enteredBy={$this->enteredBy},firstname='{$this->firstname}',lastname='{$this->lastname}',
					paymentMethod='{$this->paymentMethod}'";
			mysql_query($sql) or die($sql);
			$this->receiptID = mysql_insert_id();

			foreach($this->lineItems as $lineItem) { $lineItem->setReceiptID($this->receiptID); }
		}

		private function loadLineItems()
		{
			$lineItemList = new LineItemList();
			$lineItemList->findByReceiptID($this->receiptID);
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
		public function getLineItems() { return $this->lineItems; }
		public function getAmount()
		{
			$amount = 0;
			foreach($this->lineItems as $lineItem) { $amount+= $lineItem->getAmount(); }
			return $amount;
		}

		public function getEnteredByUser() { return new User($this->enteredBy); }

		public function setReceiptID($receiptID) { $this->receiptID = $receiptID; }
		public function setDate($date) { $this->date = $date; }
		public function setEnteredBy($userID) { $this->enteredBy = $userID; }
		public function setFirstname($firstname) { $this->firstname = $firstname; }
		public function setLastname($lastname) { $this->lastname = $lastname; }
		public function setPaymentMethod($paymentMethod) { $this->paymentMethod = $paymentMethod; }

		public function addLineItem($lineItem) { $this->lineItems[] = $lineItem; }
	}
?>