<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();
	// 2011-09-08 Robin: Not sure why its doing this here.  I'm commenting it out.
	//if ($this->getValueFromTable("Institutions_application", "application_id", $app_id, "secretariat_doc") != 0)
	//{
	//	$this->createAction ("next", "End application workflow", "submit", "", "ico_next.gif");
	//}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	The recommendation users assigned to this application are displayed below.  The recommendation is available below.  
	You are responsible for the final approval of the recommendation for this application and indicating whether this application 
	may be assigned to an AC meeting.

<?php
		// Display recommendation users that confirmed "accepted" to do the Directorate recommendation for the application
		$criteria = array("lop_status_confirm = 1");
		$recomm = $this->getSelectedRecommUserForAppProceeding($app_proc_id, $criteria);
	
		// Process cannot continue without recommendation users having confirmed.
		If (empty($recomm)){
			echo "<br><br>No recommendation writers have been appointed. It may be that this record was added manually to get data online.  Please return the application to the previous user so that recommendation writers can be appointed.";
		} else {
			echo $this->displayRecommUsers($recomm, '_recommForm_final');
		}
?>
	</td>
</tr>
<tr>
	<td>
	<?php // 2012-04-14 Robin: $this->showField('application_status');
	$this->showField('application_status_ref');
	?>
	<span class="visi">
	<br>
	Please check this box to indicate that the Directorate recommendation has been updated and passed final approval.
	<?php $this->showField("finalRecommApproval");?>
	</span>
	<br>
	</td>
</tr>
</table>
<br>