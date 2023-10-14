<h3>Progress report of National Reviews</h3>
<?php
	// $details = $this->getNRProgressDetails($_POST, Settings::get('template'),'list');

	$progId = $this->dbTableInfoArray['nr_programmes']->dbTableCurrentID;
	
	$this->displayProgressReportOfNR($progId);
?>