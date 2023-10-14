<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$SQL = "SELECT ac_mem_email FROM lnk_ACMembers_ACMeeting,AC_Members WHERE ac_member_ref=ac_mem_id AND lnk_confirmed <> 1 AND ac_meeting_ref=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
//$SQL = "SELECT * FROM AC_Members WHERE ac_mem_active=1";
$RS = mysqli_query($conn, $SQL);
$from = "";
$subject = "Confirnmation of not attending AC Meeting on - ".$this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date");
$message = ($this->getTextContent ("RefineACMeetingForm9", "AC_Member_Not_Attend"));
$filelist = "";
WHILE ($ROW = mysqli_fetch_array($RS)){
	$to = $ROW["ac_mem_email"];
	$this->mimemail ($to, $from, $subject, $message, $filelist);
}
?>
