<?php
/*
	Marks a receipt as voided

	$_POST variables:	receiptID
						reason
*/
	verifyUser("Supervisor");

	require_once(APPLICATION_HOME."/classes/Receipt.inc");
	$receipt = new Receipt($_POST['receiptID']);
	$receipt->void($_SESSION['USER']->getUserID(),$_POST['reason']);
	$receipt->save();

	Header("Location: home.php");
?>