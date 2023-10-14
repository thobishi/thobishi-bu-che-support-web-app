<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$link1 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_profile_llb');
	$link4 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_data_llb');
	$fieldsComplete = $this->getStatusOfSection('nr_programmes', $prog_id, array('ser_profile'));
	$totalComplete = round((($fieldsComplete['totalCompleted']) /($fieldsComplete['totalRows']) * 100));
?>
<table class="table table-bordered table-striped serTable">
	<tr>
		<td class="serNumber">
			1
		</td>
		<td>
			Download the Self-evaluation report (SER) template and complete each section as indicated.
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Download documentation</legend>
				<a target="_blank" href="html_documents/LLB/LLB_SER_template_August_2015.docx"><img src="images/DOC.png" alt="DOC">&nbsp;Download the template</a>
				&nbsp;&nbsp;&nbsp;
				<a target="_blank" href="html_documents/LLB/National_Reviews_ Manual_20150915.pdf"><img src="images/PDF.png" alt="PDF" />&nbsp;Download the manual</a>
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
			<a href='<?php echo $link1; ?>'>Provide profile data for the programme</a>
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Profile information</legend>
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
</table>
