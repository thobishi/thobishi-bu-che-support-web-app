<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
        
$SQL = "UPDATE AC_Meeting set is_last_meeting=0";
$rs = mysqli_query($conn, $SQL);
$SQL = "UPDATE AC_Meeting set is_last_meeting=1 WHERE ac_id=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
$rs = mysqli_query($conn, $SQL);

$SQL = "SELECT * FROM AC_Members WHERE ac_mem_active=1";
$RS = mysqli_query($conn, $SQL);
$from = "HEQC Accreditation Directorate";
$subject = "AC Meeting scheduled for ".$this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date");
$message = nl2br ($this->getTextContent ("RefineACMeetingForm3", "AC_Member_Refined"));
$minutes = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"prev_minutes_doc");
$agenda = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"agenda_doc");
$site = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"site_visit_doc");
$paper = $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"paper_eval_doc");
$filelist = array();
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $minutes,"document_url"),$this->getValueFromTable("documents", "document_id", $minutes,"document_name")));
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $agenda,"document_url"),$this->getValueFromTable("documents", "document_id", $agenda,"document_name")));
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $site,"document_url"),$this->getValueFromTable("documents", "document_id", $site,"document_name")));
array_push($filelist,array(WRK_DOCUMENTS."/".$this->getValueFromTable("documents", "document_id", $paper,"document_url"),$this->getValueFromTable("documents", "document_id", $paper,"document_name")));

$AC_attendance_list = $this->generateReport("generateAttendanceList(".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID.")");
$ext = strrchr($AC_attendance_list,".");
copy($AC_attendance_list, $this->TmpDir."AC_meeting_Attendance_list_".$this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date").$ext);
unlink($AC_attendance_list);
$AC_attendance_list = $this->TmpDir."AC_meeting_Attendance_list_".$this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date").$ext;
array_push($filelist, $AC_attendance_list);

//while ($ROW = mysqli_fetch_array($RS)){
//	$to = $ROW["ac_mem_email"];
//$this->mimemail ($this->getDBsettingsValue("che_registry_email"), $from, $subject, $message, $filelist);
//$this->showAddressesACmembers($this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID);
//}
$this->mimemail ("louwtjie@octoplus.co.za", $from, $subject, $message, $filelist);
?>
