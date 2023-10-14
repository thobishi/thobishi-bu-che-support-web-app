<a name="application_form_question5"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>5. TEACHING AND LEARNING STRATEGY: (Criterion 5)</b> [<?php $this->popupContent("Help", "MainHelp", "", true) ?>]<br>
<br>
Taking into account the required minimum standards for this item and the required supporting evidence, please answer the following questions.
<br><br>
<b>Minimum standards:</b> [<?php $this->popupContent("Minimum standards", "MinHelp", "", true) ?>]
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>5.1</b></td><td valign="top"><b>What activities does the programme have to promote student learning?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("5_1_comment") ?></td>
</tr><tr>
	<td valign="top"><b>5.2</b></td><td valign="top"><b>How does the teaching and learning strategy reflect the institutional type (as reflected in the institution's mission), mode(s) of delivery and student composition?</b> </td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("5_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>5.3</b></td><td valign="top"><b>How does the teaching and learning strategy ensure that the teaching and learning methods of the programme are appropriate to its contents and learning outcomes?</b> </td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("5_3_comment") ?></td>
</tr><tr>
	<td valign="top"><b>5.4</b></td><td valign="top"><b>To what extent does the teaching and learning strategy make provision for staff to upgrade their teaching methods? </b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("5_4_comment") ?></td>
</tr><tr>
	<td valign="top"><b>5.5</b></td><td valign="top"><b>What mechanisms to monitor progress, evaluate impact, and effect improvement are included in the strategy for teaching and learning?</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("5_5_comment") ?></td>
</tr></table>
<br><br>

<?php /*
<br><br>

<div id="notComply" style="display:none">
	<b>*Please suggest improvement:</b>
</div>
<div id="comply" style="display:Block">
<b>Taking into account the required minimum standards, please answer all aspects of question number 4:</b>
</div>
<?php//$this->showField("4_comment") ?>
<br><br>

<b>Please tick in the box the extent to which this programme meets the minimum standards for teaching and learning strategy:</b><br>
<?php // $this->showField("4_criteria") ?>
<br><br>
*/ ?>

<b>In the space below indicate to what extent does your programme comply with the criterion 5:</b><br>
<?php $this->showField("5_criteria") ?>
<br><br>

<?php /*
<b>Taking into account the evidence tables and the documentation attached, please justify your self-evaluation.</b>
<?php//$this->showField("4_self_evaluation") ?>
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
<!-- The following is for private providers  -->
<div style="display:<?php echo $display1?>">
	<ul>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Policy for the monitoring and evaluation of teaching and learning or equivalent:</b>
			<br><?php $this->showField("5_policy") ?></td>
		</tr><tr>
			<td colspan="2"><div id="div_FLD_5_policy" style="display:none">
			Please explain why not:
			<br><?php $this->showField("5_policy_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_5_policy_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "5_policy") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br> 
			<?php $this->makeLink("5_policy_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Budget for the support and development of teaching technologies:</b>
			<br><?php $this->showField("5_budget1") ?></td>
		</tr><tr>
			<td colspan="2"><div id="div_FLD_5_budget1" style="display:none">
			Please explain why not:
			<br><?php $this->showField("5_budget1_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_5_budget1_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "5_budget1") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br> 
			<?php $this->makeLink("5_budget1_doc") ?>
			</div><br>
			</td>
		</tr>
		</table>
		</li>
</div>
		<li class="topbold">
		<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="oncolour"><b>Budget for the support and development of teaching and learning.</b>
			<br><?php $this->showField("5_budget_suppport") ?></td>
		</tr><tr>
			<td colspan="2"><div id="div_FLD_5_budget_suppport" style="display:none">
			Please explain why not:
			<br><?php $this->showField("5_budget_suppport_whyNot") ?></div></td>
		</tr><tr>
			<td><div id="div_FLD_5_budget_suppport_doc" style="display:<?php echo ((($this->view) && ($this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "5_budget_suppport") == 2))?("Block"):("none"))?>">
			Upload document electronically:
			<br> 
			<?php $this->makeLink("5_budget_suppport_doc") ?>
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
			<?php $this->makeLink("5_additional_doc") ?>
			<br>
			</td>
		</tr>
		</table>
		</li>
	</ul>

<?php /*
	Take out: 2004-10-26
<tr>
	<td class="oncolour"><b>Staff development policy:</b>
	<br><?php // $this->showField("4_development") ?></td>
</tr>
<tr>
	<td colspan="2"><div id="div_FLD_4_development" style="display:none">
	Please explain why not:
	<br><?php//$this->showField("4_development_whyNot") ?></div></td>
</tr>
<tr>
	<td class="oncolour"><b>Number and distribution of support staff:</b>
	<br><?php // $this->showField("4_support") ?></td>
</tr>
<tr>
	<td colspan="2"><div id="div_FLD_4_support" style="display:none">
	Please explain why not:
	<br><?php//$this->showField("4_support_whyNot") ?></div></td>
</tr>
<tr>
	<td class="oncolour"><b>Policy on staff evaluation:</b>
	<br><?php//$this->showField("4_evaluation") ?></td>
</tr>
<tr>
	<td colspan="2"><div id="div_FLD_4_evaluation" style="display:none">
	Please explain why not:
	<br><?php//$this->showField("4_evaluation_whyNot") ?></div></td>
</tr>
<tr>
	<td class="oncolour"><b>Administrative and technical resources allocated to it:</b>
	<br><?php//$this->showField("4_admintech") ?></td>
</tr>
<tr>
	<td colspan="2"><div id="div_FLD_4_admintech" style="display:none">
	Please explain why not:
	<br><?php//$this->showField("4_admintech_whyNot") ?></div></td>
</tr>

*/ ?>

</fieldset>
<br><br>
</td></tr></table>
<?php /*
<script>
	improvement(document.defaultFrm.FLD_4_criteria, document.all.notComply, document.all.comply);
	tryExpandWhyNot();
	checkCriteria (document.defaultFrm.FLD_4_criteria);
</script>
*/ ?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>
