<?php
/*
	Form to void a receipt

	$_GET variables:	receiptID
*/
	verifyUser("Supervisor");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	$receipt = new Receipt($_GET['receiptID']);
?>
<div id="mainContent">
	<?php
		echo "<h1>Void Receipt {$receipt->getReceiptID()}</h1>";
		include(APPLICATION_HOME."/includes/receipts/receiptInfo.inc");
	?>
	<form method="post" action="voidReceipt.php">
	<fieldset><legend>Void</legend>
		<input name="receiptID" type="hidden" value="<?php echo $_GET['receiptID']; ?>" />

		<div><label for="reason">Reason</label></div>
		<textarea name="reason" id="reason" rows="3" cols="60"></textarea>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='viewReceipt.php?receiptID=$_GET[receiptID]';">Cancel</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>