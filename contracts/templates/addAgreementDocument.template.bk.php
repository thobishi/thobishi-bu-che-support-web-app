<?php 

$this->title		= "Contract Register";
$this->bodyHeader	= "formHead";
$this->body			= "addAgreementDocument";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Consultant > Contract > Add/Edit document</span>";

$this->formHidden["DELETE_RECORD"] = "";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptHead .= <<<SCRIPTHEAD
	function checkNewOtherValue() {
		if (document.all.other_document.checked == false) {
			document.all.FLD_new_document_type.value = '';
		}else {
			document.all.FLD_document_type_ref.selectedIndex = 0;
		}
	}

SCRIPTHEAD;

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if ((obj.MOVETO.value == '_agreementsList') && (obj.VIEW.value != -1)) {
			if (!valTextRequired(obj.FLD_description,'Please enter a value for description.')) {return false};
			if (!valDateRequired(obj.FLD_start_date,'Please enter a value for start date.')) {return false};
		}
		return true;
	}

SCRIPTTAIL;

?>
