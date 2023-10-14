<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	foreach ($_POST AS $key=>$val) {
		if (stristr($key, "siteVisit_payed") > "") {
			$final_arr = array();
			$tmp_arr = explode("#", $val);
			for ($i=0; $i<sizeof($tmp_arr); $i++) {
				$t = explode("|", $tmp_arr[$i]);
				$final_arr[$t[0]] = $t[1];
			}
			$SQL = "UPDATE `siteVisit` SET site_visit_payed=1 WHERE application_ref=".$final_arr["application_ref"]." AND site_ref=".$final_arr["site_ref"];
			$RS = mysqli_query($conn, $SQL);
		}
	}
?>
