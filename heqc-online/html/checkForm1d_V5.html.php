
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr>
<td>
<?php



	$this->showInstitutionTableTop ();
	?>



<?php


	$this->formActions["gotoInstitution"]->actionMayShow = false;
	//$this->formActions["cancelProc"]->actionMayShow = false;
	$this->formActions["continueNorm"]->actionMayShow = false; 

	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	// 2012-06-07 Robin: Moved to checkForm1
	//echo $app_id ;
	$this->formFields["application_ref"]->fieldValue = $app_id;
    $this->showField("application_ref");
    $this->formFields["submission_date"]->fieldValue = $this->getValueFromTable("Institutions_application","application_id",$app_id,"submission_date");
    $this->showField("submission_date");
   	$is_at_manager = $this->getValueFromTable("screening", "application_ref", $app_id, "proc_to_manager");
	$grp = 7;  //Checklisting group

//	$grp = 221;
 /*
$this->formActions["gotoInstitution"]->actionMayShow = false;
$this->formActions["cancelProc"]->actionMayShow = false;
$this->formActions["continueNorm"]->actionMayShow = false; 
*/

	$Checklist_report = $this->getValueFromTable("ia_proceedings", "application_ref", $app_id, "checklist_doc");

	$SAQA_screening_report = $this->getValueFromTable("ia_proceedings", "application_ref", $app_id, "SAQA_screening_report_doc");

	

	$Checklist_reportDoc = new octoDoc($Checklist_report);
	if ($Checklist_reportDoc->isDoc()){
		$Checklist_reportdocument= "<a href='".$Checklist_reportDoc->url()."' target='_blank'>".$Checklist_reportDoc->getFilename()."</a>";
	}

	$SAQA_screening_reportdoc = new octoDoc($SAQA_screening_report);
	if ($SAQA_screening_reportdoc->isDoc()){
		$SAQA_screening_reportdocument= "<a href='".$SAQA_screening_reportdoc->url()."' target='_blank'>".$SAQA_screening_reportdoc->getFilename()."</a>";
	}

	$active_user_ref = $this->getValueFromTable("ia_proceedings", "application_ref", $app_id, "checklist_user_ref");

	$re_submission_date = $this->getValueFromTable("ia_proceedings", "application_ref", $app_id, "re_submission_date");

	
	$email = $this->getValueFromTable("users", "user_id", $active_user_ref, "email");

	$name = $this->getValueFromTable("users", "user_id", $active_user_ref, "name");

	$surname = $this->getValueFromTable("users", "user_id", $active_user_ref, "surname");
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

		<tr>
		<td>
			

Using the checklisting report and SAQA screening reports determine whether the application must be returned to the institution for review or whether the accreditation processing may continue.  Consolidate information and upload the final report.  This is the report that the institution will receive if you return the application to the institution.
</td>
		</tr>
		</table>

		<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">

<tr>
<td>
User
</td>
<td>
Re-submission date 
</td>
<td>


Checklist Report
</td>
<td>
SAQA Screening Report
</td>
<td>
Final report to be returned to institution or to be viewed by evaluators
</td>
</tr>



<tr>
<td>
<?php echo 	$email; ?>
</td>
<td><?php echo $re_submission_date; ?></td>
<td>


<?php echo 	$Checklist_reportdocument; ?>
</td>
<td>
<?php  	echo $SAQA_screening_reportdocument ;?>

<td>
<?php $this->makeLink("checklist_final_doc");?>
</td>
</tr>

</table>
<br>
<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">
<tr>

<td>

	Approve the background for this application 
</td>

<td>
<?php $this->showField('applic_background'); ?>
</td>
</tr>

</table>
<br>



<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr>
<td>
<b>Select an option based on whether the application is valid or the institution has addressed the concerns. </b>
</td>
</tr>
</table>


	
	
	<script>

//document.getElementById('action_Returntheapplication').style.display = "none";
 // document.getElementById('action_Returntheapplication_Img').style.display = "none";
  
  
 // document.getElementById('action_Canceltheapplication').style.display = "none";
 // document.getElementById('action_Canceltheapplication_Img').style.display = "none";
 
 
 // document.getElementById('action_next').style.display = "none"; 
 // document.getElementById('action_next_Img').style.display = "none"; 

function RadioMethod(src) {
  //alert(src.value);
  
  

  if(src.value=='ReturnApplication'){

	document.getElementById('action_nextpg').style.display = "none";
  document.getElementById('action_nextpg_Img').style.display = "none";

	 
	document.getElementById('action_Returntheapplication').style.display = "block";
	document.getElementById('action_Returntheapplication_Img').style.display = "block";

	document.getElementById('action_next').style.display = "none"; 
  document.getElementById('action_next_Img').style.display = "none"; 

	document.getElementById('action_Canceltheapplication').style.display = "none";
  document.getElementById('action_Canceltheapplication_Img').style.display = "none";
 
 

 
  

	
  }
  
  if(src.value=='ContinueApplication'){

	
document.getElementById('action_next').style.display = "block"; 
  document.getElementById('action_next_Img').style.display = "block"; 

	document.getElementById('action_Returntheapplication').style.display = "none";
  document.getElementById('action_Returntheapplication_Img').style.display = "none";
  
  
  document.getElementById('action_Canceltheapplication').style.display = "none";
  document.getElementById('action_Canceltheapplication_Img').style.display = "none";

  document.getElementById('action_nextpg').style.display = "none";
  document.getElementById('action_nextpg_Img').style.display = "none";

  }
  if(src.value=='CancelApplication'){

	document.getElementById('action_nextpg').style.display = "none";
  document.getElementById('action_nextpg_Img').style.display = "none";

	document.getElementById('action_Canceltheapplication').style.display = "block";
	document.getElementById('action_Canceltheapplication_Img').style.display = "block";

	document.getElementById('action_Returntheapplication').style.display = "none";
	document.getElementById('action_Returntheapplication_Img').style.display = "none";

	document.getElementById('action_next').style.display = "none"; 
 	 document.getElementById('action_next_Img').style.display = "none"; 
  }
  if(src.value=='Returntochecklister'){

document.getElementById('action_nextpg').style.display = "block";
document.getElementById('action_nextpg_Img').style.display = "block";

document.getElementById('action_Canceltheapplication').style.display = "none";
document.getElementById('action_Canceltheapplication_Img').style.display = "none";

document.getElementById('action_Returntheapplication').style.display = "none";
document.getElementById('action_Returntheapplication_Img').style.display = "none";

document.getElementById('action_next').style.display = "none"; 
  document.getElementById('action_next_Img').style.display = "none"; 
}
  }

  
  
</script>



<br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<tr>
<td>
   <input type="radio" id="Return" name="fav_language"  onchange="RadioMethod(this);"  value="ReturnApplication">
   <label for="html"><b>Return the application to the institution for Review</b>
</td>
<td>
   If the application is missing required information as per the checklisting report then the application should be returned to the institution for review.
</label></td>

</tr>
<tr>
<td>
   <input type="radio" id="Continue" name="fav_language"  onchange="RadioMethod(this);"  value="ContinueApplication">
   <label for="css"><b>Continue accreditation processing</b>
</td>
<td>
    If the application has all information as required then the accreditation application processing should be continued
</label></td>

</tr>
<tr>
<td>
   <input type="radio" id="Cancel" name="fav_language"  onchange="RadioMethod(this);"  value="CancelApplication">
   <label for="javascript"><b>Cancel the application</b></td>
<td>
     If the institution has reviewed the application to the maximum number of allowed times and is still missing required information as per the checklisting report then this option should be selected.

</label></td>

</tr>
<tr>
<td>
   <input type="radio" id="Cancel" name="fav_language"  onchange="RadioMethod(this);"  value="Returntochecklister">
   <label for="javascript"><b>Return to checklister</b></td>
<td>
Return to checklister for screening

</label></td>

</tr>

</table>

<br>
	
	

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr>
<td>
<b>Please check this box to indicate that the screening approval has been completed and the required option has been selected above:   <?php $this->showField('screening_approval');	?>
<b/>
</td>
</tr>
</table>

</td>
</tr>
</table>



<script>

	function changeProcUser (num) {
		document.defaultFrm.gotoManager.value = num;
		return true;
	}
	function changeToInst (num) {
		document.defaultFrm.gotoInst.value = num;
		return true;
	}
	function cancelProc (num) {
		document.defaultFrm.doCancelProc.value = num;
		return true;
	}

	function changeToScreening (num) {
		document.defaultFrm.gotoscreening.value = num;
		return true;
	}

</script>