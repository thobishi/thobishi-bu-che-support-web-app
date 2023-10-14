<?php 
	 $current_user_id = $this->currentUserID;
?>
<br><br>
<table>
<tr>
	<td>
	Once you have completed your part, send it to the next person that needs to be involved in the process. To do this, select an email address from the list below:<br><br>
	<b>Colleague: </b>
<?php 
/*	$AdminRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");
	$InstRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id");

	$this->createInputFromDB("user_ref","SELECT", "users", "user_id", "email",1,"institution_ref = ".$InstRef);
	$this->formFields["user_ref"]->fieldValue = $AdminRef;
	$this->showField("user_ref");
*/
	$SQL = "SELECT email, user_id FROM users, sec_UserGroups, sec_Groups WHERE sec_group_ref=sec_group_id AND sec_group_desc='Checklisting' AND sec_user_ref=user_id AND active = 1 AND user_id != ?";
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
        $sm = $conn->prepare($SQL);
        $sm->bind_param("s", $current_user_id);
        $sm->execute();
        $RS = $sm->get_result();
	
	//$RS = mysqli_query($SQL);
?>
	<select name="user_ref">
<?php 
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		$sel = "";
		if ($this->currentUserID == $row["user_id"]) $sel = "SELECTED";
		echo '<option value="'.$row["user_id"].'" '.$sel.'>'.$row["email"].'</option>';
	}
?>
	</select>
<br><br>
	</td>
</tr>
</table>
