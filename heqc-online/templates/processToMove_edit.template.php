<?php
	$this->title		= "CHE Accreditation";
	$this->bodyHeader	= "formHead";
	$this->body			= "processToMove_edit";
	$this->bodyFooter	= "formFoot";
	$this->NavigationBar	= "<span class=pathdesc>Change user assigned to receive process</span>";

	$this->formOnSubmit = "return checkFrm(this);";

	$this->scriptTail = <<< SCRIPTTAIL
		function checkFrm(obj) {
			if (document.defaultFrm.MOVETO.value == 'next')  {
				if (!valSelectRequired(document.defaultFrm.user_ref,'Please select the user to move the processes to before continuing.'))	{return false};
			};
			return true;;
		};
SCRIPTTAIL;
?>