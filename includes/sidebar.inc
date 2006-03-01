<?php
	if (isset($_SESSION['USER']))
	{
		if ( in_array("Administrator",$_SESSION['USER']->getRoles()) || in_array("Supervisor",$_SESSION['USER']->getRoles()) ) { include(APPLICATION_HOME."/includes/admin.inc"); }
	}
?>
<div class="sidebar">
	<div class="titleBar">Manage Receipts</div>
	<ul><li><a href="<?php echo BASE_URL; ?>/receipts/addReceiptForm.php">New Receipt</a></li>
		<li><a href="<?php echo BASE_URL; ?>/receipts">View Receipts</a></li>
		<li><a href="<?php echo BASE_URL; ?>/receipts/findReceiptForm.php">Find Receipts</a></li>

	</ul>
</div>

	<?php
	/*
<div class="sidebar">
	<div class="titleBar">Deposit Slips</div>
		require_once(GLOBAL_INCLUDES."/classes/Calendar.inc");
		$calendar = new Calendar();
		$calendar->setURL(BASE_URL."/receipts/viewDepositSlip.php");
		echo $calendar->asHTML();
	</div>
</div>
	*/
	?>
