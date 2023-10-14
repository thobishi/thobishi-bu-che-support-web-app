<?php

	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "applicDocumentBasketForm";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Process application outcomes> Add/Edit document</span>";

	$this->formHidden["DELETE_RECORD"] = "";

	$this->formOnSubmit = "return checkFrm(this);";

	$this->scriptTail = <<< SCRIPTTAIL

	//if ((obj.VIEW.value != -1)) {
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next') {
			if (!valTextRequired(obj.FLD_document_title,'Please enter a title for your document.')) {return false};
			if (!valDocRequired(document.defaultFrm.FLD_application_doc,'Please upload the document before continuing.'))	{return false;}
		}
		return true;
	}
SCRIPTTAIL;
?>
