<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "candidacyProcessApplic";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Accreditation</span>";


$this->scriptTail .= <<<SCRIPTAIL
	function clearFields(obj) {
		clearForm(obj.search_institution);
		clearForm(obj.search_HEQCref);
		clearForm(obj.search_progname);
		clearForm(obj.subm_start_date);
		clearForm(obj.subm_end_date);
		clearForm(obj.invoice_start_date);
		clearForm(obj.invoice_end_date);
		clearForm(obj.evalappoint_start_date);
		clearForm(obj.evalappoint_end_date);
		clearForm(obj.recomm_due_start_date);
		clearForm(obj.recomm_due_end_date);
		clearForm(obj.acmeeting_start_date);
		clearForm(obj.acmeeting_end_date);
		clearForm(obj.heqcmeeting_start_date);
		clearForm(obj.heqcmeeting_end_date);
		clearForm(obj.search_heqc_decision);
		clearForm(obj.outcome_due_start_date);
		clearForm(obj.outcome_due_end_date);
		clearForm(obj.search_outcome);
		clearForm(obj.no_outcome);
		clearForm(obj.search_status);
		clearForm(obj.mode_delivery);
		return true;
	}
SCRIPTAIL;

?>
