<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>

	<br>
	<?php echo $this->displayReaccredHeader ($reaccred_id)?>
	<br>

	</td>
</tr>
<tr>
	<td align="center">
	<br>
	You have reached the end of the processing of the above application.  Click on continue in the actions menu to close 
	this process and remove it from your list.  This application will still be accessible from reports and relevant menu items.
	</td>
</tr>

</table>
