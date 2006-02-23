<?php
	verifyUser('Administrator','Supervisor');

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>
	<h1>New Account</h1>
	<form method="post" action="addAccount.php">
	<fieldset><legend>Account Info</legend>
		<table>
		<tr><td><label for="name">Name</label></td>
			<td><input name="name" id="name" /></td></tr>
		<tr><td><label for="accountNumber">Account Number</label></td>
			<td><input name="accountNumber" id="accountNumber" /></td></tr>
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

