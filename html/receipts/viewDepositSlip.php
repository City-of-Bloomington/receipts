<?php
/*
	$_GET variables:	date (yyyy-mm-dd)
*/
	verifyUser();

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		require_once(APPLICATION_HOME."/classes/DepositSlip.inc");
		require_once(APPLICATION_HOME."/classes/AccountList.inc");

		include(GLOBAL_INCLUDES."/errorMessages.inc");
		try
		{
			$depositSlip = new DepositSlip($_GET['date']);
			$date = explode("-",$_GET['date']);

			$now = getdate();
			if ($_GET['date'] == "$now[year]-$now[mon]-$now[mday]")
			{
				$deleteButton = "<button type=\"button\" class=\"delete\" onclick=\"document.location.href='deleteDepositSlip.php?date=$_GET[date]';\">Delete</button>";
			}
			else { $deleteButton = ""; }
			echo "
			<h1>Deposit Slip for $date[2]-$date[1]-$date[0] $deleteButton</h1>
			<table>
			<colgroup>
				<col /><col /><col class=\"money\" /><col class=\"money\" /><col class=\"money\" /><col class=\"money\" />
			</colgroup>
			";

			$accountList = new AccountList();
			$accountList->find();
			$buffer = "";

			$cashGrandTotal = 0;
			$checkGrandTotal = 0;
			$moneyOrderGrandTotal = 0;
			$bufferHasReceiptData = false;
			foreach($accountList as $account)
			{
				$accountHasReceiptData = false;
				$accountFeeBuffer = "";

				$cashSubtotal = 0;
				$checkSubtotal = 0;
				$moneyOrderSubtotal = 0;
				$accountSubtotal = 0;
				foreach($account->getFees() as $fee)
				{
					$receiptList = $depositSlip->getReceipts($fee->getFeeID());

					$numReceipts = count($receiptList);

					$cash = $receiptList->getTotalAmount('cash');
					$check = $receiptList->getTotalAmount('check');
					$moneyOrder = $receiptList->getTotalAmount('money order');
					$feeTotal = $receiptList->getTotalAmount();

					$cashSubtotal += $cash;
					$checkSubtotal += $check;
					$moneyOrderSubtotal += $moneyOrder;
					$accountSubtotal += $feeTotal;

					if ($numReceipts > 0)
					{
						$accountFeeBuffer.= "
						<tr><td>{$fee->getName()}</td>
							<td>$numReceipts</td>
							<td class=\"money\">$cash</td>
							<td class=\"money\">$check</td>
							<td class=\"money\">$moneyOrder</td>
							<td class=\"money\">$feeTotal</td>
						</tr>
						";
						$accountHasReceiptData = true;
						$bufferHasReceiptData = true;
					}
				}

				if ($accountHasReceiptData)
				{
					$cashGrandTotal += $cashSubtotal;
					$checkGrandTotal += $checkSubtotal;
					$moneyOrderGrandTotal += $moneyOrderSubtotal;

					$cashSubtotal = $cashSubtotal ? number_format($cashSubtotal,2) : "";
					$checkSubtotal = $checkSubtotal ? number_format($checkSubtotal,2) : "";
					$moneyOrderSubtotal = $moneyOrderSubtotal ? number_format($moneyOrderSubtotal,2) : "";
					$accountSubtotal = $accountSubtotal ? number_format($accountSubtotal,2) : "";

					$buffer.= "
					<tr><th colspan=\"6\">{$account->getName()} - {$account->getAccountNumber()}</th></tr>
					<tr><th>Service</th>
						<th>Quantity</th>
						<th>Cash</th>
						<th>Check</th>
						<th>Money Order</th>
						<th>Total</th>
					</tr>
					$accountFeeBuffer
					<tr class=\"total\">
						<th colspan=\"2\">Subtotal</th>
						<td class=\"money\">$cashSubtotal</td>
						<td class=\"money\">$checkSubtotal</td>
						<td class=\"money\">$moneyOrderSubtotal</td>
						<td class=\"money\">$accountSubtotal</td>
					</tr>
					";
				}
			}

			if ($bufferHasReceiptData)
			{
				$receiptList = $depositSlip->getReceipts();
				$grandTotal = number_format($receiptList->getTotalAmount(),2);

				$cashGrandTotal = $cashGrandTotal ? number_format($cashGrandTotal,2) : "";
				$checkGrandTotal = $checkGrandTotal ? number_format($checkGrandTotal,2) : "";
				$moneyOrderGrandTotal = $moneyOrderGrandTotal ? number_format($moneyOrderGrandTotal,2) : "";

				echo $buffer;
				echo "
				<tr class=\"total\">
					<th colspan=\"2\">Total Deposit</th>
					<td class=\"money\">$cashGrandTotal</td>
					<td class=\"money\">$checkGrandTotal</td>
					<td class=\"money\">$moneyOrderGrandTotal</td>
					<td class=\"money\">$grandTotal</td>
				</tr>
				</table>
				";

				echo "
				<h2>Receipts: {$receiptList->getMinReceiptID()} to {$receiptList->getMaxReceiptID()}</h2>
				<table>
				<tr><th>Receipt</th><th>Amount</th><th>Date</th></tr>
				";
				foreach($receiptList as $receipt)
				{
					$amount = number_format($receipt->getAmount(),2);
					$date = explode("-",$receipt->getDate());

					if ($receipt->getStatus() == 'void') { echo "<tr class=\"void\">"; }
					else { echo "<tr>"; }
					echo "
						<td><a href=\"viewReceipt.php?receiptID={$receipt->getReceiptID()}\">{$receipt->getReceiptID()}</a></td>
						<td class=\"money\">$amount</td>
						<td>$date[2]-$date[1]-$date[0]</td>
					</tr>
					";
				}

				echo "
				<tr class=\"total\">
					<td></td>
					<td class=\"money\">$grandTotal</td>
					<td></td>
				</tr>
				</table>
				";
			}
		}
		catch (Exception $e) { echo "<h1>There is no deposit slip for $_GET[date]</h1>"; }
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>