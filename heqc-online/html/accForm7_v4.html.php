
<?php 
	$ins_id = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;
	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites_v4($current_id); }

	$prov_type = $this->checkAppPrivPubl($current_id);

	$this->displayRelevantButtons($current_id, $this->currentUserID);

	$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");
	$cesm_generation = ($app_version >= 4) ? 'generation3_ind = 1' : 'generation = 2';
	
	$ins_ref  = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$inst_name = $this->getInstitutionName($ins_ref);

	//$this->formFields["submission_date"]->fieldValue = date("Y/m/d");
    //$this->showField("submission_date");
	
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
</td>
</tr>

<tr>
	<td>
	<br><br><b>Please use the above reference number in all future queries</b>
		<br><br><b>Once you click on "Submit to CHE", your application will be sent to the HEQC Accreditation Directorate.</b>
		<br>
		<br>
		You can view / print your application form by clicking on the "View / Print Application Form" in the actions menu.
<br><br><br><br></td></tr></table>