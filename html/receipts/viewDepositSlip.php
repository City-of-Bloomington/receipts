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
		try
		{
			$depositSlip = new DepositSlip($_GET['date']);
			echo "<h1>Deposit Slip for $_GET[date]</h1>";

			require_once(APPLICATION_HOME."/classes/AccountList.inc");
			$accountList = new AccountList();
			$accountList->find();
			foreach($accountList as $account)
			{
				$bufferHasReceiptData = false;
				$buffer = "
				<table>
				<tr><th colspan=\"7\">{$account->getName()} - {$account->getAccountNumber()}</th></tr>
				<tr><th>Service</th>
					<th>Quantity</th>
					<th>Cash</th>
					<th>Check</th>
					<th>Money Order</th>
					<th>Receipt Begin</th>
					<th>Receipt End</th>
				</tr>
				";

				foreach($account->getFees() as $fee)
				{
					$receiptList = $depositSlip->getReceipts($fee->getFeeID());

					$numReceipts = count($receiptList);
					$totalAmount = $receiptList->getTotalAmount();


					if ($numReceipts)
					{
						$buffer.= "
						<tr><td>{$fee->getName()}</td>
							<td>$numReceipts</td>
							<td>{$receiptList->getTotalAmount('cash')}</td>
							<td>{$receiptList->getTotalAmount('check')}</td>
							<td>{$receiptList->getTotalAmount('money order')}</td>
							<td>{$receiptList->getMinReceiptID()}</td>
							<td>{$receiptList->getMaxReceiptID()}</td>
						</tr>
						";
						$bufferHasReceiptData = true;
					}
				}
				$buffer.= "</table>";

				if ($bufferHasReceiptData) { echo $buffer; }
			}
		}
		catch (Exception $e) { echo "<h1>There is no deposit slip for $_GET[date]</h1>"; }
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
