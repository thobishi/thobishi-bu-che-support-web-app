<?php
	 $current_user_id = $this->currentUserID;
	 $grp = 39;
?>
<br><br>
<table>
<tr>
	<td>
	Please select the user that is responsible for drafting the letter to the institution for this programme.
	<br><br>
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