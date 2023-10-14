<?php

$this->title		= "Re-accreditation Application Status Report";
$this->bodyHeader	= "formHead";
$this->body			= "reportReaccApplicProgress";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Reports > Reaccreditation Application Status</span>";

$this->formHidden['adminproc'] = 0;

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function adminTakeApp (proc){\n";
$this->scriptTail .= "	document.defaultFrm.adminproc.value = proc;\n";
$this->scriptTail .= "	moveto('stay');";
$this->scriptTail .= "}\n\n";
?>
