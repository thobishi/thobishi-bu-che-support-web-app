<?php 
/*
	Edited by Rebecca 4/5/2007
	Added Module/Course name (which was previously commented out) for display - since some of these fields are filled in.
*/
?>

<a name="application_form_question1"></a>
<table width="100%" border=1 align="center" cellpadding="2" cellspacing="2" bgcolor="#fef6f6">
<tr><td>
<b>PROGRAMME STRUCTURE:</b>
<br><br>This is the information that had been entered into the programme structure tables that are now being phased out of the system.
<a name="appTable_1_programme_structure"></a>
<br><br>
<b>Year 1:</b>
<?php 
	$headArr = array();
	array_push($headArr, "Module/Course name");
	array_push($headArr, "Fundamental");
	array_push($headArr, "Credits");
	array_push($headArr, "Core");
	array_push($headArr, "Credits");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__course_name|size__10");
	array_push($fieldArr, "type__text|name__fundamental|size__40");
	array_push($fieldArr, "type__text|name__fund_credits|size__3");
	array_push($fieldArr, "type__text|name__core|size__40");
	array_push($fieldArr, "type__text|name__core_credits|size__3");

?>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->displayFixedGrid ("appTable_1_prog_structure", "appTable_1_prog_structure_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."|year__1", $fieldArr, $headArr, 12)
?>
</table>

<br><br>
<b>Year 2:</b>
<?php 
	$headArr = array();
	array_push($headArr, "Module/Course name");
	array_push($headArr, "Fundamental");
	array_push($headArr, "Credits");
	array_push($headArr, "Core");
	array_push($headArr, "Credits");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__course_name|size__10");
	array_push($fieldArr, "type__text|name__fundamental|size__40");
	array_push($fieldArr, "type__text|name__fund_credits|size__3");
	array_push($fieldArr, "type__text|name__core|size__40");
	array_push($fieldArr, "type__text|name__core_credits|size__3");

?>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->displayFixedGrid ("appTable_1_prog_structure", "appTable_1_prog_structure_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."|year__2", $fieldArr, $headArr, 12)
?>
</table>

<br><br>
<b>Year 3:</b>
<?php 
	$headArr = array();
	array_push($headArr, "Module/Course name");
	array_push($headArr, "Fundamental");
	array_push($headArr, "Credits");
	array_push($headArr, "Core");
	array_push($headArr, "Credits");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__course_name|size__10");
	array_push($fieldArr, "type__text|name__fundamental|size__40");
	array_push($fieldArr, "type__text|name__fund_credits|size__3");
	array_push($fieldArr, "type__text|name__core|size__40");
	array_push($fieldArr, "type__text|name__core_credits|size__3");

?>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->displayFixedGrid ("appTable_1_prog_structure", "appTable_1_prog_structure_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."|year__3", $fieldArr, $headArr, 12)
?>
</table>

<br><br>
<b>Year 4:</b>
<?php 
	$headArr = array();
	array_push($headArr, "Module/Course name");
	array_push($headArr, "Fundamental");
	array_push($headArr, "Credits");
	array_push($headArr, "Core");
	array_push($headArr, "Credits");

	$fieldArr = array();
	array_push($fieldArr, "type__text|name__course_name|size__10");
	array_push($fieldArr, "type__text|name__fundamental|size__40");
	array_push($fieldArr, "type__text|name__fund_credits|size__3");
	array_push($fieldArr, "type__text|name__core|size__40");
	array_push($fieldArr, "type__text|name__core_credits|size__3");

?>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>
<?php 
	$this->displayFixedGrid ("appTable_1_prog_structure", "appTable_1_prog_structure_id", "application_ref__".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."|year__4", $fieldArr, $headArr, 12);
?>
</table>
</td></tr>
</table>