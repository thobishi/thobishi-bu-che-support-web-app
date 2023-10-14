<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="right">A rated:</td>
	<td class="oncolour"><?php $this->showField("A_rated")?> </td>
</tr>
<tr>
	<td align="right">Name of current employer:</td>
	<td class="oncolour"><?php $this->showField("employer_ref") ?>&nbsp;Other:<?php $this->showField("new_employer")?></td>
</tr><tr>
	<td>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("employer")?> </td>
</tr><tr>
	<td align="right">Employer Type</td>
	<td class="oncolour"><?php $this->showField("Employer_type_ref")?> </td>
</tr>
<tr>
	<td align="right">Employer historical Status</td>
	<td class="oncolour"><?php $this->showField("historical_status_ref")?> </td>
</tr>
<tr>
	<td align="right">Employer Merge Status</td>
	<td class="oncolour"><?php $this->showField("merged_status_ref")?> </td>
</tr>
<tr>
	<td align="right">Department:</td>
	<td class="oncolour"><?php $this->showField("Department") ?></td>
</tr><tr>
	<td align="right">Job title:</td>
	<td class="oncolour"><?php $this->showField("Job_title") ?></td>
</tr><tr>
	<td align="right">Full/Part time:</td>
	<td class="oncolour"><?php $this->showField("Full_part") ?></td>
</tr><tr>
	<td align="right">Highest Qualification:</td>
	<td class="oncolour"><?php $this->showField("qualifications_ref") ?></td>
</tr><tr>
	<td align="right">Qualification Date:</td>
	<td class="oncolour"><?php $this->showField("Qualif_date") ?></td>
</tr>
<tr>
	<td align="right">Current sector:</td>
	<td class="oncolour"><?php $this->showField("Eval_sector_ref") ?></td>
</tr>
<tr>
	<td align="right">Organisational Type:</td>
	<td class="oncolour"><?php $this->showField("Organisation_type_ref") ?></td>
</tr>
<tr>
	<td align="right">ETQA:</td>
	<td class="oncolour"><?php $this->showField("ETQA_ref") ?></td>
</tr><tr>
	<td align="right">Teaching Experience:</td>
	<td class="oncolour"><?php $this->showField("Teaching_experience") ?></td>
</tr><tr>
	<td align="right">Research Experience:</td>
	<td class="oncolour"><?php $this->showField("Research_expereince") ?></td>
</tr><tr>
	<td align="right">Administration Experience:</td>
	<td class="oncolour"><?php $this->showField("Admin_experience") ?></td>
</tr><tr>
	<td align="right">Management Experience:</td>
	<td class="oncolour"><?php $this->showField("Manage_experience") ?></td>
</tr>
<tr>
	<td align="right">Other Experience:</td>
	<td class="oncolour"><?php $this->showField("Other_Experience_from") ?>
		Please describe:
		<?php $this->showField("Other_Experience_Desc") ?>
	</td>
</tr><tr>
	<td align="right">Total number of refereed publications:</td>
	<td class="oncolour"><?php $this->showField("Refereed_publ") ?></td>
</tr><tr>
	<td align="right">Willing to be trained:</td>
	<td class="oncolour"><?php $this->showField("Willingtobetrained") ?></td>
</tr>
</table>
<table width="85%" border=0 align="left" cellpadding="2" cellspacing="0">
	<tr>
		<td>
		Please attach the Curriculum Vitae for the applicant:
		<br> 
		<?php $this->makeLink("1_cv_doc") ?>
		<br>
		</td>
	</tr>
</table>
<br><br>
</td></tr></table>
