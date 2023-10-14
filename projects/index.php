<?

require_once ("_systems/che_projects.php");

writePhpHeader ();

// read goto or set it to 1 if not available
$flow = readGET("goto", 1);

$page = new CHEprojects ($flow);

if ($ID = readGET("ID", false)) {
	$page->setActiveWorkFlow ($ID);
}

$TreeMenu  = new HTML_TreeMenu();

// set defaults
$page->bodyMenu = "menu";
$page->bodyMenuNavigation = "topmenu";
$page->title = "CHE Project Register";
$page->styleSheet = "styles.css";
$page->bodyStart  = '<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
array_push($page->scriptFile, "js/che.js");
array_push($page->scriptFile, "js/che.js.php");

//$page->createAction("home", "Home Page", "href", "javascript:moveto(2);", "");

// load additional support
$page->support_popupWindow(300,500,120);
$page->showPage ();
?>
