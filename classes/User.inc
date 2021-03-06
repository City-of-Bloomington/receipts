<?php
	require_once(GLOBAL_INCLUDES."/classes/SystemUser.inc");

	class User extends SystemUser
	{
		private $pin;

		/**
		 * @return array
		 */
		public static function getAuthenticationMethods()
		{
			return array('local','Employee');
		}

		public function __construct($id = null)
		{
			if ($id)
			{
				# Load an existing user
				if (is_numeric($id)) { $sql = "select * from users where userID=$id"; }
				else { $sql = "select * from users where username='$id'"; }
				$result = mysql_query($sql) or die($sql.mysql_error());
				if (mysql_num_rows($result))
				{
					$user = mysql_fetch_array($result);

					$this->setUserID($user['userID']);
					$this->setUsername($user['username']);
					$this->setAuthenticationMethod($user['authenticationMethod']);
					if ($user['pin']) { $this->pin = $user['pin']; }

					if ($user['password']) { $this->setPassword($user['password']); }

					$this->loadRoles();
				}
				else { throw new Exception($sql); }
			}
		}

		public function save()
		{
			if ($this->getUserID()) { $this->update(); }
			else { $this->insert(); }

			$this->updateRoles();
		}

		public function delete()
		{
			$sql = "delete from userRoles where userID={$this->getUserID()}";
			mysql_query($sql) or die($sql.mysql_error());

			$sql = "delete from users where userID={$this->getUserID()}";
			mysql_query($sql) or die($sql.mysql_error());
		}


		private function update()
		{
			$sql = "update users set username='{$this->getUsername()}',authenticationMethod='{$this->getAuthenticationMethod()}'";
			if ($this->getPassword()) { $sql.=",password=md5('{$this->getPassword()}')"; } else { $sql.=",password=null"; }
			if ($this->pin) { $sql.=",pin={$this->pin}"; } else { $sql.=",pin=null"; }
			$sql.=" where userID=".$this->getUserID();
			mysql_query($sql) or die($sql.mysql_error());
		}

		private function insert()
		{
			$sql = "insert users set username='{$this->getUsername()}',authenticationMethod='{$this->getAuthenticationMethod()}'";
			if ($this->getPassword()) { $sql.=",password=md5('{$this->getPassword()}')"; }
			if ($this->pin) { $sql.=",pin={$this->pin}"; }
			mysql_query($sql) or die($sql.mysql_error());

			$this->setUserID(mysql_insert_id());
		}

		private function updateRoles()
		{
			$sql = "delete from userRoles where userID={$this->getUserID()}";
			mysql_query($sql) or die($sql.mysql_error());

			$roles = $this->getRoles();

			foreach($roles as $role)
			{
				$sql = "insert userRoles set userID={$this->getUserID()},role='$role'";
				mysql_query($sql) or die($sql.mysql_error());
			}
		}

		protected function saveLocalPassword()
		{
			$sql = "update users set password=md5('{$this->getPassword()}') where userID={$this->getUserID()}";
			mysql_query($sql) or die($sql.mysql_error());
		}

		protected function loadRoles()
		{
			$roles = array();
			$sql = "select role from userRoles where userID={$this->getUserID()}";
			$result = mysql_query($sql) or die($sql.mysql_error());
			while(list($role) = mysql_fetch_array($result)) { $roles[] = $role; }
			$this->setRoles($roles);
		}

		protected function authenticateDatabase($password)
		{
			$sql = "select userID from users where username='{$this->getUsername()}' and password=md5('$password')";
			if (mysql_num_rows(mysql_query($sql))) { return true; }
			else { return false; }
		}

		public function getPin() { return $this->pin; }
		public function setPin($pin) { $this->pin = ereg_replace("[^0-9]","",$pin); }
	}
?>