<?php
/*
	$_POST variables:	authenticationMethod
						username
						password		# May be optional if LDAP is used
						roles
*/
	verifyUser("Administrator");

	#--------------------------------------------------------------------------
	# Data clenaup and validation
	#--------------------------------------------------------------------------
	$_POST['username'] = sanitizeString($_POST['username']);
	$_POST['password'] = sanitizeString($_POST['password']);

	if (!$_POST['username'] || ($_POST['authenticationMethod']=='local' && !$_POST['password']) )
	{
		$_SESSION['errorMessages'][] = "missingRequiredFields";
		Header("Location: addUserForm.php");
		exit();
	}

	#--------------------------------------------------------------------------
	# Create the new account
	#--------------------------------------------------------------------------
	$user = new User();
	$user->setAuthenticationMethod($_POST['authenticationMethod']);
	$user->setUsername($_POST['username']);
	if ($_POST['password']) { $user->setPassword($_POST['password']); }
	if (isset($_POST['roles'])) { $user->setRoles($_POST['roles']); }
	$user->save();

	Header("Location: home.php");
?>
