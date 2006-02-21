<?php
/*
	Lists the fees in the system
*/
	verifyUser('Administrator');

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">
			<button type="button" class="addSmall" onclick="document.location.href='addFeeForm.php';">Add</button>
			Fees
		</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/FeeList.inc");

			$feeList = new FeeList();
			$feeList->findAll();
			foreach($feeList as $fee)
			{
				$account = $fee->getAccount();
				echo "
				<tr><td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateFeeForm.php?feeID={$fee->getFeeID()}'\">Edit</button></td>
					<td>{$fee->getName()}</td>
					<td>{$fee->getAmount()}</td>
					<td>{$account->getName()}</td>
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

