<h3>Process upload Reference committee report</h3>
<div class="row-fluid">
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->displayProgrammeInfo();
	$this->view = 1;
	$url = "javascript:showSERreadOnly($prog_id);";
	// $screeningHistory = $this->getScreeningDetails('programme_ref', $prog_id);
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id','url'));
?>
</div>

<?php
	$this->view = 0;
?>

<div class="hero-unit">
	<!--<h3>Recommendation writer</h3>
	<p>
		The recommendation writer must logon to the system to access the SER, the Reports from the Pre-lin analysts and the Panel. A recommendation writer's report template is also available and should be completed and then uploaded, The report will display below once they have uploaded it
		<ul>
			<li>
				The SER and all other reports and documents are accessible to the recommendation writer for the access date period only.
			</li>
			<li>
				You may extend the access period by clicking on previous and changing the start and end dates.
			</li>
			<li>
				The Recommendation writer must upload the report on the system. You must follow up with the Recommendation writer if you do not see the report by the required deadline date. If, for some reason, the Recommendation writer cannot upload it, you may upload it on their behalf.
			</li>
		</ul>
	</p>-->
	<p>
		<?php $this->makeLink("heqc_recommendation_report_doc", "Upload Reference committee report","","", "","heqc_recommendation_report_date_uploaded");
		?>
	</p>
</div>