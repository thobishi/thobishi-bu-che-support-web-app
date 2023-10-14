<br>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr><td>
	<table width="100%" border=0 align="left" cellpadding="2" cellspacing="2">

	<tr>
		<th align="left">
			Register New Event
			<hr>
		</th>
	</tr>
	<tr>
		<td>
			You have elected to register a new activity.
			<br>
			<br>
			Please select the name of the programme that this activity will fall under:
			<?	$this->showField("directorate_ref");
			// 2008 Sep 22: Robin - proj_code is no longer unique to a project. Select directorate instead.
				// Reason we need to capture a filed is so that grids on next page have a project_id.  If go directly
				// to edit page then project_id is still NEW as it has not been saved yet which affects the grids.
			// $this->showField("proj_code");	?>
		</td>
	</tr>

	</table>
	<br><br><br><br>

	</td>
	</tr>


</table>

<br>
<input type='hidden' name='id' value=''>


