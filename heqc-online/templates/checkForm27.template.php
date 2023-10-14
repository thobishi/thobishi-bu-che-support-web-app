<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm27";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

$this->formOnSubmit = "return checkOverride ();";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "try {\n";
$this->scriptTail .= "	check_results_back ();\n";
$this->scriptTail .= "}catch (e) {}\n";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkOverride() {\n";
$this->scriptTail .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		try {\n";
$this->scriptTail .= "			if (! (document.defaultFrm.FLD_override_email_send.checked) ) {\n";
$this->scriptTail .= "				alert('Please send this email or override it before continuing.');\n";
$this->scriptTail .= "				document.defaultFrm.MOVETO.value = '';\n";
$this->scriptTail .= "				return false;\n";
$this->scriptTail .= "			}else {\n";
$this->scriptTail .= "				if (document.defaultFrm.FLD_override_message_email_reason.value == '') {\n";
$this->scriptTail .= "					alert('Please enter the comments for overriding this email.');\n";
$this->scriptTail .= "					document.defaultFrm.MOVETO.value = '';\n";
$this->scriptTail .= "					return false;\n";
$this->scriptTail .= "				}\n";
$this->scriptTail .= "				document.defaultFrm.FLD_email_sent.value = 0;\n";
$this->scriptTail .= "			}\n";
$this->scriptTail .= "		}catch(e){}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
?>
