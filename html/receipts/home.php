<?php
	verifyUser();

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">

	<h1>Current Receipts</h1>
	<table>
	<?php
		require_once(APPLICATION_HOME."/classes/ReceiptList.inc");

		# List all the non deposited receipts
		$receiptList = new ReceiptList();
		$receiptList->find( array("depositSlipDate"=>"null") );

		foreach($receiptList as $receipt)
		{
			echo "
			<tr><td><a href=\"viewReceipt.php?receiptID={$receipt->getReceiptID()}\">{$receipt->getReceiptID()}</a></td>
				<td>{$receipt->getDate()}</td>
				<td>{$receipt->getFirstname()} {$receipt->getLastname()}</td>
				<td>\${$receipt->getAmount()}</td>
			</tr>
			";
		}
	?>
	</table>
	<button type="button" onclick="document.location.href='generateNewDepositSlip.php';">Generate Deposit Slip</button>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
