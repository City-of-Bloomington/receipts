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