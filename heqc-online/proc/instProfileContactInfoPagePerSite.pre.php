<?php 
$report_type = isset($_GET['title']) ? $_GET['title'] : "NONE";
$mailError = true;

$inst = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = null;
switch ($report_type)
{

	case "Application Form" :
		$SQL = "SELECT * FROM `institutional_profile_sites`, `lkp_sites` WHERE sites_ref=institutional_profile_sites_id AND institution_ref=? AND application_ref=?";
                
                $par = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
                $sm = $conn->prepare($SQL);
                $sm->bind_param("ss", $inst, $par);
                            
		break;
	case "Institutional Profile" :
		$SQL = "SELECT * FROM `institutional_profile_sites` WHERE institution_ref=?";

		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $inst);
                
		break;
	default	:
		$SQL = "SELECT * FROM `institutional_profile_sites` WHERE institution_ref=?";
		
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $inst);
                
		$this->writeLogInfo(10, "REPORT_TYPE=NONE", $SQL, $mailError);
	}


$sm->execute();
$RS = $sm->get_result();

//$RS = mysqli_query ($SQL);
$site_arr = array();
$i = 0;
while ($RS && ($row = mysqli_fetch_array($RS, MYSQLI_ASSOC))) {
	$site_arr[$i]["site_name"] = $row["site_name"];
	$site_arr[$i]["location"] = $row["location"];
	$site_arr[$i]["address"] = $row["address"];
	$site_arr[$i]["postal_address"] = $row["postal_address"];
	$site_arr[$i]["surname"] = $row["contact_surname"];
	$site_arr[$i]["name"] = $row["contact_name"];
	$site_arr[$i]["email"] = $row["contact_email"];
	$site_arr[$i]["contact_nr"] = $row["contact_nr"];
	$site_arr[$i]["contact_fax_nr"] = $row["contact_fax_nr"];
	$site_arr[$i]["title"] = $this->getValueFromTable ("lkp_title", "lkp_title_id", $row["contact_title_ref"], "lkp_title_desc");
	$i++;
}
?>
