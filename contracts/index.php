<?php 

require_once ("_systems/contract/contract.php");

writePhpHeader ();

//print_r($_GET);
//print_r($_SESSION);

// read goto or set it to 1 if not available
$flow = readGET("goto", 1);

//echo "<br>Flow: ".$flow . "<br>";

$page = new contractRegister ($flow);
//echo "<br>User: ".$page->currentUserID . "<br>";


if ($ID = readGET("ID", false)) {
	$page->setActiveWorkFlow ($ID);
}

$TreeMenu  = new HTML_TreeMenu();

// set defaults
$page->bodyMenu = "menu";
$page->bodyMenuNavigation = "topmenu";
$page->title = "CHE Contract Register";
$page->styleSheet = "styles.css";
$page->bodyStart  = '<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';

array_push($page->scriptFile, "js/jquery-1.4.2.min.js");
array_push($page->scriptFile, "js/che.js");

//$page->createAction("home", "Home Page", "href", "javascript:moveto(2);", "");

// load additional support
//$page->support_popupWindow(300,500,120);

$page->showPage ();
?>
