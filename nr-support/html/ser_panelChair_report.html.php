<h3>Process Virtual Site Visit - Manage chair report</h3>
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

<div class="hero-unit">
	<h3>Virtual Site Visit and panel members</h3>
	<p>
		The panel members must logon to the system to access the SER, the Virtual Site Visit report and the reviewer panel template. Only the panel chair will be able to capture the virtual site visitor data and upload the completed report. The report will display below once they have uploaded it.
		<ul>
			<li>
				The SER is accessible to the panel members for the access date period only.
			</li>
			<li>
				You may extend the access period by clicking on previous and changing the start and end dates.
			</li>
			<li>
				The panel chair must upload the report on the system. You must follow up with the chair if you do not see the report by the required deadline date. If, for some reason, the panel chair cannot upload it, you may upload it on their behalf.
			</li>
		</ul>
	</p>
	<p>
		<?php $this->makeLink("chair_report_doc", "Submit report on behalf of chair panel","","", "","chair_report_date_uploaded"); ?>
	</p>
</div>
