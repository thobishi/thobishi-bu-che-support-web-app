<?php 
// Do not display next if this is not the administrator.
// Version 3 is not taken into account because validation of page where V3 changes take place is not included here.
// accForm1_v3 is validated immediately because the administrator captures it and it must be correct before the application
// is passed on to another user.
if (!$this->sec_userInGroup("Institution")) {
	$this->formActions["next"]->actionMayShow = false;
}

	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$inst_id = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;
	$prov_type = $this->checkAppPrivPubl($app_id);

	$this->displayRelevantButtons($app_id, $this->currentUserID);

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr>
	<td>
	<span class="specialb">
	The following list indicates the fields you have not completed. Please complete these fields. Submission may <font color="red">only proceed</font> once validation of the entire institutional profile and programme application is successful.
	<br><br>
	At this point, if you are the <font color="red">institutional administrator and all validation is successful</font>, a <font color="red">Next</font> Button will appear for you to continue with the submission process.
	<br><br>
If the application form is completed to your satisfaction send it to the administrator so that it can be submitted to the HEQC. To do this use Send application back to administrator feature in the right navigation bar.
<br><br>
If the information is not complete, send it back to the relevant person.  To do this use Send application to a colleague feature in the right navigation bar.
	</span>
	</td>
</tr>
</table>
<br><br>
<b>Institutional Profile:</b>
<table width=75% border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>
	<?php	$instProfileFlag = $this->checkInstitutionalProfileContactInfo ($inst_id); ?>

		<table cellpadding="3">
			<tr>
	<?php 
	if (! $instProfileFlag ) { ?>

			<td class="oncolour"><b>Your Institutional Profile's site information does not seem to be up-to-date.</b></td>
			</tr><tr>
			<td class="oncolour">Please make sure that <font color="red">all contact information as well as all addresses of your sites</font> are complete.</td>
			<tr>
			<td class="oncolour">You can update it by clicking on the <b>Tools</b> menu and selecting the <b>"Institutional Profile"</b> option.</td>
			</tr><tr>
			<td class="oncolour">After the update, you can return to this page to complete the application.</td>
			</tr>

<?php 
			$this->formActions["next"]->actionMayShow = false;
		}else {
?>
			<td><b>Your institutional Profile's site information seems to be up-to-date.</b></td>
<?php 
		}
		
?>
			<tr>
		</table>

	</td>
</tr></table>
<br><br>
	<i>Note that you can click on the <img src="images/question_mark.gif"> next to the incomplete field, to go to the specific field.</i>
<br><br>
<b>Application Form:</b>
<table width=90% border=0 align="center" cellpadding="2" cellspacing="2">
<?php /*
	<tr>
		<td align="left" class="oncolour" colspan="3"><b>Programme Information</b></td>
	</tr>
*/

// Removed by Robin: These fields are validated on the page. Processing cannot continue unless they are valid.
// Thus it is not necessary to include them in the validation report.
//$this->validateFields("accForm1b_v2");
//$this->validateFields("accForm1_v2");

	$nqf = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "NQF_ref");
	$mode = $this->getValueFromTable("Institutions_application", "application_id", $app_id , "mode_delivery");
	$site_title = $this->getArraySiteNamesforApp($app_id);


	//
	$forms = array(array("accForm3-1_v4","SECTION C: SITE OF DELIVERY"),	
	array("accForm7_v4_3","SECTION D:PROGRAMME / QUALIFICATION DESIGN"),
		array("accForm6_v4_3","SECTION E: STUDENT RECRUITMENT, ADMISSION AND SELECTION"),
		array("accFormab7_v4","SECTION F: PROGRAMME PROVISIONING"),
		array("accForm5_v4","SECTION H: REQUIRED DOCUMENTS "),
		array("accFormI5_6_V4","SECTION I: INTERNAL QUALITY ASSURANCE"),);

	// if ($nqf >= 3) {
	 	//array_push($forms, array("accForm5_v4_3","INFRASTRUCTURE, STAFFING AND HEADCOUNT ENROLMENTS PER SITE OF DELIVERY"));
	 	//$child["accForm5_v4_3"] = "accForm5_2_v4_3";
		 //$child["accForm19_v2"] = "accForm19_2_v2";
	//}

	// // Exclude Unisa as well
	// if (($mode == 2 || $mode == 6) && $inst_id != 54) {
	// 	array_push($forms, array("accForm30_v2","C) PROGRAMMES OFFERED THROUGH DISTANCE EDUCATION"));
	// }

	switch ($prov_type){
		case 1: //private
			// $child["accForm8_1_v2"] = "accForm8_2_v2";
			// $child["accForm8b_v2"] = "accForm8b_2_v2";
			// $child["accForm15_v2"] = "accForm15_2_v2";
			// $child["accForm17_v2"] = "accForm17_2_v2";
			break;
		case 2: //public
			break;
		}

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
	//disabling validation
		//$this->formActions["next"]->actionMayShow = true;
?>

</table><br><br>
<script>
	// label to take user back to the validation page
	document.defaultFrm.VALIDATION.value = '_gotoValidationApplication_v4';
</script>
</td></tr></table>
<br><br>
