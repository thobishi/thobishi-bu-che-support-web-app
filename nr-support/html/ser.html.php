<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$link1 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_profile');
	$link4 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_data');
	$fieldsComplete = $this->getStatusOfSection('nr_programmes', $prog_id, array('ser_profile', 'ser_contact_head', 'ser_contact'));
	$tablesComplete = $this->getStatusOfTables($prog_id);
	$totalComplete = round((($fieldsComplete['totalCompleted'] + $tablesComplete['totalCompleted']) /($fieldsComplete['totalRows'] + $tablesComplete['totalRows'])) * 100);
	
	$evalStatus = $this->getSelfEvalStatus($prog_id,"institutional_administrator");
?>

<table class="table table-bordered table-striped serTable">
	<tr>
		<td class="serNumber">
			1
		</td>
		<td>
			Download the Self-evaluation report (SER) template and complete each section as indicated. Please refer to the minimum standards for each criteria
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Download documentation</legend>
				<a target="_blank" href="html_documents/SER_template.docx"><img src="images/DOC.png" alt="DOC">&nbsp;Download the template</a>
				&nbsp;&nbsp;&nbsp;
				<a target="_blank" href="html_documents/National_Review_Manual.pdf"><img src="images/PDF.png" alt="PDF" />&nbsp;Download the manual</a>
				&nbsp;&nbsp;&nbsp;
				<a target="_blank" href="html_documents/CHE_accreditation_criteria_Nov2004.pdf"><img src="images/PDF.png" alt="PDF" />&nbsp;Accreditation criteria</a>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="serNumber">
			2
		</td>
		<td class="text">
			<a href='<?php echo $link1; ?>'>Provide data as completed in the Baseline Data Tables section of the SER template</a>
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Baseline Data information</legend>
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
								<?php echo $totalComplete; ?>% complete
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
	<tr>
		<td class="serNumber">
			3
		</td>
		<td>
			Upload your Self-evaluation Report and the sign-off cover sheet provided in the SER template.  
			<br /><br />
			Please note: You can upload new versions until your report is ready for submission.
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Upload your SER and Sign-off Cover sheet</legend>
				<?php
					$this->makeLink("ser_doc", "SER document");
					$this->makeLink("signoff_doc", "SER sign-off document");
				?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="serNumber">
			4
		</td>
		<td>
			<a href='<?php echo $link4; ?>'>Indicate your performance in relation to the relevant criteria</a>
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Self-evaluation against criteria</legend>
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
								<?php echo $evalStatus . ' completed ' ; ?>
							</td>
							<td>
							</td>
							<td>
								<a href='<?php echo $link4; ?>'><img src="images/edit.png" alt="Edit" /></a>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="serNumber">
			5
		</td>
		<td>
			Before submission of the SER to CHE ensure that:
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Sign-off</legend>
			<?php
				$this->showField("signoff_nr_manual_ind");
				echo ' The manual and programme criteria were consulted during compilation of the SER  <br />';
				$this->showField("signoff_faculty_dean_ind");
				echo ' The Dean of the Faculty signed off <br />';
				$this->showField("signoff_head_ind");
				echo ' The Head of Programme signed off <br />';
				$this->showField("signoff_qa_office_ind");
				echo ' The Quality Assurance Representative signed off';
			?>
			</fieldset>
		</td>
	</tr>

	
</table>