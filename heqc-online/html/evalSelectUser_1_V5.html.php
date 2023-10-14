<?php
	 $current_user_id = $this->currentUserID;
	 $grp = 42;

?>
<br><br>
<table>
<tr>
	<td>
	Once  you have completed your part, send it to the next person that needs to be involved in the process. To do this, select an email address from the list below:<br><br>
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
		<span class="visi">Note: The users in the above list belong to the <?php echo $grp_name; ?> group</span>
	</td>
</tr>
</table>