<?php
	abstract class SystemUser
	{
		private $userID;
		private $username;
		private $password;
		private $authenticationMethod;
		private $roles = array();

		abstract public function save();
		abstract public function delete();

		abstract protected function loadRoles();
		abstract protected function authenticateDatabase($password);
		abstract protected function saveLocalPassword();


		public function authenticate($password)
		{
			switch($this->authenticationMethod)
			{
				case "local":
					return $this->authenticateDatabase($password);
				break;

				default:
					$type = $this->getAuthenticationMethod();
					return $type::authenticate($this->getUsername(),$password);
			}
		}

		public function startNewSession()
		{
			session_destroy();
			session_start();

			$_SESSION['USER'] = $this;
			$_SESSION['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
		}

		public function savePassword()
		{
			switch($this->authenticationMethod)
			{
				case "local":
					$this->saveLocalPassword();
				break;
			}
		}

		public function getUserID() { return $this->userID; }
		public function getUsername() { return $this->username; }
		public function getPassword() { return $this->password; }
		public function getAuthenticationMethod() { return $this->authenticationMethod; }
		public function getRoles() { return $this->roles; }

		public function setUserID($userID) { $this->userID = $userID; }
		public function setAuthenticationMethod($method) { $this->authenticationMethod = $method; }
		public function setRoles($roles) { $this->roles = $roles; }
		public function setUsername($username)
		{
			$username = sanitizeString($username);
			if ($username) { $this->username = $username; }
		}
		/*
		* Passwords are set here in clear text.  The only times you would want to set a password
		* is when you're adding a new password or changing a person's password.
		* Either way, it's up to the individual save routines to handle encrypting the new password
		* before storing it.  Passwords should not be loaded in the constructor - they're
		* supposed to be encrypted, so what's the point?
		*/
		public function setPassword($password) { $this->password = $password; }
	}
?>