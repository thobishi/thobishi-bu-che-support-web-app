<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>The following evaluators have been chosen by you:</b></td>
</tr><tr>
	<td><ul>
<?php 
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
	while ($RS_evalReport && ($row=mysqli_fetch_array($RS_evalReport))) {
		echo "<li>".$row["Surname"].", ".$row["Names"]."</li><br>";
	}
?>
	</ul></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><b>If this is correct, click "Next" to continue this process.</b></td>
</tr></table>
</td></tr></table>
