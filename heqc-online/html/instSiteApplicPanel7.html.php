<?php
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_app_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Review or update general attachments that will be attached to the site visit emails
		<br>
	</td>
</tr>
<tr>
	<td>
		The following documents are standard documents that apply to a site visit.  They will be emailed as attachments to 
		all panel members for each site visit.  Please review the documents to ensure that the latest version is listed here.
		If one of these documents is out of date click on <img src="images/ico_change.gif" alt="edit" /> next to the document name 
		to upload the latest version.
	</td>
</tr>
<tr>
	<td>
		<?php echo $this->buildSiteVisitAttachmentForEdit(); ?>
	</td>
</tr>

</table>

