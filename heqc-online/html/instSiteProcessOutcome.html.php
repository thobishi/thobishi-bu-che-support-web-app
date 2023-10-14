<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id); ?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<span class="loud">
			Approve outcome, upload the HEQC decision letter for institution and enter the due date for deferrals, conditions or representations.
		</span>
		<br>
	</td>
</tr>
<tr>
	<td>
	The outcome of the proceedings for this programme is: 
	<hr>
			<?php echo $this->display_site_outcomes('heqc', $site_proc_id, 'int'); ?>
	<hr>
	</td>
</tr>
<tr>
	<td>
		Please approve that the outcome displayed above matches the outcome in the decision letter by checking this box <?php $this->showField('decision_approved_ind'); ?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<br>
		<table width="95%" border=0>
		<tr>
			<td valign="top">Upload the HEQC decision letter that was sent to the institution</td>
			<td><?php $this->makeLink("decision_doc"); ?></td>
		</tr>
		<tr>
			<td>Enter the due date (for deferrals, conditions or representations):</td>
			<td><?php $this->showField("heqc_decision_due_date"); ?></td>
		</tr>
		<tr>
			<td valign="top">Upload the outcome acceptance letter from the institution</td>
			<td><?php $this->makeLink("inst_outcome_accept_doc"); ?></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
