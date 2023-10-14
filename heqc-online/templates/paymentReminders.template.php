<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "paymentReminders";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Admin > Send payment reminders</span>";

$this->scriptHead = <<<SCRIPTHEAD
	function checkSendReminder(obj){
		if (!(obj.checked == true)) {
			alert('Please check the send reminder checkbox');
			return false;
		}else{
			moveto('stay');
		}
	}
SCRIPTHEAD;

?>
