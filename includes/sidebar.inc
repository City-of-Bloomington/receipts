<?php
	if (isset($_SESSION['USER']))
	{
		if (in_array("Administrator",$_SESSION['USER']->getRoles())) { include(APPLICATION_HOME."/includes/admin.inc"); }
	}
?>
<div class="sidebar">
	<div class="titleBar">Receipts</div>
	<ul><li><a href="<?php echo BASE_URL; ?>/receipts">Receipts</a></li>
		<li><a href="<?php echo BASE_URL; ?>/receipts/addReceiptForm.php">New Receipt</a></li>
	</ul>
</div>