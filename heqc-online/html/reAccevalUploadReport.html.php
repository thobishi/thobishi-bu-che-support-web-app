<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Please upload the evaluator report:</b></td>
</tr><tr>
	<td>
		<?php 
		$eval_id = $this->dbTableInfoArray["evalReport"]->dbTableCurrentID;
		$reacc_id = $this->getValueFromTable("evalReport", "evalReport_id", $eval_id, "reaccreditation_application_ref");

		if ($this->getValueFromTable("evalReport", "evalReport_id", $eval_id, "evalReport_doc") > 0) {
			$this->createAction ("next", "Confirm upload", "submit", "", "ico_next.gif");
		}
		$this->makeLink("evalReport_doc");
		?>
		<br>
		Please note that once you click "Confirm upload", the evaluator report will be flagged as ready to be viewed by the HEQC.
	</td>
</tr>

<tr>
	<td>
		<br>
		The following evaluator reports have been uploaded (click on the document name to view the report).
		<br>
		<table width="100%" cellspacing=2 cellpadding=2>
			<tr class="oncolourb">
				<td width="40%">Evaluator</td>
				<td>Report</td>
			</tr>
		<?php 
			$SQL  = "SELECT * FROM evalReport WHERE reaccreditation_application_ref=? AND evalReport_status_confirm='1'";
			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			if ($conn->connect_errno) {
			    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
			    printf("Error: %s\n".$conn->error);
			    exit();
			}

			$sm = $conn->prepare($SQL);
			$sm->bind_param("s", $reacc_id);
			$sm->execute();
			$rs = $sm->get_result();

			//$rs = mysqli_query($SQL);
			while ($row = mysqli_fetch_array($rs))
			{
				echo "<tr class='onblue'>";
				$user_id =  $this->getValueFromTable("Eval_Auditors", "Persnr", $row['Persnr_ref'], "user_ref");
				$title_ref =  $this->getValueFromTable("users", "user_id", $user_id, "title_ref");
				$evalReportDoc = new octoDoc($row["evalReport_doc"]);
				$linkToDoc = ($row["evalReport_doc"] > 0) ?"<a href='".$evalReportDoc->url()."' target='_blank'>".$evalReportDoc->getFilename()."</a>" : "No report has been uploaded yet";

				echo "<td>".$this->getValueFromTable("lkp_title", "lkp_title_id", $title_ref, "lkp_title_desc")." ";
				echo " ".$this->getValueFromTable("Eval_Auditors", "Persnr", $row['Persnr_ref'], "Names");
				echo " ".$this->getValueFromTable("Eval_Auditors", "Persnr", $row['Persnr_ref'], "Surname");
				echo "</a>";
				if ($row["do_summary"] == 2) {
					echo " (Chair)";
					$isChairID = $row['Persnr_ref'];
				}
				echo "<br>";

				echo "<td>".$linkToDoc."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</td>
</tr>

</table>
</td></tr></table>


