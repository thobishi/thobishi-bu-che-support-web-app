<?php
	require_once ("_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$page = new HEQConline (1);
	$page->sendPaymentReminders();
?>