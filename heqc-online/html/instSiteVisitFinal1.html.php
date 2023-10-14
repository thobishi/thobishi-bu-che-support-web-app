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
		Enter site visit details:
		<br>
	</td>
</tr>
</table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>Official letter to panel members
	</td>
	<td>
		<?php $this->makeLink("panel_members_letter_doc"); ?>
	</td>
</tr>
<tr>
	<td>Final site visit schedule
	</td>
	<td>
		<?php $this->makeLink("schedule_doc"); ?>
	</td>
</tr>
<tr>
	<td>
		Any other information specific to this site visit that must be included in the email 
		that will be sent to the panel members
		e.g. Travel and accomodation arrangements or directions
		<br>
	</td>
	<td>
		<?php $this->showField("final_arrangements"); ?>
	</td>
</tr>
</table>