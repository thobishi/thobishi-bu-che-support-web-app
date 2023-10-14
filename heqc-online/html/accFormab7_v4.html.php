
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>SECTION F: PROGRAMME PROVISIONING</h2>
	

</span>
</td></tr>
</table>
<?php 
	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites_v4($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);


	// $site_id = $this->dbTableInfoArray["ia_criteria_per_site"]->dbTableCurrentID;
	// $app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	// $this->getApplicationInfoTableTopForHEI_perSite($app_id, $site_id);


?>







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
	array_push($fieldArr, "type__checkbox|name__select_checkbox");
	array_push($fieldArr, "type__text|name__perc_contact|size_4");
	array_push($fieldArr, "type__text|name__perc_online|size__4");

?>
	
	<b>1. TEACHING AND LEARNING </b> <br>
	<b>Select the modes of provisioning for this programme</b><br>
	<b>Note that only 1 application for accreditation must be completed irrespective of mode of provisioning. </b>
	<br><br>
	<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
	<?php 
		$this->gridShow("ia_modes_of_delivery", "ia_mode_of_delivery_id", "application_ref__".
		$current_id, $fieldArr, $headArr, 
		"lkp_modeofdelivery", "id", "lkp_modeofdelivery_Description",
		 "lkp_mode_of_delivery_ref", 2);

		 // $this->gridShowRowByRow("ia_modes_of_delivery","ia_mode_of_delivery_id","application_ref__".
		 //$current_id,$dFields,$hFields, 40, 5, "true", "true",1);
	?>
	</tr>
	
	</table>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>

<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>2. Discuss the assessment strategy for the programme / qualification. Provide the types and forms of assessment undertaken to determine students’ conceptual understanding and applied competencies and successful completion of learning. Refer to integrated assessment (formative and summative assessment, including percentage weighting of tasks; WIL).: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("6_1_comment");?></td>
	</tr>

<!-- <tr>
<td colspan="2">
	<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0">
	<?php
		// $dFields = array();
		// array_push($dFields, "type__text|name__perc_contact");

		// //lkp_mode_of_delivery_id lkp_mode_of_delivery_desc
		// //2010-07-28 Robin: Limit NQF level to a drop down list for version 3 applications and up.
		// $app_version = $this->getValueFromTable("Institutions_application", "application_id", $current_id, "app_version");
		// // $app_version =3;
		// // if ($app_version <= 2){
		// // 	array_push($dFields, "type__text|name__nqf_level");
		// // }
		// if ($app_version >= 3){
		// 	array_push($dFields, "type__select|name__lkp_mode_of_delivery_ref|description_fld__lkp_mode_of_delivery_desc|fld_key__lkp_mode_of_delivery_id|lkp_table__lkp_mode_of_delivery|lkp_condition__1|order_by__lkp_mode_of_delivery_id");
		// }

		// array_push($dFields, "type__text|name__perc_online");

		// $hFields = array("Indicate % contact provisioning", "Mode of delivery", "Indicate % online provisioning");

		// $this->gridShowRowByRow("ia_modes_of_delivery","ia_mode_of_delivery_id","application_ref__".$current_id,$dFields,$hFields, 40, 5, "true", "true",1);
	?>
	</table>
</td>
</tr> -->
</table>





<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>

	<tr>
		<td valign="top"><b></b></td>
		<td valign="top" align='center'><b>PROGRAMME PROVISIONING DOCUMENTS.</b></td>
	</tr>
	
	<br>
	</table>
	<br>
<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">

<tr>
<td>
1.	LEARNING AND TEACHING 
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/LEARNING AND TEACHING.docx"?>">Learning and Teaching.docx</a>
</td>
<td>
<?php $this->makeLink("learning_and_teaching_doc");?>
</td>
</tr>

<tr>
<td>
2.	ASSESSMENT STRATEGY 
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/ASSESSMENT STRATEGY.docx"?>">Assessment Strategy.docx</a>
</td>
<td>
<?php $this->makeLink("assessment_doc");?>
</td>
</tr>


<tr>
<td>
3.	STAFFING: staff members relevant to this programme / qualification
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/STAFFING.docx"?>"> Staffing.docx</a>
</td>
<td>
<?php $this->makeLink("staffing_doc");?> 
</td>
</tr>


<tr>
<td>
4.	LEARNING MANAGEMENT SYSTEM  
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/LEARNING MANAGEMENT SYSTEM.docx"?>"> Learning Management System.docx</a>
</td>
<td>
<?php $this->makeLink("lms_doc");?>
</td>
</tr>

<tr>
<td>
5.	MANAGEMENT INFORMATION SYSTEM 
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/MANAGEMENT INFORMATION SYSTEM.docx"?>">Management Information System.docx</a>
</td>
<td>
<?php $this->makeLink("mis_doc");?>
</td>
</tr>

<tr>
<td>
6.	POSTGRADUATE PROGRAMME / QUALIFICATION
</td>
<td>
 <a href="<?php echo WRK_DOCUMENTS . "/POSTGRADUATE PROGRAMME.docx"?>"> Postgraduate.docx</a>
</td>
<td>
<?php $this->makeLink("postgraduate_doc");?> 
</td>
</tr>
</table>