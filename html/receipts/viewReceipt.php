<?php
/*
	$_GET variables:	receiptID
*/
	verifyUser();

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		require_once(APPLICATION_HOME."/classes/Receipt.inc");
		$receipt = new Receipt($_GET['receiptID']);

		echo "<h1>Receipt $_GET[receiptID]<button type=\"button\" class=\"print\" onclick=\"document.location.href='printReceipt.php?receiptID=$_GET[receiptID]';\">Print</button>";
		if ($receipt->getAmount() > 0) { echo "<button type=\"button\" class=\"refund\" onclick=\"document.location.href='refundReceiptForm.php?receiptID=$_GET[receiptID]';\">Refund</button>"; }
		echo "</h1>";
	?>
	<table>
	<tr><th colspan="2">Customer Name</th><th>Payment Method</th><th></th></tr>
	<tr><td colspan="2"><?php echo "{$receipt->getFirstname()} {$receipt->getLastname()}"; ?></td>
		<td><?php echo "{$receipt->getPaymentMethod()}"; ?></td>
		<td></td>
	</tr>
	<tr><th>Service</th><th>Units</th><th>Cost</th><th>Notes</th></tr>
	<?php
		foreach($receipt->getLineItems() as $lineItem)
		{
			echo "
			<tr><td>{$lineItem->getFee()->getName()}</td>
				<td>{$lineItem->getQuantity()}</td>
				<td>{$lineItem->getAmount()}</td>
				<td>{$lineItem->getNotes()}</td>
			</tr>
			";
		}
	?>
	<tr><td colspan="3"><?php echo $receipt->getNotes(); ?></td></tr>
	</table>

</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
