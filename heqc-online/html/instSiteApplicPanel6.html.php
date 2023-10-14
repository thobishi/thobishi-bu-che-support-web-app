<?php
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_app_id); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Upload final site visit schedules and travel arrangements:
		<br>
	</td>
</tr>
<tr>
	<td>
		The final communication to the evaluators will be in the form of an email per site visit.  
		Each evaluator involved in conducting the site visit will receive the email. The process to obtain all the information for these
		site visits is:
		<ul>
			<li>Upload the finalised site visit schedules and capture the travel arrangements per site</li>
			<li>Review all the documents that must be emailed as attachments (next page)</li>
			<li>Review the email content per site that will be sent to each evaluator (following pages)</li>
		</ul>
		Click on <img src="images/ico_print.gif" alt="edit" /> next to the site name to upload relevant documents.
	</td>
</tr>
<tr>
	<td>
		<?php echo $this->buildSiteVisitListForEdit($site_app_id,'final'); ?>
	</td>
</tr>
</table>
