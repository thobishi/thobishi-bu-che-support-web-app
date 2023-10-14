<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
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
					Please complete the background for this application proceedings.  The proceedings relates to the type of processing taking place for 
					the application e.g. application for accreditation or deferral.
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
			<td class="oncolour">Record of proceedings relating to:</td>
			<td>
			<?php 
				$this->showField('lkp_proceedings_ref');
			?>				
			</td>
		</tr>
		<tr>
			<td valign="top" class="oncolour">Background</td>
			<td>
			<?php 
				if ($this->formFields['applic_background']->fieldValue == ""):
					$this->formFields["applic_background"]->fieldValue = $this->getApplicationBackground($app_id);
				endif;
				$this->showField('applic_background'); 
			?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
