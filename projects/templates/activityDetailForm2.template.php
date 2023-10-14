<?php

$this->title		= "CHE Project Register";
$this->bodyHeader	= "formHead";
$this->body			= "activityDetailForm2";
$this->bodyFooter	= "formFoot";

$this->formOnSubmit = "return checkFrm(this);";

$scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (obj.MOVETO.value == 'next'|| obj.MOVETO.value == '998') {
			if (!valTextRequired(obj.FLD_project_short_title,'Please enter a value for project short title.')) {return false};
			if (!valTextRequired(obj.FLD_project_full_title,'Please enter a value for project full title.')) {return false};
		    return true;
		}
		return true;
	}

SCRIPTTAIL;

$this->scriptTail = $scriptTail;

?>
