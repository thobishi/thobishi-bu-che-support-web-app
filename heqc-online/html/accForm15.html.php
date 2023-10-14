<a name="application_form_question7"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>7. INFRASTRUCTURE AND LIBRARY RESOURCES: (Criterion 7)</b>&nbsp; [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
Taking into account the minimum standards and the required supporting documentation, please answer the following questions:
<br><br>
<b>Minimum standards:</b> [<?php $this->popupContent("Minimum standards", "MinimumStandards", "", true) ?>]<br>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>7.1</b></td><td valign="top"><b>To what extent does the programme have suitable and sufficient lecturing venues in relation to the expected student intake and the nature of the programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("7_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>7.2</b></td><td valign="top"><b>How do the IT infrastructure and library resources available for students and staff match the programme requirements? </b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("7_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>7.3</b></td><td valign="top"><b>How are the management and maintenance of library resources, including support and access for students and staff regulated?</b> </td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("7_3_comment") ?></td>
</tr><tr>
	<td valign="top"><b>7.4</b></td><td valign="top"><b>What mechanism does the institution have to establish the correspondence between the nature and availability of resources and the nature of the programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("7_4_comment") ?></td>
</tr><tr>
	<td valign="top"><b>7.5</b></td><td valign="top"><b>If the programme is offered on a distance mode, what mechanisms are available for students to have access to  extra reading material?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("7_5_comment") ?></td>
</tr></table>
<br><br>

<!--
<br><br>

<div id="notComply" style="display:none">
	<b>*Please suggest improvement:</b>
</div>
<div id="comply" style="display:Block">
<b>Taking into account the minimum standards, please answer all aspects of question number 6:</b><br>
</div>
<?php//$this->showField("6_comment") ?>
<br><br>
-->

<b>In the space below indicate to what extent does your programme comply with the criterion 7:</b><br>
<?php $this->showField("7_criteria") ?>
<br><br>

<!--
<b>Please tick in the box the extent to which this programme meets the minimum standards for infrastructure and library resources:</b><br>
<?php//$this->showField("6_criteria") ?>
<br><br>

<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation.</b>
<?php // $this->showField("6_self_evaluation") ?>
<br><br>
-->
<fieldset>
<legend><b>Required Documentation</b></legend>
<br>

<?php 
	$prov_type = $this->checkAppPrivPubl($this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID);
	$display1 = "none";
	$display2 = "none";
	if ($prov_type == 1) {
		$display1 = "Block";
	}
	if ($prov_type == 2) {
		$display2 = "Block";
	}
?>

<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
<tr>
<td><?php $this->showInstProfileUploadedDocs($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "institution_id"));?></td>
</tr>
</table>
<br><br>

<!-- The following is for private providers  -->
<div style="display:<?php echo $display1?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPrivate()?>
</td></tr></table>
</div>

<!-- The following is for PUBLIC providers  -->
<div style="display:<?php echo $display2?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPublic()?>
</td></tr></table>
</div>

<br><br>
<!-- The following is for private providers  -->
<div style="display:<?php echo $display1?>">
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Financial plan for the maintenance and upgrading of infrastructure</b>
			<br><?php $this->showField("7_infrastructure") ?></td>
		</tr><tr>
			<td><div id="div_FLD_7_infrastructure" style="display:none">
			Please explain why not:
			<br><?php $this->showField("7_infrastructure_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_7_infrastructure_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "7_infrastructure") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("7_infrastructure_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
</div>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Financial plan for the maintenance and upgrading of infrastructure/resources.</b>
			<br><?php $this->showField("7_financial_maintenance") ?></td>
		</tr><tr>
			<td><div id="div_FLD_7_financial_maintenance" style="display:none">
			Please explain why not:
			<br><?php $this->showField("7_financial_maintenance_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_7_financial_maintenance_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "7_financial_maintenance") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("7_financial_maintenance_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Documentation providing information on your programme management information system to demonstrate compliance with minimum standard 1.</b>
			<br><?php $this->showField("7_docs_management") ?></td>
		</tr><tr>
			<td><div id="div_FLD_7_docs_management" style="display:none">
			Please explain why not:
			<br><?php $this->showField("7_docs_management_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_7_docs_management_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "7_docs_management") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("7_docs_management_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Upload any other documentation which will indicate your compliance with this criterion.</b><br></td>
		</tr><tr>
			<td>
			Upload document electronically:
			<br>
			<?php $this->makeLink("7_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>
<!-- Take out: 2004-10-26

<tr>
	<td class="oncolour"><b>Ratio computer:student</b>
	<br><?php // $this->showField("6_student") ?></td>
</tr><tr>
	<td><div id="div_FLD_6_student" style="display:none">
	Please explain why not:
	<br><?php // $this->showField("6_student_whyNot") ?></div></td>
</tr><tr>
	<td class="oncolour"><b>Ratio computer:staff</b>
	<br><?php//$this->showField("6_staff") ?></td>
</tr><tr>
	<td><div id="div_FLD_6_staff" style="display:none">
	Please explain why not:
	<br><?php // $this->showField("6_staff_whyNot") ?></div></td>
</tr><tr>
	<td class="oncolour"><b>IT budget</b>
	<br><?php // $this->showField("6_budget") ?></td>
</tr><tr>
	<td><div id="div_FLD_6_budget"" style="display:none">
	Please explain why not:
	<br><?php // $this->showField("6_budget_whyNot") ?></div></td>
</tr><tr>
	<td class="oncolour"><b>Number of libraries</b>
	<br><?php // $this->showField("6_libraries") ?></td>
</tr><tr>
	<td><div id="div_FLD_6_libraries" style="display:none">
	Please explain why not:
	<br><?php // $this->showField("6_libraries_whyNot") ?></div></td>
</tr>
<tr>
	<td class="oncolour"><b>Number of library holdings</b>
	<br><?php // $this->showField("6_libraryholdings") ?></td>
</tr><tr>
	<td><div id="div_FLD_6_libraryholdings" style="display:none">
	Please explain why not:
	<br><?php // $this->showField("6_libraryholdings_whyNot") ?></div></td>
</tr>
<tr>
	<td class="oncolour"><b>Library budget</b>
	<br><?php//$this->showField("6_librarybudget") ?></td>
</tr><tr>
	<td><div id="div_FLD_6_librarybudget" style="display:none">
	Please explain why not:
	<br><?php//$this->showField("6_librarybudget_whyNot") ?></div></td>
</tr>
-->
</fieldset>

<script>
//	improvement(document.defaultFrm.FLD_6_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
//	checkCriteria (document.defaultFrm.FLD_6_criteria);
</script>
<?php /*
<br><br>
<table width="85%" border=0 cellpadding="2" cellspacing="2"><tr>
	<td><b>Please complete the following table:</b></td>
</tr></table>

	$headArr = array();
	array_push($headArr, "");
	array_push($headArr, "2004/5");
	array_push($headArr, "2005/6");
	array_push($headArr, "2006/7");

	$evalArr = array();
	array_push($evalArr, "lkp_library_budget_desc");

	$fieldsArr = array();
	array_push($fieldsArr, "budget_2004_5");
	array_push($fieldsArr, "budget_2005_6");
	array_push($fieldsArr, "budget_2006_7");

<br><br>
<b>Library budget: </b><i>Only complete if the programme has a specific allocation for library acquisitions</i>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='1'>

	$this->makeGRID("lkp_library_budget", $evalArr, "lkp_library_budget_id", "1", "appTable_6_library_buget", "appTable_6_library_buget_id", "lkp_library_budget_ref", "application_ref", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $fieldsArr, $headArr);

</table>

	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
	}
*/
?>
<?php 
/*
	This was moved to the general profile
<br><br>
<b>Library inventory:</b>
	// Library Inventory
	// appTable_6_library_inventory
	$fieldsArr = array();
	$fieldsArr["library_name"] = "Library Name";
	$fieldsArr["institution"] = "Institution";
	$fieldsArr["location"] = "Location";
	$fieldsArr["holdings_books"] = "Holdings Books (published after 1980)";
	$fieldsArr["holdings_journals"] = "Holdings Journals (published after 1990)";
	$fieldsArr["staff_number"] = "Staff Number";
	$fieldsArr["business_hours"] = "Business Hours";

	echo $this->gridDisplay("Institutions_application", "appTable_6_library_inventory", "appTable_6_library_inventory_id", "application_ref",$fieldsArr, 10);
	*/
/*
	This was moved to the general profile
<br><br>
<b>IT inventory:</b>
<?php 
	// IT INVENTORY
	// appTable_6_it_inventory
	$fieldsArr = array();
	$fieldsArr["computer_student_ratio"] = "COMPUTER/STUDENT RATIO";
	$fieldsArr["computer_staff_ratio"] = "COMPUTER/STAFF RATIO";
	$fieldsArr["business_hours"] = "Business hours computer laboratory";
	$fieldsArr["it_staff_number"] = "IT staff number";

	echo $this->gridDisplay("Institutions_application", "appTable_6_it_inventory", "appTable_6_it_inventory_id", "application_ref",$fieldsArr);
*/
/*
	This was moved to the general profile
<br><br>
<b>Lecture rooms and facilities:</b>
<?php 
	// Lecture rooms and facilities
	// appTable_6_facilities
	$fieldsArr = array();
	$fieldsArr["room_number"] = "Lecture halls/rooms Number";
	$fieldsArr["room_capacity"] = "Lecture rooms capacity";
	$fieldsArr["laboratory_type"] = "Laboratories Type";
	$fieldsArr["laboratory_capacity"] = "Capacity";
		$fieldsArr["other"] = "Other";

	echo $this->gridDisplay("Institutions_application", "appTable_6_facilities", "appTable_6_facilities_id", "application_ref",$fieldsArr);
*/
?>
<br><br>
</td></tr></table>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
