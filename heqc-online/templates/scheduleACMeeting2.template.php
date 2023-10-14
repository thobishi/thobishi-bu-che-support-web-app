<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "scheduleACMeeting2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>AC Meeting > Schedule</span>";

$this->formOnSubmit = "return checkFrm(this);";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkFrm(obj) {\n";
$this->scriptTail .= "	var anyChecked = 0;\n";
$this->scriptTail .= "	var count = 0;\n";
$this->scriptTail .= "	if ((obj.MOVETO.value == 'next') && (obj.submitACmembers.value == 1)) {\n";
$this->scriptTail .= "    for(i=0; i<document.defaultFrm.elements.length;i++){ \n";
$this->scriptTail .= "	    if ((document.defaultFrm.elements[i].checked) && (document.defaultFrm.elements[i].name.match('atMeeting_'))){ \n";
$this->scriptTail .= "		    anyChecked++;\n";
$this->scriptTail .= "	    }\n";
$this->scriptTail .= "	  }\n";
$this->scriptTail .= "    if (anyChecked == 0){ \n";
$this->scriptTail .= "	    alert('Please select at least one AC member to continue');\n";
$this->scriptTail .= "	    obj.MOVETO.value = '';\n";
$this->scriptTail .= "	    return false;\n";
$this->scriptTail .= "	  }\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
$this->scriptTail .= "\n\n";

?>


