<?php
	if (isset($_SESSION['USER']))
	{
		if (in_array("Administrator",$_SESSION['USER']->getRoles())) { include(APPLICATION_HOME."/includes/admin.inc"); }
	}
?>
<div class="sidebar">
	<div class="titleBar">Receipts</div>
</div>