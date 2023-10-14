<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;


	$this->showField("recomm_complete_ind");

	$this->showInstitutionTableTop ();
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	The recommendation users assigned to this application are displayed below.  They will now have access to the current application through the HEQC-online system. 
	They will be able to capture the Directorate recommendation for the application.
	<br>
	<br>

<?php

		// Display recommendation users that confirmed "accepted" to do the Directorate recommendation for the application
		$criteria = array("lop_status_confirm = 1");
		$recomm = $this->getSelectedRecommUserForAppProceeding($app_proc_id, $criteria);

		// Process cannot continue without recommendation users having confirmed.
		If (empty($recomm)){
			echo "<br>No recommendation writers have been appointed. It may be that this record was added manually to get data online.  Please return the application to the previous user so that recommendation writers can be appointed.";
		} else {
			echo $this->displayRecommUsers($recomm,'_recommForm_prelim');
		}	
		//echo $app_proc_id;

		// 2012-04-14 Robin: $this->showField('application_status');
		// 2014-02-15 An application must be ready for AC meeting after intermediate approval
		$this->showField('application_status_ref');	
		//echo $app_id;			
?>
	</td>
</tr>
<tr>
	<td>
	The above users will have access to the programme until: <?php $this->showfield('recomm_access_end_date'); ?>
	</td>
</tr>
<tr>
	<td>
	<br>
	<span class="visi">Please check this box to indicate that the Directorate recommendation has passed preliminary approval and may be assigned to an AC Meeting: <?php $this->showField("readyForACMeeting"); ?></span>
	<br><i>Please note if you check this box and click on <span class="specialb">Proceed to next process and user</span>, the application will be passed to management for intermediate approval of the Directorate recommendation.</i>
	</td>
</tr>
</table>
<br>
