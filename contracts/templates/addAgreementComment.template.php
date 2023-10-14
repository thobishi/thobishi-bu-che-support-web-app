<?php 

$this->title		= "Contract Register";
$this->bodyHeader	= "formHead";
$this->body			= "addAgreementComment";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Consultant > Contract > Add/Edit Comment</span>";

$this->formHidden["DELETE_RECORD"] = "";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if ((obj.MOVETO.value == 'next')) {
			if (!valDateRequired(obj.FLD_comment_date,'Please enter a value for comment date.')) {return false};
			if (!valTextRequired(obj.FLD_comment,'Please enter your comment.')) {return false};
		}
		return true;
	}

SCRIPTTAIL;

?>
