<?php
	require_once(APPLICATION_HOME."/classes/Account.inc");

	class Fee
	{
		private $feeID;
		private $name;
		private $amount;
		private $accountID;

		public function __construct($id=null)
		{
			if ($id)
			{
				$sql = "select * from fees where feeID=$id";
				$result = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($result))
				{
					$fee = mysql_fetch_array($result);
					$this->feeID = $id;
					$this->name = $fee['name'];
					$this->amount = $fee['amount'];
					$this->accountID = $fee['accountID'];
				}
				else { throw new Exception($sql); }
			}
		}

		public function save()
		{
			if ($this->feeID) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			$sql = "update fees set name='{$this->name}',amount='{$this->amount}',accountID={$this->accountID} where feeID={$this->feeID}";
			mysql_query($sql) or die($sql.mysql_error());
		}

		private function insert()
		{
			$sql = "insert fees set name='{$this->name}',amount='{$this->amount}',accountID={$this->accountID}";
			mysql_query($sql) or die($sql.mysql_error());
			$this->feeID = mysql_insert_id();
		}

		public function getFeeID() { return $this->feeID; }
		public function getName() { return $this->name; }
		public function getAmount() { return $this->amount; }
		public function getAccountID() { return $this->accountID; }
		public function getAccount() { return new Account($this->accountID); }

		public function setName($name) { $this->name = $name; }
		public function setAmount($amount) { $this->amount = $amount; }
		public function setAccountID($accountID) { $this->accountID = $accountID; }


	}
?>
