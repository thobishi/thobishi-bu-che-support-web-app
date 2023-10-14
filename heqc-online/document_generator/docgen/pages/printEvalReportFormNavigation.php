<?php 
$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');

$run_in_script_mode = true;

$app = new HEQConline (1);
$str = base64_decode(readGET("workflow_settings"));

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
			<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question1';return false;" title="PROGRAMME DESIGN">1</a>&nbsp;|&nbsp;
			<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question2';return false;" title="STUDENT RECRUITMENT, ADMISSION AND SELECTION">2</a>&nbsp;|&nbsp;
			<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question3';return false;" title="STAFFING">3</a>&nbsp;|&nbsp;
			<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question4';return false;" title="STAFFING (continued)">4</a>&nbsp;|&nbsp;
			<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question5';return false;" title="STUDENT ASSESSMENT POLICIES AND PROCEDURES">5</a>&nbsp;|&nbsp;
			<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question6';return false;" title="VENUES AND INFRASTRUCTURE">6</a>&nbsp;|&nbsp;
			<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question7';return false;" title="PROGRAMME ADMINISTRATIVE SERVICES">7</a>&nbsp;|&nbsp;
			<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question8';return false;" title="POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS">8</a>
		</td>
	</tr></table>
	</td>
	
</tr>
</table>


</body>
</html>
