<?php
//xhprof_enable();

function pr($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

if(strpos(__FILE__, 'wl') !== false) {
	define ('CONFIG', 'WLDEV');
}
$path = dirname(__FILE__) . '/';
require_once ("/var/www/html/common/_systems/heqc-online.php");
require_once ('/var/www/html/common/document_generator/cl_xml2driver.php');

writePhpHeader ();

// read goto or set it to 1 if not available
$flow = readGET("goto", 1);

$page = new HEQConline ($flow);

if ($ID = readGET("ID", false)) {
	$page->setActiveWorkFlow ($ID);
}
// $TreeMenu  = new HTML_TreeMenu();

// set defaults

$page->bodyMenu = "menu";
$page->bodyMenuNavigation = "topmenu";
$page->title = "CHE Accreditation";
$page->styleSheet = "styles.css";
$page->bodyStart  = '<body style="margin:0 0 0 0;">';
array_push($page->scriptFile, "js/jquery-1.4.2.min.js");
array_push($page->scriptFile, "js/che.js");
array_push($page->scriptFile, "js/che.js.php");
array_push($page->scriptFile, "js/jquery-ui.js");
array_push($page->scriptFile, "js/chosen.jquery.min.js");

//$page->createAction("home", "Home Page", "href", "javascript:moveto(2);", "");

// load additional support
//$page->support_popupWindow(300,500,120);

$page->showPage ();

/*$xhprof_data = xhprof_disable();

include_once "/var/www/xhprof_lib/utils/xhprof_lib.php";
include_once "/var/www/xhprof_lib/utils/xhprof_runs.php";

$xhprof_runs = new XHProfRuns_Default();

$run_id = $xhprof_runs->save_run($xhprof_data, "heqc-online");

echo "---------------\n".
     "<a href='http://ra/xhprof/index.php?run=$run_id&source=heqc-online' target='_blank'>See run</a>\n".
     "---------------\n";*/
?>
