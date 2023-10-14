<h3>Process Virtual Site Visit - Finish</h3>
<div class="row-fluid">
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->displayProgrammeInfo();
	$this->view = 1;
	$url = "javascript:showSERreadOnly($prog_id);";
	$screeningHistory = $this->getScreeningDetails('programme_ref', $prog_id);
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'screeningHistory', 'url'));
?>
</div>

<?php
	$this->showField("active_user_ref");
	$this->view = 0;
?>

<div class= "alert alert-block alert-success alert-large">
<?php
	echo "The virtual site visit process is now complete. Click on '<strong>Next</strong>' to proceed to the next user and process.";
?>
</div>