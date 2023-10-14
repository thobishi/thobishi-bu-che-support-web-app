<?php
	$this->title			= "CHE National Reviews";
	$this->bodyHeader		= "formHead";
	$this->body				= "ChangeUserPassword";
	$this->bodyFooter		= "formFoot";
	$this->NavigationBar	= array('Admin', 'Manage Users', 'Change password');
	$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail = <<<SCRIPTTAIL
	function checkFrm() {
		var regexp = new RegExp(/^(?=.*\d)[a-zA-Z0-9]{8,}$/);
		if (document.defaultFrm.MOVETO.value == 'next')  {
			var newpassVal = document.getElementById("newPassword").value;
			var newpassRetypedVal = document.getElementById("newPasswordRetype").value;
			if ((newpassVal != newpassRetypedVal)){
				alert("The passwords you entered must match!");
				return false;
			}
			if((!regexp.test(newpassVal)) || (!regexp.test(newpassRetypedVal)) ){
				alert("The password must be at least 8 characters long and must have at least 1 number!");
				return false;			
			}

		}	
		return true;
	}
SCRIPTTAIL;
?>
