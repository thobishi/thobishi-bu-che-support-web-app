<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm25";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";


$this->scriptTail .= "\n\n";
$this->scriptTail .= "showHideAction('next', false);";
$this->scriptTail .= "try {\n";
$this->scriptTail .= "	check_results_back ();\n";
$this->scriptTail .= "}catch (e) {}\n";
?>
