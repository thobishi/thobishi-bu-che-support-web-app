<?php

	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "documentBasketForm";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Apply for Re-accreditation > Add/Edit document</span>";

/*	$this->formHidden["DELETE_RECORD"] = "";

	$this->formOnSubmit = "return checkFrm(this);";

	$this->scriptTail = <<< SCRIPTTAIL

	function checkFrm(obj) {
		if ((obj.VIEW.value != -1)) {
			if (!valTextRequired(obj.FLD_reaccred_document_title,'Please enter a title for your document.')) {return false};
		}
		return true;
	}

SCRIPTTAIL;
*/

?>
