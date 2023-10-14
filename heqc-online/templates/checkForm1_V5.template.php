<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm1_V5";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

//$this->setFormDBinfo("Institutions_application", "application_id");
//$this->formStatus = FLD_STATUS_DISABLED;



$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {

//alert(document.defaultFrm.MOVETO.value);

if (document.defaultFrm.MOVETO.value == '_Start Process_V5' )  {
		
		
   // if (document.defaultFrm.FLD_checklist_doc.value == "0" || document.defaultFrm.FLD_checklist_doc.value == ""){
    //            alert("Please upload a checklist document");
    //            document.defaultFrm.MOVETO.value = "";
    //            return false;
    //          }
      
    }
		return true;
	}
SCRIPTTAIL;

?>