<?php
	verifyUser();

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">

	<h1>Today's Receipts</h1>
	<table>
	<?php
		$now = getdate();
		$today = "$now[year]-$now[mon]-$now[mday]";

		require_once(APPLICATION_HOME."/classes/ReceiptList.inc");
		$receiptList = new ReceiptList();
		$receiptList->findByDate($today);
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
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
