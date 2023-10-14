<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "payCheckForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Payment > Checklist</span>";

$this->formOnSubmit = "return enableTotal();";

$this->scriptHead .= "\n";
$this->scriptHead .= "function enableTotal() {\n";
$this->scriptHead .= "	if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptHead .= "		if (isNaN(parseInt(document.defaultFrm.FLD_new_inst_fee.value))) {\n";
$this->scriptHead .= "			alert('You can only use numbers in the Fee for accreditation providers field.');\n";
$this->scriptHead .= "			document.defaultFrm.FLD_new_inst_fee.focus();\n";
$this->scriptHead .= "			document.defaultFrm.MOVETO.value = '';\n";
$this->scriptHead .= "			return false;\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	document.defaultFrm.FLD_invoice_total.value = parseInt(document.defaultFrm.FLD_programme_fee.value) + parseInt(document.defaultFrm.FLD_new_inst_fee.value) + parseInt(document.defaultFrm.FLD_prog_fee_additional_sites.value);\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n";

?>
