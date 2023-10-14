<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accForm1b_v2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Institution Information</span>";

//$this->formOnSubmit = " enableNOS();selectAll();return checkNOS();";

$this->formHidden["FLD_user_ref"] = $this->currentUserID;

//$this->formOnSubmit = "return selectAll();";
$this->formOnSubmit = "return checkFrm(this);";

$this->scriptHead .= <<<TXT1

function selectAll() {
	var rsSEL = document.defaultFrm.elements['FLDS_resultsSelect[]'];
	sLength = rsSEL.options.length;
	for (i=0; i<sLength; i++) {
		rsSEL.options[i].selected=true;
	}
	return true;
}

function addSites() {
	var obj = document.defaultFrm.elements['sites_select'];
	var obj2 = document.defaultFrm.elements['FLDS_resultsSelect[]'];
	sLen = obj.length;
	rLen = obj2.length;
	var count = 0;
	var count2 = 0;
	for ( i=0; i<sLen; i++){
		count += (obj.options[i].selected == true )?(1):(0);
	}
	for ( i=0; i<rLen; i++){
		count2 += 1;
	}
	if ((count+count2) < 31) {
		for ( i=0; i<sLen; i++){
			if (obj.options[i].selected == true ) {
				obj2Len = obj2.length;
				obj2.options[obj2Len]= new Option(obj.options[i].text, obj.options[i].value);
			}
		noOfSites();
		}
		for ( i=(sLen-1); i>=0; i--) {
			if (obj.options[i].selected == true ) {
					obj.options[i] = null;
			}
		}
	}else{
		alert('You cannot select more than 30 sites.');
	}
}

function removeSites() {
	var obj = document.defaultFrm.elements['sites_select'];
	var obj2 = document.defaultFrm.elements['FLDS_resultsSelect[]'];
	sLen = obj2.length;
	for ( i=0; i<sLen ; i++){
		if (obj2.options[i].selected == true ) {
			objLen = obj.length;
			obj.options[objLen]= new Option(obj2.options[i].text, obj2.options[i].value);
		}
	}
	for ( i = (sLen -1); i>=0; i--){
		if (obj2.options[i].selected == true ) {
			obj2.options[i] = null;
		}
	}
	noOfSites();
}

function noOfSites() {
	var obj = document.defaultFrm.elements['FLDS_resultsSelect[]'];
	document.defaultFrm.FLD_noOfSites.value = obj.length;
}

function enableNOS() {
	document.defaultFrm.FLD_noOfSites.disabled = false;
}

function checkSites_select(obj) {
	var resultSelect = document.defaultFrm.elements['FLDS_resultsSelect[]'];
	if (resultSelect.length > 0) {
		for (i=0; i<resultSelect.length; i++) {
			if (obj[j].text == resultSelect.options[i].text) {
				obj[j] = null;
			}
		}
	}
}

function checkNOS() {
	if (document.defaultFrm.MOVETO.value == 'next') {
		if (document.defaultFrm.FLD_noOfSites.value == '0') {
			alert('Please select a site before continuing');
			document.defaultFrm.FLD_noOfSites.disabled = true;
			document.defaultFrm.MOVETO.value = '';
			return false;
		}
	}
	return true;
}

function checkFrm(obj) {
	var flag = false;
	var count = 0;
	if (obj.MOVETO.value == 'next') {
		if (obj.FLD_program_name.value == '') {
			alert('Please enter the programme name.');
			obj.FLD_program_name.focus();
			obj.MOVETO.value = '';
			return false;
		}
		if (obj.FLD_mode_delivery.value == '0') {
			alert('Please enter the programme\'s mode of delivery.');
			obj.FLD_mode_delivery.focus();
			obj.MOVETO.value = '';
			return false;
		}
		if ((document.defaultFrm.FLD_mode_delivery.value == '5') && (document.defaultFrm.FLD_mode_delivery_specify_char.value == '')) {
			alert('You have selected "Other" for mode of delivery. Please specify the mode of delivery.');
			obj.FLD_mode_delivery_specify_char.focus();
			document.defaultFrm.MOVETO.value = '';
			return false;
		}
		if (document.defaultFrm.FLD_noOfSites.value == '0') {
			alert('Please select a site before continuing');
			document.defaultFrm.FLD_noOfSites.disabled = true;
			document.defaultFrm.MOVETO.value = '';
			return false;
		}
	}
	selectAll();
	return true;
}

TXT1;

$this->scriptTail .= <<<TXT2

noOfSites();

for (j=0; j<document.defaultFrm.elements['sites_select'].length; j++) {
	checkSites_select(document.defaultFrm.elements['sites_select']);
}


TXT2;

?>
