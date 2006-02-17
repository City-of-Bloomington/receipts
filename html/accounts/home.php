<?php
/*
	Lists the accounts in the system
*/
	verifyUser('Administrator');

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<h1>Accounts</h1>
	<table>
	<?php
		require_once(APPLICATION_HOME."/classes/AccountList.inc");

		$accountList = new AccountList();
		$accountList->findAll();
		foreach($accountList as $account)
		{
			echo "
			<tr><td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateAccountForm.php?accountID={$account->getAccountID}'\">Edit</button></td>
				<td>{$account->getName}</td>
				<td>{$account->getAccountNumber}</td>
			";
		}
	?>
	</table>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
