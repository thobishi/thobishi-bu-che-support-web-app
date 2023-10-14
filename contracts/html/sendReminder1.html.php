<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<span class="loud">Remind contract managers to rate performance</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			Reminders were successfully emailed to the following contract managers:  
		</td>
	</tr>
<?php
	$html = "";

	foreach ($manager_arr as $mgr_id){
		$mgr = $this->displaySupervisor($mgr_id);
		$html .= <<<MGR
			<tr>
				<td width="5%">&nbsp;</td>
				<td>
					$mgr
				</td>
			</tr>
MGR;
	}
	
	$html = ($html == "") ? "-- No reminders were sent --" : $html;
	
	echo $html;

?>
</table>