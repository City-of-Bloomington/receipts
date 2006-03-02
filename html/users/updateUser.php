<?php
/*
	$_POST variables:	userID
						authenticationMethod
						username
						password
						roles
*/
	verifyUser("Administrator","Supervisor");

	$user = new User($_POST['userID']);
	$user->setAuthenticationMethod($_POST['authenticationMethod']);
	$user->setUsername($_POST['username']);
	$user->setPin($_POST['pin']);
	if (isset($_POST['roles'])) { $user->setRoles($_POST['roles']); } else { $user->setRoles(array()); }

	# only update the password if they typed in a new one
	if ($_POST['password']) { $user->setPassword($_POST['password']); }


	# Make sure we've got all the required fields before we save
	if (!$user->getUsername() || ($user->getAuthenticationMethod()=='local' && !$user->getPassword()))
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: updateUserForm.php?userID=$_POST[userID]");
		exit();
	}


	$user->save();

	Header("Location: home.php");
?>
