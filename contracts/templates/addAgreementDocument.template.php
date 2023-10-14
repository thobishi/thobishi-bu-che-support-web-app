<?php 

$this->title		= "Contract Register";
$this->bodyHeader	= "formHead";
$this->body			= "addAgreementDocument";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Consultant > Contract > Add/Edit document</span>";

$this->formHidden["DELETE_RECORD"] = "";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if ((obj.MOVETO.value == '_agreementsList') && (obj.VIEW.value != -1)) {
			if (!valTextRequired(obj.FLD_document_title,'Please enter a title for your document.')) {return false};
			if (!valDateRequired(obj.FLD_document_type_ref,'Please enter the type of document. Request your administrator to add another type if it is not in the list.')) {return false};
		}
		return true;
	}

SCRIPTTAIL;

?>
