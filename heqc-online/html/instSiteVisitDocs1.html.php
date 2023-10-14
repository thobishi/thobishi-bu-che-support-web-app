<?php
	$site_visit_id = $this->dbTableInfoArray["inst_site_visit"]->dbTableCurrentID;
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("inst_site_app_proceedings", "inst_site_app_proc_id",$site_app_id, "institution_ref");
	$html = "";
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteVisitTableTop($site_visit_id); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Upload site visit documents to be emailed with site visit communication:
		<br>
	</td>
</tr>
</table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td width="30%" align="top">
		<b>Site visit schedule</b><br />
		<span class="specialsi">
			(This site visit schedule will be emailed to the institution.
			Once the final schedule is uploaded it will also be emailed as an attachment to the evaluators conducting the site visit.)
		</span>
	</td>
	<td>
		<?php $this->makeLink("schedule_doc"); ?>
	</td>
</tr>
<tr>
	<td align="top">
		<b>Upload official notification letter to the institution</b>
		<br />
		<span class="specialsi">
			(This is the formal letter to the institution.  It is customised per institution containing the reasons for the site 
			visit and all the site visit specific information.)
			
		</span>
	</td>
	<td>
		<?php $this->makeLink("institution_notification_doc"); ?>
	</td>
</tr>
</table>