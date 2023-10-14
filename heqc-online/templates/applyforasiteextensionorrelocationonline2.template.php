<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body			= "applyforasiteextensionorrelocationonline2";
$this->bodyFooter	= "formFoot";
//$this->NavigationBar	= "<span class=pathdesc>Outcome> Result of outcome</span>";

//$this->formOnSubmit = "return checkFrm();";

//$this->scriptTail = <<<SCRIPTTAIL

//$provider = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "priv_publ");

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
//alert(document.defaultFrm.MOVETO.value);

		if (document.defaultFrm.MOVETO.value == 'next')  {

//if (!valDocRequired(document.defaultFrm.FLD_deferral_doc,'Please upload the deferral before continuing.'))
			
			if (!valDocRequired(document.defaultFrm.FLD_siteapp_doc,'Please upload the  new site or extension Application document before submitting the application.'))		
				{return false;}
			
	  	}
		return true;
	}
SCRIPTTAIL;

?>




