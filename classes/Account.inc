<?php
	class Account
	{
		private $accountID;
		private $accountNumber;
		private $name;

		public function __construct($id=null)
		{
			if ($id)
			{
				$sql = "select * from accounts where accountID=$id";
				$result = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($result))
				{
					$account = mysql_fetch_array(mysql_query($sql)) or die($sql.mysql_error());

					$this->accountID = $id;
					$this->accountNumber = $account['accountNumber'];
					$this->name = $account['name'];
				}
				else { throw new Exception($sql); }
			}
		}

		public function save()
		{
			if ($this->accountID) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			$sql = "update accounts set accountNumber='{$this->accountNumber}',name='{$this->name}' where accountID={$this->accountID}";
			mysql_query($sql) or die($sql.mysql_error());
		}

		private function insert()
		{
			$sql = "insert accounts set accountNumber='{$this->accountNumber}',name='{$this->name}'";
			mysql_query($sql) or die($sql.mysql_error());
			$this->accountID = mysql_insert_id();
		}

		public function getAccountID() { return $this->accountID; }
		public function getAccountNumber() { return $this->accountNumber; }
		public function getName() { return $this->name; }

		public function setAccountID($accountID) { $this->accountID = $accountID; }
		public function setAccountNumber($accountNumber) { $this->accountNumber = $accountNumber; }
		public function setName($name) { $this->name = $name; }
	}
?>
