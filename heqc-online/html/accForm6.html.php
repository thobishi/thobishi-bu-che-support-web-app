<a name="application_form_question2"></a>
<table width="95%" border=0 align=center cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>2. STUDENT RECRUITMENT, ADMISSION AND SELECTION: (Criterion 2)</b> [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
Taking into account the required minimum standards, and the supporting documentation you are attaching, please answer <b>all aspects</b> of question number 2.
<br><br>
<b>Minimum standards:</b> [<?php $this->popupContent("Minimum standards", "MinHelp", "", true) ?>]
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>2.1</b></td><td valign="top"><b>Supply details of the information you plan to provide students about programme requirements.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>2.2</b></td><td valign="top"><b>What are your admission requirements and how do they relate to the requirements for the academic level of the programme?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>2.3</b></td><td valign="top"><b>How does the selection process take into account the optimal number of students?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_3_comment") ?></td>
</tr><tr>
	<td valign="top"><b>2.4</b></td><td valign="top"><b>How do your recruitment  and admission policies take into account the objective of widening access to higher education?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_4_comment") ?></td>
</tr></table>
<br><br>

<?php /*
<br><Br>
<div id="notComply" style="display:none">
	<b>*Please suggest improvement:</b>
</div>
<div id="comply" style="display:Block">
<b>Taking into account the required minimum standards, please answer all aspects of question number 2<br>
</div>
<?php//$this->showField("2_comment") ?>
<br><br>

<b>Please tick in the box the extent to which this programme meets the minimum standards for student recruitment, admission and selection:</b><br>
<?php//$this->showField("2_criteria") ?>
<Br><br>
*/ ?>
<b>In the space below indicate to what extent does your programme comply with the criterion 2:</b><br>
<?php $this->showField("2_criteria") ?>
<br><br>
<?php /*
<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation.</b> <br>
<?php//$this->showField("2_self_evaluation") ?>
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
<?php/*
<!-- The following is for private providers  -->
*/ ?>
<div style="display:<?php echo $display1?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPrivate()?>
</td></tr></table>
</div>
<?php/*
<!-- The following is for PUBLIC providers  -->
*/ ?>
<div style="display:<?php echo $display2?>">
<table><tr><td>
<?php $this->showMessageRequiredDocsPublic()?>
</td></tr></table>
</div>


<?php /*
	taken out: 2005-01-11: The "no" option no longer applies.
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2" align="center">
			<fieldset class="go">
				<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
					<tr>
						<td>
						<span class="msg">
						Please Note:</span>
						<br>
						<span class="msgn">
						If you select "No" to one of the following,
						a box will be displayed in which to specify a motivation for your selection.
						</span>
						</td>
					</tr>
				</table>
			</fieldset>
	</td>
</tr>
</table>
*/ ?>

<br><br>
<?php/*
<!-- The following is for PUBLIC providers  -->
*/ ?>
<div style="display:<?php echo $display2?>">
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td class="oncolour"><b>Admission requirements:</b>
			<br><?php $this->showField("2_admpolicy") ?></td>
		</tr><tr>
			<td><div id="div_FLD_2_admpolicy" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("2_admpolicy_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_2_admpolicy_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "2_admpolicy") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("2_admpolicy_doc") ?>
			</div><br>
		</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Selection criteria and procedures:</b>
			<br><?php $this->showField("2_selectpolicy") ?></td>
		</tr><tr>
			<td><div id="div_FLD_2_selectpolicy" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("2_selectpolicy_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_2_selectpolicy_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "2_selectpolicy") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("2_selectpolicy_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Recognition of prior learning policy:</b>
			<br><?php $this->showField("2_learningpolicy") ?></td>
		</tr><tr>
			<td><div id="div_FLD_2_learningpolicy" style="display:none">
			Please explain why not:
			<br>
			<?php $this->showField("2_learningpolicy_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_2_learningpolicy_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "2_learningpolicy") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br>
			<?php $this->makeLink("2_learningpolicy_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
</div>
<?php/*
<!-- The following is for PUBLIC AND PRIVATE providers  -->
*/ ?>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Upload any other documentation which will indicate your compliance with this criterion.</b><br></td>
		</tr><tr>
			<td>
			Upload document electronically:
			<br>
			<?php $this->makeLink("2_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>

<?php /*  Take out: 2004-10-26

<tr>
	<td class="oncolour"><b>Programme prospectus:</b>
	<br><?php // $this->showField("2_prospectus") ?></td>
</tr><tr>
	<td><div id="div_FLD_2_prospectus" style="display:none">
	Please explain why not:
	<br>
	<?php // $this->showField("2_prospectus_whyNot") ?></div></td>
</tr><tr>
	<td class="oncolour"><b>Academic calendar:</b>
	<br><?php // $this->showField("2_calendar") ?></td>
</tr>
<tr>
	<td><div id="div_FLD_2_calendar" style="display:none">
	Please explain why not:
	<br>
	<?php // $this->showField("2_calendar_whyNot") ?></div></td>
</tr>
<tr>
	<td class="oncolour"><b>Recruitment strategy:</b>
	<br><?php // $this->showField("2_strategy") ?></td>
</tr><tr>
	<td><div id="div_FLD_2_strategy" style="display:none">
	Please explain why not:
	<br>
	<?php // $this->showField("2_strategy_whyNot") ?></div></td>
</tr>
*/ ?>

</fieldset>
<br><br>
</td></tr></table>
<?php /*
<script>
	improvement(document.defaultFrm.FLD_2_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
	checkCriteria (document.defaultFrm.FLD_2_criteria);
</script>
*/ ?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
