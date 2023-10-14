<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "EditActiveProcess1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Admin > Settings</span>";

$this->formHidden["prev_user_ref"] = "";

$this->scriptTail .= "\ndocument.all.defaultFrm.prev_user_ref.value = document.all.defaultFrm.FLD_user_ref.value;\n";

?>
