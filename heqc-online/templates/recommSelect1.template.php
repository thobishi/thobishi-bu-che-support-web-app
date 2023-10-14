<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "recommSelect1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Directorate Recommendation> Search for user to appoint</span>";

$this->formOnSubmit = "return checkFrm(document.defaultFrm.recomm_user_id);";

$this->scriptTail .= <<<SCRIPTAIL
	function checkFrm(obj) {
		if (document.defaultFrm.MOVETO.value == 'next')  {
			if (!valRadioRequired(obj,'Please assign a user to do the directorate recommendation before continuing.')) {return false};
  		}
		return true;
	}
SCRIPTAIL;
?>
