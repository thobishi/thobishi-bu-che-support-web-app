<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm1d_V5";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

//$this->formOnSubmit = " return checkTable(this);";
$this->formHidden["DELETE_RECORD"] = "";

$this->formOnSubmit = "return checkFrm();";

$this->scriptTail = <<<SCRIPTTAIL

document.getElementById('action_Returntheapplication').style.display = "none";
	document.getElementById('action_Returntheapplication_Img').style.display = "none";

	document.getElementById('action_next').style.display = "none"; 
  document.getElementById('action_next_Img').style.display = "none"; 

	document.getElementById('action_Canceltheapplication').style.display = "none";
  document.getElementById('action_Canceltheapplication_Img').style.display = "none";

	function checkFrm() {

  

        var flag = false;

       

	//	if (document.defaultFrm.MOVETO.value == '_label_ChecklistApplication_V5' || document.defaultFrm.MOVETO.value == '_changeCheckListToCollegue_V5' || document.defaultFrm.MOVETO.value == '_label_ChecklistConfirm_V5')  {
           if ( document.defaultFrm.MOVETO.value == 'next' || document.defaultFrm.MOVETO.value == '_goCommentOnApplicationforReturnInstitution_V5' || document.defaultFrm.MOVETO.value == '_goCommentOnApplicationforReturnScreening_V5' || document.defaultFrm.MOVETO.value == '_goCommentOnApplicationforCancel_V5')  {
         
          if (document.defaultFrm.FLD_checklist_final_doc.value == "0" || document.defaultFrm.FLD_checklist_final_doc.value == ""){
              alert("Please upload a checklist Final report ");
              document.defaultFrm.MOVETO.value = "";
              return false;
          }
              
          
            if (document.defaultFrm.FLD_screening_approval.checked) {
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