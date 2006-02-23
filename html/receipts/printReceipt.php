<?php
/*
	$_GET variables:	receiptID
*/
	verifyUser();

	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	$receipt = new Receipt($_GET['receiptID']);

$FO = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<fo:root xmlns:fo=\"http://www.w3.org/1999/XSL/Format\">
	<fo:layout-master-set>
		<fo:simple-page-master master-name=\"receipt\" page-height=\"11in\" page-width=\"8.5in\" margin-left=\"1in\" margin-right=\"1in\" margin-top=\"0.5in\" margin-bottom=\"0in\">
			<fo:region-body margin-top=\"1.5in\" margin-bottom=\"1.5in\"/><fo:region-before extent=\"1.5in\"/><fo:region-after extent=\"1.5in\"/>
		</fo:simple-page-master>
		<fo:page-sequence-master master-name=\"receiptForm\">
			<fo:repeatable-page-master-reference master-reference=\"receipt\"/>
		</fo:page-sequence-master>
	</fo:layout-master-set>
	<fo:page-sequence master-reference=\"receiptForm\">
		<fo:static-content flow-name=\"xsl-region-before\" border-bottom=\"1px solid black\">
			<fo:block font-size=\"10pt\">
				<fo:table space-after=\"1em\">
					<fo:table-column column-width=\"34%\"/><fo:table-column column-width=\"33%\"/><fo:table-column column-width=\"33%\"/>
					<fo:table-body>
						<fo:table-row>
							<fo:table-cell>
								<fo:block font-size=\"14pt\" font-weight=\"bold\">City of Bloomington</fo:block>
								<fo:block>Police Department</fo:block>
							</fo:table-cell>
							<fo:table-cell><fo:block></fo:block></fo:table-cell>
							<fo:table-cell>
								<fo:block>Date: {$receipt->getDate()}</fo:block>
								<fo:block>Receipt #: {$receipt->getReceiptID()}</fo:block>
								<fo:block>Received By: {$receipt->getEnteredBy()}</fo:block>
							</fo:table-cell>
						</fo:table-row>
					</fo:table-body>
				</fo:table>
			</fo:block>
		</fo:static-content>
		<fo:static-content flow-name=\"xsl-region-after\">
			<fo:block font-size=\"8pt\">Form Prescribed by State Board of Accounts</fo:block>
		</fo:static-content>

		<fo:flow flow-name=\"xsl-region-body\">
			<fo:block text-align=\"center\" font-size=\"14pt\" font-weight=\"bold\" space-after=\"2em\">Receipt of Payment</fo:block>

			<fo:block space-after=\"1em\">
				<fo:table>
					<fo:table-column column-width=\"50%\"/>
					<fo:table-column column-width=\"50%\"/>
					<fo:table-body>
						<fo:table-row>
							<fo:table-cell><fo:block font-weight=\"bold\">Customer Name</fo:block></fo:table-cell>
							<fo:table-cell><fo:block font-weight=\"bold\">Payment Method</fo:block></fo:table-cell>
						</fo:table-row>
						<fo:table-row>
							<fo:table-cell><fo:block>{$receipt->getFirstname()} {$receipt->getLastname()}</fo:block></fo:table-cell>
							<fo:table-cell><fo:block>{$receipt->getPaymentMethod()}</fo:block></fo:table-cell>
						</fo:table-row>
					</fo:table-body>
				</fo:table>
			</fo:block>

			<fo:block>
				<fo:table>
					<fo:table-column column-width=\"50%\"/>
					<fo:table-column column-width=\"25%\"/>
					<fo:table-column column-width=\"25%\"/>
					<fo:table-body>
						<fo:table-row>
							<fo:table-cell><fo:block font-weight=\"bold\">Service</fo:block></fo:table-cell>
							<fo:table-cell><fo:block font-weight=\"bold\">Quantity</fo:block></fo:table-cell>
							<fo:table-cell><fo:block font-weight=\"bold\">Cost</fo:block></fo:table-cell>
						</fo:table-row>
";
						foreach($receipt->getLineItems() as $lineItem)
						{
							$FO.= "
							<fo:table-row>
								<fo:table-cell><fo:block>{$lineItem->getFee()->getName()}</fo:block></fo:table-cell>
								<fo:table-cell><fo:block>{$lineItem->getQuantity()}</fo:block></fo:table-cell>
								<fo:table-cell><fo:block>{$lineItem->getAmount()}</fo:block></fo:table-cell>
							</fo:table-row>
							";
						}
$FO.= "
					</fo:table-body>
				</fo:table>
			</fo:block>
			<fo:block>
				<fo:table border-top=\"1px solid black\" space-before=\"1em\">
					<fo:table-column column-width=\"50%\"/>
					<fo:table-column column-width=\"25%\"/>
					<fo:table-column column-width=\"25%\"/>
					<fo:table-body>
						<fo:table-row border-top=\"1px solid black\" margin-top=\"2em\">
							<fo:table-cell><fo:block></fo:block></fo:table-cell>
							<fo:table-cell text-align=\"right\"><fo:block font-weight=\"bold\">Amount Received: </fo:block></fo:table-cell>
							<fo:table-cell><fo:block>{$receipt->getAmount()}</fo:block></fo:table-cell>
						</fo:table-row>
					</fo:table-body>
				</fo:table>
			</fo:block>
			<fo:block font-weight=\"bold\" space-before=\"2em\">Additional Notes</fo:block>
			<fo:block>{$receipt->getNotes()}</fo:block>

		</fo:flow>
	</fo:page-sequence>
</fo:root>
";



	# Create the PDF
	$time = time();
	file_put_contents("/tmp/$time.fo",$FO);
	exec("/usr/local/XEP/xep -fo /tmp/$time.fo -pdf /tmp/$time.pdf");

	# Send it to the printer
	exec("lpr -P ".RECEIPT_PRINTER." /tmp/$time.pdf");

	Header("Location: viewReceipt.php?receiptID=$_GET[receiptID]");
?>
