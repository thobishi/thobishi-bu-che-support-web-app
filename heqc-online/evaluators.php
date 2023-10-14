<?php 

require_once ("_systems/heqc-online.php");

writePhpHeader ();

// read goto or set it to 1 if not available
$flow = readGET("goto", 1);

$page = new HEQConline ($flow);

if ($ID = readGET("ID", false)) {
	$page->setActiveWorkFlow ($ID);
}


$TreeMenu  = new HTML_TreeMenu();

// set defaults
$page->bodyMenu = "menu";
$page->bodyMenuNavigation = "topmenu";
$page->title = "CHE Accreditation";
$page->styleSheet = "styles.css";
$page->bodyStart  = '<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
$page->userInterface = 3;

array_push($page->scriptFile, "js/che.js");

//$page->createAction("home", "Home Page", "href", "javascript:moveto(2);", "");

// load additional support
$page->support_popupWindow(300,500,120);

$page->showPage ();
?>
