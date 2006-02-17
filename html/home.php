<?php
	# Destroy any previous login
	if (isset($_SESSION)) { session_destroy(); }

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");

	include(GLOBAL_INCLUDES."/errorMessages.inc");
?>

	<form id="loginBox" method="post" action="login.php">
	<fieldset><legend>Login</legend>
		<table>
		<tr><td><label for="username">Username:</label></td>
			<td><input name="username" id="username" /></td></tr>
		<tr><td><label for="password">Password:</label></td>
			<td><input name="password" id="password" type="password" /></td></tr>
		</table>

		<button type="submit" class="login">Login</button>
	</fieldset>
	</form>

	<script type="text/javascript">document.forms[0].username.focus();</script>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
