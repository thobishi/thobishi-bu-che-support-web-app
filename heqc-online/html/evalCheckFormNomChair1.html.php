<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<br><br>
		
		<table width="60%" border=1 align="center" cellpadding="2" cellspacing="2"><tr>
			<td colspan="2">Please choose a chair person to do the final evaluation:</td>
		</tr>
			<?php 
			$eval_already_nominated = false;
			$SQL = "SELECT Persnr_ref, evalReport_id, Names, Surname FROM `Eval_Auditors`, evalReport WHERE application_ref=? AND Persnr_ref=Persnr";

			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			if ($conn->connect_errno) {
			    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
			    printf("Error: %s\n".$conn->error);
			    exit();
			}

			$sm = $conn->prepare($SQL);
			$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
			$sm->execute();
			$RS_evalReport = $sm->get_result();

			//$RS_evalReport = mysqli_query($SQL);
			echo '<tr><td>';
			echo '<select name="do_summary">';
			while ($RS_evalReport && ($row=mysqli_fetch_array($RS_evalReport))) {
				$SEL = "";
				$sql2 = "SELECT do_summary FROM evalReport_nominees WHERE Persnr_ref=".$row["Persnr_ref"]." AND application_ref=?";

				$sm = $conn->prepare($sql2);
				$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
				$sm->execute();
				$rs2 = $sm->get_result();

				//$rs2 = mysqli_query($sql2);
				if (mysqli_num_rows($rs2) == 0) {
					$sql3 = "SELECT do_summary FROM evalReport WHERE Persnr_ref=".$row["Persnr_ref"]." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
					$rs2 = mysqli_query($sql3);
				}
				if ($rs2 && ($row2=mysqli_fetch_array($rs2))) {
					if ($row2["do_summary"] > 0) {
						$eval_already_nominated = true;
						$SEL = " SELECTED ";
					}
				}
				echo '<option value="'.$row["evalReport_id"].'" '.$SEL.'>'.$row["Names"]." ".$row["Surname"].'</option>';
			}
			echo '</select>';
			echo '</td></tr>';
			?>
		</tr>
<?php 
		if ($eval_already_nominated) {
?>
		<tr>
			<td colspan="2"><b>The above evaluator has already been nominated as chairperson. If you want to change the evaluator, use the dropdown above.</b></td>
		</tr>
<?php 
		}
?>
		</table>
<br><br>
</td></tr></table>
