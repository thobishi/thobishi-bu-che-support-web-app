<h3>Virtual Site Visit </h3>

<div class="row-fluid">
<?php
	$this->view = 1;
	$currentUserID = Settings::get('currentUserID');
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	$this->displayProgrammeInfo();
	$url = "javascript:showSERreadOnly(" . $prog_id . ");";
	$evalStatus = $this->getSelfEvalStatus($prog_id,'panel_chair');
         echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'url'));


	
?>
</div>

<?php
	$this->displayRoleDueDates("chair");
	$this->showField("panel_user_ref");
	$this->view = 0;
	$link1 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_panelCriteriaEvaluation');
?>

<table class="table table-bordered  screeningTable">
	<tr>
		<td class="scrNumber">
			1
		</td>


                             
		<td class="scrDescription">
			Download the HEQC Virtual Site Visit Report template, save it locally and complete
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Download the Virtual Site Visit Report template</legend>
				<a target="_blank" href="html_documents/SER Desktop Evaluation Report Template_ 2020-06-19 _Final .docx"><img src="images/DOC.png" alt="DOC">&nbsp;Download the template</a>
			</fieldset>
		</td>
	</tr>
	<!--<tr>
		<td class="scrNumber">
			2
		</td>
		<td  class="scrDescription">
			Upload the Draft Evaluation Report<br><br>
			Please note: You can upload newer versions until your report is ready for submission
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Upload the Virtual Site Visit Report</legend>
			<?php 
				$this->makeLink("chair_report_doc", "Preliminary Virtual Site Visit Report","","", "","chair_report_date_uploaded");
				
			?>
			</fieldset>
		</td>
	</tr> -->


<tr>
		<td class="scrNumber">
			2
		</td>
		<td  class="scrDescription">
			Final Virtual Site Visit Report
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Upload the Draft Evaluation Report</legend>
			<?php 
				$this->makeLink("additional_doc", "Draft Evaluation Report");
				
			?>
			</fieldset>
		</td>
	</tr>

<tr>
		<td class="scrNumber">
			3
		</td>
		<td  class="scrDescription">
			Upload Final Report
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Upload Final Report</legend>
			<?php 
				$this->makeLink("draft_evaluation_report", "Final Report");
			?>
			</fieldset>
		</td>
	</tr>

	<tr>
		<td class="scrNumber">
			4
		</td>
		<td class="scrDescription">
			Indicate the institution's performance in relation to the relevant criteria
		</td>
		<td class="fieldsetData sign-off">
			<fieldset><legend>Panel's evaluation against criteria</legend>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>
								Status
							</th>
							<th>
								Date completed
							</th>
							<th>
								Actions
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?php 
									echo $evalStatus. "completed"; 
								?>
							</td>
							<td>
							</td>
							<td>
								<a href='<?php echo $link1; ?>'><img src="images/edit.png" alt="Edit" /></a>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
