<div class="sidebar">
	<div class="titleBar">Login</div>
	<form method="post" action="<?php echo BASE_URL; ?>/login.php">
		<input name="returnURL" type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
		<table>
		<tr><td><label for="username">User:</label></td>
			<td><input name="username" id="username" size="10" /></td></tr>
		<tr><td><label for="password">Pass:</label></td>
			<td><input type="password" name="password" id="password" size="10" /></td></tr>
		</table>
		<button type="submit" class="login">Login</button>
	</form>
</div>
