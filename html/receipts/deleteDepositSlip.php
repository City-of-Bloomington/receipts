<?php
/*
	Clears out a deposit slip from the receipts

	$_GET variables:	date (yyyy-mm-dd)
*/
	verifyUser("Administrator","Supervisor");

	require_once(APPLICATION_HOME."/classes/DepositSlip.inc");
	try
	{
		$depositSlip = new DepositSlip($_GET['date']);
		$depositSlip->delete();
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e->getMessage();
		Header("Location: viewDepositSlip.php?date=$_GET[date]");
		exit();
	}

	Header("Location: home.php");
?>