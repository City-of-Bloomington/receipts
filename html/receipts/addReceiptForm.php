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
	<script type="text/javascript">
		var feeRequest = getXMLHttpRequestObject();
		var i;

		function updateFeeAmount(index,feeID)
		{
			if (feeID != "")
			{
				i = index;
				feeRequest.open("get","<?php echo BASE_URL; ?>/AJAX/getFeeAmount.php?feeID="+feeID);
				feeRequest.onreadystatechange = handleFeeAmount;
				feeRequest.send(null);
			}
			else
			{
				document.getElementById("feeQuantity_"+index).value = "";
				document.getElementById("feeAmount_"+index).value = "";
			}
		}

		function handleFeeAmount()
		{
			if (feeRequest.readyState == 4)
			{
				var quantity = document.getElementById("feeQuantity_"+i).value;
				if (quantity == "") { quantity = 1; }

				var amount = feeRequest.responseText;

				var total = quantity * amount;
				total = total.toFixed(2);
				document.getElementById("feeQuantity_"+i).value = quantity;
				document.getElementById("feeAmount_"+i).value = total;

				updateTotal();
			}
		}

		function updateTotal()
		{
			var total = 0;

			var inputs = document.getElementsByName('feeAmounts[]');
			for (var i=0; i<inputs.length; i++)
			{
				if (inputs[i].value != "") { total += Number(inputs[i].value); }
			}

			if (total == 0) { total = ""; }
			else { total = "$" + total.toFixed(2); }
			document.getElementById('total').innerHTML = total;
		}
	</script>
	<form method="post" action="addReceipt.php">
	<fieldset><legend>1. Customer Info</legend>
		<table>
		<tr><td><label for="firstname">First Name</label></td>
			<td><label for="lastname" class="required">Last Name</label></td></tr>
		<tr><td><input name="firstname" id="firstname" /></td>
			<td><input name="lastname" id="lastname" /></td></tr>
		</table>
	</fieldset>
	<fieldset><legend>2. Payment Method</legend>
		<ul style="list-style-type:none;">
			<li><label><input name="paymentMethod" type="radio" value="cash" />Cash</label></li>
			<li><label><input name="paymentMethod" type="radio" value="check" />Check</label></li>
			<li><label><input name="paymentMethod" type="radio" value="credit card" />Credit Card</label></li>
			<li><label><input name="paymentMethod" type="radio" value="money order" />Money Order</label></li>
		</ul>
	</fieldset>
	<fieldset><legend>3. Services</legend>
		<table id="lineItemTable">
		<tr><th>Service</th><th>Qty</th><th>Cost</th><th>Notes</th></tr>
		<?php
			require_once(APPLICATION_HOME."/classes/FeeList.inc");
			$feeList = new FeeList();
			$feeList->find();

			for($i=1; $i<=5; $i++)
			{
				echo "
				<tr><td><select name=\"feeIDs[]\" id=\"feeID_$i\" onchange=\"updateFeeAmount($i,this.options[this.selectedIndex].value)\"><option></option>";
				foreach($feeList as $fee)
				{
					echo "<option value=\"{$fee->getFeeID()}\">{$fee->getName()}</option>";
				}
				echo "
						</select>
					</td>
					<td><input name=\"feeQuantities[]\" id=\"feeQuantity_$i\" size=\"2\" onchange=\"updateFeeAmount($i,document.getElementById('feeID_$i').options[document.getElementById('feeID_$i').selectedIndex].value);\" /></td>
					<td><input name=\"feeAmounts[]\" id=\"feeAmount_$i\" size=\"5\" onchange=\"updateTotal();\" /></td>
					<td><input name=\"feeNotes[]\" id=\"feeNote_$i\"/></td>
				</tr>
				";
			}
		?>
		<tr class="total"><th colspan="2">Total</th><td id="total"></td><td></td></tr>
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
