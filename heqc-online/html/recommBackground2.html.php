<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$grp = 7;  //Checklisting group
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td colspan="2">
<?php
				$instr = <<<TEXT
					Please screen the background for this application proceedings.  If it is incomplete or erroneous please return it to your colleague.
TEXT;
				echo $instr;
?>
			<br><br>
			</td>
		</tr>
		<tr>
		<td class="visi" colspan="2"><span class="">Please note that the background will form part of the AC Meeting documentation for this application.</span></td>
		</tr>
		<tr>
			<td valign="top" class="oncolour">Background</td>
			<td>
			<?php 
				$this->showField('applic_background'); 
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				$dd = $this->makeDropdownOfGroupUsers($grp);
			?>
				<hr>
				If returning the background:
				<br><br>
				Select the colleague to return it to: <?php echo $dd; ?>
				<br><br>
				Enter the instruction to email to your colleague with the background. Click on <span class="specialb">Return background to colleague</span> in the Actions menu.
				<?php $this->showField("request"); ?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
