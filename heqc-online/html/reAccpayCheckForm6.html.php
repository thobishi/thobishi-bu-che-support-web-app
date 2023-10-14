<?php
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->displayReaccredHeader ($reaccred_id)?>
		<br>
		<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			<br>
			<?php
			$this->showEmailAsHTML("reAccpayCheckForm6", "reAccfirstPaymentReminder");
			?>
			</td>
		</tr>
		
		</table>
	</td>
</tr>
</table>
