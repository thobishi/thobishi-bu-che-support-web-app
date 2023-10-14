<?php
$startTime = microtime();

require_once ("/var/www/common/_systems/nr-online.php");
require_once ('/var/www/common/document_generator/cl_xml2driver.php');

writePhpHeader ();

// read goto or set it to 1 if not available
$flow = readGET("goto", 1);

$page = new NRonline ($flow);

if ($ID = readGET("ID", false)) {
	$page->setActiveWorkFlow ($ID);
}

// 2012-06-04 &new deprecated - moving this to file where it is used: evaluatorForm2
//$TreeMenu  = new HTML_TreeMenu();

// set defaults
$page->bodyMenu = "menu";
$page->bodyMenuNavigation = "topmenu";
$page->title = "CHE National Reviews Online";
$page->bodyStart  = '<body>';
Settings::set('userInterface', 2);

array_push($page->styleSheet, "js/bootstrap.css");
array_push($page->styleSheet, "js/bootstrap-responsive.css");
array_push($page->styleSheet, "bootstrap-overrides.css");
array_push($page->styleSheet, "js/datepicker.css");
array_push($page->styleSheet, "js/select2.css");
array_push($page->styleSheet, "styles.css");

// array_push($page->scriptFile, "js/jquery-1.9.1.min.js");
array_push($page->scriptFile, "http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js");
array_push($page->scriptFile, "js/bootstrap.min.js");
array_push($page->scriptFile, "js/jquery.form.js");
array_push($page->scriptFile, "js/clicknscroll.js");
array_push($page->scriptFile, "js/bootstrap-datepicker.js");
array_push($page->scriptFile, "js/select2.min.js");
array_push($page->scriptFile, "js/national-reviews.js");
array_push($page->scriptFile, "js/national-reviews.js.php");


//$page->createAction("home", "Home Page", "href", "javascript:moveto(2);", "");

// load additional support
//$page->support_popupWindow(300,500,120);

$page->showPage ();

if (Settings::get('debug_mode')) {
	echo '<div class="hero-unit"><h1>Debug information</h1>';

	echo '<h2>Session data</h2>';
	var_dump($_SESSION);

	echo '<h2>Settings</h2>';
	Settings::printAll();

	echo '<h2>SQL Log</h2>';
	$page->db->showSqlLog();

	echo '<p>Request time: ' . (microtime() - $startTime) , ' seconds</p>';
	echo '</div>';
}