<?php
/*
	Lists the accounts in the system
*/
	verifyUser('Administrator','Supervisor');

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">
			<button type="button" class="addSmall" onclick="document.location.href='addAccountForm.php';">Add</button>
			Accounts
		</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/AccountList.inc");

			$accountList = new AccountList();
			$accountList->find();
			foreach($accountList as $account)
			{
				echo "
				<tr><td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateAccountForm.php?accountID={$account->getAccountID()}'\">Edit</button></td>
					<td>{$account->getName()}</td>
					<td>{$account->getAccountNumber()}</td>
				";
			}
		?>
		</table>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
