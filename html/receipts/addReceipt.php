<?php
/*
	$_POST variables:	firstname			feeIDs[]
						lastname			feeQuantities[]
											feeAmounts[]
						paymentMethod
*/
	verifyUser();

	$_POST['firstname'] = sanitizeString($_POST['firstname']);
	$_POST['lastname'] = sanitizeString($_POST['lastname']);

	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	require_once(APPLICATION_HOME."/classes/LineItem.inc");
	$receipt = new Receipt();
	$receipt->setFirstname($_POST['firstname']);
	$receipt->setLastname($_POST['lastname']);
	$receipt->setPaymentMethod($_POST['paymentMethod']);

	foreach($_POST['feeIDs'] as $index=>$feeID)
	{
		$feeAmount = ereg_replace("[^0-9.]","",$_POST['feeAmounts'][$index]);
		if ($feeID && $feeAmount)
		{
			$feeQuantity = ereg_replace("[^0-9]","",$_POST['feeQuantities'][$index]);
			if (!$feeQuantity) { $feeQuantity = 1; }

			$lineItem = new LineItem();
			$lineItem->setFeeID($feeID);
			$lineItem->setQuantity($feeQuantity);
			$lineItem->setAmount($feeAmount);
			$receipt->addLineItem($lineItem);
		}
	}


	# Before we save, make sure we've got all the required fields.
	if (!$receipt->getFirstname() || !$receipt->getLastname() || !$receipt->getPaymentMethod() || !count($receipt->getLineItems()))
	{
		#$_SESSION['errorMessages'][] = "missingRequiredFields";
		#Header("Location: addReceiptForm.php");
		print_r($receipt);
		exit();
	}

	$receipt->save();

	#Header("Location: home.php");
?>
