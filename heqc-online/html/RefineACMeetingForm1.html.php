<script language="JavaScript" src="js/popupcalendar.js"></script>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
The AC Meeting is scheduled for 
<?php 
	echo "<b>".$this->formFields["ac_start_date"]->fieldValue."</b>";
?>
<br><br>
Now you must start making the necessary arrangements for the meeting. Use the tables below to record the information.
<br><br>
<b>Logistic arrangements for the AC Meeting:</b>
<br><br>
Catering arrangements:<br><br>
<table cellpadding="2" cellspacing="2" border="1" width="90%">
<tr>
<td class="oncolourb" align="center">Catering</td>
<td class="oncolourb" align="center">Done</td>
<td class="oncolourb" align="center">Date Requisition</td>
<td class="oncolourb" align="center">Responsible Person</td>
</tr>
<tr>
<td>Tea</td>
<td align="center"><?php $this->showField('ac_tea_done');?></td>

<td><?php $this->showField('ac_tea_date_requisition');?></td>
<td><?php $this->showField('ac_tea_responsible');?></td>
</tr>
<tr>
<td>Lunch</td>
<td align="center"><?php $this->showField('ac_lunch_done'); ?></td>
<td><?php $this->showField('ac_lunch_date_requisition'); ?></td>
<td><?php $this->showField('ac_lunch_responsible'); ?></td>
</tr>
</table>
<br><br>
Parking and Car arrangements:<br><br>
<table cellpadding="2" cellspacing="2" border="1" width="90%">
<?php 
$headingArray = array();
array_push($headingArray,"AC Member");
array_push($headingArray,"Parking Bay");
array_push($headingArray,"Date");
array_push($headingArray,"Responsible");
array_push($headingArray,"Date of Car Rental");
array_push($headingArray,"Car Rental Ref#");

$refDispArray = array();
array_push($refDispArray,"ac_mem_name");
array_push($refDispArray,"ac_mem_surname");

$dispFields = array();
array_push($dispFields,"lnk_parkingbay");
array_push($dispFields,"lnk_date_communicated");
array_push($dispFields,"lnk_responsible");
array_push($dispFields,"lnk_car_date");
array_push($dispFields,"lnk_car_ref");
$this->makeGRID("AC_Members,lnk_ACMembers_ACMeeting",$refDispArray,"ac_mem_id","(ac_mem_active = 1 AND ac_mem_id=ac_member_ref AND lnk_confirmed = 1 AND ac_meeting_ref = ".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID.") ","lnk_ACMembers_ACMeeting","lnk_id","ac_member_ref","ac_meeting_ref",$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,$dispFields,$headingArray);
?></table><br><br>
Airfare arrangements:<br><br>
<table cellpadding="2" cellspacing="2" border="1" width="90%">
<?php 
$headingArray = array();
array_push($headingArray,"AC Member");
array_push($headingArray,"Date");
array_push($headingArray,"From");
array_push($headingArray,"To");
array_push($headingArray,"Time");
array_push($headingArray,"Ref#");

$refDispArray = array();
array_push($refDispArray,"ac_mem_name");
array_push($refDispArray,"ac_mem_surname");

$dispFields = array();
array_push($dispFields,"lnk_airfare_date");
array_push($dispFields,"lnk_airfare_from");
array_push($dispFields,"lnk_airfare_to");
array_push($dispFields,"lnk_airfare_time");
array_push($dispFields,"lnk_airfare_ref");
$this->makeGRID("AC_Members,lnk_ACMembers_ACMeeting",$refDispArray,"ac_mem_id","(ac_mem_active = 1 AND ac_mem_id=ac_member_ref AND lnk_confirmed = 1 AND ac_meeting_ref = ".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID.") ","lnk_ACMembers_ACMeeting","lnk_id","ac_member_ref","ac_meeting_ref",$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,$dispFields,$headingArray);
?></table><br><br>
Shuttle arrangements:<br><br>
<table cellpadding="2" cellspacing="2" border="1" width="90%">
<?php 
$headingArray = array();
array_push($headingArray,"AC Member");
array_push($headingArray,"Date");
array_push($headingArray,"From");
array_push($headingArray,"To");
array_push($headingArray,"Time");
array_push($headingArray,"Ref#");

$refDispArray = array();
array_push($refDispArray,"ac_mem_name");
array_push($refDispArray,"ac_mem_surname");

$dispFields = array();
array_push($dispFields,"lnk_shuttle_date");
array_push($dispFields,"lnk_shuttle_from");
array_push($dispFields,"lnk_shuttle_to");
array_push($dispFields,"lnk_shuttle_time");
array_push($dispFields,"lnk_shuttle_ref");
$this->makeGRID("AC_Members,lnk_ACMembers_ACMeeting",$refDispArray,"ac_mem_id","(ac_mem_active = 1 AND ac_mem_id=ac_member_ref AND lnk_confirmed = 1 AND ac_meeting_ref = ".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID.") ","lnk_ACMembers_ACMeeting","lnk_id","ac_member_ref","ac_meeting_ref",$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID,$dispFields,$headingArray);
?></table><br><br>

</td></tr></table>
</td></tr></table>
<script>
	function setDefaultDates () {
		obj = document.defaultFrm;
		for (i=0; i<obj.length; i++) {
			if (obj[i].name.indexOf("_date") > 0) {
				obj[i].value = "<?php echo $this->getValueFromTable("AC_Meeting", "ac_id", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID, "ac_start_date")?>";
			}
		}
	}
	setDefaultDates ();
</script>
