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
		Site visit email attachment:
		<br>
	</td>
</tr>
<tr>
	<td>
		
		<table>
		<tr>
			<td>
				Description of the attachment
				<br>
			</td>
			<td>
			<?php $this->showField("attachment_title"); ?>
			</td>
		</tr>
		<tr>
			<td>
				Attachment:
			</td>
			<td>
				<?php $this->makeLink("attachment_doc"); ?>
			</td>
		</tr>
		</table>
	
	</td>
</tr>
</table>