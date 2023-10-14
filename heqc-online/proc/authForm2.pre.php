<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$user_id = $this->dbTableInfoArray["users"]->dbTableCurrentID;
$inst_ref = $this->getValueFromTable("users", "user_id", $user_id, "institution_ref");
$usr_email = $this->getValueFromTable("users", "user_id", $user_id, "email");
$public_nursing_college = $this->getValueFromTable("users", "user_id", $user_id, "public_nursing_college");

$outcome = "";
$other = $this->getValueFromTable("users", "user_id", $user_id, "institution_name");

if (isset($_POST["doAccept"]) && ($_POST["doAccept"] == 1)) {
	$valid = false;

	// Institution has been selected
	if (isset($_POST["FLD_institution_ref"]) && $_POST["FLD_institution_ref"] > 0) $valid = true;

	// New institution is to be inserted.
	if (isset($_POST["new_inst"]) && ($_POST["new_inst"] == 1 || $_POST["new_inst"] == 'on')){  // value is set to on in enableinst javascript onclick.
		if ($public_nursing_college == 2){
			$inst_type = "PN";
		} else {
			$inst_type = "PR";
		}
		$hei_code = $this->getLastHEIcode($inst_type);
		$SQL = "INSERT INTO `HEInstitution` (HEI_name, priv_publ, HEI_code) VALUES ('".mysqli_real_escape_string($conn, $other)."', 1, '".$hei_code."')";

		$errorMail2 = false;
		$rs = mysqli_query($conn, $SQL);
		if ($rs){
			$new_inst_id = mysqli_insert_id($conn);
			$this->setValueInTable("users", "user_id", $user_id, "institution_ref", $new_inst_id);
			$valid = true;
		} else {
			$errorMail2 = true;
		}
		$this->writeLogInfo(10, "SQL-INSREC", $SQL."  --> ".mysqli_error($conn), $errorMail2);
	}

	// Only email if new institution was successfully inserted or the user applied for an existing institution.
	if ($valid === true){
		$outcome = "accepted";
		$to = $user_id;
		$message = $this->getTextContent ("authForm2", "username and password has been created");
		$this->misMail ($to, "Registration application outcome", $message);

		$this->setValueInTable("users", "user_id", $user_id, "active", 1);
		$this->setValueInTable("users", "user_id", $user_id, "registration_date", date("Y-m-d"));

		$SQL = "INSERT INTO `sec_UserGroups` (sec_group_ref, sec_user_ref) VALUES ('4', '".$user_id."')";
		$errorMail1 = false;
		$rs = mysqli_query($conn, $SQL) or $errorMail1 = true;
		$this->writeLogInfo(10, "SQL-INSREC", $SQL."  --> ".mysqli_error($conn), $errorMail1);
	}

}

if (isset($_POST["doAccept"]) && ($_POST["doAccept"] == 2)) {
		$outcome = "declined";
		$to = $user_id;
		$message = $this->getTextContent ("authForm2", "application for user registration has been declined");
		$this->misMail ($to, "Registration application outcome", $message);
		
		$rand_email = $usr_email . " (rejected " . date("Y-m-d") . ") ". rand(1,999);
		// Change email address (it is unique) to prevent an error occurring when the user tries to re-register after a rejection.
		$this->setValueInTable("users", "user_id", $user_id, "email",$rand_email );
}

?>
