<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
	$pay_id = $this->dbTableInfoArray["payment"]->dbTableCurrentID;
	$paid = $this->getValueFromTable("payment", "payment_id", $pay_id, "received_confirmation");
	if ($paid == 1){
		$pay_date = date("Y-m-d h:i:s");
		$this->setValueInTable ("payment", "payment_id", $pay_id, "date_payment", $pay_date);
	}

?>