<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		In cases where a programme is not recommended for accreditation, the institution may make representations. These representations: 
		<ol>
		<li>should be in writing </li>
		<li>should not repeat the contents of the original application </li>
		<li>should be confined to the information provided by the institution during the evaluation process: that is, information that was part of the institution's application and that was made available to the panel of evaluators </li>
		<li>should address the report and recommendations of the Accreditation Committee focusing on any errors and omissions that may have occurred in the evaluation process </li>
		<li>should reach the HEQC within 21 working days of the date of the letter </li>
		</ol>
		Should the decision not to accredit a programme be upheld, the institution may not submit the same programme for accreditation for a period of two years.	
	</td>
</tr>
<tr>
	<td>
		Is there a representation? <?php $this->showField('representation_ind'); ?>	
	</td>
</tr>
<tr>
	<td>
		<b>If yes: </b>
	</td>
</tr>
<tr>
	<td>
		Upload the representation: <?php $this->makeLink('representation_doc'); ?>
	</td>
</tr>
<tr>
	<td>
		Date that the representation was submitted: <?php $this->showField('representation_submission_date'); ?>
	</td>
</tr>
<tr>
	<td>
		<b>If no click on Next to close the proceedings </b>
	</td>
</tr>
</table>
<br>