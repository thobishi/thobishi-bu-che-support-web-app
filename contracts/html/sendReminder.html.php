<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<span class="loud">Remind contract managers to rate performance</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			The following email text will be sent to all the selected contract managers.  You may edit this text to 
			add any additional information that is required.
		</td>
	</tr>
	<tr>
		<td align="right">
			<br>
			<?php 
			$this->formFields['reminder_email']->fieldValue = $this->getTextContent("sendReminder", "reminderContractPerformance");
			$this->showfield('reminder_email');
			?>
		</td>
	</tr>
</table>
<?php
	$sql = <<<MANAGER
		SELECT DISTINCT che_supervisor_user_ref, u.*
		FROM d_consultant_agreements a, users u
		WHERE a.che_supervisor_user_ref = u.user_id 
		ORDER BY u.surname, u.name
MANAGER;

	$rs = mysqli_query($sql);
	$n = mysqli_num_rows($rs);

	$html = <<<HTMLSTR
		<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
		<tr>
			<td colspan="4">
			<br>
			Select the contract managers by checking the box next to the manager.  
			Click on <span class="specialb">Email reminders to selected managers</span> in the Actions menu to email 
			the above text to the selected contract managers.
			</td>
		</tr>
		<tr><td align="right" colspan="4"><b>Number of managers: $n</b></td></tr>
		<tr class='oncolourcolumnheader'>
			<td>Name</td>
			<td>Email</td>
			<td>Telephone No.</td>
			<td>
				<a href="javascript:checkall(document.defaultFrm.elements['id_manager[]'],true);"><i>Select All</i></a>
				<br><a href="javascript:checkall(document.defaultFrm.elements['id_manager[]'],false);"><i>Deselect All</i></a>
			</td>
		</tr>
HTMLSTR;

	$n = 0;
	while($row = mysqli_fetch_array($rs)){
		$chk_manager = '<input type="Checkbox" name="id_manager[]" value="'.$row["che_supervisor_user_ref"].'">';
		$name = $row["name"] . " " . $row["surname"];
		$bgColor = (fmod($n,2)) ?("ongreycolumn"):("oncolourcolumn");
		$html .= <<<HTMLSTR
			<tr class="$bgColor">
				<td>$name</td>
				<td>$row[email]</td>
				<td>$row[contact_nr]</td>
				<td>$chk_manager</td>
			</tr>
HTMLSTR;

		$n++;

	}
	$html .= <<<HTMLSTR
		</table>
HTMLSTR;
	echo $html;
?>