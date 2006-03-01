<?php
/*
	$_POST variables:	firstname			feeIDs[]
						lastname			feeQuantities[]
											feeAmounts[]
						paymentMethod		feeNotes[]
						notes
*/
	verifyUser();

	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	require_once(APPLICATION_HOME."/classes/LineItem.inc");
	$receipt = new Receipt();
	$receipt->setFirstname($_POST['firstname']);
	$receipt->setLastname($_POST['lastname']);
	$receipt->setPaymentMethod($_POST['paymentMethod']);
	if ($_POST['notes']) { $receipt->setNotes($_POST['notes']); }


	# Populate all the line items for the receipt
	foreach($_POST['feeIDs'] as $index=>$feeID)
	{
		if ($feeID && $_POST['feeAmounts'][$index])
		{
			if (!$_POST['feeQuantities'][$index]) { $_POST['feeQuantities'][$index] = 1; }

			$lineItem = new LineItem();
			$lineItem->setFeeID($feeID);
			$lineItem->setQuantity($_POST['feeQuantities'][$index]);
			$lineItem->setAmount($_POST['feeAmounts'][$index]);
			if ($_POST['feeNotes'][$index]) { $lineItem->setNotes($_POST['feeNotes'][$index]); }
			$receipt->addLineItem($lineItem);
		}
	}


	# Before we save, make sure we've got all the required fields.
	if ( !$receipt->getLastname() )
	{
		$_SESSION['errorMessages'][] = "missingLastname";
	}
	if (!$receipt->getPaymentMethod())
	{
		$_SESSION['errorMessages'][] = "missingPaymentMethod";
	}
	if (!$receipt->getLineItems())
	{
		$_SESSION['errorMessages'][] = "missingService";
	}
	if (isset($_SESSION['errorMessages']))
	{
		Header("Location: addReceiptForm.php");
		exit();
	}


	$receipt->save();

	Header("Location: home.php");
?>
