<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "reAccForm2_v2";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Apply for Re-accreditation</span>";

	$this->formOnSubmit = "return checkFrm(this);";

	$this->scriptHead .= "function selectAll() {\n";
	$this->scriptHead .= "	sLength = document.defaultFrm.elements['FLDS_resultsSelect[]'].length;\n";
	$this->scriptHead .= "	for (i=0; i<sLength; i++) {\n";
	$this->scriptHead .= "		document.defaultFrm.elements['FLDS_resultsSelect[]'].options[i].selected = true;\n";
	$this->scriptHead .= "	}\n";
	$this->scriptHead .= "	return true;\n";
	$this->scriptHead .= "}\n";

	$this->scriptHead .= "\n\n";
	$this->scriptHead .= "function checkSites_select(obj) {\n";
	$this->scriptHead .= "	var resultSelect = document.defaultFrm.elements['FLDS_resultsSelect[]'];\n";
	$this->scriptHead .= "	if (resultSelect.length > 0) {\n";
	$this->scriptHead .= "		for (i=0; i<resultSelect.length; i++) {\n";
	$this->scriptHead .= "			if (obj[j].text == resultSelect.options[i].text) {\n";
	$this->scriptHead .= "				obj[j] = null;\n";
	$this->scriptHead .= "			}\n";
	$this->scriptHead .= "		}\n";
	$this->scriptHead .= "	}\n";
	$this->scriptHead .= "}\n";
	
	$this->scriptHead .= "\n\n";
	$this->scriptHead .= "function isInteger (str) {\n";
	$this->scriptHead .= "	var regexp = /(^-?\d\d*$)/;"."\n";
	$this->scriptHead .= "	return regexp.test(str);\n";
	$this->scriptHead .= "}\n\n";

	$this->scriptTail .= "\n\n";
	$this->scriptTail .= "for (j=0; j<document.defaultFrm.elements['sites_select'].length; j++) {\n";
	$this->scriptTail .= "	checkSites_select(document.defaultFrm.elements['sites_select']);\n";
	$this->scriptTail .= "}\n";

	$this->scriptTail .= "\n\n";
	$this->scriptTail .= <<<CHECKFORM
	function checkFrm(obj) {
		var flag = false;
		var count = 0;
		if (obj.MOVETO.value == 'next') {
			if (obj.FLD_programme_name.value == '') {
				alert('Please enter the programme name.');
				obj.FLD_programme_name.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_NQF_level.value == '0') {
				alert('Please enter the programme\'s NQF level.');
				obj.FLD_NQF_level.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_saqa_credits.value == '') {
				alert('Please enter number of credits.');
				obj.FLD_saqa_credits.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_full_time_duration.value == '') {
				alert('Please enter full time duration.');
				obj.FLD_full_time_duration.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_part_time_duration.value == '') {
				alert('Please enter part time duration.');
				obj.FLD_part_time_duration.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_mode_delivery.options[obj.FLD_mode_delivery.selectedIndex].value == 0) {
				alert('Please select mode of delivery.');
				obj.FLD_mode_delivery.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_CESM_level2_ref.options[obj.FLD_CESM_level2_ref.selectedIndex].value == 0) {
				alert('Please select first qualifier.');
				obj.FLD_CESM_level2_ref.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_CESM_level3_ref.options[obj.FLD_CESM_level3_ref.selectedIndex].value == 0) {
				alert('Please select second qualifier.');
				obj.FLD_CESM_level3_ref.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_is_reg_saqa_nqf.options[obj.FLD_is_reg_saqa_nqf.selectedIndex].value == 0) {
				alert('Please select if the qualification is registered by SAQA on the NQF.');
				obj.FLD_is_reg_saqa_nqf.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (document.defaultFrm.elements['FLDS_resultsSelect[]'].length == '0') {
				alert('Please select the sites that this programme is offered at before continuing');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_qualification_type_ref.options[obj.FLD_qualification_type_ref.selectedIndex].value == 0) {
				alert('Please select a qualification type.');
				obj.FLD_qualification_type_ref.focus();
				obj.MOVETO.value = '';
				return false;
			}
			var qtype = obj.FLD_qualification_type_ref.options[obj.FLD_qualification_type_ref.selectedIndex].value;
			if (obj.FLD_CESM_code1.options[obj.FLD_CESM_code1.selectedIndex].value == 0) {
				alert('Please select a CESM category.');
				obj.FLD_CESM_code1.focus();
				obj.MOVETO.value = '';
				return false;
			}
			/*//if (parseInt(obj.FLD_NQF_level.value) + 4 != a_nqfexit[qtype]) {
			//var nqf = parseInt(obj.FLD_NQF_level.options[obj.FLD_NQF_level.selectedIndex].value) + 4;
			//if ((nqf < a_nqfmin[qtype]) || (nqf > a_nqfmax[qtype])) {
			//	var nqf_range = a_nqfmin[qtype];
			//	if (a_nqfmin[qtype] != a_nqfmax[qtype]){
			//		nqf_range = a_nqfmin[qtype] + ' to ' + a_nqfmax[qtype];
			//	}
			//	alert('NQF level must be ' + nqf_range + ' for a ' + a_qualtypedesc[qtype]);
			//	obj.FLD_NQF_level.focus();
			//	obj.MOVETO.value = '';
			//	return false;
			//}
			var totcred = obj.FLD_saqa_credits.value;
			//if ((totcred == '') || (!isInteger(totcred)) || (totcred < a_mincreditrange[qtype]) || (totcred > a_maxcreditrange[qtype])) {
			if ((totcred == '') || (!isInteger(totcred)) || (totcred < a_mincreditrange[qtype]) || (totcred > 999)) {
				//alert('Total number of credits is required - digits only - and must be in the range '+a_mincreditrange[qtype]+' - '+a_maxcreditrange[qtype]+ ' for a ' + a_qualtypedesc[qtype]);
				alert('Total number of credits is required - digits only - and has a minimum of '+a_mincreditrange[qtype]+ ' credits for a ' + a_qualtypedesc[qtype]);
				obj.FLD_saqa_credits.focus();
				obj.MOVETO.value = '';
				return false;
			}
			var dur_ft = obj.FLD_full_time_duration.value;
			if ( (!isInteger(dur_ft)) || ((dur_ft != 0) && (dur_ft < a_yearsft[qtype]))) {
				alert('Minimum duration (years) for completion - full time has a minimum of '+a_yearsft[qtype]+ ' for a ' + a_qualtypedesc[qtype] + ' or 0 if this programme is not offered full-time.');
				obj.FLD_full_time_duration.focus();
				obj.MOVETO.value = '';
				return false;
			}
			var dur_pt = obj.FLD_part_time_duration.value;
			if ( (!isInteger(dur_pt)) || ((dur_pt != 0) && (dur_pt < a_yearspt[qtype]))) {
				alert('Minimum duration (years) for completion - part time has a minimum of '+a_yearspt[qtype]+ ' for a ' + a_qualtypedesc[qtype] + ' or 0 if this programme is not offered part-time.');
				obj.FLD_part_time_duration.focus();
				obj.MOVETO.value = '';
				return false;
			}
*/		}
		selectAll();
		return true;
		}
CHECKFORM;

$this->scriptTail .= "\n\n";

// 2017-01-16 Richard
// Populate CESM level 2 array so that CESM_level2_ref SELECT can dynamically display options based on the selection
// of CESM_code1 SELECT.
// Javascript function displayCESMLevel2 in ON CLICK event on CESM_code1 controls it.

$this->scriptTail .= "\n"."a_cesmlevel2 = new Array();\n";

	$sql = <<<QUAL1
	SELECT mid(SpecialisationCESM_qualifiers_id,1,3), SpecialisationCESM_qualifiers_id, Description
	FROM SpecialisationCESM_qualifiers
	WHERE generation = '2'
	AND level = '2'
	ORDER BY DOE_CESM_code
QUAL1;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

	$rs = mysqli_query($conn, $sql);
	$prev_CESM = "";
	while ($row = mysqli_fetch_array($rs)) {
		if ($prev_CESM != $row[0]) {
			$this->scriptTail .= "a_cesmlevel2[\"".$row[0]."\"] = new Array();\n";
			$prev_CESM = $row[0];			
		}
		$this->scriptTail .= 'a_cesmlevel2["'. $row[0] .'"]["'.$row[1].'"] = new Array("' . $row[2]  . '");' . "\n";
	}

// 2017-01-16 Richard
// Populate CESM level 3 array so that CESM_level3_ref SELECT can dynamically display options based on the selection
// of CESM_level2_ref SELECT.
// Javascript function displayCESMLevel3 in ON CLICK event on CESM_level2_ref controls it.

$this->scriptTail .= "a_cesmlevel3 = new Array();\n";
$this->scriptTail .= "a_cesmlevel3_defn = new Array();\n";
$this->scriptTail .= 'a_cesmlevel3_defn["0"] = new Array("nothing selected");'."\n";

	$sql = <<<QUAL1
	SELECT mid(SpecialisationCESM_qualifiers_id,1,5) AS id, SpecialisationCESM_qualifiers_id, Description, order_3_definition
	FROM SpecialisationCESM_qualifiers
	WHERE generation = '2'
	AND level = '3'
	ORDER BY DOE_CESM_code
QUAL1;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

	$rs = mysqli_query($conn,$sql);
	$prev_CESM = "";
	while ($row = mysqli_fetch_array($rs)) {
		if ($prev_CESM != $row[0]) {
			$this->scriptTail .= "a_cesmlevel3[\"".$row[0]."\"] = new Array();\n";
			$prev_CESM = $row[0];			
		}
		$this->scriptTail .= 'a_cesmlevel3["'. $row[0] .'"]["'.$row[1].'"] = new Array("' . $row[2]  . '");' . "\n";
		$this->scriptTail .= 'a_cesmlevel3_defn["'.$row[1].'"] = new Array("' . $row[3]  . '");' . "\n";

	}

$this->scriptTail .= "displayCESMLevel3Defn();\n";
	
// 2017-01-16 Richard
// Validation of total number of credits, minimum duration FT and PT depend on the type of qualification
// Valid ranges are stored in the qualification type lookup table.
$this->scriptTail .= "a_qualtypedesc = new Array();\n";
$this->scriptTail .= "a_nqfexit = new Array();\n";
$this->scriptTail .= "a_nqfmin = new Array();\n";
$this->scriptTail .= "a_nqfmax = new Array();\n";
$this->scriptTail .= "a_mincreditrange = new Array();\n";
$this->scriptTail .= "a_maxcreditrange = new Array();\n";
$this->scriptTail .= "a_yearsft = new Array();\n";
$this->scriptTail .= "a_yearspt = new Array();\n";

	$sql = <<<TYPE
	SELECT lkp_qualification_type_id, lkp_qualification_type_desc, min_nqf_exit_level, min_credit_range, max_credit_range, min_years_full_time, min_years_part_time, max_nqf_exit_level
	FROM lkp_qualification_type 
	ORDER BY lkp_qualification_type_id
TYPE;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	$rs = mysqli_query($conn,$sql);
	while ($row = mysqli_fetch_array($rs)) {
		if ($row["min_nqf_exit_level"] == $row["max_nqf_exit_level"]){
			$this->scriptTail .= 'a_nqfexit['. $row[0] .'] = Array(' . $row["min_nqf_exit_level"]  . ');' . "\n";
		} else {
			$this->scriptTail .= 'a_nqfexit['. $row[0] .'] = Array("' . $row["min_nqf_exit_level"] . " or "  . $row["max_nqf_exit_level"] . '");' . "\n";
		}
		$this->scriptTail .= 'a_qualtypedesc['. $row[0] .'] = "' . $row[1]  . '";' . "\n";
		$this->scriptTail .= 'a_nqfmin['. $row[0] .'] = ' . $row["min_nqf_exit_level"]   . ';' . "\n";
		$this->scriptTail .= 'a_nqfmax['. $row[0] .'] = ' . $row["max_nqf_exit_level"]   . ';' . "\n";
		$this->scriptTail .= 'a_mincreditrange['. $row[0] .'] = ' . $row[3]   . ';' . "\n";
		$this->scriptTail .= 'a_maxcreditrange['. $row[0] .'] = ' . $row[4]   . ';' . "\n";
		$this->scriptTail .= 'a_yearsft['. $row[0] .'] = ' . $row[5]  . ';' . "\n";
		$this->scriptTail .= 'a_yearspt['. $row[0] .'] = ' . $row[6]  . ';' .  "\n";
	}

?>
