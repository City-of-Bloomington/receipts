<?php
/*
	$_POST variables:	authenticationMethod
						username
						password		# May be optional if LDAP is used
						pin
						roles
*/
	verifyUser("Administrator","Supervisor");

	#--------------------------------------------------------------------------
	# Create the new account
	#--------------------------------------------------------------------------
	$user = new User();
	$user->setAuthenticationMethod($_POST['authenticationMethod']);
	$user->setUsername($_POST['username']);
	if ($_POST['password']) { $user->setPassword($_POST['password']); }
	if ($_POST['pin']) { $user->setPin($_POST['pin']); }
	if (isset($_POST['roles'])) { $user->setRoles($_POST['roles']); }


	# Make sure we've got all the required fields before we save
	if (!$user->getUsername() || ($user->getAuthenticationMethod()=='local' && !$user->getPassword()))
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: addUserForm.php");
		exit();
	}

	$user->save();

	Header("Location: home.php");
?>
