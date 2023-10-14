<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->returnAppToInstWithPayment($app_id,"screening");
?>