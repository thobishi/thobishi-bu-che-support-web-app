<?php 
	$this->setValueInTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID, "evalReport_completed", 2);
	$this->setValueInTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID, "evalReport_date_completed", date("Y-m-d"));
?>