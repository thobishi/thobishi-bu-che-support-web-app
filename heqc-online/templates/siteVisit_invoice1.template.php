<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "siteVisit_invoice1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Site Visit > </span>";

$this->formOnSubmit = "returnTotal();";

$this->scriptTail .= "\n\n";
$this->scriptTail .= "function calcTotal () {\n";
$this->scriptTail .= "	var obj = document.defaultFrm;\n";
$this->scriptTail .= "	cost = ".$this->getDBsettingsValue('payment_site_fee').";\n";
$this->scriptTail .= "	if (obj.FLD_direct_travel_costs.value != '') cost += parseInt(obj.FLD_direct_travel_costs.value);\n";
$this->scriptTail .= "	if (obj.FLD_direct_accomodation_costs.value != '') cost += parseInt(obj.FLD_direct_accomodation_costs.value);\n";
$this->scriptTail .= "	if (obj.FLD_direct_subsistence_costs.value != '') cost += parseInt(obj.FLD_direct_subsistence_costs.value);\n";
$this->scriptTail .= "	obj.FLD_total_costs.value = Math.round((cost*1.14)*100)/100;\n";
$this->scriptTail .= "}\n\n";

$this->scriptTail .= "function returnTotal() {\n\n";
$this->scriptTail .= "	document.defaultFrm.FLD_total_costs.disabled = false;\n";
$this->scriptTail .= "}\n\n";
?>
