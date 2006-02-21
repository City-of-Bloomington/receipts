<?php
	verifyUser();

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">

	<h1>Receipts</h1>
	<table>
	<?php
		require_once(APPLICATION_HOME."/classes/ReceiptList.inc");
		$receiptList = new ReceiptList();
		$receiptList->findAll();
		foreach($receiptList as $receipt)
		{
			echo "
			<tr><td>{$receipt->getReceiptID()}</td>
				<td>{$receipt->getDate()}</td>
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

