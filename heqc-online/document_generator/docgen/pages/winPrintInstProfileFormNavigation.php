<?php 
$run_in_script_mode = true;
$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');

$app = new HEQConline (1);
$str = base64_decode($_GET["workflow_settings"]);

$app_id = substr($str, (strPos($str, "application_id=")+15), strlen($str));

$programme_name = $app->getValueFromTable("Institutions_application", "application_id", $app_id, "program_name");
$inst_id = $app->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
$inst = $app->getValueFromTable("HEInstitution", "HEI_id", $inst_id, "HEI_name");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<link rel=STYLESHEET TYPE="text/css" href="../styles.css" title="Normal Style">
</head>

<body>
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td height="2"></td>
</tr><tr>
	<td bgcolor="#ECF1F6" width="300">
		<img src="../images/help_top.gif" width="255" height="45">
	</td>
	<td align="right" bgcolor="#ECF1F6" >
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question1';return false;" title="POLICIES AND PROCEDURES ON PROGRAMME DESIGN">1</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question2';return false;" title="ADMISSION AND SELECTION POLICIES">2</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question3';return false;" title="HUMAN RESOURCES POLICIES AND PROCEDURES">3</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question4';return false;" title="TEACHING AND LEARNING STRATEGY">4</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question5';return false;" title="ASSESSMENT AND EVALUATION PROCESS AND PROCEDURE">5</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question6';return false;" title="CERTIFICATION">6</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question7';return false;" title="POSTGRADUATE POLICIES AND PROCEDURES">7</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question8';return false;" title="MANAGEMENT INFORMATION SYSTEM">8</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question9';return false;" title="NAME, TYPE  AND LOCATION OF LIBRARIES">9</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question10';return false;" title="GENERAL LIBRARY BUDGET">10</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question11';return false;" title="NAME AND TYPE OF LABORATORIES">11</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question12';return false;" title="IT INFRASTRUCTURE">12</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question13';return false;" title="LECTURE ROOMS">13</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question14';return false;" title="INFRASTRUCTURE">14</a>&nbsp;|&nbsp;
	<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question15';return false;" title="STATUS OF PROGRAMME OFFERINGS">15</a>
</tr></table>
	</td>
</tr>
</table>


</body>
</html>
