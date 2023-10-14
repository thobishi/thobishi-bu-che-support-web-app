<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accForm3-2_v4";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution Information</span>";

$this->formHidden["FLD_user_ref"] = $this->currentUserID;
$this->formHidden["DELETE_RECORD"] = "";
$this->formOnSubmit = "return checkFrm(this);";

$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
$prov_type = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");
$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");
$cesm_generation = ($app_version >= 4) ? 'generation3_ind = 1' : 'generation = 2';
$cesm_generation2 = ($app_version >= 4) ? 'generation3_ind = 1' : 'generation = 2';


array_push($this->scriptFile, "js/TreeMenu.js");



$this->scriptHead .= "\n\n";
$this->scriptHead .= "function selectAll() {\n";
$this->scriptHead .= "	sLength = document.defaultFrm.elements['FLDS_Specialisations[]'].length;\n";
$this->scriptHead .= "	for (i=0; i<sLength; i++) {\n";
$this->scriptHead .= "		document.defaultFrm.elements['FLDS_Specialisations[]'].options[i].selected = true;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n";




$this->scriptHead .= "\n\n";
$this->scriptHead .= "function isInteger (str) {\n";
$this->scriptHead .= "	var regexp = /(^-?\d\d*$)/;"."\n";
$this->scriptHead .= "	return regexp.test(str);\n";
$this->scriptHead .= "}\n\n";

$this->scriptTail .= <<<CHECKFORM
	function checkFrm(obj) {
		var flag = false;
		
		if (obj.MOVETO.value == 'next') {
			
			if (obj.FLD_1_9_yn.options[obj.FLD_1_9_yn.selectedIndex].value == 0) {
				alert('Please select Yes/No if this is a professional qualification with oversight by a statutory professional body');
				obj.FLD_1_9_yn.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_MRTEQ_yn.options[obj.FLD_MRTEQ_yn.selectedIndex].value == 0) {
				alert('Please select Yes/No if this an education programme/ qualification.');
				obj.FLD_MRTEQ_yn.focus();
				obj.MOVETO.value = '';
				return false;
			}
			
			if (obj.FLD_qualification_type_ref.options[obj.FLD_qualification_type_ref.selectedIndex].value == 0) {
				alert('Please select a qualification type.');
				obj.FLD_qualification_type_ref.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_NQF_ref.options[obj.FLD_NQF_ref.selectedIndex].value == 0) {
				alert('Please select a NQF Level.');
				obj.FLD_NQF_ref.focus();
				obj.MOVETO.value = '';
				return false;
			}
			
			
			if (document.defaultFrm.FLD_min_credits_heqsf.value == 0 ) {
				alert('Please enter the number of credits linked to the qualification type as prescribed in the HEQSF');
				document.defaultFrm.MOVETO.value = '';
				obj.FLD_min_credits_heqsf.focus();
				return false;
			}
			//Specialisations
			
			if (document.defaultFrm.elements['FLDS_Specialisations[]'].length==0) {
			alert('Please select a Specialisation before continuing');
			document.defaultFrm.FLD_Specialisations.disabled = true;
			document.defaultFrm.MOVETO.value = '';
			return false;
		}

           // if (document.defaultFrm.FLD_min_credits_pb.value == 0 ) {
			//	alert('Please enter the number of total minimum credits as per Professional Body requirements');
			//	document.defaultFrm.MOVETO.value = '';
			//	obj.FLD_min_credits_pb.focus();
			//	return false;
			//}

            if (document.defaultFrm.FLD_num_credits.value == 0 ) {
				alert('Please enter the total number of credits');
				document.defaultFrm.MOVETO.value = '';
				obj.FLD_num_credits.focus();
				return false;
			}

			if (document.defaultFrm.FLD_full_time.value == 0 ) {
				alert('Please enter the minimum duration (years) for completion full time');
				document.defaultFrm.MOVETO.value = '';
				obj.FLD_full_time.focus();
				return false;
			}
			if (document.defaultFrm.FLD_part_time.value == 0 ) {
				alert('Please enter the minimum duration (years) for completion part time');
				document.defaultFrm.MOVETO.value = '';
				obj.FLD_part_time.focus();
				return false;
			}

          //  if (document.defaultFrm.FLD_min_credits_heqsf.value > document.defaultFrm.FLD_min_credits_pb.value ) {
		//		if (document.defaultFrm.FLD_excess_credit_motivation.value != "" ) {
		//			return true;
		//		}
		//		else{
		//			alert('Please provide a motivation');
		//			document.defaultFrm.MOVETO.value = '';
		//			obj.FLD_excess_credit_motivation.focus();
		//			return false;
		//		}
		//	}
		
		//start
			if (obj.FLD_field_ID.options[obj.FLD_field_ID.selectedIndex].value == 0) {
				alert('Please select a  Field.');
				obj.FLD_field_ID.focus();
				obj.MOVETO.value = '';
				return false;
			}
			if (obj.FLD_subfield_ID.options[obj.FLD_subfield_ID.selectedIndex].value == 0) {
				alert('Please select a  Sub Field.');
				obj.FLD_subfield_ID.focus();
				obj.MOVETO.value = '';
				return false;
			}



		




			//end
		//	if (obj.FLD_CESM_code1.options[obj.FLD_CESM_code1.selectedIndex].value == 0) {
		//		alert('Please select a  CESM.');
		//		obj.FLD_CESM_code1.focus();
		//		obj.MOVETO.value = '';
		//		return false;
		//	}

		//	 if (obj.FLD_CESM_level2_ref.options[obj.FLD_CESM_level2_ref.selectedIndex].value == 0) {
		//		alert('Please select a  First Qualifier.');
		//		obj.FLD_CESM_level2_ref.focus();
		//		obj.MOVETO.value = '';
		//		return false;
		//	}
		//	if (obj.FLD_CESM_level3_ref.options[obj.FLD_CESM_level3_ref.selectedIndex].value == 0) {
		//		alert('Please select a Second Qualifier.');
		//		obj.FLD_CESM_level3_ref.focus();
		//		obj.MOVETO.value = '';
		//		return false;
		//	} 

		}
		selectAll();
		return true;
	}
CHECKFORM;
// vukile field and sub field dependency
$this->scriptTail .= "\n"."a_fieldlevel2 = new Array();\n";

	$sql = <<<QUAL1
	SELECT field_ID, subfield_ID, subfield_description
	FROM lkp_saqa_field
	WHERE 1
	ORDER BY field_ID,subfield_ID
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
			$this->scriptTail .= "a_fieldlevel2[\"".$row[0]."\"] = new Array();\n";
			$prev_CESM = $row[0];			
		}
		$this->scriptTail .= 'a_fieldlevel2["'. $row[0] .'"]["'.$row[1].'"] = new Array("' . $row[2]  . '");' . "\n";
	}

//end of vukile trying field and sub field



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
