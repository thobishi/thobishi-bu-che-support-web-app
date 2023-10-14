<?php
$this->title			= "Contract Register";
$this->bodyHeader		= "formHead";
$this->body				= "commentsForm";
$this->bodyFooter		= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Admin > Ratings/Comments</span>";

$this->formOnSubmit = "return checkFrm(this);";

$scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (obj.MOVETO.value == 'next') {
			if (!valSelectRequired(obj.FLD_deliverydate_deadlines,'Please rate delivery date deadlines for this contract.')) {return false};
			if (!valSelectRequired(obj.FLD_meeting_requirements,'Please rate the meeting of requirements for this contract.')) {return false};
			if (!valSelectRequired(obj.FLD_quality_work,'Please rate the quality of work for this contract.')) {return false};
			if (!valTextRequired(obj.FLD_CHEcomment,'Please enter your comments.')) {return false};
			return true;
		}
		return true;
	}

SCRIPTTAIL;

$this->scriptTail = $scriptTail;
?>