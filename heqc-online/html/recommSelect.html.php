<?php
	$this->showInstitutionTableTop ();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->formFields["application_ref"]->fieldValue = $app_id;
    $this->showField("application_ref");
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td colspan="2">
				This application is ready for the directorate recommendation to be done.  Please complete the background for this application 
				and then proceed to appoint a user to do the directorate recommendation.
				<br><br>
			</td>
		</tr>
		<tr>
		<td class="visi" colspan="2"><span class="">Please note that the proceedings and background will form part of the AC Meeting documentation for this application.</span></td>
		</tr>
		<tr>
			<td class="oncolour">Record of proceedings relating to:</td>
			<td>
			<?php 
				// Set proceedings to apply for accreditation
				if ($this->formFields["lkp_proceedings_ref"]->fieldValue == 0){
					$this->formFields["lkp_proceedings_ref"]->fieldValue = 1;
				}
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
