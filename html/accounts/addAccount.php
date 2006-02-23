<?php
/*
	$_POST variables:	name
						accountNumber
*/
	verifyUser('Administrator','Supervisor');

	#--------------------------------------------------------------------------
	# Data clenaup and validation
	#--------------------------------------------------------------------------
	$_POST['name'] = sanitizeString($_POST['name']);
	$_POST['accountNumber'] = sanitizeString($_POST['accountNumber']);

	if (!$_POST['name'] || !$_POST['accountNumber'])
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: addAccountForm.php");
		exit();
	}


	#--------------------------------------------------------------------------
	# Create the new account
	#--------------------------------------------------------------------------
	require_once(APPLICATION_HOME."/classes/Account.inc");
	$account = new Account();
	$account->setName($_POST['name']);
	$account->setAccountNumber($_POST['accountNumber']);
	$account->save();

	Header("Location: home.php");
?>
