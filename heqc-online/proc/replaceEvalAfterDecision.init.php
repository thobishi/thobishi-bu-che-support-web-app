<?php 
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$SQL = "SELECT * FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=Persnr";
		$RS = mysqli_query($conn, $SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
				$nom_sql = "UPDATE `evalReport_nominees` SET evalReport_status_confirm=".$row["evalReport_status_confirm"]." WHERE application_ref=".$row["application_ref"]." AND Persnr_ref=".$row["Persnr_ref"];
				mysqli_query($conn, $nom_sql);
		}
?>
