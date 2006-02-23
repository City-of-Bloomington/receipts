<?php
/*
	$_GET variables:	userID
*/
	verifyUser("Administrator","Supervisor");

	$user = new User($_GET['userID']);
	$user->delete();

	Header("Location: home.php");
?>
