<a name="application_form_question5"></a>
<br>
<?php

	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>5. TEACHING AND LEARNING STRATEGY: (Criterion 5)</b>
<br>
<br>
<fieldset>
<legend>Minimum standards</legend>
<?php echo $this->getTextContent("accForm9_v2", "minimumStandards"); ?>
</fieldset>
<br><br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>
<tr>
	<td valign="top"><b>5.1</b></td>
	<td valign="top"><b>Describe how the teaching and learning strategy reflects the institution's mission.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("5_1_teachstrategy_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>5.2</b></td>
	<td valign="top"><b>Explain the teaching methods, mode of delivery and the materials development for the achievement of the stated outcomes of the qualification.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("5_2_achieveoutcomes_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>5.3</b></td>
	<td valign="top"><b>Provide an overview of academic support programmes or assistance provided to students on the programme.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("5_3_academicsupport_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>5.4</b></td>
	<td valign="top"><b>Describe the mechanisms in place to monitor student progress, evaluate programme impact and effect improvement.</b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("5_4_studentprogress_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>5.5</b></td>
	<td valign="top"><b>If the institution offers the programme at different sites or modes of delivery,
	an account should be provided on how the quality of teaching and learning is maintained.
	Areas to be covered in the report should include:
		<ul>
		<li>Learning materials and study guides</li>
		<li>Details of student assistance and support.</li>
		</ul></b>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("5_5_teachinglearning_text") ?><br><br></td>
</tr>
<tr>
	<td valign="top"><b>5.6</b></td>
	<td valign="top"><b>Describe processes in place to identify and support inactive and/or underperforming students. </b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign="top"><?php $this->showField("5_6_inactivestudent_text") ?></td>
</tr>
<tr>
	<td>&nbsp;</td><td valign="top"><?php?></td>
</tr>
</table>

<fieldset>
<legend><b>The following documentation to be uploaded as it pertains to this programme</b></legend>

<?php
	$prov_type = $this->checkAppPrivPubl($current_id);
	$display1 = "none";
	$display2 = "none";
	if ($prov_type == 1) {
		$display1 = "Block";
	}
	if ($prov_type == 2) {
		$display2 = "Block";
	}

/*
<!-- The following is for private providers  -->
*/ ?>
<div style="display:<?php echo $display1?>">
	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Budget for the support and development of teaching and learning:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("5_budgetdevteachlearn_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Budget for the support and development of teaching technologies:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("5_budgetdevteachtech_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>
</div>

<!-- The following is for PUBLIC AND PRIVATE providers  -->

	<ul>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>The teaching and learning policy of the institution/faculty:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("5_policyteachlearn_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour"><b>Module outlines, student guides, and programme handbooks:</b></td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("5_moduleoutlines_doc") ?><br></td>
				</tr>
			</table>
		</li>
		<li class="topbold">
			<table width="85%" border=0 align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="oncolour">
					<b>Suggested documents. Please zip documents and upload electronically:</b>
						<ul>
							<li>Implementation of the teaching and learning policy</li>
							<li>Policy for the monitoring and evaluation of teaching and learning or equivalent</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td>Upload document electronically:<?php $this->makeLink("5_additional_doc") ?><br></td>
				</tr>
			</table>
		</li>
	</ul>



</fieldset>
<br><br>
</td>
</tr>
</table>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="right">[<a href="#">Back to Top</a>]</td>
</tr>
</table>

<hr>