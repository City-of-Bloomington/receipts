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

		include(APPLICATION_HOME."/includes/receipts/receiptInfo.inc");
	?>

	<button type="button" class="print" onclick="document.location.href='printReceipt.php?receiptID=<?php echo $_GET['receiptID']; ?>';">Print</button>
	<?php
		if (!$receipt->getDepositSlipDate() && in_array("Supervisor",$_SESSION['USER']->getRoles()))
		{
			echo "<button type=\"button\" class=\"void\" onclick=\"document.location.href='voidReceiptForm.php?receiptID=$_GET[receiptID]';\">Void</button>";
		}
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>