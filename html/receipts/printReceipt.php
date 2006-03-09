<?php
/*
	$_GET variables:	receiptID
*/
	ini_set("output_buffering",false);
	session_cache_limiter("public");

	verifyUser();
	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	$receipt = new Receipt($_GET['receiptID']);

	$enteredBy = new User($receipt->getEnteredBy());
	$date = explode("-",$receipt->getDate());
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
								<fo:block font-size=\"14pt\" font-weight=\"bold\">City of Bloomington, IN</fo:block>
								<fo:block font-weight=\"bold\">Police Department</fo:block>
								<fo:block>220 East 3rd St</fo:block>
								<fo:block>Bloomington, IN 47401</fo:block>
								<fo:block>(812) 339-4477</fo:block>
							</fo:table-cell>
							<fo:table-cell><fo:block></fo:block></fo:table-cell>
							<fo:table-cell>
								<fo:block>Receipt #<fo:inline font-weight=\"bold\">{$receipt->getReceiptID()}</fo:inline></fo:block>
								<fo:block>Date: $date[2]-$date[1]-$date[0]</fo:block>
								<fo:block>Received By: {$enteredBy->getPin()}</fo:block>
							</fo:table-cell>
						</fo:table-row>
					</fo:table-body>
				</fo:table>
			</fo:block>
		</fo:static-content>
		<fo:static-content flow-name=\"xsl-region-after\">
			<fo:block font-size=\"8pt\">Form Prescribed by State Board of Accounts (2006)</fo:block>
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
							<fo:table-cell text-align=\"right\"><fo:block font-weight=\"bold\">Cost</fo:block></fo:table-cell>
						</fo:table-row>
";
						foreach($receipt->getLineItems() as $lineItem)
						{
							$amount = number_format(abs($lineItem->getAmount()),2);
							$FO.= "
							<fo:table-row>
								<fo:table-cell><fo:block>{$lineItem->getFee()->getName()}</fo:block></fo:table-cell>
								<fo:table-cell><fo:block>{$lineItem->getQuantity()}</fo:block></fo:table-cell>
								<fo:table-cell text-align=\"right\"><fo:block>\$$amount</fo:block></fo:table-cell>
							</fo:table-row>
							";
						}

						$totalAmount = number_format(abs($receipt->getAmount()),2);
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
							<fo:table-cell text-align=\"right\"><fo:block>\$$totalAmount</fo:block></fo:table-cell>
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
	$output = exec("/usr/local/XEP/xep -fo /tmp/$time.fo -pdf /tmp/$time.pdf");

	$filesize = filesize("/tmp/$time.pdf");

	# Stream the PDF to the browser, so they can print it themselves.
	Header("Pragma: public");
	Header('Content-type: application/pdf');
	Header("Content-Disposition: inline; filename=receipt.pdf");
	Header("Content-length: $filesize");

	readfile("/tmp/$time.pdf");

	# Send it to the printer
	#exec("lpr -P ".RECEIPT_PRINTER." /tmp/$time.pdf");

	#Header("Location: viewReceipt.php?receiptID=$_GET[receiptID]");
?>