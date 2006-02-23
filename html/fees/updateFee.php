<?php
/*
	$_POST variables:	feeID
						name
						amount
						accountID
*/
	verifyUser("Administrator","Supervisor");

	#--------------------------------------------------------------------------
	# Data clenaup and validation
	#--------------------------------------------------------------------------
	$_POST['name'] = sanitizeString($_POST['name']);
	$_POST['amount'] = ereg_replace("[^0-9.]","",$_POST['amount']);

	if (!$_POST['name'] || !$_POST['amount'])
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: updateFeeForm.php");
		exit();
	}


	#--------------------------------------------------------------------------
	# Create the new fee
	#--------------------------------------------------------------------------
	require_once(APPLICATION_HOME."/classes/Fee.inc");
	$fee = new Fee($_POST['feeID']);
	$fee->setName($_POST['name']);
	$fee->setAmount($_POST['amount']);
	$fee->setAccountID($_POST['accountID']);
	$fee->save();

	Header("Location: home.php");
?>

