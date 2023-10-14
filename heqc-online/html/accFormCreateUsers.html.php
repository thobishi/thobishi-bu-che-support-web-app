<a name="application_form_admin_page"></a>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<b>Adding Colleague Details</b>
		<br><br>
		As the administrator of the system inside your institution you have four main functions:<br>
		<li>To give access to the system.
		<li>To control that the programme has been approved by the necessary internal structures.
		<li>To submit the application for programme accreditation to the HEQC.
		<li>To construct and update the institutional profile.
		<br><br>
		In order to start filling in the application for programme accreditation for this particular programme, you need to give the different individuals and structures/offices who will provide information and/or approve the programme internally access to the system.
		<br><br>
		In order to add contact details so that the programme coordinator (or any other person) can complete the rest of the application forms you create, enter the required information into the table below.	Please make sure to enter your colleagues' details correctly as the HEQC does not take responsibility for any typing errors that will break internal communication.

<?php 

		// Robin Naude 26 March 2007
		// Changing how one gets the administrator and institution because this is accessible from a menu item outside of an application
		//(previously accessible only from within the workflow of proc 5)

		$AdminRef = $this->currentUserID;
		$InstRef = $this->getValueFromTable("users", "user_id", $AdminRef, "institution_ref");


		// Check that this is the administrator of an institution. Only administrators may add or disable institutional users.
		$isAdmin = $this->sec_partOfGroup(4);

		if (!$isAdmin){
			echo "<br><br><br><b>You are not the HEQC Online Administrator for an Institution. Only the HEQC Online Administrator may manage users for their institution.</b><br>";
		}

		if ($isAdmin){
?>
			<br><br>
			<b>Current Institutional users:</b><br>
<?php 
			echo display_users(1,$InstRef);
?>
			<br><br>
			<b>Institutional users set to disabled so that they can no longer access the system:</b><br>
<?php 
			echo display_users('0,2,3',$InstRef);
			
			
			if ($this->getValueFromCurrentTable ("user_id") == "") {
?>
			<br>
			<br>
				<b>Fill in the form below and click "save" to add a user:</b><br>
				<table width="90%">
				<input type=hidden name="FLD_password" value="5486d832202c473b">
<?php 
				$this->formFields["active"]->fieldValue = 1;
				$this->formFields["active"]->fieldType = 'HIDDEN';
				$this->showfield("active");

			}
			else
			{
?>
				<b>Edit the form below and click "save" to update the user:</b><br>
				<table width="90%">
<?php 
			}
?>
			<input type=hidden name="cmd_clear" value="1">
			<input type=hidden name="FLD_institution_ref" value="<?php echo $InstRef?>">

			<tr>
				<td>Title:</td><td><?php echo $this->showField("title_ref"); ?></td>
			</tr>
			<tr>
				<td>Surname:</td><td><?php echo $this->showField("surname"); ?></td>
			</tr>
			<tr>
				<td>Name:</td><td><?php echo $this->showField("name"); ?></td>
			</tr>
			<tr>
				<td>Email:</td><td><?php echo $this->showField("email"); ?></td>
			</tr>
			<tr>
				<td>Contact No:</td><td><?php echo $this->showField("contact_nr"); ?></td>
			</tr>
<?php 
			if ($this->getValueFromCurrentTable ("user_id") > "") {
?>
			<tr>
				<td>Status:</td>
				<td>
					<span class="specials">Setting a user to disabled is equivalent to deleting a user.  They will no longer appear in your list.
					They will not be able to login.</span>
					<?php echo $this->showField("active"); ?>
				</td>
			</tr>
<?php } ?>
			<tr>
				<td></td>
				<td><input type=button value="Save" onClick="javascript:document.defaultFrm.VIEW.value=0;checkSave();"><input type=button value="Cancel" onClick="javascript:document.defaultFrm.VIEW.value=-1;document.defaultFrm.CHANGE_TO_RECORD.value='NEW';moveto('stay');"></td>
			</tr>
			</table>
<?php 
		} // end if ($isAdmin)
?>
	</td>
</tr>
</table>
<?php
function display_users($status, $InstRef){
	$html = <<<HTM
			<table width="90%" border=1>
			<tr>
				<td>Name</td>
				<td>Email</td>
				<td>Contact</td>
				<td>Action(s)</td>
			</tr>
HTM;

                        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                        if ($conn->connect_errno) {
                            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                            printf("Error: %s\n".$conn->error);
                            exit();
                        }
			$SQL = "SELECT * FROM users WHERE active IN (".$status.") AND institution_ref = ".$InstRef." ORDER BY surname, name";
			
			
			$RS = mysqli_query ($conn, $SQL);
			while ($row = mysqli_fetch_array ($RS)) {
			$html .= <<<HTM
				<tr>
					<td>$row[surname], $row[name]</td>
					<td>$row[email]</td>
					<td>$row[contact_nr]</td>
					<td>[<a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value={$row["user_id"]}; moveto('stay');">Edit</a>]
					</td>
				</tr>
HTM;
			}

			$html .= "</table>";
			return $html;
}
?>
<script>

try {
	document.defaultFrm.VIEW.value = -1;
}catch(e){}

</script>
