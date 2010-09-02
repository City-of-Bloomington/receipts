<?php
	abstract class MySQLResultIterator implements Iterator,Countable
	{
		protected $key;
		protected $result;
		protected $current;

		abstract public function find($fields=null,$sorting="");
		abstract protected function loadCurrent();

		public function rewind()
		{
			$this->key = 0;
			if (mysql_num_rows($this->result)) { mysql_data_seek($this->result,$this->key); }
			$this->loadCurrent();
		}

		public function current() { return $this->current; }

		public function key() { return $this->key; }

		public function next()
		{
			$this->key++;
			$this->loadCurrent();
		}

		public function valid()
		{
			if (mysql_num_rows($this->result)) { return $this->key < mysql_num_rows($this->result); }
			else { return false; }
		}

		public function count()
		{
			return mysql_num_rows($this->result);
		}
	}
?>