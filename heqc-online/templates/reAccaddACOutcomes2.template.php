<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "reAccaddACOutcomes2";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Re-accreditation > Add-Edit outcomes</span>";

	$this->formHidden["DELETE_RECORD"] = "";

	$this->formOnSubmit = "return checkFrm(this);";

	$this->scriptTail = <<< SCRIPTTAIL

	function checkFrm(obj) {
		if ((obj.VIEW.value != -1)) {
			if (!valDateRequired(obj.FLD_reacc_acmeeting_date,'Please enter the AC meeting date.')) {return false};
			if (!valSelectRequired(obj.FLD_reacc_decision_ref,'Please enter the outcome.')) {return false};
			if (obj.FLD_reacc_decision_ref.selectedIndex == 2){
				if (!valTextRequired(obj.FLD_reacc_conditions,'Please enter the conditions.')) {return false};
				if (!valDateRequired(obj.FLD_reacc_conditiondue_date,'Please enter the due date for conditions to be met.')) {return false};
			}
			if (obj.FLD_reacc_decision_ref.selectedIndex == 4){
				if (!valTextRequired(obj.FLD_reacc_deferral_comment,'Please enter the deferral notes.')) {return false};
				if (!valDateRequired(obj.FLD_reacc_deferdue_date,'Please enter the due date for the deferral.')) {return false};
			}
		}
		return true;
	}

SCRIPTTAIL;

?>
