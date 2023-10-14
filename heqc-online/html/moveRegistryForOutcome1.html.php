<table><tr><td>
<?php
	$reg_usr = $this->getValueFromTable("settings","s_key",'che_registry_user_id',"s_value");
	$reg_name = $this->getValueFromTable("users","user_id",$reg_usr,"concat(name,' ',surname)");
	$actproc_id  = $this->dbTableInfoArray["active_processes"]->dbTableCurrentID;
?>
	<table width="95%" border=0 align="left" cellpadding="2" cellspacing="2">
	<tr>
		<td class="special1">
		<br>
		<span class="specialb">
		REGISTRY TRANSFER
		</span>
		<br>
		<br>
		</td>
	</tr>
<?php 
	$process = false;
	if ($actproc_id > 0){
		$arr = $this->parseOtherWorkFlowProcess($actproc_id);
		foreach ($arr AS $k=>$v) {
			if ($k == "Institutions_application" OR $k == "Institutions_application_reaccreditation") {

				$this->formFields["acc_or_reacc"]->fieldValue = $k;
				$this->showField("acc_or_reacc");
				
				// Get registry fields based on whether its accreditation or re-accreditation.
				$areg = $this->getRegistryProcessInfo($k);
				$heqc_ref = $this->getValueFromTable($v->dbTableName, $v->dbTableKeyField, $v->dbTableCurrentID, $areg['heqc_ref_field']);
				$process = true;
?>
				<tr>
					<td>
						<?php echo "Click continue to transfer $heqc_ref to $reg_name or cancel to return to the list."?>						
					</td>
				</tr>
<?php 
			}
		}
?>

<?php 
	} 
	if ($process === false){
?>
				<tr>
					<td>
						<?php echo "No application was found. Click cancel to return to the list."?>						
					</td>
				</tr>
<?php 		
	}
?>
	</table>
	<br><br>
</td></tr></table>
