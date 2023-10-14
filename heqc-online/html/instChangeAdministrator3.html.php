<?php

	$new_adm = $this->getUserName($new_adm_id);
	if ($status == "complete"){
		$val_msg = <<<MSG
				<b>The new administrator has been successfully changed to: <b>$new_adm</b>.
				<br><br>
MSG;
	}
	if ($status == "new"){
		$val_msg = <<<MSG
				<b>No changes have taken place.
				<br><br>
MSG;
	}
?>

<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td align="left" class="special1">
			<br>
			<span class="specialb">
			CHANGE INSTITUTIONAL ADMINISTRATOR
			</span>
		</td>
	</tr>
	<tr>
		<td><br><?php echo $val_msg; ?></td>
	</tr>
</table>
<br>
