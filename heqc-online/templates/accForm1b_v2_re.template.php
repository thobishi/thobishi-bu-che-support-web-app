<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accForm1b_v2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution Information</span>";

//$this->formOnSubmit = " enableNOS();selectAll();return checkNOS();";

$this->formHidden["FLD_user_ref"] = $this->currentUserID;
$this->formHidden["FLD_application_type"] = "reaccred";

//$this->formOnSubmit = "return selectAll();";
$this->formOnSubmit = "return checkFrm(this);";

$this->scriptHead .= "function selectAll() {\n";
$this->scriptHead .= "	sLength = document.defaultFrm.elements['FLDS_resultsSelect[]'].length;\n";
$this->scriptHead .= "	for (i=0; i<sLength; i++) {\n";
$this->scriptHead .= "		document.defaultFrm.elements['FLDS_resultsSelect[]'].options[i].selected = true;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n";

$this->scriptHead .= "\n\n";
$this->scriptHead .= "function addSites() {\n";
$this->scriptHead .= "	var obj = document.defaultFrm.elements['sites_select'];\n";
$this->scriptHead .= "	var obj2 = document.defaultFrm.elements['FLDS_resultsSelect[]'];\n";
$this->scriptHead .= "	sLen = obj.length;\n";
$this->scriptHead .= "	rLen = obj2.length;\n";
$this->scriptHead .= "	var count = 0;\n";
$this->scriptHead .= "	var count2 = 0;\n";
$this->scriptHead .= "	for ( i=0; i<sLen; i++){\n";
$this->scriptHead .= "		count += (obj.options[i].selected == true )?(1):(0);\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	for ( i=0; i<rLen; i++){\n";
$this->scriptHead .= "		count2 += 1;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	if ((count+count2) < 31) {";
$this->scriptHead .= "		for ( i=0; i<sLen; i++){\n";
$this->scriptHead .= "			if (obj.options[i].selected == true ) {\n";
$this->scriptHead .= "				obj2Len = obj2.length;\n";
$this->scriptHead .= "				obj2.options[obj2Len]= new Option(obj.options[i].text, obj.options[i].value);\n";
$this->scriptHead .= "			}\n";
$this->scriptHead .= "		noOfSites();\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "		for ( i=(sLen-1); i>=0; i--) {\n";
$this->scriptHead .= "			if (obj.options[i].selected == true ) {\n";
$this->scriptHead .= "					obj.options[i] = null;\n";
$this->scriptHead .= "			}\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}else{\n";
$this->scriptHead .= "		alert('You cannot select more than 30 sites.');\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";
$this->scriptHead .= "function removeSites() {\n";
$this->scriptHead .= "	var obj = document.defaultFrm.elements['sites_select'];\n";
$this->scriptHead .= "	var obj2 = document.defaultFrm.elements['FLDS_resultsSelect[]'];\n";
$this->scriptHead .= "	sLen = obj2.length;\n";
$this->scriptHead .= "	for ( i=0; i<sLen ; i++){\n";
$this->scriptHead .= "		if (obj2.options[i].selected == true ) {\n";
$this->scriptHead .= "			objLen = obj.length;\n";
$this->scriptHead .= "			obj.options[objLen]= new Option(obj2.options[i].text, obj2.options[i].value);\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	for ( i = (sLen -1); i>=0; i--){\n";
$this->scriptHead .= "		if (obj2.options[i].selected == true ) {\n";
$this->scriptHead .= "			obj2.options[i] = null;\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	noOfSites();\n";
$this->scriptHead .= "}\n";

$this->scriptHead .= "\n\n";
$this->scriptHead .= "function noOfSites() {\n";
$this->scriptHead .= "	var obj = document.defaultFrm.elements['FLDS_resultsSelect[]'];\n\n";
$this->scriptHead .= "	document.defaultFrm.FLD_noOfSites.value = obj.length;\n\n";
$this->scriptHead .= "}\n";

$this->scriptHead .= "function enableNOS() {\n";
$this->scriptHead .= "	document.defaultFrm.FLD_noOfSites.disabled = false;\n\n";
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
$this->scriptHead .= "function checkNOS() {\n";
$this->scriptHead .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptHead .= "		if (document.defaultFrm.FLD_noOfSites.value == '0') {\n";
$this->scriptHead .= "			alert('Please select a site before continuing');\n";
$this->scriptHead .= "			document.defaultFrm.FLD_noOfSites.disabled = true;\n";
$this->scriptHead .= "			document.defaultFrm.MOVETO.value = '';\n";
$this->scriptHead .= "			return false;\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n";


$this->scriptTail .= "\n\n";
$this->scriptTail .= "noOfSites();\n";
$this->scriptTail .= "\n\n";
$this->scriptTail .= "for (j=0; j<document.defaultFrm.elements['sites_select'].length; j++) {\n";
$this->scriptTail .= "	checkSites_select(document.defaultFrm.elements['sites_select']);\n";
$this->scriptTail .= "}\n";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkFrm(obj) {\n";
$this->scriptTail .= "	var flag = false;\n";
$this->scriptTail .= "	var count = 0;\n";
$this->scriptTail .= "	if (obj.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		if (obj.FLD_program_name.value == '') {\n";
$this->scriptTail .= "			alert('Please enter the programme name.');\n";
$this->scriptTail .= "			obj.FLD_program_name.focus();\n";
$this->scriptTail .= "			obj.MOVETO.value = '';\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		if (obj.FLD_mode_delivery.value == '0') {\n";
$this->scriptTail .= "			alert('Please enter the programme\'s mode of delivery.');\n";
$this->scriptTail .= "			obj.FLD_mode_delivery.focus();\n";
$this->scriptTail .= "			obj.MOVETO.value = '';\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		if ((document.defaultFrm.FLD_mode_delivery.value == '5') && (document.defaultFrm.FLD_mode_delivery_specify_char.value == '')) {\n";
$this->scriptTail .= "			alert('You have selected \"Other\" for mode of delivery. Please specify the mode of delivery.');\n";
$this->scriptTail .= "			obj.FLD_mode_delivery_specify_char.focus();\n";
$this->scriptTail .= "			document.defaultFrm.MOVETO.value = '';\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "		if (document.defaultFrm.FLD_noOfSites.value == '0') {\n";
$this->scriptTail .= "			alert('Please select a site before continuing');\n";
$this->scriptTail .= "			document.defaultFrm.FLD_noOfSites.disabled = true;\n";
$this->scriptTail .= "			document.defaultFrm.MOVETO.value = '';\n";
$this->scriptTail .= "			return false;\n";
$this->scriptTail .= "		}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	selectAll();\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";

?>
