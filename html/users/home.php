<?php
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<h1>Users</h1>
	<table>
	<?php
		require_once(APPLICATION_HOME."/classes/UserList.inc");

		$userList = new UserList();
		$userList->findAll();
		foreach($userList as $user)
		{
			echo "
			<tr><td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateUserForm.php?userID={$user->getUserID()}'\">Edit</button>
				<td>{$user->getUsername()}</td>
				<td>{$user->getAuthenticationMethod()}</td></tr>
			";
		}
	?>
	</table>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>

