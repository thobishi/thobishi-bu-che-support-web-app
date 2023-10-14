<?php 

//setting hidden field for Past Meetings and Upload Minutes meetings
echo '<input type="hidden" name="gotoPast" value="0">';
echo '<input type="hidden" name="gotoUploadMinutes" value="0">';
?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	$title = "Please select a meeting to manage, or <b>\"Schedule a new meeting\"</b>: <br>";
	$today = date("Y-m-d");
	$SQL = <<<ACCESSDAYS
                SELECT s_value FROM `settings`
                WHERE s_key = 'heqc_meeting_days_access'
ACCESSDAYS;

$rs = mysqli_query($conn, $SQL);
        $rs->data_seek(0);
        $dataRow = $rs->fetch_array();
       $accessDays = $dataRow[0];
      
        
  //$accessDays = mysqli_result($rs);
   
//echo $SQL;

        // $rs= mysqli_query($conn, $SQL);
      //$accessDays = mysqli_fetch_assoc($rs,0);

        //$accessDays = mysqli_num_rows($rs);
	$SQL = <<<CURRMEETING
		SELECT * FROM `HEQC_Meeting` 
		WHERE DATE_ADD(heqc_to_date, INTERVAL '$accessDays' DAY) >= '$today' 
		OR heqc_start_date = '1000-01-01' 
		OR heqc_to_date >= '$today'
		ORDER BY heqc_start_date DESC
CURRMEETING;

       //  $rs= mysqli_query($conn, $SQL);
//echo $SQL;
	$this->displayHEQCMeetings($title, $SQL);
?>

</td></tr>

<tr><td>

<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
	echo "<br><br>";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

	$title = "The following list is of past meetings which have already been held, but have not yet been closed. Please select a meeting to upload its minutes and mark it as complete.";
	$SQL = "SELECT * FROM `HEQC_Meeting` WHERE DATE_ADD(heqc_to_date, INTERVAL '$accessDays' DAY) < '".date("Y-m-d")."' AND heqc_end_date ='1000-01-01' ORDER BY heqc_start_date DESC";
	$moveto = "_scheduleHEQCMeetingUploadMinutes";

//echo $SQL;

	$rs=mysqli_query($conn, $SQL);
	$this->displayHEQCMeetings($title, $SQL, $moveto);

?>

</td></tr>

<tr><td>

<?php 

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

	echo "<br><br>";
    
	$title = "The following is a list of past meetings. Please select to view documentation, applications and decisions relevant to this meeting:";
	$SQL = "SELECT * FROM `HEQC_Meeting` WHERE heqc_end_date != '1000-01-01' ORDER BY heqc_start_date DESC";
	//$rs = mysqli_query($conn, $SQL);
	$moveto = "_scheduleHEQCMeetingDisplayPast";
	//$rs=mysqli_query($conn, $SQL);
	
	//echo $SQL;
	$this->displayHEQCMeetings($title, $SQL, $moveto);


?>

</td></tr>
</table>
<br>
