<?php 
$run_in_script_mode = true;
$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');

$app = new HEQConline (1);
$str = base64_decode($_GET["workflow_settings"]);

$reacc_id = substr($str, (strPos($str, "Institutions_application_reaccreditation_id=")+44), strlen($str));
//echo "reacc_id: **************" . $reacc_id . "<br>";
$reference_code = $app->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, "referenceNumber");
$programme_name = $app->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, "programme_name");
//echo "programme name: **************" . $programme_name . "<br>";
$inst_id = $app->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, "institution_ref");
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
	<td height="2">
	</td>
</tr><tr>
	<td bgcolor="#ECF1F6" width="300">
		<img src="../images/help_top.gif" width="255" height="45">
	</td>
	<td bgcolor="#ECF1F6" >
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="right" valign="top"><b>CHE Ref Number:</b></td><td valign="top"><?php echo $reference_code?></td>
		</tr>
		<tr>
			<td align="right" valign="top"><b>Programme:</b></td><td valign="top"><?php echo $programme_name?></td>
		</tr>
		<tr>
			<td align="right" valign="top"><b>Institution:</b></td><td valign="top"><?php echo $inst?></td>
		</tr>
		</table>
	</td>
	<td align="right" bgcolor="#ECF1F6" >
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
		<td align="right">
		<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question1';return false;" title="PROGRAMME DESIGN">1</a>&nbsp;|&nbsp;
		<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question2';return false;" title="STUDENT RECRUITMENT, ADMISSION AND SELECTION">2</a>&nbsp;|&nbsp;
		<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question3';return false;" title="STAFF QUALIFICATIONS">3</a>&nbsp;|&nbsp;
		<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question4';return false;" title="STAFF SIZE AND SENIORITY">4</a>&nbsp;|&nbsp;
		<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question5';return false;" title="TEACHING AND LEARNING STRATEGY">5</a>&nbsp;|&nbsp;
		<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question6';return false;" title="ASSESSMENT">6</a>&nbsp;|&nbsp;
		<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question7';return false;" title="INFRASTRUCTURE AND LIBRARY RESOURCES">7</a>&nbsp;|&nbsp;
		<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question8';return false;" title="PROGRAMME ADMINISTRATIVE SERVICES">8</a>
		<?php /*
			$NQF_id = $app->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, "NQF_level");
			if ($NQF_id > 2) {
		*/
		?>
<!--			&nbsp;|&nbsp;<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question9';return false;" title="POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS">9</a>
-->
		<?php /* }
			$mode_id = $app->getValueFromTable("Institutions_application", "application_id", $reacc_id, "mode_delivery");
			$hei_id = $app->getValueFromTable("Institutions_application", "application_id", $reacc_id, "institution_id");
			if (($mode_id == 2) && ($hei_id != 54)) {
			*/
		?>
<!--			&nbsp;|&nbsp;<a href="#" onclick="window.parent.frames['bodyFrame'].location.hash='application_form_question10';return false;" title="C. PROGRAMMES OFFERED THROUGH DISTANCE EDUCATION">10</a>
-->
		<?php /* } */ ?>

		</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</body>
</html>
