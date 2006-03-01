<?php
/*
	$_POST variables:	receiptID			feeIDs[]
											feeQuantities[]
											feeAmounts[]
						notes
*/
	verifyUser();

	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	$receipt = new Receipt($_POST['receiptID']);
	$refund = new Receipt();

	$refund->setFirstname($receipt->getFirstname());
	$refund->setLastname($receipt->getLastname());
	$refund->setPaymentMethod($receipt->getPaymentMethod());
	if ($_POST['notes']) { $refund->setNotes($_POST['notes']); }


	# Populate all the line items for the receipt
	foreach($_POST['feeIDs'] as $index=>$feeID)
	{
		if ($feeID && $_POST['feeAmounts'][$index])
		{
			if (!$_POST['feeQuantities'][$index]) { $_POST['feeQuantities'][$index] = 1; }

			$lineItem = new LineItem();
			$lineItem->setFeeID($feeID);
			$lineItem->setQuantity($_POST['feeQuantities'][$index]);
			$lineItem->setAmount(0 - $_POST['feeAmounts'][$index]);
			$refund->addLineItem($lineItem);
		}
	}


	# Before we save, make sure we've got all the required fields.
	if (!count($refund->getLineItems()))
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: refundReceiptForm.php");
		#print_r($refund);
		exit();
	}


	$refund->save();

	Header("Location: home.php");
?>
