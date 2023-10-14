<?php
	 $current_user_id = $this->currentUserID;
	 $grp = 7;
?>
<br><br>
<table>
<tr>
	<td>
	Please identify the user who will read the compliance with conditions document and check that all conditions have been addressed for a particular type e.g.
prior to commencement, short or long term.  Select the user by selecting their email address from the list below and clicking on Next in the Actions menu:<br><br>
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