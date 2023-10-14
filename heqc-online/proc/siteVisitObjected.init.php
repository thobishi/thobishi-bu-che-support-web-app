<?php 
	$this->setValueInTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "site_visit", "No");
	$this->setValueInTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, "active_process_ref", $this->active_processes_id);
?>