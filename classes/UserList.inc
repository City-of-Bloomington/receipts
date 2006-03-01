<?php
	require_once(APPLICATION_HOME."/classes/User.inc");
	require_once(GLOBAL_INCLUDES."/classes/MySQLResultIterator.inc");

	class UserList extends MySQLResultIterator
	{
		public function find($fields=null,$sort = "username")
		{
			if ($fields)
			{
				$options = array();
				$sql = "select distinct users.userID from users";

				if (isset($fields['userID'])) { $options[] = "userID=$fields[userID]"; }
				if (isset($fields['username'])) { $options[] = "username='$fields[username]'"; }
				if (isset($fields['authenticationMethod'])) { $options[] = "authenticationMethod='$fields[authenticationMethod]'"; }

				# To search on Role, you have to join the userRoles table
				if (isset($fields['role']))
				{
					$sql = " left join userRoles using (userID)";
					$options[] = "role='$fields[role]'";
				}

				$where = " where ".implode(" and ",$options);
				$sql.= "$where order by $sort";
			}
			else { $sql = "select userID from users order by $sort"; }

			$this->result = mysql_query($sql) or die($sql.mysql_error());
		}

		protected function loadCurrent()
		{
			if (mysql_num_rows($this->result))
			{
				list($userID) = mysql_fetch_array($this->result);
				$this->current = new User($userID);
			}
		}
	}
?>
