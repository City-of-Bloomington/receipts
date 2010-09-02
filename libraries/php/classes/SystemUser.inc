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
				case "LDAP":
					if ($this->authenticateLDAP($password)) { return true; }
					else { return false; }
				break;

				case "local":
					if ($this->authenticateDatabase($password)) { return true; }
					else { return false; }
				break;
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
				case "LDAP":
					$this->saveLDAPPassword();
				break;

				case "local":
					$this->saveLocalPassword();
				break;
			}
		}


		private function authenticateLDAP($password)
		{
			$connection = ldap_connect(LDAP_SERVER) or die("Couldn't connect to LDAP");
			ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($connection);

			$result = ldap_search($connection,LDAP_DN,LDAP_USERNAME_ATTRIBUTE."={$this->username}");
			if (ldap_count_entries($connection,$result))
			{
				$entries = ldap_get_entries($connection, $result);


				if (eregi("^\{crypt\}(.+)",$entries[0][LDAP_PASSWORD_ATTRIBUTE][0],$matches))
				{
					$ldapPassword = $matches[1];
					$salt = substr($ldapPassword,0,2);

					$encryptedPassword = crypt($password,$salt);
					if ($encryptedPassword == $ldapPassword) { return true; }
					else
					{
						return false;
					}
				}
				else { throw new Exception("passwordIsCorrupted"); }
			}
			else { throw new Exception("unknownUser"); }
		}

		private function saveLDAPPassword()
		{
			$connection = ldap_connect(LDAP_SERVER);
			ldap_set_option($connection,LDAP_OPT_PROTOCOL_VERSION,3);
			ldap_bind($connection,LDAP_USERNAME_ATTRIBUTE."=".LDAP_ADMIN_USER.",o=".LDAP_DOMAIN,LDAP_ADMIN_PASS) or die(ldap_error($connection));

			$result = ldap_search($connection,LDAP_DN,LDAP_USERNAME_ATTRIBUTE."={$this->username}");
			$entries = ldap_get_entries($connection, $result);

			$dn = LDAP_USERNAME_ATTRIBUTE."={$this->username},ou=people,o=".LDAP_DOMAIN;
			if ($this->password)
			{

				$salt = substr(md5(time()),0,2);
				$encryptedPassword = "{CRYPT}".crypt($this->password,$salt);

				$password = array(LDAP_PASSWORD_ATTRIBUTE=>$encryptedPassword);

				if (isset($entries[0][LDAP_PASSWORD_ATTRIBUTE]))
				{
					# Modify
					ldap_mod_replace($connection,$dn,$password) or die(print_r($password).ldap_error($connection));
				}
				else
				{
					# Add
					ldap_mod_add($connection,$dn,$password) or die(print_r($password).ldap_error($connection));
				}
			}
			else
			{
				# Delete
				$password = array();
				ldap_mod_del($connection,$dn,$password) or die(print_r($password).ldap_error($connection));
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