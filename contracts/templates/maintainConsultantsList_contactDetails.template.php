<?php 

$this->title			= "Contract Register";
$this->bodyHeader		= "formHead";
$this->body				= "maintainConsultantsList_contactDetails";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Edit > Consultant Details</span>";

$this->formOnSubmit = "return checkFrm(this);";

// NB leave blank line before STAIL.  This newline is required or javascript will give a syntax error - MISSING }
$this->scriptTail = <<<STAIL
	function checkFrm(obj) {
		if (obj.MOVETO.value == '_startConsultantDisplayList') {
			if (!valNumberRequired(obj.FLD_type,'Please select the type of consultant.')) {return false};
			if (!valNumberRequired(obj.FLD_title,'Please select the Contact person title.')) {return false};
			if (!valTextRequired(obj.FLD_initials,'Please enter a value for Contact person initials.')) {return false};
			if (!valTextRequired(obj.FLD_name,'Please enter a value for Contact person name.')) {return false};
			if (!valTextRequired(obj.FLD_surname,'Please enter a value for Contact person surname.')) {return false};
			if (obj.FLD_type.value == 2){
				if (!valTextRequired(obj.FLD_company,'Please enter the service provider Organisation/institution name.')) {return false};
			}
		}
		return true;
	}

STAIL;

?>
