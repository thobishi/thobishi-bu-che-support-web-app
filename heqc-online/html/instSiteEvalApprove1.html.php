<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Site visit reports from evaluators
		<br>
	</td>
</tr>
<tr>
	<td>
		<br>
		The Project Manager has indicated that this application is ready for approval by management.<br>
		The following tasks must be completed by you in the system:
		<ul>
			<li>Approve the evaluator reports.  If you do not approve the reports send the application back to the Project Administrator with instructions.</li>
			<li>Ensure that the evaluators have received payment.</li>
			<li>Indicate that you approve that this application is ready for the Directorate recommendation to be done.</li>
		</ul>
		The list below displays the evaluator reports. You are able to view these reports by clicking on the link.
		<br>
	</td>
</tr>
<tr>
	<td>
		<?php echo $this->buildSiteVisitListForEdit($site_proc_id,'default'); ?>
	</td>
</tr>
<tr>
	<td>
	<br>
	<span class="visi">
	Please check this box to indicate that you have approved the evaluator reports for this application and to indicate that it may proceed to the Directorate recommendation processing.
	<?php $this->showField("readyForRecomm"); ?>
	</span>
	</td>
</tr>
</table>
