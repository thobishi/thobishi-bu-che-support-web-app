<br>
<?php 

	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	//setting up numbering (it differs between public and private)
	$n = 1;
	$prov_type = $this->checkAppPrivPubl($current_id);

	$this->displayRelevantButtons($current_id, $this->currentUserID);
?>

<a name="application_form_question2"></a>
<table width="95%" border=0 align=center cellpadding="2" cellspacing="2"><tr><td>
<b>2. STUDENT RECRUITMENT, ADMISSION AND SELECTION: (Criterion 2)</b><br>
<br>
<fieldset>
<legend>Minimum standards</legend>
<?php echo $this->getTextContent("accForm6_v2", "minimumStandards"); ?>
</fieldset>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<?php if ($prov_type == 1)
	{
?>
	<tr>
		<td valign="top"><b>2.<?php echo $n++?></b></td><td valign="top"><b>Provide an account of the marketing strategies to be used for this programme.</b></td>
	</tr><tr>
		<td>&nbsp;</td><td valign="top"><?php $this->showField("2_1_comment") ?></td>
	</tr>
<?php 
	}
?>
<tr>
	<td valign="top"><b>2.<?php echo $n++?></b></td><td valign="top"><b>State the admission requirements for this programme.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_2_comment") ?></td>
</tr><tr>
	<td valign="top"><b>2.<?php echo $n++?></b></td><td valign="top"><b>Specify the selection criteria for this programme.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_3_comment") ?></td>
</tr>
<tr>
	<td valign="top"><b>2.<?php echo $n++?></b></td><td valign="top"><b>Provide the enrolment plan for this programme.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_4_comment") ?></td>
</tr>
<tr>
	<td valign="top"><b>2.<?php echo $n++?></b></td><td valign="top"><b>Describe how the objective of widening access to higher education will be promoted.</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_5_comment") ?></td>
</tr>
<tr>
	<td valign="top"><b>2.<?php echo $n++?></b></td><td valign="top"><b>Provide details of how RPL will be  applied (if applicable).</b></td>
</tr><tr>
	<td>&nbsp;</td><td valign="top"><?php $this->showField("2_6_comment") ?></td>
</tr>
</table>
<br><br>

<fieldset>
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>

<?php 
$display2 = "block";
?>
<div style="display:<?php echo $display2?>">
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Admission policy for this programme:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("2_admpolicy_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>RPL policy:
					<?php echo ($prov_type == 2) ? "(if different from institutional policy)" : ""; ?>:
					</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("2_selectpolicy_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>
</div>
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Any other documentation, including advertising of the programme, which will indicate your compliance with this criterion.</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("2_additional_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>



</fieldset>
<br><br>
</td></tr></table>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr></table>

<hr>