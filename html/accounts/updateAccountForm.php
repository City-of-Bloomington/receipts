<?php
/*
	$_GET variables:	accountID
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		include(GLOBAL_INCLUDES."/errorMessages.inc");

		require_once(APPLICATION_HOME."/classes/Account.inc");
		$account = new Account($_GET['accountID']);
	?>
	<h1>Edit Account</h1>

	<form method="post" action="updateAccount.php">
	<fieldset><legend>Account Info</legend>
		<input name="accountID" type="hidden" value="<?php echo $account->getAccountID(); ?>" />
		<table>
		<tr><td><label for="name">Name</label></td>
			<td><input name="name" id="name" value="<?php echo $account->getName(); ?>" /></td></tr>
		<tr><td><label for="accountNumber">Account Number</label></td>
			<td><input name="accountNumber" id="accountNumber" value="<?php echo $account->getAccountNumber(); ?>" /></td></tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='home.php';">Cancel</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
