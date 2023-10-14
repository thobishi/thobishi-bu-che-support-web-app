<?php 
$evals = $this->getEvaluatorsPerApplication($this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "application_ref"))
?>
