

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td align=center class="special1" colspan="2">
<br>
<span class="specialb">
	
	
	<h2>SECTION E: STUDENT RECRUITMENT, ADMISSION AND SELECTION</h2>
</span>
</td></tr>
</table>
<br>

<a name="application_form_question3"></a>
<br>
<?php

	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites_v4($current_id); }
	$this->displayRelevantButtons($current_id, $this->currentUserID);
	$prov_type = $this->checkAppPrivPubl($current_id);
	//get HEI_id of user, so we can display declaration if they belong to CHE
	$hei_id = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");


?>



<br>
<table width='95%' cellpadding='2' cellspacing='2' align='center' border='0'>

	<tr>
		<td valign="top"><b></b></td>
		<td valign="top"><b>PROVIDE CONCISE RESPONSES TO THE FOLLOWING QUESTIONS.</b></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>1. State the admission requirements for this programme / qualification: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("2_2_comment");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>2.	Specify the selection criteria for this programme / qualification: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("2_3_comment");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>3.	Describe how the objective of widening access to higher education will be promoted: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("2_5_comment");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>4.	Provide details of how recognition of prior learning (RPL) will be applied for this programme / qualification, including the assessment process: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("2_6_comment");?></td>
	</tr>

	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>5.	If RPL is not envisaged for this programme / qualification, please indicate the reason/s for this: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("6_policies_rpl_whyNot");?></td>
	</tr>


	<tr>
		<td ALIGN=RIGHT valign="top" width="35%"><b>6.	Provide details of how Credit Accumulation and Transfer (CAT) will be applied in this programme / qualification: </b></td>
		<td valign="top" class="oncolour"><?php $this->showField("accumulation_transfer_details");?></td>
	</tr>

	<tr>
		<td>
		Refer to the accompanying <a href="documents/GUIDELINES FOR COMPLETING THE APPLICATION FOR PROGRAMME ACCREDITATION AND QUALIFICATION REGISTRATION.docx" target="_blank">
				guidelines </a> for completion of this form
			<img src="images/word.gif">
		</td>
	</tr>

</table>