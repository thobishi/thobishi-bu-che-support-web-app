<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "checkForm2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Project Management > Checklist</span>";

$this->formOnSubmit = " return checkSend();";

$this->scriptHead .= "function checkSendMessage(obj){\n";
$this->scriptHead .= "	if (!(obj.checked == true)) {\n";
$this->scriptHead .= "		alert('Please check the send message checkbox');\n";
$this->scriptHead .= "		return false;\n";
$this->scriptHead .= "	}else{\n";
$this->scriptHead .= "		return true;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkSend() {\n";
$this->scriptTail .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		try {\n";
$this->scriptTail .= "			if (document.defaultFrm.FLD_saqa_email_count.value == 0) {\n";
$this->scriptTail .= "				if ( (document.defaultFrm.send_message.checked) ) {\n";
$this->scriptTail .= "					alert('Please send the message first.');\n";
$this->scriptTail .= "					return false;\n";
$this->scriptTail .= "				}else {\n";
$this->scriptTail .= "					if (! (document.defaultFrm.FLD_override_saqa_send_message.checked) ) {\n";
$this->scriptTail .= "						alert('Please send the message first or override if you know the answer.');\n";
$this->scriptTail .= "						return false;\n";
$this->scriptTail .= "					}\n";
$this->scriptTail .= "				}\n";
$this->scriptTail .= "			}\n";
$this->scriptTail .= "		}catch(e){}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";

?>
