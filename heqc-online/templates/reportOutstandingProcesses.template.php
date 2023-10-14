<?php

$this->title		= "Outstanding CHE processes";
$this->bodyHeader	= "formHead";
$this->body		= "reportOutstandingProcesses";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Reports > Outstanding CHE processes</span>";

$this->formHidden['adminproc'] = 0;

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function adminTakeApp (proc){\n";
$this->scriptTail .= "	document.defaultFrm.adminproc.value = proc;\n";
$this->scriptTail .= "	moveto('stay');";
$this->scriptTail .= "}\n\n";
?>
