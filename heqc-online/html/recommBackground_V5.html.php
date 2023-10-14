<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	// 2012-06-07 Robin: Moved to checkForm1
	echo $app_id ;
	$this->formFields["application_ref"]->fieldValue = $app_id;
    $this->showField("application_ref");
    $this->formFields["submission_date"]->fieldValue = $this->getValueFromTable("Institutions_application","application_id",$app_id,"submission_date");
    $this->showField("submission_date");
   	$is_at_manager = $this->getValueFromTable("screening", "application_ref", $app_id, "proc_to_manager");
	//$grp = 7;  //Checklisting group

	$grp = 221;
?> 
<br>
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
Checklist report
</td>
<td>
SAQA screening report
</td>
<td>
Final report to be returned to institution or to be viewed by evaluators
</td>
</tr>



<tr>
<td>
Isaiah@gmail.com
</td>
<td>
2021-12-17
</td>
<td>
SAQA screening report
</td>
<td>
SAQA screening report
</td>

<td>
<?php $this->makeLink("checklist_final_doc");?>
</td>
</tr>

</table>
<br>
<br>



<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td colspan="2">
<?php 
	$headArr = array();
	// array_push($headArr, "Contact:vertical");
	// array_push($headArr, "Distance:vertical");
	array_push($headArr, "Select:vertical");
	array_push($headArr, "mode of provisioning");
	array_push($headArr, "Indicate % contact provisioning");
	array_push($headArr, "Indicate % online provisioning");

	$fieldArr = array();

	// array_push($fieldArr, "type__checkbox|name__contact_checkbox");
	// array_push($fieldArr, "type__checkbox|name__distance_checkbox");
	array_push($fieldArr, "type__radio|name__select_checkbox");
	array_push($fieldArr, "type__text|name__perc_contact|size_4");
	array_push($fieldArr, "type__text|name__perc_online|size__4");

?>
	<br>
	<b>Select an option based on whether the application is valid or the institution has addressed the concerns.</b>
	<br><br>
	<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
	<?php 
		$this->gridShow("lkp_checklistreport", "id", "application_ref__".
		$current_id, $fieldArr, $headArr, 
		"lkp_checklistreport", "id", "lkp_checklistreport_Description",
		 "lkp_mode_of_delivery_ref", 2);

			?>
	</tr>
	
	</table>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>


</table>
		
	</td>
</tr>
</table>
