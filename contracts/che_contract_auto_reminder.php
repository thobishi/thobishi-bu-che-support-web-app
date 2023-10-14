<?php
	require_once ("_systems/contract/contract.php");
	$dbConnect = new dbConnect();
	$page = new contractRegister (1);
	$page->sendReminders();

?>