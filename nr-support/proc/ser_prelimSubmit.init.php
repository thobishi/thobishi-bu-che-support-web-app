<?php
	$prog_id = $this->dbTableInfoArray['nr_programmes']->dbTableCurrentID;
	
	$this->db->setValueInTable('nr_programmes','id',$prog_id,'prelimAnalysis_completed','1');
	$this->db->setValueInTable('nr_programmes','id',$prog_id,'prelimAnalysis_date_submitted', date('Y-m-d'));

?>