<?php 
require_once ('_systems/heqc-online.php');

$page = new HEQConline (2);

// set defaults
$page->bodyMenu = "menu";
$page->title = "CHE Accreditation";
$page->styleSheet = "styles.css";
$page->bodyStart  = '<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
array_push($page->scriptFile, "js/che.js");

// load additional support
$page->support_popupWindow(300,500,120);

$page->showPage ();

?>
