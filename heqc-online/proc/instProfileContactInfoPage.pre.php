<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$inst = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;

$SQL = "SELECT * FROM `institutional_profile_sites` WHERE institution_ref='".$inst."' AND main_site=1";
$RS = mysqli_query ($conn, $SQL);
while ($RS && ($row = mysqli_fetch_array($RS, MYSQL_ASSOC))) {
	$surname = $row["contact_surname"];
	$name = $row["contact_name"];
	$email = $row["contact_email"];
	$contact_nr = $row["contact_nr"];
	$contact_fax_nr = $row["contact_fax_nr"];
	$title = $this->getValueFromTable ("lkp_title", "lkp_title_id", $row["contact_title_ref"], "lkp_title_desc");
}
//$user = $this->getValueFromTable ("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");
/*
$surname = $this->getValueFromTable ("users", "user_id", $user, "surname");
$name = $this->getValueFromTable ("users", "user_id", $user, "name");
$email = $this->getValueFromTable ("users", "user_id", $user, "email");
$contact_nr = $this->getValueFromTable ("users", "user_id", $user, "contact_nr");
$title = $this->getValueFromTable ("users", "user_id", $user, "title_ref")
*/
$this->formFields["surname"]->fieldValue = $surname;
$this->formFields["name"]->fieldValue = $name;
$this->formFields["title_ref"]->fieldValue = $title;
$this->formFields["email"]->fieldValue = $email;
$this->formFields["contact_nr"]->fieldValue = $contact_nr;
$this->formFields["contact_fax_nr"]->fieldValue = $contact_fax_nr;
?>
