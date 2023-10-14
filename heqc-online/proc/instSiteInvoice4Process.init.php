<?php

$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;




$SQLinst =<<<SITEAPPHEAD
	SELECT  inst_site_app_proceedings.institution_ref, HEInstitution.HEI_name, HEInstitution.priv_publ,IF(inst_site_application.submition_date IS NULL,'no date',inst_site_application.submition_date) as submition_date,inst_site_application.siteapp_doc,inst_site_application.site_application_no
	FROM inst_site_app_proceedings, HEInstitution,inst_site_application
	WHERE inst_site_app_proceedings.institution_ref = HEInstitution.HEI_id  AND  inst_site_application.inst_site_app_id=inst_site_app_proceedings.inst_site_app_ref
	AND inst_site_app_proc_id = $site_proc_id
SITEAPPHEAD;
$Datesubmitted="";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);

if ($conn->connect_errno) {

    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);

    printf("Error: %s\n".$conn->error);

    exit();

}

$rs = mysqli_query($conn, $SQLinst);
			if (mysqli_num_rows($rs) > 0) {
				while ($row = mysqli_fetch_array($rs)) {					
                    $Datesubmitted=$row[submition_date];
            }}
    if($Datesubmitted != 'no date'){

        $usr_setting = ("usr_siteapp_payment");

    }else{

         $usr_setting = ("usr_site_payment");
    }
    
   

	$new_user = $this->getValueFromTable("settings", "s_key", $usr_setting, "s_value");
  




	$message = $this->getTextContent ("Siteapplication", "Siteapplicationnextprocces");

	$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
		
		$this->misMailByName($to, "Site Application ", $message);
		$id = $this->addActiveProcesses (226, $new_user);
		$this->completeActiveProcesses();	

		
?>
