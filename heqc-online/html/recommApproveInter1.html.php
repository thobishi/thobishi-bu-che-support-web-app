<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$this->showInstitutionTableTop ();
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	The recommendation users assigned to this application are displayed below.  The recommendation is available below.  
	You are responsible for intermediate approval of this application.
	<br>
	<br>

<?php
		// Display recommendation users that confirmed "accepted" to do the Directorate recommendation for the application
		$criteria = array("lop_status_confirm = 1");
		$recomm = $this->getSelectedRecommUserForAppProceeding($app_proc_id, $criteria);
		
		// Process cannot continue without recommendation users having confirmed.
		If (empty($recomm)){
			echo "<br><br>No recommendation writers have been appointed. It may be that this record was added manually to get data online.  Please return the application to the previous user so that recommendation writers can be appointed.";
		} else {
			echo $this->displayRecommUsers($recomm, '_recommForm_inter');
		}	
	
?>
	</td>
</tr>
<tr>
	<td>
	<br>
	<span class="visi">Please check this box to indicate that the Directorate recommendation has passed intermediate approval: <?php $this->showField("interRecommApproval");?></span>
	<br><i>Please note if you check this box and click on <span class="specialb">Proceed to next process and user</span>, the application will be passed to management for final approval of the Directorate recommendation.</i>
	</td>
</tr>
</table>
<br>