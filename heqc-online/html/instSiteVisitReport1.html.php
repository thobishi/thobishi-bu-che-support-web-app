<?php
	$site_visit_id = $this->dbTableInfoArray["inst_site_visit"]->dbTableCurrentID;
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
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
		Upload site visit report:
		<br>
	</td>
</tr>
<tr>
	<td>
		The chairman who conducted the site visit is responsible for compiling the site visit report and making it available to the CHE.
		Please upload the site visit report for the above site.
	</td>
</tr>
</table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>Site visit report<br>
	</td>
	<td>
		<?php $this->makeLink("site_visit_report_doc"); ?>
	</td>
</tr>
</table>