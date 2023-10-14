<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "payCheckForm2";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Payment > Checklist</span>";

$this->formOnSubmit = " return checkSend();";

$this->scriptHead .= "function checkSendInvoice(obj){\n";
$this->scriptHead .= "	if (!(obj.checked == true)) {\n";
$this->scriptHead .= "		alert('Please check the send invoice checkbox');\n";
$this->scriptHead .= "		return false;\n";
$this->scriptHead .= "	}else{\n";
$this->scriptHead .= "		moveto('stay');\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "}\n";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function checkSend() {\n";
$this->scriptTail .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptTail .= "		try {\n";
$this->scriptTail .= "			if (document.defaultFrm.FLD_invoice_sent_count.value == 0) {\n";
$this->scriptTail .= "				if (! (document.defaultFrm.send_invoice.checked) ) {\n";
$this->scriptTail .= "					alert('Please send the invoice first.');\n";
$this->scriptTail .= "					return false;\n";
$this->scriptTail .= "				}\n";
$this->scriptTail .= "			}\n";
$this->scriptTail .= "		}catch(e){}\n";
$this->scriptTail .= "	}\n";
$this->scriptTail .= "	return true;\n";
$this->scriptTail .= "}\n";
?>
