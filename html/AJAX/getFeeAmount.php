<?php
/*
	$_GET variables:	feeID
*/
	require_once(APPLICATION_HOME."/classes/Fee.inc");
	$fee = new Fee($_GET['feeID']);
	echo $fee->getAmount();
?>