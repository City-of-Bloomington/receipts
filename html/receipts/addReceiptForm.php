<?php
	verifyUser();

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<h1>Receipt of Payment</h1>
	<form method="post" action="addReceipt.php">
	<fieldset><legend>1. Customer Info</legend>
		<table>
		<tr><td><label for="firstname">First Name</label></td>
			<td><label for="lastname">Last Name</label></td></tr>
		<tr><td><input name="firstname" id="firstname" /></td>
			<td><input name="lastname" id="lastname" /></td></tr>
		</table>
	</fieldset>
	<fieldset><legend>2. Payment Method</legend>
		<ul style="list-style-type:none;">
			<li><label><input name="paymentMethod" type="radio" value="cash" />Cash</label></li>
			<li><label><input name="paymentMethod" type="radio" value="check" />Check</label></li>
		</ul>
	</fieldset>
	<fieldset><legend>3. Services</legend>
		<table id="lineItemTable">
		<tr><th>Service</th><th>Qty</th><th>Cost</th><th>Notes</th></tr>
		<?php
			require_once(APPLICATION_HOME."/classes/FeeList.inc");
			$feeList = new FeeList();
			$feeList->findAll();

			for($i=1; $i<=5; $i++)
			{
				echo "
				<tr><td><select name=\"feeIDs[]\"><option></option>";
				foreach($feeList as $fee)
				{
					echo "<option value=\"{$fee->getFeeID()}\">{$fee->getName()}</option>";
				}
				echo "
						</select>
					</td>
					<td><input name=\"feeQuantities[]\" size=\"2\" /></td>
					<td><input name=\"feeAmounts[]\" size=\"5\" /></td>
					<td><input name=\"feeNotes[]\" /></td>
				</tr>
				";
			}
		?>
		</tr>
		</table>

	</fieldset>
	<fieldset><legend>Additional Notes</legend>
		<div><textarea name="notes" id="notes" rows="4" cols="60"></textarea></div>
		<div><button type="submit" class="submit">Submit</button></div>
	</fieldset>
	</form>
</div>

<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
