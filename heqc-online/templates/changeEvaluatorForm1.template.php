<?php
$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "changeEvaluatorForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluation > Checklist</span>";

$this->formAction = "html/changeEvalForm1.iframe.html.php";
$this->formTarget = "resultsFrame";
$this->formName = "searchFrm";

$this->scriptHead .= "function doSearch() {\n";
$this->scriptHead .= "	document.searchFrm.submit();\n";
$this->scriptHead .= "}\n";

$this->formOnSubmit = "return selectAll();";

$this->scriptHead .= "function selectAll() {\n";
$this->scriptHead .= "	sLength = document.defaultFrm.elements['FLDS_resultsSelect[]'].length;\n";
$this->scriptHead .= "	if (!(sLength >= 3)) {\n";
$this->scriptHead .= "		if (document.defaultFrm.MOVETO.value == 'next') {\n";
$this->scriptHead .= "			alert('Please select at least 3 evaluators (2 evaluators and 1 QA managers).');\n";
$this->scriptHead .= "			return false;\n";
$this->scriptHead .= "		}\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	for (i=0; i<sLength; i++) {\n";
$this->scriptHead .= "		document.defaultFrm.elements['FLDS_resultsSelect[]'].options[i].selected = true;\n";
$this->scriptHead .= "	}\n";
$this->scriptHead .= "	return true;\n";
$this->scriptHead .= "}\n";

?>
