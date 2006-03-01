<?php
	verifyUser();

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>
	<h1>Find Receipts</h1>
	<form method="get" action="findReceiptResults.php">
	<fieldset><legend>Receipt Info</legend>
		<table>
		<tr><td><label for="receiptID">Receipt Number</label></td>
			<td><input name="receiptID" id="receiptID" /></td></tr>
		<tr><td><label for="month">Date</label></td>
			<td><select name="month" id="month"><option></option>
					<?php for($i=1; $i<=12; $i++) { echo "<option>$i</option>"; } ?>
				</select>
				<select name="day"><option></option>
					<?php for($i=1; $i<=31; $i++) { echo "<option>$i</option>"; } ?>
				</select>
				<select name="year"><option></option>
					<?php
						require_once(APPLICATION_HOME."/classes/ReceiptList.inc");
						$receiptList = new ReceiptList();
						$receiptList->find();
						for($i=$receiptList->getMinYear();$i<=$receiptList->getMaxYear();$i++) { echo "<option>$i</option>"; }
					?>
				</select>
			</td>
		</tr>
		</table>
	</fieldset>
	<fieldset><legend>Deposit Slip Info</legend>
		<table>
		<tr><td><label for="depositSlipMonth">Date</label></td>
			<td><select name="depositSlipMonth" id="depositSlipMonth"><option></option>
					<?php for($i=1; $i<=12; $i++) { echo "<option>$i</option>"; } ?>
				</select>
				<select name="depositSlipDay"><option></option>
					<?php for($i=1; $i<=31; $i++) { echo "<option>$i</option>"; } ?>
				</select>
				<select name="depositSlipYear"><option></option>
					<?php for($i=$receiptList->getMinYear();$i<=$receiptList->getMaxYear();$i++) { echo "<option>$i</option>"; } ?>
				</select>
			</td>
		</tr>
		</table>
	</fieldset>
	<fieldset><legend>Customer Info</legend>
		<table>
		<tr><td><label for="firstname">First Name</label></td>
			<td><label for="lastname">Last Name</label></td></tr>
		<tr><td><input name="firstname" id="firstname" /></td>
			<td><input name="lastname" id="lastname" /></td></tr>
		</table>
	</fieldset>
	<fieldset><legend>Payment Method</legend>
		<ul style="list-style-type:none;">
			<li><label><input name="paymentMethod" type="radio" value="cash" />Cash</label></li>
			<li><label><input name="paymentMethod" type="radio" value="check" />Check</label></li>
			<li><label><input name="paymentMethod" type="radio" value="money order" />Money Order</label></li>
		</ul>
	</fieldset>
	<fieldset><legend>Services</legend>
		<table>
		<tr><td><label for="service">Service</label></td>
			<td><label for="lineItemNotes">Notes</label></td></tr>
		<tr><td><select name="service" id="service"><option></option>
				<?php
					require_once(APPLICATION_HOME."/classes/FeeList.inc");
					$feeList = new FeeList();
					$feeList->find();
					foreach($feeList as $fee) { echo "<option>{$fee->getName()}</option>"; }
				?>
				</select>
			</td>
			<td><input name="lineItemNotes" id="lineItemNotes" /></td>
		</tr>
		</table>
	</fieldset>
	<fieldset><legend>Additional Notes</legend>
		<div><textarea name="notes" rows="3" cols="60"></textarea></div>
		<div><button type="submit" class="search">Search</button></div>
		</table>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
