<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	//echo $site_proc_id;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id, "sites"); ?>



	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Acceptance of appointment by recommendation user
		<br>
	</td>
</tr>
<tr>
	<td>
	<br>
	</table>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td align="center">
		<br>
		The displayed letter of portal access may be sent to the recommendation user.
		<b>To send the letter of portal access email to the user click on the checkbox under the heading Send Email.</b>
		<br>
		The following recommendation user has confirmed that they will do the directorate recommendation for this application.
		</td>
	</tr>
	
	<tr>
		<td>
	
		<?php
		// Display recommendation users that confirmed "accepted" to do the Directorate recommendation for the application
		$criteria = array("lop_status_confirm = 1");
		$r = $this->getSelectedRecommUserForSiteApplication($site_proc_id, $criteria);
		$date_sent = ($r["portal_sent_date"] > '1000-01-01') ? $r["portal_sent_date"] : '&nbsp;';
		$checked = "";
		if ($r["portal_sent_date"] == '1000-01-01'){
			$checked = "CHECKED";
		}
		
		// Process cannot continue without recommendation users having confirmed.
		
		$html = '<table width="80%" align="center">';
		
		$html .= <<<html
			<tr class='oncolourb'>
				<td><b>User name</b></td>
				<td><b>Email address</b></td>
				<td><b>Telephone</b></td>
				<td><b>Date Email Sent</b></td>
				<td>Send email</td>
			</tr>
html;
		
		$n_confirm = count($r);
		
		// Only allow workflow to continue (display Next button) if at least one recommendation user has confirmed.
		if ($n_confirm == 0){
			$this->formActions["next"]->actionMayShow = false;
			$html .= <<<html
				<tr>
					<td colspan="4">No recommendation users have confirmed.  This process cannot continue.
					Please click Previous and confirm which recommendation users have accepted.
					</td>
				</tr>
html;
		}
		
		// Recommendation users who have confirmed must be notified of the portal via email.
		if ($n_confirm > 0){
				
				$html .= <<<html
				<tr>
					<td>$r[user_name]</td>
					<td>$r[email]</td>
					<td>$r[contact_nr] $r[contact_cell_nr]</td>
					<td>$r[portal_sent_date]</td>
					<td><input name="chkRecomm$r[user_id]" type="Checkbox" $checked></td>
				</tr>
html;
		}
		
		$html .= "</table>";
		
		echo $html;
		?>
	
		</td>
	</tr>
	
	<tr>
		<td>
			<br>
			<b>The above recommendation user will receive the following email (if it hasn't been sent to them before - see <i>Date Email Sent</i>) 
			to inform them on how to access the directorate recommendation portal.</b>
			<br>
		</td>
	</tr>
	
	<tr>
		<td valign="top"> <span class="loud">Letter on Directorate Recommendation Portal Access:</span></td>
	</tr>
	<tr>
		<td align="center">
		<?php
		$this->formFields['recommendation_portal_email']->fieldValue = $this->getTextContent("instSiteRecommAppoint4", "Letter of portal access for site recommendation");
		$this->showField('recommendation_portal_email');
		?>
		</td>
	</tr>
	
	

</td>
</tr>
</table>