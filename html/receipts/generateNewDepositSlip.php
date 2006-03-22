<?php
/*
	Gathers all the outstanding receipts and saves a depositSlipDate for them
	You can only generate one deposit slip for a given day
*/
	verifyUser("Supervisor");

	require_once(APPLICATION_HOME."/classes/DepositSlip.inc");
	try
	{
		$depositSlip = new DepositSlip();
		$depositSlip->save();
	}
	catch (Exception $e)
	{
		# DepositSlip will throw an exception if today's date is aleady a deposit slip.
		# For now, let's just send them to view today's deposit slip
		$now = getdate();
		$date = "$now[year]-$now[mon]-$now[mday]";
		Header("Location: viewDepositSlip.php?date=$date");
		exit();
	}

	Header("Location: viewDepositSlip.php?date=".$depositSlip->getDate());
?>