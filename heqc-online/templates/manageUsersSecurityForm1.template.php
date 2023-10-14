<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "manageUsersSecurityForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Admin > User Management</span>";





//$return_value = checkifEmailExists($_POST['email']);



$this->formOnSubmit = "return checkFrm(this)";



$this->scriptTail = <<< SCRIPTTAIL


	function checkFrm(obj) {



		var contactCellNo = document.defaultFrm.FLD_contact_cell_nr;

		var contactNo = document.defaultFrm.FLD_contact_nr;
		if (document.defaultFrm.MOVETO.value == 'next') {


			if (!valSelectRequired(obj.FLD_title_ref,'Please enter the title of the user.')) {return false};
			if (!valTextRequired(obj.FLD_name,'Please enter the name of the user.')) {return false};
			if (!valTextRequired(obj.FLD_surname,'Please enter the surname of the user.')) {return false};
			if (!valTextRequired(obj.FLD_email,'Please enter the email address of the user.')) {return false};
			if (!valEmailFormat(obj.FLD_email,'Please enter a valid email address.')) {return false};
			if (contactNo.value == '' && contactCellNo.value == '') {
				alert('Please enter your contact telephone number or cell phone number.');
				contactNo.focus();
				return false;
			}
			if (contactNo.value > ''){
				if (!valTelNo(contactNo)) {return false};
			}
			if (contactCellNo.value > ''){
				if (!valCellNo(contactCellNo)) {return false};
			}
			
			
				
 				
				

			
		}
		return true;
	}




SCRIPTTAIL;


  




?>
