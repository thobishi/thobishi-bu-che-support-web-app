<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "accFormab7_v4";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Programme information > Report</span>";

$this->setFormDBinfo("Institutions_application", "application_id");
$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;



$this->formOnSubmit = "return checkFrm(this);";



// If the application does not have a CHE reference number, give one.

$inst_id = $this->getValueFromCurrentTable ("institution_id");

$this->scriptTail .= "\n"."ia_modes_of_delivery = new Array();\n";

	$sql = <<<QUAL1
	SELECT *
	FROM ia_modes_of_delivery
	WHERE application_ref = $current_id
	
QUAL1;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);

        if ($conn->connect_errno) {

            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);

            printf("Error: %s\n".$conn->error);

            exit();

        }
		$delivery_ref = 0;
	$rs = mysqli_query($conn, $sql);
	$prev_CESM = "";
	while ($row = mysqli_fetch_array($rs)) {
		if ($prev_CESM != $row[0]) {
			$this->scriptTail .= "ia_modes_of_delivery[\"".$row[0]."\"] = new Array();\n";
			$prev_CESM = $row[0];			
		}
		$ia_mode_of_delivery_id = $row['ia_mode_of_delivery_id'];
		//$contactp = $row['perc_contact'];
		//$onlinep = $row['perc_online'];
		$delivery_ref = $row['lkp_mode_of_delivery_ref'];
		

		$this->scriptTail .= 'ia_modes_of_delivery["'. $row[0] .'"] = new Array("' . $row[2]  . '");' . "\n";
	}

	$checkname = "GRID_".$ia_mode_of_delivery_id."\$ia_mode_of_delivery_id\$select_checkbox\$ia_modes_of_delivery";

$contactp = "GRID_$ia_mode_of_delivery_id\$ia_mode_of_delivery_id\$perc_contact\$ia_modes_of_delivery";
$onlinep  = "GRID_$ia_mode_of_delivery_id\$ia_mode_of_delivery_id\$perc_online\$ia_modes_of_delivery";



	$this->scriptTail .= <<<CHECKFORM
	function checkFrm(obj) {

		//alert(obj.$checkname.value);

		if (obj.MOVETO.value == 'next' ) {


			if (typeof obj.$checkname === 'undefined') {
				alert('Please click save first.');
						return false;
			  }


				if ((obj.$checkname.checked)) {	
						//alert((parseInt(obj.$contactp.value) + parseInt(obj.$onlinep.value)));		
					if(((parseInt(obj.$contactp.value) + parseInt(obj.$onlinep.value))) != 100 && ($delivery_ref == 2))
					{
						alert('Blended total must equal 100% ');
						return false;
					}
					
				}


			//alert('Please select the mode of provisioning ');
			//return false;

				
		}
	
		return true;
	}
CHECKFORM;

//$this->createField("application_id", "TEXT");





