<script>
function sendMail(){
	document.defaultFrm.sendMail.value = '1';
	moveto('stay');
}
</script>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
<?php 
if (isset($_POST['sendMail']) && $_POST['sendMail']){
	$SQL = "SELECT * FROM AC_Members,lnk_ACMembers_ACMeeting WHERE ac_mem_active=1 AND ac_member_ref=ac_mem_id AND lnk_confirmed=-1 AND ac_meeting_ref=?";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID);
	$sm->execute();
	$RS = $sm->get_result();

	//$RS = mysqli_query($SQL);
	$from = "HEQC Accreditation Directorate";
	$subject = "Confirm AC Meeting date - ".$this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,"ac_start_date");
	$message = ($this->getTextContent ("RefineACMeetingFormEmail1", "Confirm AC Date"));
	$filelist = "";
	WHILE ($ROW = mysqli_fetch_array($RS)){
		$to = $ROW["ac_mem_email"];
		$this->mimemail ($to, $from, $subject, $message, $filelist);
	}
	echo "<strong>A reminder has been sent to the AC Member.</strong><br><br>";
}
?>

<input type='hidden' name='sendMail' value='0'>
Below is the complete list of the AC members. Check who has responded to the reminder about the meeting on <strong><?php echo $this->formFields["ac_start_date"]->fieldValue?></strong>. Tick in the boxes to indicate who is coming to the meeting. 
<br><br>
If you would like to send reminders to the members who have not responded to the e-mail yet, click <a href="javascript:sendMail();">here</a>.
<br><br>
The meeting needs a 2/3 quorum to take place.
<br><br>
<ul>
<li>Once the quorum has been confirmed you may start preparing the documentation and logistics for the meeting.</li>
<li>If there are members who have not responded yet, please send them urgent reminders.</li>
<li>Should you not manage to achieve quorum for the planned date, introduce a comment line on the administration screen, and alert your supervisor.</li>
</ul>
</td>
</tr>
<tr>
<td>
<table cellpadding="2" cellspacing="2" border="1">
<?php 
$headingArray = array();
array_push($headingArray,"AC Member");
array_push($headingArray,"Confirmed");

$refDispArray = array();
//array_push($refDispArray,"ac_mem_title_ref");
array_push($refDispArray,"ac_mem_name");
array_push($refDispArray,"ac_mem_surname");

$dispFields = array();
array_push($dispFields,"lnk_confirmed");
$this->makeGRID("AC_Members",$refDispArray,"ac_mem_id","ac_mem_active = 1","lnk_ACMembers_ACMeeting","lnk_id","ac_member_ref","ac_meeting_ref",$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,$dispFields,$headingArray,"javascript:checkMembers();");
?>
</table>
</td>
</tr></table>
</td></tr></table>
<script>
function checkMembers(){
	var obj = document.defaultFrm;
	var docArrChecked = new Array();
	var docArr = new Array();
	var docArrCount = 0;
	var docArrCheckedCount = 0;
	for (i=0; i<obj.elements.length; i++) {
		if ((obj.elements[i].type == "radio") && obj.elements[i].checked) {
			docArr[docArrCount] = obj.elements[i];
			docArrCount++;
			if (obj.elements[i].value == 1) {
				docArrChecked[docArrCheckedCount]	= obj.elements[i];
				docArrCheckedCount++;
			}
		}
	}
	if (docArrChecked.length/docArr.length >= 0.66 ) {
		showHideAction("next", true);
	}else {
		showHideAction("next", false);
	}	
}
</script>
