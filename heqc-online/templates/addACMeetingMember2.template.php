<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "addACMeetingMember2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>AC Meeting > Add member</span>";

//$this->createAction ("next", "Next", "submit", "", "ico_next.gif");

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL

	function checkFrm(obj) {
		if ((obj.VIEW.value != -1)) {
			if (!valSelectRequired(obj.FLD_ac_mem_title_ref,'Please enter the title of the user.')) {return false};
			if (!valTextRequired(obj.FLD_ac_mem_name,'Please enter the name of the user.')) {return false};
			if (!valTextRequired(obj.FLD_ac_mem_surname,'Please enter the surname of the user.')) {return false};
			if (!valTextRequired(obj.FLD_ac_mem_email,'Please enter the email address of the user.')) {return false};
			if (!valEmailFormat(obj.FLD_ac_mem_email,'Please enter a valid email address.')) {return false};
			if (!valTelNo(obj.FLD_ac_mem_tel,'Please enter a valid telephone number.')) {return false};
		}
		return true;
	}

SCRIPTTAIL;
?>