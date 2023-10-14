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
		Verify all site information (dates, panel members and applications) before proceeding
		<br>
	</td>
</tr>
<tr>
	<td>
		Verify that all the information pertaining to this site visit is correct before proceeding with the communication of the site visit details 
		to all the relevant people.  The following will now take place on the next few pages:
		<ul>
			<li>The site visit schedules must be drawn up</li>
			<li>The institution will be notified of the site visit details</li>
			<li>The travel arrangements must be made</li>
			<li>All additional site visit documentation that must be emailed to the institution and evaluators must be reviewed</li>
			<li>Final letter of site visit details: site schedule, travel arrangements and additional attached documentation must be 
			emailed to the evaluators.</li>
		</ul>
	</td>
</tr>
<tr>
	<td><span class="visi">Note: If any details are incorrect then go back and correct them, re-assign applications or re-appoint evaluators.</span></td>
</tr>
<tr>
	<td>
		<?php echo $this->buildSiteVisitListForEdit($site_app_id); ?>
	</td>
</tr>
</table>
