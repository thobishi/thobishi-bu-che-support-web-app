<?php

$this->title		= "CHE Project Register";
$this->bodyHeader	= "formHead";
$this->body			= "activityDetailForm2_add";
$this->bodyFooter	= "formFoot";

$this->formOnSubmit = "return checkFrm(this);";

$scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (obj.MOVETO.value == 'next') {
			if (!valNumberRequired(obj.FLD_directorate_ref,'Please select a value for programme.')) {return false};
			return true;
		}
		return true;
	}

SCRIPTTAIL;

$this->scriptTail = $scriptTail;

?>
