<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
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
		<span class="loud">Edit the AC meeting outcome and minutes for this application</span>
	</td>
</tr>
<tr>
	<td>
		<?php 
			// Display site application header in a table
			echo $this->edit_site_recomm('ac',$site_proc_id); 
		?>
	</td>
</tr>
<tr>
	<td><hr></td>
</tr>
<tr>
	<td>
	The following are the minutes taken at the AC meeting and the discussion that took place with respect to this application
	</td>
</tr>
<tr>
	<td>
	<?php $this->showField('minutes_discussion'); ?>
	</td>
</tr>
</table>
<br>