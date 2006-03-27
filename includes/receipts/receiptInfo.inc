<?php
/*
	Displays info for a single receipt

	Required Variables:		$receipt
*/
	if ($receipt->getStatus() == "void") { $status = "Voided"; } else { $status = ""; }

	echo "<h1>Receipt $_GET[receiptID] $status";
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
<?php
	if ($receipt->getStatus() == "void")
	{
		echo "
		<div class=\"interfaceBox\"><div class=\"titleBar\">Voided By {$receipt->getVoidedByUser()->getPin()}</div>
		<table>
		<tr><th>{$receipt->getVoidedDate()}</th>
			<td>{$receipt->getVoidedReason()}</td></tr>
		</table>
		</div>
		";
	}
?>