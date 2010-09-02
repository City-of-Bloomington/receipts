<?php
/*
	Handle any errors that were passed to this page

	These variables must be set in the configuration.inc of the application
	that is using this library
		$GLOBAL_INCLUDES
		$APPLICATION_HOME
*/
	if (isset($_SESSION['errorMessages']))
	{
		echo "<div id=\"errorMessages\"><h1>No, that&#39;s wrong:</h1>";
			foreach($_SESSION['errorMessages'] as $error)
			{
				if(file_exists(GLOBAL_INCLUDES."/errorMessages/$error.inc")) { include(GLOBAL_INCLUDES."/errorMessages/$error.inc"); }
				else { include(APPLICATION_HOME."/includes/errorMessages/$error.inc"); }
			}
		echo "</div>";

		unset($_SESSION['errorMessages']);
	}
?>
