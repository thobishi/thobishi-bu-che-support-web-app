<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br><br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td><b>The application has been checked for completion. Now you need to establish whether the programme conforms to current policy. Use the template below to do the check. This same template will be incorporated into your summary of the programme for the Accreditation Committee meeting.</b></td>
</tr>
</table>
<table cellpadding="2" cellspacing="2" border="0" width="90%">
<?php 
	$this->showGeneralProgramInfo ($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
?>
</table>
<br><br>
<table cellpadding="2" cellspacing="2" border="1" width="90%">
<tr>
	<td colspan="3" align="center"><b>COMPLIANCE WITH APPROPRIATE REGULATIONS:</b></td>
</tr>
<?php 
$headingArray = array();
array_push($headingArray,"REGULATION");
array_push($headingArray,"COMPLY");
array_push($headingArray,"COMMENTS <br>(include in the comments what actions have been taken in case of lack/incorrect information)");

$refDispArray = array();
array_push($refDispArray,"lkp_screening_regulation_desc");

$dispFields = array();
array_push($dispFields,"yes_no");
array_push($dispFields,"comments_text");

$where = "lkp_screening_regulation_id=1 OR lkp_screening_regulation_id=4";
if ($this->getValueFromTable("Institutions_application", "application_id", $app_id, "prog_type") == 3) {
	$where .= " OR lkp_screening_regulation_id=5";
}

if ($this->getValueFromTable("institutional_profile", "institution_ref", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id"), "institutional_type") == 2) {
	$where .= " OR lkp_screening_regulation_id=2";
}

if ($this->table_field_info($this->active_processes_id, "InstitutionType") != "Private") {
	$where .= " OR lkp_screening_regulation_id=3";
}

$where .= " OR (lkp_screening_regulation_id > 6 AND lkp_screening_regulation_id < 15)";

if ($this->getValueFromTable("Institutions_application", "application_id", $app_id, "NQF_ref") >= 3) {
	$where .= " OR lkp_screening_regulation_id=15";
}

$this->makeGRID("lkp_screening_regulation",$refDispArray,"lkp_screening_regulation_id","1 AND ".$where,"screening_compliance","screening_compliance_id","regulation_ref","screening_ref",$this->dbTableInfoArray["screening"]->dbTableCurrentID,$dispFields,$headingArray, "", "", "100%", 5);
//$this->gridShow("lkp_screening_regulation",$refDispArray,"lkp_screening_regulation_id","1 AND ".$where,"screening_compliance","screening_compliance_id","regulation_ref","screening_ref",$this->dbTableInfoArray["screening"]->dbTableCurrentID,$dispFields,$headingArray, "", "", "100%", 5);
//function gridShow ($table, $key_fld, $unique_flds, $fields_arr, $html_table_headings_arr, )

//application.class.php function gridshow


/*
$checkBoxArr = explode("|", $documentation);
$no_docs = $docs = $doc_url = array();
$this->returnApplicationDocs (&$docs, &$no_docs, &$doc_url);
$flag = 0;

foreach ($docs AS $key=>$value) {
	if (($checkBoxArr[0] > "") && (!(in_array(substr($key, 9, strlen($key)), $checkBoxArr))) ) {
		$flag = 1;
	}
	if ($checkBoxArr[0] == "") {
		$flag = 1;
	}
}
*/

?>


</table>
</td></tr></table>
<script>
	function checkDocs () {
//		var flag = <?php //=$flag?>;
		var count = 0;
		if (document.defaultFrm.MOVETO.value == "next") {
//			if (flag == 1) {
//				alert('There is still some supporting documentation missing. Please click \'Previous\' to go back and check it.');
//				document.defaultFrm.MOVETO.value = '';
//				return false;
//			}
			for (i=0; i<document.defaultFrm.length; i++) {
				if ((document.defaultFrm[i].type=='radio')) {
					if ((document.defaultFrm[i].value == 2) && !(document.defaultFrm[i].checked) ) {
						count++;
					}
				}
			}
			if (count > 0) {
				alert("Please complete the yes/no questions.");
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			return true;
		}
	}
</script>