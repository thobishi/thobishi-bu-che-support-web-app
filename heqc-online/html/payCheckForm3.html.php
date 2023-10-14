<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2">I have received confirmation of the payment <?php $this->showField("receive_confirmation")?>&nbsp; <i>(Tick to indicate 'Yes')</i></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr>
<?php 

/*
	Edited by Rebecca on 3/5/2007
	- so that the email is signed by the user currently in charge of usr_registry_payment, and not sent by the user currently logged onto the system.
	- added a "cc" parameter to the email so that the user_registry_payment also receives a copy of the email.

*/

	if ((isset($_POST["send_query"])) && ($_POST["send_query"] > "")) {
		$message = "Dear ".$this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("usr_registry_payment_query"), "name").", \n\n Could you please confirm that the following invoice was paid.\n\n HEQC Reference No: ".$this->getValueFromTable("Institutions_application", "application_id", $app_id, "CHE_reference_code")." \n Institution Name: ".$this->getValueFromTable("HEInstitution", "HEI_id", $ins_id, "HEI_name")." \n\n Thank you, \n ".$this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("usr_registry_payment"), "name")." ".$this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("usr_registry_payment"), "surname");
		$this->misMail($this->getDBsettingsValue("usr_registry_payment_query"), "HEQC - ".$this->getValueFromTable("Institutions_application", "application_id", $app_id, "CHE_reference_code")." - Payment Query", $message, $this->getValueFromTable("users", "user_id", $this->getDBsettingsValue("usr_registry_payment"), "email"), $this->getDBsettingsValue("che_registry_email"));
		echo '<tr><td colspan="2" align="center">Your query was sent successfully.</td></tr>';
	}else {

		$reminder = "";
		$anchor="";

		// Modified by Robin on 2 Nov 2006
		//if (((isset($due_date)) && ($due_date != (date("Y-m-d")))) && ((!(isset($first_date))) || ($first_date == "1970-01-01"))) {
		if ((!(isset($first_date))) || ($first_date == "1000-01-01")) {
			$anchor = "If the institution did not yet respond, please click <a href=\"javascript:changeConfirm(2);moveto('next')\">here</a> to send the <b>first</b> reminder</td>";
		}

		if ( ((isset($due_date)) && ($due_date > date("Y-m-d"))) && ( (isset($first_date)) && ($first_date != "1000-01-01") ) && (!(isset($final_date) && ($final_date !="1000-01-01")))) {
			$reminder = "A first reminder was sent to the institution on " . $first_date . ". A final reminder may be sent on or after the due date of ".$due_date.".";
		}

		//if (((isset($due_date)) && ($due_date == date("Y-m-d"))) && ((isset($first_date)) && ($first_date != "1970-01-01"))&& (!(isset($final_date) && ($final_date !="1970-01-01")))) {
		if ( ((isset($due_date)) && ($due_date <= date("Y-m-d"))) && ( (isset($first_date)) && ($first_date != "1000-01-01") ) && (!(isset($final_date) && ($final_date !="1970-01-01")))) {
			$reminder = "A <b>first</b> reminder has already been sent to the institution on ".$first_date.".";
			$anchor = "If the institution did not respond on the first reminder, please click <a href=\"javascript:changeConfirm(4);moveto('next')\">here</a> to send the <b>final</b> reminder";
		}

		//if (((isset($due_date)) && ($due_date == date("Y-m-d"))) && ((isset($first_date)) && ($first_date != "1970-01-01")) && ((isset($final_date)) && ($final_date != "1970-01-01"))) {
		if ( ((isset($first_date)) && ($first_date != "1000-01-01")) && ((isset($final_date)) && ($final_date != "1000-01-01"))) {
			$reminder = "A <b>final</b> reminder has already been sent to the institution on ".$final_date.".";
			$anchor = "If the institution did not pay, please click <a href=\"javascript:changeConfirm(10);moveto('next')\">here</a> to cancel this application.  Please note that the application is cancelled immediately.";
		}
?>
<tr>
	<td colspan="2"><?php echo $reminder;?></td>
</tr>
<tr>
	<td colspan="2"><?php echo $anchor?></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2">If you would like to query finances about the payment, please click the following checkbox and click the send query button</td>
</tr>
<tr>
	<td colspan="2">Send query to finances? <?php $this->showField("send_query") ?> &nbsp; <input type="button" class="btn" value="Send Query" onClick="checkQuery(document.defaultFrm.send_query);"></td>
</tr>
<?php 
	}
?>
</table>
<?php $this->showField("received_confirmation") ?>
</td></tr></table>
<script>
	function changeConfirm(val) {
		document.defaultFrm.FLD_received_confirmation.value = val;
	}

	// robin 2010-03-18: Commented out the rest of the function and the calls to it from the field
	// and template because showHideAction was commented out thus obsolete
	//function checkRecieved (obj) {
		//if (obj.checked) {
			//showHideAction("next", true);
		//}else {
			//showHideAction("next", false);
		//}
	//}
</script>
