<?php

	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "reAccProcessDocumentBasketForm";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Process re-accreditation application > Add/Edit document</span>";

	$this->formHidden["DELETE_RECORD"] = "";

	$this->formOnSubmit = "return checkFrm(this);";

	$this->scriptTail = <<< SCRIPTTAIL

	//if ((obj.VIEW.value != -1)) {
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (!valTextRequired(obj.FLD_reaccred_document_title,'Please enter a title for your document.')) {return false};
			if (!valDocRequired(document.defaultFrm.FLD_reaccred_document_ref,'Please upload the document before continuing.'))	{return false;}
		}
		return true;
	}

SCRIPTTAIL;
?>
