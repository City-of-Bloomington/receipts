<?php
/*
	$_GET variables:	userID
*/
	verifyUser("Administrator","Supervisor");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		include(GLOBAL_INCLUDES."/errorMessages.inc");

		$user = new User($_GET['userID']);
	?>
	<h1>Edit <?php echo $user->getUsername(); ?></h1>
	<form method="post" action="updateUser.php">
	<fieldset><legend>Login Info</legend>
		<input name="userID" type="hidden" value="<?php echo $user->getUserID(); ?>" />
		<table>
		<tr><td><label for="authenticationMethod">Authentication</label></td>
			<td><select name="authenticationMethod" id="authenticationMethod">
				<?php
					foreach (User::getAuthenticationMethods() as $method) {
						$selected = $user->getAuthenticationMethod()==$method
							? 'selected="selected"'
							: '';
						echo "<option $selected>$method</option>";
					}
				?>
				</select>
			</td>
		</tr>
		<tr><td><label for="username">Username</label></td>
			<td><input name="username" id="username" value="<?php echo $user->getUsername(); ?>" /></td></tr>
		<tr><td><label for="password">Password</label></td>
			<td><input name="password" id="password" /></td></tr>
		<tr><td><label for="pin">Pin</label></td>
			<td><input name="pin" size="5" maxlength="4" value="<?php echo $user->getPin(); ?>" /></td></tr>
		<tr><td><label for="roles">Roles</label></td>
			<td><select name="roles[]" id="roles" size="5" multiple="multiple">
				<?php
					$sql = "select role from roles";
					$roles = mysql_query($sql) or die($sql.mysql_error());
					while(list($role) = mysql_fetch_array($roles))
					{
						if (in_array($role,$user->getRoles())) { echo "<option selected=\"selected\">$role</option>"; }
						else { echo "<option>$role</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='home.php';">Cancel</button>
	</fieldset>
	</form>
</div>

<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
