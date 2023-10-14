<?php 

	function showScreens($curP) {
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$SQL = "SELECT  work_flows_id,template,taskName,workFlow_desc,display,command,work_flows.condition as condition,sec_no FROM  work_flows,workflowtype  WHERE workFlow_id=workFlowType_ref AND  `processes_ref` = $curP ORDER  BY  `sec_no`  ASC";
		//file_put_contents('php://stderr', print_r($SQL, TRUE));
		$rs = mysqli_query($conn, $SQL);
		while ($row = mysqli_fetch_array($rs)) {
		$options = array ();
		$editOptions = array ();
		array_push ($editOptions, array("Edit", "document.defaultFrm.CHANGE_TO_RECORD.value='work_flows|".$row["work_flows_id"]."';moveto(244);"));
		array_push ($editOptions, array("view", "viewPage(".$row["work_flows_id"].");"));
			// BUG: eintlik function skryf...
		$SQL = "SELECT * FROM template_text, text_type WHERE text_type_id = text_type_ref AND template_ref = ?";
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $row["template"]);
		$sm->execute();
		$rsText = $sm->get_result();

		//$rsText = mysqli_query ($conn, $SQL);
		while ($rowText = mysqli_fetch_array ($rsText)) {
			array_push ($options, array("Edit ".$rowText["text_type_desc"]." - ".$rowText["template_text_desc"], $rowText["template_text_id"] ));
		}
?>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>
			<table border="1" cellpadding="2" cellspacing="2" width="100%">
			<tr>
				<td valign="top" width="30%">
				<b><?php echo $row["workFlow_desc"]?></b> (<?php echo $row["sec_no"]?>)<br>
				<?php 
// REMOVE NEXT 3 LINES BUT DO NOT KNOW WHHY
//				$this->mis_eval_pre(__LINE__, __FILE__);
				eval('printf('.$row[4].');');
//				$this->mis_eval_post("printf($row[4]);");
				?> : <b><?php echo $row["taskName"]?></b>
				</td>
				<td width="70%">
<?php 
		foreach ($editOptions AS $key) {
			$link  = "[";
			if ($key[1] > "") $link .= "<a href=\"javascript:".$key[1]."\">";
			$link .= $key[0];
			if ($key[1] > "") $link .= "<a/>";
			$link .= "] &nbsp; ";
			echo $link;
		}
?>
<?php 
			if ($row["workFlow_desc"] == "TEMPLATE"){
			echo "[<a href=\"javascript:setValues('".$row["template"]."');document.defaultFrm.CHANGE_TO_RECORD.value='template_text|NEW';\">Add Text</a>]";
			}

		foreach ($options AS $key) {
			$link  = "<br>[";
			if ($key[1] > "") $link .= "<a href=\"javascript:document.defaultFrm.CHANGE_TO_RECORD.value='template_text|".$key[1]."';moveto(211);\">";
			$link .= $key[0];
			if ($key[1] > "") $link .= "<a/>";
			$link .= "]";
			echo $link;
		}
?>
				</td>
			</tr>
			</table>

</td>
		</tr>
<?php 
		}		
?>
				
		</td>
		</tr>
		<tr><td colspan=3>&nbsp;</td></tr>
<?php 
	}

	function showFlow() {
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$stopProccess = 2;   // if this or smaller, we accept that we are going home

		$rs = mysqli_query($conn, "SELECT * FROM processes WHERE processes_id > 0 ORDER BY processes_id");
		while (($row = mysqli_fetch_array($rs))) {
			echo "<tr><td colspan=3>";
			echo "<large><b>Proc ".$row["processes_id"]." -> ".$row["currentFlow_next_process_ref"]."</b></large> &nbsp; ";

			echo "[<a href=\"javascript:setProcValues('".$row["processes_id"] ."');document.defaultFrm.CHANGE_TO_RECORD.value='work_flows|NEW';\">Add workflow</a>]";

			echo "</td><tr>";
			echo "<tr><td colspan=3>";
			echo "<b>".$row["processes_desc"]."</b> &nbsp; ";
			echo "[<a href=\"javascript:document.defaultFrm.CHANGE_TO_RECORD.value='processes|".$row["processes_id"]."';moveto(181);\">Edit Proccess</a>]";
			echo "</td></tr>\n";

			showScreens($row["processes_id"]);

		}
	}
?>

<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
<?php 	showFlow(); ?>
<br>
	</table>
</td></tr></table>
<input type="hidden" name="temp">
<input type="hidden" name="Tproc">
<script>
function setValues(temp){
	document.defaultFrm.temp.value = temp;
	moveto(211);
}

function setProcValues(Tproc){
	document.defaultFrm.Tproc.value = Tproc;
	moveto(244);
}

</script>
