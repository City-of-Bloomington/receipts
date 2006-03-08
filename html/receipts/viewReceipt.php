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

		echo "<h1>Receipt $_GET[receiptID]";
		if ($receipt->getAmount() > 0) { echo ""; }
		echo "</h1>";
	?>
	<table class="receipt">
	<tr><th colspan="2">Customer Name</th></tr>
	<tr><td><?php echo "{$receipt->getFirstname()} {$receipt->getLastname()}"; ?></td></tr>

	<tr><th>Payment Method</th></tr>
	<tr><td><?php echo "{$receipt->getPaymentMethod()}"; ?></td></tr>
	</table>

	<table>
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
	<tr class="total">
		<th colspan="2">Total</th>
		<td>$<?php echo number_format($receipt->getAmount(),2); ?></td>
		<td></td>
	</tr>
	<tr><td colspan="3"><?php echo $receipt->getNotes(); ?></td></tr>
	</table>

	<button type="button" class="print" onclick="window.open('printReceipt.php?receiptID=<?php echo $_GET['receiptID']; ?>');">Print</button>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
