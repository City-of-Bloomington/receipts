<?php
	require_once(APPLICATION_HOME."/classes/Account.inc");

	class AccountList implements Iterator
	{
		private $key;
		private $result;
		private $currentAccount;

		public function findAll($sortby = "name")
		{
			$sql = "select * from accounts order by $sortby";
			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		public function rewind()
		{
			$this->key = 0;
			if (mysql_num_rows($this->result)) { mysql_data_seek($this->result,$this->key); }
			$this->loadCurrentAccount();
		}

		public function current() { return $this->currentAccount; }

		public function key() { return $this->key; }

		public function next()
		{
			$this->key++;
			$this->loadCurrentAccount();
		}

		public function valid()
		{
			if (mysql_num_rows($this->result)) { return $this->key < mysql_num_rows($this->result); }
			else { return false; }
		}

		private function loadCurrentAccount()
		{
			if (mysql_num_rows($this->result))
			{
				list($userID) = mysql_fetch_array($this->result);
				$this->currentAccount = new Account($userID);
			}
		}
	}
?>
