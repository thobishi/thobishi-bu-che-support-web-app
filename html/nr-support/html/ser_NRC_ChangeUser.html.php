<?php
	 $grp = 3;
	 $grp_name = $this->db->getValueFromTable("sec_Groups", "sec_group_id", $grp, "sec_group_desc");
?>
	<div>
		<p>You have decided to send the current process to a colleague. To do this, select an email address from the list below:</p>
		<strong>Colleague: </strong>

<?php
		$dd = $this->makeDropdownOfGroupUsers($grp);
		echo $dd;
?>		
	<br><br>
	</div>
	<div>
		<p>Note: The users in the above list belong to the <?php echo $grp_name; ?> group.  Please contact the user administrator
		if you need to add someone to the list.</p>
	</div>
	
	<div>
		<p>Enter the request to your colleague below.  This text will be included in the email that will be sent to your colleague notifying
		them that there is a process to attend to.</p>
	</div>
	<div>	
<?php
	$this->showField("send_to_colleague_request"); 
?>
	</div>