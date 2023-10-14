<?php 
// Do not display next if this is not the administrator.
if (!$this->sec_userInGroup("Institution")) {
	$this->formActions["next"]->actionMayShow = false;
}

	$app_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id",$app_id,"institution_ref");

//	$inst_id = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;

//	$this->displayRelevantButtons($app_id, $this->currentUserID);

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">
		<?php echo $this->displayReaccredHeader($app_id); ?>
	</td>
</tr>
<tr>
<td class="loud">Validation of re-accreditation application form<br><hr></td>
</tr>
<tr>
	<td>
	<span class="specialb">
	The following list indicates the fields you have not completed. Please complete these fields. Submission may <font color="red">only proceed</font> once validation of the entire institutional profile and programme application is successful.
	<br>
	At this point, if you are the <font color="red">institutional administrator and all validation is successful</font>, a <font color="red">Next</font> Button will appear for you to continue with the submission process.
	<br><br>
	</td>
</tr>
</table>
	<i>Note that you can click on the <img src="images/question_mark.gif"> next to the incomplete field, to go to the specific field.</i>
<br><br>
<b>Institutional Profile:</b>
<table width=90% border=0 align="center" cellpadding="2" cellspacing="2">
	<?php

	// If an institution id is not on the application then the user cannot continue.  Email a message to support to fix it.
	// A re-accreditation application should always have an institution id.
	if ($inst_id > 0){
		$instProfileValid = "true";
		$instProfileHtml = "";

		$instProfileFlag = $this->checkInstitutionalProfileContactInfoHeads ($inst_id);
			if (! $instProfileFlag ) $instProfileValid = "false";
		$instProfileFlag = $this->checkInstitutionalProfileContactInfo ($inst_id);
			if (! $instProfileFlag ) $instProfileValid = "false";

		// Institutional profile passes validation
		if ($instProfileValid == "true"){
			$instProfileHtml = <<<INSTHTML
				<tr><td class="oncolour">Institutional Profile is up to date.</td></tr>
INSTHTML;
		}
		
		// Institutional profile fails validation
		if ($instProfileValid == "false"){

			$this->formActions["next"]->actionMayShow = false;

			$instProfileHtml = <<<INSTHTML
				<tr>
					<td class="oncolour">
						Your institutional profile is not up to date.  
						<p>
						Please make sure that <font color="red">all contact information</font> is complete and that all 
						required <font color="red">documents</font> have been uploaded.
						</p>
						<p>
						You can update it by clicking on the <b>Tools</b> menu and selecting the <b>"Institutional Profile"</b> option.  
						Go to the validation page to see what information is missing.
						</p>
						<p>
						After the update, you can return to this page to submit this application.
						</p>
					</td>
				</tr>
INSTHTML;
		}
		
		echo $instProfileHtml;
		
	} else {
		// mail support
	}

	?>
</table>
<br>
<b>Re-accreditation Application Form:</b>
<table width=90% border=0 align="center" cellpadding="2" cellspacing="2">
<?php 
// 						array("reAccForm3","2.1 PROGRAMME DETAILS"),

	$forms = array(array("reAccForm2","2.1 PROGRAMME NAME, LEVEL, SAQA CREDITS AND REGISTRATION"),
						array("reAccForm5","2.3 PRIMARY CONTACT"),
						array("reAccForm6","2.4 PROGRAMME AND CONTEXT"),
						array("reAccForm7","2.5 PROGRAMME COORDINATION"),
						array("reAccForm8","2.6 WORK-BASED LEARNING"),
						array("reAccForm9","2.7 PROGRAMME DESIGN"),
						array("reAccForm10","2.8 STUDENT RECRUITMENT"),
						array("reAccForm11","2.9 STAFFING"),
						array("reAccForm12","2.10 TEACHING AND LEARNING"),
						array("reAccForm13","2.11 POST-GRADUATE PROGRAMMES"),
						array("reAccForm14","2.12 STUDENT ASSESSMENT"),
						array("reAccForm15","2.13 STUDENT RETENTION"),
						array("reAccForm16","2.14 PROGRAMME REVIEW"),
						array("reAccForm17","2.15 SELF-EVALUATION"),
						array("reAccForm18","2.16 FULFILLMENT OF CONDITIONS"));


	foreach($forms as $form){
?>
 		<tr>
			<td class="oncolour" colspan="3"><b><?php echo $form[1];?></b></td>
		</tr>
<?php
		$this->validateFields("$form[0]");
		if (isset($child["$form[0]"]) && $child["$form[0]"] > ""){
			$this->validateFieldsperChild($site_title,$child["$form[0]"],"application_ref",$app_id);
		}
	}

?>

</table><br><br>
<script>
	// label to take user back to the validation page
	document.defaultFrm.VALIDATION.value = '_labelReaccreditationValidation';
</script>
</td></tr></table>
<br><br>
