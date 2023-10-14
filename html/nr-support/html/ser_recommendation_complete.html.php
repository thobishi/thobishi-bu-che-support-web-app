<h3>Process site visit - Finish</h3>
<div class="row-fluid">
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->displayProgrammeInfo();
	$this->view = 1;
	$url = "javascript:showSERreadOnly($prog_id);";
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id','url'));
?>
</div>

<?php
	$this->view = 0;
?>

<div class= "alert alert-block alert-success alert-large">
<?php
	echo "The recommendation process is now complete. Click on '<strong>Next</strong>' to proceed to the next user and process.";
?>
</div>