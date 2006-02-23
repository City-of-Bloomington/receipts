<?php
/*
	$_POST variables:	userID
						authenticationMethod
						username
						password
						roles
*/
	verifyUser("Administrator","Supervisor");

	if (!$_POST['username'] || ($_POST['authenticationMethod']=='local' && !$_POST['password']) )
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: updateUserForm.php?userID=$_POST[userID]");
		exit();
	}

	#--------------------------------------------------------------------------
	# Update the account
	#--------------------------------------------------------------------------
	$user = new User($_POST['userID']);
	$user->setAuthenticationMethod($_POST['authenticationMethod']);
	$user->setUsername($_POST['username']);
	if (isset($_POST['roles'])) { $user->setRoles($_POST['roles']); } else { $user->setRoles(array()); }

	# only update the password if they typed in a new one
	if ($_POST['password']) { $user->setPassword($_POST['password']); }

	$user->save();

	Header("Location: home.php");
?>
