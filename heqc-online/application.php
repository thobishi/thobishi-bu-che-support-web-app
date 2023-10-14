<?php 
require_once ("_systems/heqc-online.php");
require_once ('document_generator/cl_xml2driver.php');

writePhpHeader ();

// read goto or set it to 1 if not available
$flow = readGET("goto", 1);

$page = new HEQConline ($flow);

if ($ID = readGET("ID", false)) {
	$page->setActiveWorkFlow ($ID);
}

// 2012-06-04 &new deprecated - moving this to file where it is used: evaluatorForm2
//$TreeMenu  = new HTML_TreeMenu();

// set defaults
$page->bodyMenu = "menu";
$page->bodyMenuNavigation = "topmenu";
$page->title = "CHE Accreditation";
$page->styleSheet = "styles.css";
$page->bodyStart  = '<body style="margin:0 0 0 0;">';
$page->userInterface = 2;

array_push($page->scriptFile, "js/jquery-1.4.2.min.js");
array_push($page->scriptFile, "js/che.js");
array_push($page->scriptFile, "js/che.js.php");
array_push($page->scriptFile, "js/jquery-ui.js");
array_push($page->scriptFile, "js/chosen.jquery.min.js");

//$page->createAction("home", "Home Page", "href", "javascript:moveto(2);", "");

// load additional support
//$page->support_popupWindow(300,500,120);

$page->showPage ();
?>

