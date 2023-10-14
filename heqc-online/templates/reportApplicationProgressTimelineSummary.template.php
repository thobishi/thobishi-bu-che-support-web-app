<?php

$this->title		= "Outstanding CHE processes Summary";
$this->bodyHeader	= "formHead";
$this->body		= "reportApplicationProgressTimelineSummary";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Reports > Outstanding CHE processes (Summary)</span>";

$this->formHidden['adminproc'] = 0;

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function adminTakeApp (proc){\n";
$this->scriptTail .= "	document.defaultFrm.adminproc.value = proc;\n";
$this->scriptTail .= "	moveto('stay');";
$this->scriptTail .= "}\n\n";
?>
