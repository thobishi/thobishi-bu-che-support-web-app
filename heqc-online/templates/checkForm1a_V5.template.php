<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm1a_V5";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

$this->setFormDBinfo("Institutions_application", "application_id");

$this->createInput("create_registry", "CHECKBOX");

// $this->scriptHead .= "function emailRegistry(){\n";
// $this->scriptHead .= "	document.defaultFrm.createRegistry.value='1';\n";
// $this->scriptHead .= "}\n";



$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL


	function checkFrm() {

        var flag = false;

        //alert(document.defaultFrm.MOVETO.value);

	//	if (document.defaultFrm.MOVETO.value == '_label_ChecklistApplication_V5' || document.defaultFrm.MOVETO.value == '_changeCheckListToCollegue_V5' || document.defaultFrm.MOVETO.value == '_label_ChecklistConfirm_V5')  {
           if ( document.defaultFrm.MOVETO.value == 'next')  {
         
          if (document.defaultFrm.FLD_checklist_doc.value == "0" || document.defaultFrm.FLD_checklist_doc.value == ""){
              alert("Please upload a checklist document");
              document.defaultFrm.MOVETO.value = "";
              return false;
          }
              
          
            if (document.defaultFrm.FLD_completed_checklisting.checked) {
                flag = true;
            }
            
            if (flag == false) {
              alert('Please  Check this box to indicate that you have completed checklisting this application.');
                document.defaultFrm.MOVETO.value  = '';
               return false;
            }
            
        }
       
        

		return true;
	}
SCRIPTTAIL;

?>

