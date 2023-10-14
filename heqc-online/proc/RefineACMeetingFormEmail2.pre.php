<?php 
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
        
$SQL = "SELECT minutes_doc FROM AC_Meeting WHERE is_last_meeting=1";
$rs = mysqli_query($conn, $SQL);
if (mysqli_num_rows($rs) > 0){
	$row = mysqli_fetch_array($rs);
	$SQL = "SELECT * FROM documents WHERE document_id=".$row[0];
	$rs = mysqli_query($conn, $SQL);
	$rrow = mysqli_fetch_array($rs);
	$SQL = "INSERT INTO documents (creation_date,last_update_date,document_name,document_url) VALUES('".$rrow["creation_date"]."','".$rrow["last_update_date"]."','".$rrow["document_name"]."','".$rrow["document_url"]."')";
	$rs = mysqli_query($conn, $SQL);
	$SQL = "UPDATE AC_Meeting SET prev_minutes_doc=".mysqli_insert_id($conn)." WHERE ac_id=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$rs = mysqli_query($conn, $SQL);
}


$SQL = "SELECT * FROM AC_Members WHERE ac_mem_active=1";
$RS = mysqli_query($conn, $SQL);
$from = "";
$subject = "Confirm AC Meeting date - ".$this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date");
$message = ($this->getTextContent ("RefineACMeetingFormEmail1", "Confirm AC Date"));
$filelist = "";
WHILE ($ROW = mysqli_fetch_array($RS)){
	$to = $ROW["ac_mem_email"];
	$this->mimemail ($to, $from, $subject, $message, $filelist);
}
?>
