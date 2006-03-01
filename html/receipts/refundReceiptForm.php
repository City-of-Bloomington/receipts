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

		echo "<h1>Refund Receipt $_GET[receiptID]</h1>";
	?>
	<form method="post" action="refundReceipt.php">
	<fieldset><legend>Receipt Info</legend>
		<input name="receiptID" type="hidden" value="<?php echo $_GET['receiptID']; ?>" />

		<table>
		<tr><th colspan="2">Customer Name</th><th>Payment Method</th><th></th><th colspan="2"></th></tr>
		<tr><td colspan="2"><?php echo "{$receipt->getFirstname()} {$receipt->getLastname()}"; ?></td>
			<td><?php echo "{$receipt->getPaymentMethod()}"; ?></td>
			<td></td>
			<td colspan="2"></td>
		</tr>
		<tr><th>Service</th><th>Units</th><th>Cost</th><th>Notes</th><th>Refund Qty</th><th>Refund Amount</th></tr>
		<?php
			foreach($receipt->getLineItems() as $lineItem)
			{
				echo "
				<tr><td>{$lineItem->getFee()->getName()}</td>
					<td>{$lineItem->getQuantity()}</td>
					<td>{$lineItem->getAmount()}</td>
					<td>{$lineItem->getNotes()}</td>
					<td><input name=\"feeIDs[]\" type=\"hidden\" value=\"{$lineItem->getFeeID()}\" />
						<input name=\"feeQuantities[]\" size=\"2\" /></td>
					<td><input name=\"feeAmounts[]\" size=\"5\" /></td>
				</tr>
				";
			}
		?>
		<tr><td colspan="6"><textarea name="notes" rows="3" cols="60"><?php echo $receipt->getNotes(); ?></textarea></td></tr>
		</table>

		<button type="submit" class="submit">Refund</button>
	</fieldset>
	</form>

</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>

