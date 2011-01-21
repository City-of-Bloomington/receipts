<?php
/*
	Logs a user into the system.
	A logged in user will be stored in the session as $_SESSION['USER']
	There should also be a $_SESSION['IP_ADDRESS'] to check for ijacking attacks.

	$_POST Variables:	username
						password
*/
try {
	$user = new User($_POST['username']);

	if ($user->authenticate($_POST['password'])) {
		$user->startNewSession();
	}
	else {
		$_SESSION['errorMessages'][] = 'wrongPassword';
	}
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e->getMessage();
	header('Location: '.BASE_URL);
	exit();
}


# Send them to an appropriate page depending on their roles.
if (in_array("Administrator",$_SESSION['USER']->getRoles())) { Header("Location: users"); }
else { Header("Location: receipts/addReceiptForm.php"); }
