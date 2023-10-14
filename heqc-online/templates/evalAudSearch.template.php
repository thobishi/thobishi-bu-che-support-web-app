<?php

$this->title		= "CHE Evaluators and Auditors";
$this->bodyHeader	= "formHead";
$this->body		= "evalAudSearch";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Reports > Evaluator/Auditor Search</span>";

$this->scriptHead .= "function showInfo(uid) {\n";
$this->scriptHead .= '	URL = "html/evalAudSearch.infowin.html.php?userid=";' . "\n";
$this->scriptHead .= "	URL = URL + uid;\n";
$this->scriptHead .= "	infoWinLeft = (screen.width-700)/2;\n";
$this->scriptHead .= "	infoWinTop = (screen.height-400)/2;\n";
$this->scriptHead .= '	options = "status=yes,scrollbars=yes, resizable=yes,width=700,height=400,top="+infoWinTop+",left="+infoWinLeft;' . "\n";
$this->scriptHead .= "	var infoWin = open(URL,'',options);\n";
$this->scriptHead .= "}\n";

?>
