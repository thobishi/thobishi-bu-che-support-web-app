<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
<?php 
$this->showInstitutionTableTop ();
$id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
?>

<br>
The Project Manager has indicated that this application is ready for approval by management.<br>
The following tasks must be completed by you in the system:<br>
<ul>
<li>Approve the evaluator reports.  If you do not approve the reports send the application back to the Project Administrator with instructions.</li>
<li>Ensure that the evaluators have received payment.</li>
<li>Indicate that you approve that this application is ready to be assigned to an AC Meeting.</li>
<li>Upload the directorate recommendation that will go to the AC Meeting.</li>
</ul>
<br>
The list below displays the evaluator reports. You are able to view these reports by clicking on the link.
<br>
<span class="visi">Please note that applications evaluated without a chairman will not have a final report.</span>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<?php 

	echo "<tr>";
	echo "<td class='oncolourb' valign='top' align='center' width='20%'>Evaluator</td>";
	echo "<td class='oncolourb' valign='top' align='center' width='20%'>Date sent to evaluator</td>";
	echo "<td class='oncolourb' valign='top' align='center' width='10%'>Date received from evaluator</td>";
	echo "<td class='oncolourb' valign='top' align='center' width='20%'>Report link</td>";
	echo "<td class='oncolourb' valign='top' align='center' width='20%'>Final Report</td>";
	echo "</tr>";

	$SQL = "SELECT * FROM evalReport WHERE application_ref =? AND evalReport_status_confirm=1";
	$isChairID = "";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $id);
	$sm->execute();
	$rs = $sm->get_result();


	//$rs = mysqli_query($SQL);
	if (mysqli_num_rows($rs) > 0){
		while ($row = mysqli_fetch_array($rs)){

			$eDoc = new octoDoc($row['evalReport_doc']);
			$evalReportID = $row["evalReport_id"];
			$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
			$tmpSettings = "DBINF_Institutions_application___application_id=".$id."&DBINF_evalReport___evalReport_id=".$row["evalReport_id"];
			$compDate = $row["evalReport_date_completed"];
			$a_sDoc = "No final report uploaded";

			if ($row["do_summary"]==2){
				$name .= " (Chair)";
				$isChairID = $row['Persnr_ref'];
				$sDoc = new octoDoc($row['application_sum_doc']);
					if ($sDoc->isDoc()) {
					$a_sDoc = '<a href="'.$sDoc->url().'" target="_blank">'.$sDoc->getFilename().'</a>';
					}
			}

			echo "<tr  class='onblue'>";
			echo "<td valign='top' align='left'>";
			echo ' <a href="javascript:winEvalContactDetails(\'Evaluator Contact Details\',\''.$row['Persnr_ref'].'\', \''.base64_encode($tmpSettings).'\', \'\');">';
			echo $name;
			echo "</a></td>";
			echo "<td valign='top' align='center'>".$row["evalReport_date_sent"]."</td>";
			echo "<td valign='top' align='center'>".$compDate."</td>";
			echo "<td valign='top' align='center'>";
				if ($eDoc->isDoc()) {
						echo '<a href="'.$eDoc->url().'" target="_blank">'.$eDoc->getFilename().'</a>';
				} else { echo "No final report uploaded"; }
			echo "</td>";
			echo "<td valign='top' align='center'>".$a_sDoc."</td>";
			echo "</tr>";
		}
	}

?>

</table>
<br>
</td>
</tr>
</table>




