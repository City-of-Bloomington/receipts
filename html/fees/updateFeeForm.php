<?php
/*
	$_GET variables:	feeID
*/
	verifyUser("Administrator","Supervisor");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		include(GLOBAL_INCLUDES."/errorMessages.inc");

		require_once(APPLICATION_HOME."/classes/Fee.inc");
		$fee = new Fee($_GET['feeID']);
	?>
	<h1>Edit Fee</h1>
	<form method="post" action="updateFee.php">
	<fieldset><legend>Fee Info</legend>
		<input name="feeID" type="hidden" value="<?php echo $_GET['feeID']; ?>" />

		<table>
		<tr><td><label for="name">Name</label></td>
			<td><input name="name" id="name" value="<?php echo $fee->getName(); ?>" /></td></tr>
		<tr><td><label for="amount">Amount</label></td>
			<td><input name="amount" id="amount" value="<?php echo $fee->getAmount(); ?>" /></td></tr>
		<tr><td><label for="accountID">Account</label></td>
			<td><select name="accountID" id="accountID">
				<?php
					require_once(APPLICATION_HOME."/classes/AccountList.inc");

					$accountList = new AccountList();
					$accountList->find();
					foreach($accountList as $account)
					{
						if ($fee->getAccountID() != $account->getAccountID()) { echo "<option value=\"{$account->getAccountID()}\">{$account->getName()} - {$account->getAccountNumber()}</option>"; }
						else { echo "<option value=\"{$account->getAccountID()}\" selected=\"selected\">{$account->getName()} - {$account->getAccountNumber()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		</table>

		<button type="submit" class="save">Save</button>
		<button type="button" class="cancel" onclick="document.location.href='home.php';">Cancel</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>

