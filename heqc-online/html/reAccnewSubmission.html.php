<?php
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
	$che_ref_no = $this->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reaccred_id,"referenceNumber");

	$this->formFields["application_ref"]->fieldValue = $this->getValueFromTable("Institutions_application","CHE_reference_code",$che_ref_no,"application_id");
	$this->formFields["reaccreditation_application_ref"]->fieldValue = $reaccred_id;
	$this->formFields["submission_date"]->fieldValue = $this->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reaccred_id,"reacc_submission_date");
	$this->formFields["lkp_proceedings_ref"]->fieldValue = 5;  // Set proceedings to apply for accreditation

    $this->showField("application_ref");
    $this->showField("reaccreditation_application_ref");
    $this->showField("submission_date");
	$this->showField("lkp_proceedings_ref");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->displayReaccredHeader ($reaccred_id); ?>
		<br>
		<br>
		<table width="85%" border=0  cellpadding="2" cellspacing="2">
		<tr>
			<td><b>The above application has been submitted for Reaccreditation.</b></td>
		</tr>
		<tr>
			<td class="oncolour">A reaccreditation proceedings will be created for this application:</td>
			<td>
			<?php 
			?>				
			</td>
		</tr>
		<tr>
			<td>
				<br>
				Please click on Next in the actions menu to pass this process to the user responsible for 
				processing the payment for this application.
			</td>
		</tr>
		</table>
		<br>
		<br>
	</td>
</tr>
</table>

