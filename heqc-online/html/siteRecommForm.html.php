<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;

	switch ($this->flowID){
	case '179':
		$this->createAction ("cancel", "Cancel without saving", "href", "javascript:cancelView('_siteRecommApprovePrelim');", "ico_cancel.gif");
	break;
	case '180':
		$this->createAction ("cancel", "Cancel without saving", "href", "javascript:cancelView('_siteRecommApproveInter');", "ico_cancel.gif");
		break;
	case '181':
		$this->createAction ("cancel", "Cancel without saving", "href", "javascript:cancelView('_siteRecommApproveFinal');", "ico_cancel.gif");
		break;
	}	
?>	
	<input type='hidden' name='cmd' value=''>
	<input type='hidden' name='id' value=''>
<?php
	// The following is required for GridShowRowByRow as well as the inputs above.
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		$this->getCMD_action($cmd);
		echo '<!--script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script-->';
	}

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<span class="loud">Directorate recommendation</span>
		<br>
		<br>Please complete the directorate recommendation below.  Once completed, check this box to indicate to CHE that you have 
		completed the recommendation: <?php $this->showField("recomm_complete_ind"); ?>
	</td>
</tr>
<tr>
	<td>
		<hr>
	</td>
</tr>
<tr>
	<td align="center">
		HIGHER EDUCATION QUALITY COMMITTEE<br>
		ACCREDITATION COMMITTEE<br>
		MEETING TO BE HELD
		<br>
	</td>
</tr>
<tr>
	<td><?php 
			// Display site application header in a table
			echo $this->edit_site_recomm('recomm',$site_proc_id); 
		?>
	</td>
</tr>
</table>
