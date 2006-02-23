<?php
/*
	$_GET variables:	receiptID			month
						firstname			day
						lastname			year
						service
						lineItemNotes		notes

						paymentMethod 	# May not be set, if they didn't click antyhing
*/
	verifyUser();

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<h1>Receipts Found</h1>
	<?php
		# If they typed in a receiptID, you don't get a list, you should go straight to the receipt
		$_GET['receiptID'] = ereg_replace("[^0-9]","",$_GET['receiptID']);
		if ($_GET['receiptID'])
		{
			Header("Location: viewReceipt.php?receiptID=$_GET[receiptID]");
			exit();
		}
		else
		{
			if (!isset($_GET['paymentMethod'])) { $_GET['paymentMethod'] = ""; }

			echo "<table>";
			require_once(APPLICATION_HOME."/classes/ReceiptList.inc");
			$receiptList = new ReceiptList();
			$receiptList->search($_GET['month'],$_GET['day'],$_GET['year'],
								$_GET['firstname'],$_GET['lastname'],$_GET['paymentMethod'],
								$_GET['service'],$_GET['lineItemNotes'],$_GET['notes']);
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
			echo "</table>";
		}
	?>
</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
