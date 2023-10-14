<a name="application_form_question8"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>8. PROGRAMME ADMINISTRATIVE SERVICES: (Criterion 8)</b> [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
Taking into account the required minimum standards for this item and the requested supporting documentation, please answer the following questions.
<br><br>
<b>Minimum standards:</b> [<?php $this->popupContent("Minimum standards", "MinHelp", "", true) ?>]<br>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>8.1</b></td><td valign="top"><b>What administrative services does the programme have in order to provide information, manage the programme information system, and deal with the needs of a diverse student population?</b> </td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>8.2</b></td><td valign="top"><b>How do administrative services ensure the integrity of the processes leading to certification of the qualification obtained through the programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("8_2_comment") ?></td>
</tr></table>
<br><br>

<?php /* 
<br><br>

<div id="notComply" style="display:none">
	<b>*Please suggest improvement:</b>
</div>
<div id="comply" style="display:Block">
<b>Taking into account the required minimum standards, please answer all aspects of question number 7:</b><br>
</div>
<?php $this->showField("7_comment") ?>
<br><br>

<b>Please tick in the box the extent to which this programme meets the minimum standards for programme administrative services:</b><br>
<?php $this->showField("7_criteria") ?>
<br><br>
*/ ?>

<b>In the space below indicate to what extent does your programme comply with the criterion 8:</b><br>
<?php $this->showField("8_criteria") ?>
<br><br>

<?php /* 
<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation</b>
<?php // $this->showField("7_self_evaluation") ?>
<br><br>
*/ ?>

<fieldset>
<legend><b>Required Documentation</b></legend>
<br>

<?php
	$prov_type = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "institution_ref"), "priv_publ");
	$display1 = "none";
	$display2 = "none";
	if ($prov_type == 1) {
		$display1 = "Block";
	}
	if ($prov_type == 2) {
		$display2 = "Block";
	}
?>

<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
<tr>
<td><?php $this->showInstProfileUploadedDocs($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "institution_id"));?></td>
</tr>
</table>
<br><br>

<!-- The following is for private providers  -->
<div style="display:<?php echo $display1?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPrivate()?>
</td></tr></table>
</div>

<!-- The following is for PUBLIC providers  -->
<div style="display:<?php echo $display2?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPublic()?>
</td></tr></table>
</div>

<br><br>
<!-- The following is for private providers  -->
<div style="display:<?php echo $display1?>">
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Policies / procedures for the certification of qualifications:</b>
			<br><?php $this->showField("8_policies") ?></td>
		</tr><tr>
			<td><div id="div_FLD_8_policies" style="display:none" >
			Please explain why not:
			<br><?php $this->showField("8_policies_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_8_policies_doc" style="display:<?php echo echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "8_policies") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br> 
			<?php $this->makeLink("8_policies_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
</div>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Upload any other documentation which will indicate your compliance with this criterion.</b><br></td>
		</tr><tr>
			<td>
			Upload document electronically:
			<br> 
			<?php $this->makeLink("8_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>

</fieldset>
<?php
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
?>
<br><br>
<b>Management Information System:</b>
<br><br>
<a name="appTable_7_management_info_system"></a>
<?php
	// Management Information System
	// appTable_7_management_info_system
/*	$fieldsArr = array();
	$fieldsArr["tech_desc_textFLD"] = "Technical Description (Such as platform, type of database/s, software, etc.)";
	$fieldsArr["fields_info_textFLD"] = "Fields of Information (eg. Student Registration Number, Race, Gender, Marks, etc.)";
	$fieldsArr["periodicity_reports_textFLD"] = "Type and Periodicity of Reports (eg. Passing Rates - Annually, Progression - Quarterly, etc.)";
	$fieldsArr["inst_central_mis_textFLD"] = "Interface with institution's central MIS (describe)";
	$fieldsArr["sec_features_textFLD"] = "Security Features (describe)";
	$fieldsArr["online_access_selectFLD"] = "Online access for students (yes/no)";

	$select_arr["online_access_selectFLD"]["description_fld"] = "lkp_yn_desc";
	$select_arr["online_access_selectFLD"]["fld_key"] = "lkp_yn_id";
	$select_arr["online_access_selectFLD"]["lkp_table"] = "lkp_yes_no";
	$select_arr["online_access_selectFLD"]["lkp_condition"] = "1";
	$select_arr["online_access_selectFLD"]["order_by"] = "lkp_yn_desc";
	
	echo $this->gridDisplayPerTable("Institutions_application", "appTable_7_management_info_system", "appTable_7_management_info_system_id", "application_ref",$fieldsArr, 5, "", "", "", $select_arr, "", "", 1, false);
*/
	$headArr = array();
	array_push($headArr, "Technical Description (Such as platform, type of database/s, software, etc.)");
	array_push($headArr, "Fields of Information (eg. Student Registration Number, Race, Gender, Marks, etc.)");
	array_push($headArr, "Type and Periodicity of Reports (eg. Passing Rates - Annually, Progression - Quarterly, etc.)");
	array_push($headArr, "Interface with institution's central MIS (describe)");
	array_push($headArr, "Security Features (describe)");
	array_push($headArr, "Online access for students (yes/no)");
	
	$fieldArr = array();
	array_push($fieldArr, "type__textarea|name__tech_desc_textFLD");
	array_push($fieldArr, "type__textarea|name__fields_info_textFLD");
	array_push($fieldArr, "type__textarea|name__periodicity_reports_textFLD");
	array_push($fieldArr, "type__textarea|name__inst_central_mis_textFLD");
	array_push($fieldArr, "type__textarea|name__sec_features_textFLD");
	array_push($fieldArr, "type__select|name__online_access_selectFLD|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__1|order_by__lkp_yn_desc");
	
	$this->gridShowTableByRow("appTable_7_management_info_system", "appTable_7_management_info_system_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldArr, $headArr, 70, 10, false);

?>
<br><br>

<?php /*
<script>
	improvement(document.defaultFrm.FLD_7_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
	checkCriteria (document.defaultFrm.FLD_7_criteria);
</script>
*/ ?> 
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
</td></tr></table>
