<?php 
//setting hidden field for Past Meetings and Upload Minutes meetings
echo '<input type="hidden" name="gotoPast" value="0">';
echo '<input type="hidden" name="gotoUploadMinutes" value="0">';

	function displayACMeetings($title, $rs, $moveto="") {


$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
		//$rs = mysqli_query($conn);

		echo $title;
		echo '<table width="95%" border=0 align="left" cellpadding="2" cellspacing="2" align="center">';

		if (mysqli_num_rows($rs)) {
			echo "<tr class='oncolourb' valign='top'>";
			echo "<td width='20%'>Date AC Meeting held</td>";
			echo "<td>AC Meeting venue</td>";
			echo "</tr>";
			while ($row = mysqli_fetch_array($rs)) {
			 echo "<tr class='onblue'>";
			 echo "<td width='15%' align='center'>";
			 echo "<a href='javascript:setMeetingRow(\"".$row["ac_id"]."\");".$moveto."moveto(\"next\");'>";
			 echo $row['ac_start_date'];
			 echo "</a></td>";
			 echo "<td>".$row['ac_meeting_venue'];
			 echo "</td></tr>";
			}
		}
		else {
			echo "<tr class='onblue'><td align='center'> - No AC Meetings match this criteria - </td></tr>";
		}
		echo "</table>";
	}
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
                WHERE s_key = 'ac_meeting_days_access'
ACCESSDAYS;

 $rs = mysqli_query($conn, $SQL);
        $rs->data_seek(0);
        $dataRow = $rs->fetch_array();
        $accessDays = $dataRow[0]; 
    
	$SQL = <<<CURRMEETING
		SELECT * FROM `AC_Meeting` 
		WHERE ac_start_date >= '$today' 
		OR ac_start_date = '1000-01-01' 
		OR DATE_ADD(ac_to_date, INTERVAL '$accessDays' DAY) >= '$today'
		ORDER BY ac_start_date DESC
CURRMEETING;

//echo $SQL;
$rs = mysqli_query($conn, $SQL);

//echo $rs;

	displayACMeetings($title, $rs);

    
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

	$title = "The following list is of past meetings which have already been held, but have not yet been closed. Please select a meeting to upload its minutes and mark it as complete.";
	$SQL = "SELECT * FROM `AC_Meeting` WHERE DATE_ADD(ac_to_date, INTERVAL '$accessDays' DAY) < '".date("Y-m-d")."' AND ac_end_date ='1000-01-01' ORDER BY ac_start_date DESC";
	$moveto = "changeToUploadMinutes(1);";
	$rs = mysqli_query($conn, $SQL);
	displayACMeetings($title, $rs, $moveto);
	
	//echo $SQL;
	//echo $accessDays;
?>

</td></tr>

<tr><td>

<?php 

	echo "<br><br>";

	$title = "The following is a list of past meetings. Please select to view documentation, applications and decisions relevant to this meeting:";
	$SQL = "SELECT * FROM `AC_Meeting` WHERE ac_end_date != '1000-01-01' ORDER BY ac_start_date DESC";
	$moveto = "changeToPast(1);";
	$rs = mysqli_query($conn, $SQL);
	displayACMeetings($title, $rs, $moveto);
	//echo $SQL;

?>

</td></tr>
</table>
<br>

<script>
function setMeetingRow(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='AC_Meeting|'+val;
}

function changeToPast(num) {
	document.defaultFrm.gotoPast.value = num;
}

function changeToUploadMinutes(num) {
	document.defaultFrm.gotoUploadMinutes.value = num;
}
</script>
