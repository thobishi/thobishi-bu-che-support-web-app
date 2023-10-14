<h3>Process upload National Review Committee report</h3>
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

<div class="hero-unit">
	<p>
		<?php 
			$this->makeLink("heqc_nrc_report_doc", "Upload National Review Committee report","","", "","heqc_nrc_report_date_uploaded");
		?>
	</p>
</div>