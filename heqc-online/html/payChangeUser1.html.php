<?php
	 $current_user_id = $this->currentUserID;
	 $grp = 32;
?>
<br><br>
<table>
<tr>
	<td>
	You have decided to send the current process to a colleague. To do this, select an email address from the list below:<br><br>
	<b>Colleague: </b>
<?php
	$dd = $this->makeDropdownOfGroupUsers($grp);
	echo $dd;
?>
<br><br>
	</td>
</tr>
<tr>
	<td>
		<?php $grp_name = $this->getValueFromTable("sec_Groups","sec_group_id",$grp,"sec_group_desc"); ?>
		<span class="visi">Note: The users in the above list belong to the <?php echo $grp_name; ?> group.  Please contact the user administrator
		if you need to add someone to the list.</span>
	</td>
</tr>
<tr>
	<td>
		<br />
		Enter the request to your colleague below.  This text will be included in the email that will be sent to your colleague notifying
		them that there is a process to attend to.
		<?php $this->showField("request"); ?>
	</td>
</tr>
</table>