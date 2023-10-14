<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accFormCreateUsers";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Programme information > Programme design</span>";
//what about navigation bar for menu item?

$this->setFormDBinfo("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref"), "creation_date", "last_updated");

$this->formHidden["DELETE_RECORD"] = "";

// chek if we got a DELETE_RECORD

if (! (isset($_POST["CHANGE_TO_RECORD"]) && ($_POST["CHANGE_TO_RECORD"]>"")) ) {
	$this->setFormDBinfo("users", "user_id", "NEW");
}

$this->scriptTail .= <<<TXT

	function checkSave() {
		text = '';
		go=true;
		if (document.defaultFrm.FLD_surname.value == '') {
			go=false;
			text = text + '- Surname\\n';
		}
		if (document.defaultFrm.FLD_name.value == '') {
			go=false;
			text = text + '- Name\\n';
		}
		if (document.defaultFrm.FLD_email.value == '') {
			go=false;
			text = text + '- Email\\n';
		}
		if (document.defaultFrm.FLD_contact_nr.value == '') {
			go=false;
			text = text + '- Contact No\\n';
		}
		if (!go) {
			alert('Please fill in:\\n' + text);
		} else {
			moveto('next');
		}
	}

TXT;

?>
