<?php

$this->title			= "CHE Accreditation";
$this->bodyHeader		= "formHead";
$this->body				= "accForm1_v3";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution Information</span>";

$this->formHidden["FLD_user_ref"] = $this->currentUserID;
$this->formHidden["DELETE_RECORD"] = "";
$this->formOnSubmit = "return checkFrm(this);";

$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
$prov_type = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");
$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");
$cesm_generation = ($app_version >= 4) ? 'generation3_ind = 1' : 'generation = 2';

$this->scriptHead .= "\n\n";
$this->scriptHead .= "function isInteger (str) {\n";
$this->scriptHead .= "	var regexp = /(^-?\d\d*$)/;"."\n";
$this->scriptHead .= "	return regexp.test(str);\n";
$this->scriptHead .= "}\n\n";

$this->scriptTail .= <<<CHECKFORM
	function checkFrm(obj) {
		var flag = false;
		if (obj.MOVETO.value == 'next' || obj.MOVETO.value == 'stay' || obj.MOVETO.value == '_labelSiteList') {
			for (j=0; j<obj.FLD_prog_type.length; j++) {
				if ((obj.FLD_prog_type[j].checked)) {
					flag = true;
				}
			}
			if (!(flag)) {
				alert('Please select a programme type.');
				obj.MOVETO.value = '';
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
			if (obj.FLD_NQF_ref.options[obj.FLD_NQF_ref.selectedIndex].value == 0) {
				alert('Please select an NQF level.');
				obj.FLD_NQF_ref.focus();
				obj.MOVETO.value = '';
				return false;
			}
//			if (parseInt(obj.FLD_NQF_ref.options[obj.FLD_NQF_ref.selectedIndex].value) + 4 != a_nqfexit[qtype]) {
			var nqf = parseInt(obj.FLD_NQF_ref.options[obj.FLD_NQF_ref.selectedIndex].value) + 4;
			if ((nqf < a_nqfmin[qtype]) || (nqf > a_nqfmax[qtype])) {
				var nqf_range = a_nqfmin[qtype];
				if (a_nqfmin[qtype] != a_nqfmax[qtype]){
					nqf_range = a_nqfmin[qtype] + ' to ' + a_nqfmax[qtype];
				}
				alert('NQF level must be ' + nqf_range + ' for a ' + a_qualtypedesc[qtype]);
				obj.FLD_NQF_ref.focus();
				obj.MOVETO.value = '';
				return false;
			}
			var totcred = obj.FLD_num_credits.value;
			//if ((totcred == '') || (!isInteger(totcred)) || (totcred < a_mincreditrange[qtype]) || (totcred > a_maxcreditrange[qtype])) {
			if ((totcred == '') || (!isInteger(totcred)) || (totcred < a_mincreditrange[qtype]) || (totcred > 999)) {
				//alert('Total number of credits is required - digits only - and must be in the range '+a_mincreditrange[qtype]+' - '+a_maxcreditrange[qtype]+ ' for a ' + a_qualtypedesc[qtype]);
				alert('Total number of credits is required - digits only - and has a minimum of '+a_mincreditrange[qtype]+ ' credits for a ' + a_qualtypedesc[qtype]);
				obj.FLD_num_credits.focus();
				obj.MOVETO.value = '';
				return false;
			}
			var dur_ft = obj.FLD_full_time.value;
			if ( (!isInteger(dur_ft)) || ((dur_ft != 0) && (dur_ft < a_yearsft[qtype]))) {
				alert('Minimum duration (years) for completion - full time has a minimum of '+a_yearsft[qtype]+ ' for a ' + a_qualtypedesc[qtype] + ' or 0 if this programme is not offered full-time.');
				obj.FLD_full_time.focus();
				obj.MOVETO.value = '';
				return false;
			}
			var dur_pt = obj.FLD_part_time.value;
			if ( (!isInteger(dur_pt)) || ((dur_pt != 0) && (dur_pt < a_yearspt[qtype]))) {
				alert('Minimum duration (years) for completion - part time has a minimum of '+a_yearspt[qtype]+ ' for a ' + a_qualtypedesc[qtype] + ' or 0 if this programme is not offered part-time.');
				obj.FLD_part_time.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_senate_approved.value != 2) {
				alert('The programme must be approved by an institutional structure.');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if ((document.defaultFrm.FLD_senate_approved_date.value == '') || (document.defaultFrm.FLD_senate_approved_date.value == '1970-01-01')) {
				alert('Please enter the date of institutional structure approval');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
			if ($prov_type == 2) {
				if ((obj.FLD_is_part_pqm.options[obj.FLD_is_part_pqm.selectedIndex].value == 1) && (obj.FLD_doe_pqm_lkp.options[obj.FLD_doe_pqm_lkp.selectedIndex].value == 1)) {
					alert('You may not apply for accreditation without PQM approval.');
					return false;
				}
				if (obj.FLD_is_part_pqm.options[obj.FLD_is_part_pqm.selectedIndex].value == 0) {
					alert('Please select whether the programme forms part of your institution’s approved PQM.');
					obj.FLD_is_part_pqm.focus();
					obj.MOVETO.value = '';
					return false;
				}
				if ((obj.FLD_is_part_pqm.options[obj.FLD_is_part_pqm.selectedIndex].value == 2) && (obj.FLD_doe_pqm_doc.value == '0')) {
					alert('Please upload the DoE PQM approval document.');
					return false;
				}
				if (obj.FLD_is_part_pqm.options[obj.FLD_is_part_pqm.selectedIndex].value == 2) {
					if (obj.FLD_doe_pqm_date.value == '1970-01-01') {
						alert('Please enter the date when the PQM application was made to DoE.');
						obj.FLD_doe_pqm_date.focus();
						return false;
					}
				}
				if (obj.FLD_is_part_pqm.options[obj.FLD_is_part_pqm.selectedIndex].value == 1) {
					if (obj.FLD_doe_pqm_lkp.options[obj.FLD_doe_pqm_lkp.selectedIndex].value == 0) {
						alert('Please select whether you have applied for PQM approval with the DoE for this programme.');
						obj.FLD_doe_pqm_lkp.focus();
						obj.MOVETO.value = '';
						return false;
					}
				}
			}
			
			if ((document.defaultFrm.FLD_prog_start_date.value == '') || (document.defaultFrm.FLD_prog_start_date.value == '1970-01-01')) {
				alert('Please enter the date by which you plan to start offering the programme.');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
		}
		return true;
	}
CHECKFORM;

// 2009-12-30 Robin
// Populate CESM level 2 array so that CESM_level2_ref SELECT can dynamically display options based on the selection
// of CESM_code1 SELECT.
// Javascript function displayCESMLevel2 in ON CLICK event on CESM_code1 controls it.
// 2016-03-24 Robin
// Set CESM generation based on app_version.  CESM_generation 3 is used for app_version 4, 2 for app_version 3.
$this->scriptTail .= "\n"."a_cesmlevel2 = new Array();\n";

	$sql = <<<QUAL1
	SELECT mid(SpecialisationCESM_qualifiers_id,1,3), SpecialisationCESM_qualifiers_id, Description
	FROM SpecialisationCESM_qualifiers
	WHERE {$cesm_generation}
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

// 2010-01-04 Robin 
// Populate CESM level 3 array so that CESM_level3_ref SELECT can dynamically display options based on the selection
// of CESM_level2_ref SELECT.
// Javascript function displayCESMLevel3 in ON CLICK event on CESM_level2_ref controls it.

$this->scriptTail .= "a_cesmlevel3 = new Array();\n";
$this->scriptTail .= "a_cesmlevel3_defn = new Array();\n";
$this->scriptTail .= 'a_cesmlevel3_defn["0"] = new Array("nothing selected");'."\n";

	$sql = <<<QUAL1
	SELECT mid(SpecialisationCESM_qualifiers_id,1,5) AS id, SpecialisationCESM_qualifiers_id, Description, order_3_definition
	FROM SpecialisationCESM_qualifiers
	WHERE {$cesm_generation}
	AND level = '3'
	ORDER BY DOE_CESM_code
QUAL1;

	$rs = mysqli_query($conn, $sql);
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
	
// 2010-01-04 Robin 
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

	$rs = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_array($rs)) {
		if ($row["min_nqf_exit_level"] == $row["max_nqf_exit_level"]){
			$this->scriptTail .= 'a_nqfexit['. $row[0] .'] = Array(' . $row["min_nqf_exit_level"]  . ');' . "\n";
		} else {
			$this->scriptTail .= 'a_nqfexit['. $row[0] .'] = Array("' . $row["min_nqf_exit_level"] . " or "  . $row["max_nqf_exit_level"] . '");' . "\n";
		}
		$this->scriptTail .= 'a_qualtypedesc['. $row[0] .'] = "' . $row[1]  . '";' . "\n";
		$this->scriptTail .= 'a_nqfmin['. $row[0] .'] = ' . $row["min_nqf_exit_level"]  . "\n";
		$this->scriptTail .= 'a_nqfmax['. $row[0] .'] = ' . $row["max_nqf_exit_level"]  . "\n";
		$this->scriptTail .= 'a_mincreditrange['. $row[0] .'] = ' . $row[3]  . "\n";
		$this->scriptTail .= 'a_maxcreditrange['. $row[0] .'] = ' . $row[4]  . "\n";
		$this->scriptTail .= 'a_yearsft['. $row[0] .'] = ' . $row[5] . "\n";
		$this->scriptTail .= 'a_yearspt['. $row[0] .'] = ' . $row[6] .  "\n";
	}

?>
