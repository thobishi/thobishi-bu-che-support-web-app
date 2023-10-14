<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "secrRecommForm";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Directorate Recommendation> Data capture form</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm);";

$this->scriptTail = <<< SCRIPTTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valCheckboxRequired(obj.FLD_recomm_complete_ind,'Note: You may save without indicating that you have completed the recommendation.  However, please check the box when completed to indicate that to the CHE.'))	{return true};
			if (obj.FLD_recomm_complete_ind.checked == true) {
				if (!valTextRequired(obj.FLD_applic_background,'Please complete the background for this application.'))	{return false;}
				if (!valTextRequired(obj.FLD_eval_report_summary,'Please complete the evaluator summary for this application.')) {return false;}
				if (!valSelectRequired(obj.FLD_recomm_decision_ref,'Please select the recommendation for this application.')) {return false;}
			}
	  	}
		return true;
	}
SCRIPTTAIL;
?>
