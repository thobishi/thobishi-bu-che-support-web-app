<h3>Upload recommendation report</h3>

<div class="row-fluid">
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->displayProgrammeInfo();
	$this->view = 1;
	$url = "javascript:showSERreadOnly(" . $prog_id . ");";
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	$evalStatus = $this->getSelfEvalStatus($prog_id,'recommendation_writer');
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'url'));
	
?>
</div>

<?php
	$this->displayRoleDueDates("recommendation");
	$this->view = 0;
	$link1 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_recommWriterCriteriaEvaluation');
?>

<table class="table table-bordered  screeningTable">
	<tr>
		<td class="scrNumber">
			1
		</td>
		<td class="scrDescription">
			Download the HEQC Recommendation Report template, save it locally and complete
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Download the Evaluation Report template</legend>
				<a target="_blank" href="html_documents/Recommendation_template.docx"><img src="images/DOC.png" alt="DOC">&nbsp;Download the template</a>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="scrNumber">
			2
		</td>
		<td  class="scrDescription">
			Upload the completed Recommendation Report<br><br>
			Please note: You can upload newer versions until your report is ready for submission
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Upload the Recommendation Report</legend>
			<?php 
				$this->makeLink("recommendation_report_doc", "Upload recommendation report","","", "","recommendation_report_date_uploaded");
			?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="scrNumber">
			3
		</td>
		<td class="scrDescription">
			Indicate the institution's performance in relation to the relevant criteria
		</td>
		<td class="fieldsetData sign-off">
			<fieldset><legend>Recommendation's evaluation against criteria</legend>
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


<!-- -->
<p>
<?php
	// $this->makeLink("recommendation_report_doc", "Upload recommendation report","","", "","recommendation_report_date_uploaded");
?>
</p>
