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
	<fieldset><legend>Customer Info</legend>
		<table>
		<tr><td><label for="firstname">Firstname</label></td>
			<td><label for="lastname">Lastname</label></td>
			<td>Payment Method</td>
		</tr>
		<tr><td><input name="firstname" id="firstname" /></td>
			<td><input name="lastname" id="lastname" /></td>
			<td><ul style="list-style-type:none;">
					<li><label><input name="paymentMethod" type="radio" value="cash" checked="checked" />Cash</label></li>
					<li><label><input name="paymentMethod" type="radio" value="check" />Check</label></li>
				</ul>
			</td>
		</tr>
		</table>
	</fieldset>
	<fieldset><legend>Services</legend>
		<table id="lineItemTable">
		<tr><th>Service</th><th>Unit</th><th>Cost</th></tr>
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
					<td><input name=\"feeQuantities[]\" id=\"feeQuantities[]\" size=\"2\" /></td>
					<td><input name=\"feeAmounts[]\" id=\"feeAmounts[]\" size=\"5\" /></td>
				</tr>
				";
			}
		?>
		</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
	</fieldset>
	</form>
</div>

<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
