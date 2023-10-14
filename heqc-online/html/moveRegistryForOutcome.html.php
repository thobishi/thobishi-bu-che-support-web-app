<?php
	//$pa_priv_usr = $this->getValueFromTable("settings","s_key",'usr_project_admin_priv',"s_value");
	//$pa_pub_usr = $this->getValueFromTable("settings","s_key",'usr_project_admin_pub',"s_value");	
	$reg_usr = $this->getValueFromTable("settings","s_key",'che_registry_user_id',"s_value");
	
	//$pa_priv_name = $this->getValueFromTable("users","user_id",$pa_priv_usr,"concat(name,' ',surname)");
	//$pa_pub_name = $this->getValueFromTable("users","user_id",$pa_pub_usr,"concat(name,' ',surname)");
	$reg_name = $this->getValueFromTable("users","user_id",$reg_usr,"concat(name,' ',surname)");
?>
<table width="95%" border=0 align="left" cellpadding="2" cellspacing="2">
<tr>
	<td class="special1">
	<br>
	<span class="specialb">
	LIST OF ACTIVE PROCESSES FOR PROJECT ADMINISTRATORS - TRANSFER TO REGISTRY FOR OUTCOMES
	</span>
	<br>
	<br>
	Move active processes for accreditation or re-accreditation phases that are in processes:
	<ul>
		<li>Checklisting</li>
		<li>Screening</li>
		<li>Evaluators</li>
	</ul>
	to the registry user to process the outcome of the application.
	<br><br>
	The current registry user is:
		<table align="center" border="1" width="60%">
<!--
		<tr>
			<td><b>Project Administrator - private</b></td><td><?php // echo $pa_priv_name; ?></td>
		</tr>
		<tr>
			<td><b>Project Administrator - public</b></td><td><?php // echo $pa_pub_name; ?></td>	
		</tr>
-->
		<tr>		
			<td><b>Registry - outcomes</b></td><td><?php echo $reg_name; ?></td>
		</tr>
		</table>
		<br>
	</td>
</tr>
<tr>
	<td>
<?php 
	$SQL = <<<PROCESSES
		SELECT * FROM active_processes, processes, users 
		WHERE processes_ref = processes_id  
		AND user_ref = user_id 
		AND status=0 
		AND processes_ref in (7, 11, 47, 106, 112, 110,143, 147, 148)
		ORDER BY processes_ref, last_updated desc
PROCESSES;
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$rs = mysqli_query($conn, $SQL);
	$num_processes = mysqli_num_rows($rs);
	if ($num_processes > 0) {
?>
		<table width="95%" border=1 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td colspan="5" align="right">Total: <?php echo $num_processes?></td>
		</tr>
		<tr>
			<td class="oncolourb" align="center">User</td>
			<td class="oncolourb" align="center">Process</td>
			<td class="oncolourb" align="center">Reference Number</td>
			<td class="oncolourb" align="center">Last Update</td>
			<td class="oncolourb" align="center">Options</td>
		</tr>
<?php
		while ($row = mysqli_fetch_array ($rs)) {

			// Only display active processes that deal with applications and have a reference number.
			$arr = $this->parseOtherWorkFlowProcess($row["active_processes_id"]);

			foreach ($arr AS $k=>$v) {
				if ($k == "Institutions_application" OR $k == "Institutions_application_reaccreditation") {

					// Get registry fields based on whether its accreditation or re-accreditation.
					$areg = $this->getRegistryProcessInfo($k);
					$heqc_ref = $this->getValueFromTable($v->dbTableName, $v->dbTableKeyField, $v->dbTableCurrentID, $areg['heqc_ref_field']);
	?>
					<tr>
						<td valign="top"><?php echo $row["surname"]?>, <?php echo $row["name"]; ?></td>
						<td valign="top"><?php echo $this->workflowDescription($row["active_processes_id"],$row["processes_id"]); ?></td>
						<td valign="top"><?php echo $heqc_ref; ?></td>
						<td valign="top"><?php echo $row["last_updated"]?></td>
						<td valign="top">
						<nobr><a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value='active_processes|<?php echo $row["active_processes_id"]?>';moveto('next');">Transfer to Registry</a></nobr>
						</td>
					</tr>
	<?php 				break;
				}
			}
		}
	}else{
		echo '<tr><td colspan="4">There are currently no active processes</td></tr>';
	}
?>
</table>
<br><br>
</td></tr></table>
