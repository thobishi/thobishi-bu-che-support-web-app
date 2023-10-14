<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accFormDeleteSite1_V5";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Remove this programme offering from this site</span>";

$this->formOnSubmit = "return checkConfirmation(document.defaultFrm)";

$this->scriptHead .= <<<VALCONFIRM
	function checkConfirmation(obj){

		if (obj.MOVETO.value == 'next') {

			

			if (obj.confirm_site_delete.checked) {
				return true;
			}else {
				alert('You must confirm that you want to delete all programme information for this site by checking the box.  Once deleted this information cannot be recovered.  Please be sure before you proceed.');
				document.defaultFrm.MOVETO.value = '';
				return false;
			}
		}
	return true;
	}
VALCONFIRM;
?>
