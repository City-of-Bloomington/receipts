<?php
/*
	Global, shared functions for all PHP web applications
*/
	#----------------------------------------------------------------------------
	# Load classes on the fly as needed
	#----------------------------------------------------------------------------
	function __autoload($class) { require_once(APPLICATION_HOME."/classes/$class.inc"); }


	#----------------------------------------------------------------------------
	# We're setting up the servers to provide nicely formatted error messages when PHP bombs out.
	#----------------------------------------------------------------------------
	function prettyPrintErrorMessages ($errno, $errstr, $errfile, $errline)
	{
			echo "
			<div id=\"errorMessages\">
			<p><em>from Cliff:</em>  There is an error in the code on this page that is through no fault of your own.
					Errors of this sort need to be fixed immediately, though.
					Please help me out by copying and pasting the following error message into an email and sending it to me at
					<a href=\"mailto:inghamn@bloomington.in.gov\">inghamn@bloomington.in.gov</a>.</p>
			<p><strong>Code Error:</strong>  Error on line $errline of file $errfile:</p><p>$errstr</p>
			</div>
			";
	}
	set_error_handler("prettyPrintErrorMessages");

	# Object oriented exceptions are handled differently from other PHP errors.
	function exception_handler($exception)
	{
			echo "
			<div id=\"errorMessages\">
			<p><em>from Cliff:</em>  There is an error in the code on this page that is through no fault of your own.
					Errors of this sort need to be fixed immediately, though.
					Please help me out by copying and pasting the following error message into an email and sending it to me at
					<a href=\"mailto:inghamn@bloomington.in.gov\">inghamn@bloomington.in.gov</a>.</p>
			<p><strong>Uncaught exception:</strong>  Exception on line {$exception->getLine()} of file {$exception->getFile()}:</p><p>{$exception->getMessage()}</p>
			</div>
			";
	}
	set_exception_handler('exception_handler');



	function verifyUser()
	{
		# Make sure they're logged in
		if (!isset($_SESSION['USER']) || $_SESSION['IP_ADDRESS']!=$_SERVER['REMOTE_ADDR'])
		{
			# They're not logged in.  Boot them out to the login page
			$_SESSION['errorMessages'][] = "notLoggedIn";
			Header("Location: ".BASE_URL);
			exit();
		}

		# Check their roles against the required roles for the page
		if (func_num_args() > 0)
		{
			$userIsValid = false;
			$validTypes = func_get_args();
			$userRoles = $_SESSION['USER']->getRoles();
			foreach($validTypes as $validType)
			{
				if (in_array($validType,$userRoles)) { $userIsValid = true; break; }
			}
			if (!$userIsValid)
			{
				#$_SESSION['errorMessages'][] = "noAccessAllowed";
				#Header("Location: ".BASE_URL);
				print_r($validTypes);
				print_r($userRoles);
				exit();
			}
		}
	}



	#----------------------------------------------------------------------------
	# There are more bad characters than htmlspecialchars deals with.  We just want
	# to add in some other characters to clean.  While here, we might as well
	# have it trim out the whitespace too.
	#----------------------------------------------------------------------------
	function sanitizeString ($string)
	{
			$string = htmlspecialchars(trim($string),ENT_QUOTES);
			$string = str_replace("\\","&#92;",$string);
			return $string;
	}
?>