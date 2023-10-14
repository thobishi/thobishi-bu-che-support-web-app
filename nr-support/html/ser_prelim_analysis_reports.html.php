<!-- <h3>Process Virtual Site Visit - Manage reports</h3> -->
<h3>Process desktop evaluation- Assign date and users</h3>
<div class="row-fluid">
<?php
	/*$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
//echo $prog_id;
	$this->displayProgrammeInfo();
	$this->view = 1;
	$url = "javascript:showSERreadOnly(" . $prog_id . ");";
	$screeningHistory = $this->getScreeningDetails('programme_ref', $prog_id);
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'screeningHistory', 'url'));*/
	
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
 $details = $this->getInstProgressDetails($_POST, Settings::get('template'));
 $nr_programme_id = $details[0]['id'];

//echo $prog_id;
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'screeningHistory', 'url'));
?>
</div>

<?php
	$this->showField("active_user_ref");
	$this->view = 0;
?>

<div class="hero-unit">
	<h3>Virtual Site Visit</h3>
	<p>
		The virtual site visitor must login to the system to access the SER, the Virtual Site Visit template and to upload their completed report. The report will display below once the have uploaded it.
		<ul>
			<li>
				The SER is accessible to the virtual site visitor for the access date period only.
			</li>
			<li>
				You may extend the access period by clicking on previous and changing the start and end dates.
			</li>
			<li>
				The virtual site visitor must upload the report on the system. You must follow up with the virtual site visitor if you do not see the report by the required deadline date. If, for some reason, the virtual site visitor cannot upload it, you may upload it on their behalf.
			</li>
		</ul>
	</p>
	<p>
		<?php $this->makeLink("analyst_report_doc", "Submit report on behalf of analyst");
		?>
	</p>
</div>
