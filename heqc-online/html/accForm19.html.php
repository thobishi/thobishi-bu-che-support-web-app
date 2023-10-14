<a name="application_form_question9"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>9. POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS: (Criterion 9)</b> [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
Taking into account the required minimum standards for this item and the requested supporting documentation, please answer the following questions.
<br><br>
<b>Minimum standards:</b> [<?php $this->popupContent("Minimum standards", "MinHelp", "", true) ?>]<br>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>9.1</b></td><td valign="top"><b>What processes are applied for the admission and selection of students into postgraduate programmes?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>9.2</b></td><td valign="top"><b>How are supervisors selected and appointed?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>9.3</b></td><td valign="top"><b>How are the definition of the roles and responsibilities of supervisors and students regulated and managed?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_3_comment") ?></td>
</tr><tr>
	<td valign="top"><b>9.4</b></td><td valign="top"><b>What are the policies in relation to the length of theses and dissertations, and their weight in the student's final mark?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("9_4_comment") ?></td>
</tr></table>
<br><br>

<?php /*
<br><br>

<div id="notComply" style="display:none">
	<b>*Please suggest improvement:</b>
</div>
<div id="comply" style="display:Block">
<b>Please make an overall comment on postgraduate policies, procedures and regulations of the proposed programme in response to question 8:</b><br>
</div>
<?php//$this->showField("8_comment") ?>
<br><br>

<b>Please tick in the box the extent to which the proposed programme meets the required minimum standards for this criterion:</b><br>
<?php//$this->showField("8_criteria") ?>
<br><br>
*/ ?>

<b>In the space below indicate to what extent does your programme comply with the criterion 9:</b><br>
<?php $this->showField("9_criteria") ?>
<br><br>

<?php /*
<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation</b>
<?php // $this->showField("8_self_evaluation") ?>
<br><br>
*/ ?>

<fieldset>
<legend><b>Required Documentation</b></legend>
<br>

<?php 
	$prov_type = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "institution_ref"), "priv_publ");
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
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Policies/procedures for the appointment  of supervisors:</b>
			<br>
			<?php $this->showField("9_regulations") ?></td>
		</tr>	
		<tr>
			<td><div id="div_FLD_9_regulations" style="display:none" >
			Please explain why not:
			<br><?php $this->showField("9_regulations_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_9_regulations_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "9_regulations") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br> 
			<?php $this->makeLink("9_regulations_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Policies and procedures for the admission of students to postgraduate degree:</b>
			<br>
			<?php $this->showField("9_policies") ?></td>
		</tr>
		<tr>
			<td><div id="div_FLD_9_policies" style="display:none" >
			Please explain why not:
			<br><?php $this->showField("9_policies_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_9_policies_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "9_policies") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br> 
			<?php $this->makeLink("9_policies_doc") ?>
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
			<?php $this->makeLink("9_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>

</fieldset>
<!-- The following is for private providers  -->
<div style="display:<?php echo $display1?>">
<?php /*
<br><br>
<b>Supervisor(s):</b>
<br><br>
*/ ?>
<a name="appTable_8_supervisors"></a>
<?php 
	// Supervisors
	// appTable_8_supervisors
	$headArr = array();
	$headArr["Name"] = 1;
	$headArr["Surname"] = 1;
	$headArr["Highest Qualification"] = "1";
	$headArr["Institutional Affiliation"] = "1";
	$headArr["Level of Supervision"] = "2";

	$fieldsArr = array();
	$fieldsArr["name"] = array("", 30);
	$fieldsArr["surname"] = array("", 30);
	$fieldsArr["highest_qual"] = array("", 60);
	$fieldsArr["institutional_affiliation"] = array("", 60);
	$fieldsArr["level_supervision_ma_checkboxFLD"] = "Master's";
	$fieldsArr["level_supervision_phd_checkboxFLD"] = "Doctorate";

	$addRowText = "another supervisor";
//	echo $this->gridDisplayPerTable("Institutions_application", "appTable_8_supervisors", "appTable_8_supervisors_id", "application_ref",$fieldsArr, 5, 0, $headArr, "", "", "", $addRowText, 1);

/* html
<br><br>
<b>External examiner(s):</b>
<br><br>
*/
?>
<a name="appTable_8_external_examiner"></a>
<?php 
	// External Examiner
	// appTable_8_external_examiner
	$headArr = array();
	$headArr["Name"] = 1;
	$headArr["Surname"] = 1;
	$headArr["Highest Qualification"] = 1;
	$headArr["Institutional Affiliation"] = 1;
	$headArr["Level of Supervision"] = 2;

	$fieldsArr = array();
	$fieldsArr["name"] = array("", 30);
	$fieldsArr["surname"] = array("", 30);
	$fieldsArr["highest_qual"] = array("", 60);
	$fieldsArr["institutional_affiliation"] = array("", 60);
	$fieldsArr["level_supervision_ma_checkboxFLD"] = array("Master's");
	$fieldsArr["level_supervision_phd_checkboxFLD"] = array("Doctorate");

//	echo $this->gridDisplay("Institutions_application", "appTable_8_external_examiner", "appTable_8_external_examiner_id", "application_ref",$fieldsArr, 5, 0, $headArr);
	$addRowText = "another external examiner";
//	echo $this->gridDisplayPerTable("Institutions_application", "appTable_8_external_examiner", "appTable_8_external_examiner_id", "application_ref",$fieldsArr, 5, 0, $headArr, "", "", "", $addRowText, 1);
?>
</div>
<br><br>
<?php 
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
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
?>
</td></tr></table>
<?php /*
<script>
	improvement(document.defaultFrm.FLD_8_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
	checkCriteria (document.defaultFrm.FLD_8_criteria);
</script>
*/ ?>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
