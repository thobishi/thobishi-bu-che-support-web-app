
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
	<td class="specialh">
		<br>
		This application has been paid. Click Proceed to next user and process to proceed.
		<br>
	</td>
</tr>

</table>
<br>