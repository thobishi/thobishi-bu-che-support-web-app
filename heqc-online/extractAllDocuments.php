<?php
// 1. Identify field names of institutional profile documents
// 2. Identify field names of application documents
// 3. Identify field names of re-accreditation documents
// 4. Identify all CHE doc,uments e.g. evaluator reports, outcome letters.
// 
// Process to use is 'show fields from table_name like '%doc,'
//
// 5. Produce a list of documents: Title : description : date so that Christa can identify the 
//    folders they must go to.
//
// There are two versions of application form.
//
// Extract all documents for an institution to PR000n folder.
// Extract all documents for an application to PR000n/E000nCAN folder.


function write_file($content){
		$f=fopen('/tmp/HEQC_documents.txt',"wb");
		fwrite($f,$content);
		fclose($f);
}

function get_doc_fields($table){
	$farr = array();

	$sql = "SHOW FIELDS FROM $table LIKE '%doc'";
	$rs = mysqli_query($sql) or die(mysqli_error());
	while ($row = mysqli_fetch_array($rs)){
		array_push($farr, $row[0]);
	}

	$dsql = "SHOW FIELDS FROM $table LIKE '%document_ref'";
	$drs = mysqli_query($dsql) or die(mysqli_error());
	while ($drow = mysqli_fetch_array($drs)){
		array_push($farr, $drow[0]);
	}
	
	return $farr;
}

function strip_chars($str){

	$str = iconv("ISO-8859-1","UTF-8//IGNORE", $str);

        $a_chr = array("'", "&",   "%20", "/", ",", "`", "▒", "?", "â", "û");
        $a_r   = array("", " and "," ",   "-", " ", "" , " ", "-", "a", "u");

	$str  = str_replace($a_chr, $a_r, $str);

	return $str;
}

function get_doc_data($doc_id, $dest_path){

	$html = "";

	$sql = <<<SQL
		SELECT * 
		FROM documents
		WHERE document_id = $doc_id
SQL;

	$rs = mysqli_query($sql) or die(mysqli_error());
	if ($row = mysqli_fetch_array($rs)){

		$filename = "/var/www/heqc-docs/" . $row["document_url"];
		// Check if physical file exists
		if (file_exists($filename)) {

			$dest_doc_name = ($row["document_name"] > '') ? $row["document_name"] : "Unnamed_" . $row["document_url"];

			$toSpace = array ("\t", ":", "?");
			$cleanDocName = str_replace($toSpace, " ", $dest_doc_name);
			$cleanDocName = iconv("ISO-8859-1","UTF-8//IGNORE",$cleanDocName);

				
			$destfile = $dest_path . "/" . trim($cleanDocName);

		    $rc = copy($filename, $destfile);

			if (!$rc) {
				die ("Error copying: '$filename' to '$destfile'\n");
			}
		} else {
		    echo ("\nThe sourcec file '$filename' does not exist\n");
		}

		
		// record document

		$html = <<<HTML
			<td>$row[document_id]</td>
			<td>$row[creation_date]</td>
			<td>$row[last_update_date]</td>
			<td>$row[document_name]</td>
			<td>$row[document_url]</td>
HTML;
	}

	return $html;
}

function get_reacc_docs($app_id,$app_path,$che_ref){

	$html = "";

	// Search for doc in database to identify any other tables.
	$tabl_arr = array(
		"reaccred_document"
		);

	foreach ($tabl_arr as $tabl){  // START FOR EACH APP TABLE

		$fld_arr = get_doc_fields($tabl);
	
		foreach ($fld_arr as $fld){  // START FOR EACH DOC FIELD IN APP TABLE


			/********************************************************************************/
			// STEP 5:
			//
			// Search through all document fields for a particular application and identify all valid document ids .				
			//
			// Get all document data for each id.
			//
			/********************************************************************************/	


			$sql = <<<SQL
				SELECT $fld 
				FROM $tabl 
				WHERE reaccred_programme_ref = $app_id
SQL;
			$rs = mysqli_query($sql) or die(mysqli_error());

			while ($row = mysqli_fetch_array($rs)){
				if ($row[0] > 0){
//echo "<p>$tabl: $fld: $row[0]: $app_path";
					$doc_str = get_doc_data($row[0], $app_path);
					if ($doc_str > ""){
						$html .= <<<HTML
							<tr>
							<td>$tabl</td>
							<td>$fld</td>
							<td>$che_ref</td>
							$doc_str
							</tr>
HTML;
	
					}
				}
			}
		} // END FOR EACH FIELD IN APP TABLE
	} // END FOR EACH APP TABLE

	return $html;
}

function get_app_docs($app_id,$app_path,$che_ref){
	$html = "";

	// Search for doc in database to identify any other tables.
	$tabl_arr = array(
		"ia_criteria_per_site", 
		"evalReport",
		"ia_proceedings"
		);

	foreach ($tabl_arr as $tabl){  // START FOR EACH APP TABLE

	
		$fld_arr = get_doc_fields($tabl);
		
		foreach ($fld_arr as $fld){  // START FOR EACH DOC FIELD IN APP TABLE


			/********************************************************************************/
			// STEP 5:
			//
			// Search through all document fields for a particular application and identify all valid document ids .				
			//
			// Get all document data for each id.
			//
			/********************************************************************************/	


			$sql = <<<SQL
				SELECT $fld 
				FROM $tabl 
				WHERE application_ref = $app_id
SQL;
			$rs = mysqli_query($sql) or die(mysqli_error());

			while ($row = mysqli_fetch_array($rs)){

				if ($row[0] > 0){
//echo "<p>$tabl: $fld: $row[0]: $app_path";
					$doc_str = get_doc_data($row[0], $app_path);
					if ($doc_str > ""){
						$html .= <<<HTML
							<tr>
							<td>$tabl</td>
							<td>$fld</td>
							<td>$che_ref</td>
							$doc_str
							</tr>
HTML;
	
					}
				}
			}
		} // END FOR EACH FIELD IN APP TABLE
	} // END FOR EACH APP TABLE

	return $html;
}


function get_docs($inst_id){

	/********************************************************************************/
	// STEP 2:
	//
	// Browse through all tables that have 'doc' fields and identify all document fields.				
	//
	// The assumption is made that a document field can be identified by having 'doc' 
	//	at the end of the field. This is based on the naming convention standard for  
	// 	HEQC-online and as far as I know this convention has been adhered to.
	//
	/********************************************************************************/


	$isql = <<<SQL
		SELECT HEI_code, HEI_name
		FROM HEInstitution
		WHERE HEI_id = $inst_id
SQL;
	$irs = mysqli_query($isql) or die(mysqli_error());
	
	// Institution ref on user record is not valid.
	if (mysqli_num_rows($irs) == 0) {
		$html = <<<HTML
			Institution id:  $inst_id has no corresponding record in the database.
HTML;
		return $html;
	}
	
	$irow = mysqli_fetch_array($irs);
	$inst_code = $irow["HEI_code"];

	$html = <<<HTML
		<p>
		Institution: $inst_code : $irow[HEI_name]: $inst_id
		<br>
		<br>
		<table border="1">
			<tr>
			<td>Table</td>
			<td>Field</td>
			<td>Application<br>(if applicable)</td>
			<td>document_id</td>
			<td>creation_date</td>
			<td>last_update_date</td>
			<td>document_name</td>
			<td>document_url</td>
			</tr>
HTML;

	$inst_name_dir  = strip_chars($irow["HEI_name"]);

//	echo "<p>$inst_id: " . "$inst_code $inst_name_dir";
	$dest_path = "/mnt/orange/13 PROGRAMME ACCREDITATION/13.5 Accreditation Management/HEQC online mirror/$inst_code $inst_name_dir";

	if (!file_exists($dest_path)){
		$mrc =  mkdir ($dest_path);
		if (!$mrc) die("Error trying to create directory: $dest_path" );
	}

	// Search for doc in database to identify any other tables.
	$tabl_arr = array("institutional_profile_pol_budgets_admission", 
		"institutional_profile_pol_budgets_assessment_eval", 
		"institutional_profile_pol_budgets_certification",
		"institutional_profile_pol_budgets_hr", 
		"institutional_profile_pol_budgets_infrastracture",
		"institutional_profile_pol_budgets_learning_strat", 
		"institutional_profile_pol_budgets_post_grad_pol", 
		"institutional_profile_pol_budgets_prog_design",
		"institutional_profile_pol_budgets_prog_offerings",
		"Institutions_application",
		"Institutions_application_reaccreditation"
		);

	foreach ($tabl_arr as $tabl){  // START FOR EACH TABLE

	
		$fld_arr = get_doc_fields($tabl);
		
		foreach ($fld_arr as $fld){  // START FOR EACH DOC FIELD IN TABLE


			/********************************************************************************/
			// STEP 3:
			//
			// Search through all document fields for a particular institution and identify all valid document ids .				
			//
			// Get all document data for each id.
			//
			/********************************************************************************/	


			$sql = <<<SQL
				SELECT $fld 
				FROM $tabl 
				WHERE institution_ref = $inst_id
SQL;

			switch ($tabl){
			case 'Institutions_application':
				$sql = <<<SQL
					SELECT $fld, CHE_reference_code 
					FROM $tabl 
					WHERE institution_id = $inst_id
					AND submission_date > '1970-01-01'
					AND application_status >= 0
SQL;
				// Do we want to exclude cancelled applications ?????  If yes - add
				// AND application_status >= 0
				break;
			case 'Institutions_application_reaccreditation':
				$sql = <<<SQL
					SELECT $fld, referenceNumber
					FROM $tabl 
					WHERE institution_ref = $inst_id
					AND reacc_submission_date > '1970-01-01'
					AND reacc_application_status >= 0
SQL;
			}

			$rs = mysqli_query($sql) or die(mysqli_error());

			while ($row = mysqli_fetch_array($rs)){

				$app_path = $dest_path;

				if ($row[0] > 0) {
					switch ($tabl){
					case'Institutions_application':
						$che_ref = $row[1];
						$app_dir = strip_chars($che_ref);
						$app_path = $dest_path . "/" . $app_dir;
						if (!file_exists($app_path)){
							$mrc =  mkdir ($app_path);
							if (!$mrc) die("Error trying to create directory: $app_path" );
						}
						$app_path = $app_path . "/Application submission";
						if (!file_exists($app_path)){
							$mrc =  mkdir ($app_path);
							if (!$mrc) die("Error trying to create directory: $app_path" );
						}
						break;
					case'Institutions_application_reaccreditation':
						$che_ref = $row[1];
						$app_dir = strip_chars($che_ref);
						$app_path = $dest_path . "/" . $app_dir;
						if (!file_exists($app_path)){
							$mrc =  mkdir ($app_path);
							if (!$mrc) die("Error trying to create directory: $app_path" );
						}
						$app_path = $app_path . "/Re-accreditation";
						if (!file_exists($app_path)){
							$mrc =  mkdir ($app_path);
							if (!$mrc) die("Error trying to create directory: $app_path" );
						}
						break;
					default:
						$app_path = $app_path . "/Institution profile";
						if (!file_exists($app_path)){
							$mrc =  mkdir ($app_path);
							if (!$mrc) die("Error trying to create directory: $app_path" );
						}
						$che_ref = "&nbsp;";
					}

					$doc_str = get_doc_data($row[0], $app_path);
					if ($doc_str > ""){

						$html .= <<<HTML
							<tr>
							<td>$tabl</td>
							<td>$fld</td>
							<td>$che_ref</td>
							$doc_str
							</tr>
HTML;
	
					}
				}
			}

		}  // END FOR EACH FIELD IN TABLE

	} // END FOR EACH TABLE

	// Get all application data for this institution and then get all documents for the application.
	
	$asql = <<<SQL
		SELECT application_id, CHE_reference_code
		FROM Institutions_application 
		WHERE institution_id = $inst_id
		AND submission_date > '1970-01-01'
		AND application_status >= 0
SQL;
	$ars = mysqli_query($asql) or die($asql . " " . mysqli_error());;
	while ($arow = mysqli_fetch_array($ars)){
		$app_id = $arow["application_id"];
		if ($app_id > 0 ){
			$che_ref = $arow["CHE_reference_code"];
			$app_dir = strip_chars($che_ref);
			$app_path = $dest_path . "/" . $app_dir;
			if (!file_exists($app_path)){
				$mrc =  mkdir ($app_path);
				if (!$mrc) die("Error trying to create directory: $app_path" );
			}
			
			$app_path = $app_path . "/Application submission";
			if (!file_exists($app_path)){
				$mrc =  mkdir ($app_path);
				if (!$mrc) die("Error trying to create directory: $app_path" );
			}
			
			$html .= get_app_docs($app_id,$app_path,$che_ref);
		}
	}

	// Get all reaccreditation data for this institution and then get all documents for the reaccreditation application.

	$rsql = <<<SQL
		SELECT Institutions_application_reaccreditation_id, referenceNumber
		FROM Institutions_application_reaccreditation
		WHERE institution_ref = $inst_id
		AND reacc_submission_date > '1970-01-01'
		AND reacc_application_status >= 0
SQL;
	$rrs = mysqli_query($rsql) or die($rsql . " " . mysqli_error());;
	while ($rrow = mysqli_fetch_array($rrs)){
		$reacc_app_id = $rrow["Institutions_application_reaccreditation_id"];
		if ($reacc_app_id > 0 ){

			$che_ref = $rrow["referenceNumber"];
			$app_dir = strip_chars($che_ref);
			$app_path = $dest_path . "/" . $app_dir;
			if (!file_exists($app_path)){
				$mrc =  mkdir ($app_path);
				if (!$mrc) die("<p>Error trying to create directory: $app_path" );
			}

			$reacc_app_path = $app_path . "/Re-accreditation";
			if (!file_exists($reacc_app_path)){
				$mrc =  mkdir ($reacc_app_path);
				if (!$mrc) die("<p>Error trying to create directory: $reacc_app_path" );
			}

			$html .= get_reacc_docs($reacc_app_id,$reacc_app_path,$che_ref);
		}
	}

	
	$html .= <<<HTML
		</table>
HTML;

	return $html;
	
}


	$dbhandle = mysqli_connect("localhost", "heqc", "w0rkflow")
	  or die("Unable to connect to MySQL");

	$selected = mysqli_select_db("CHE_heqconline",$dbhandle)
	  or die("Could not select database");


	/********************************************************************************/
	// STEP 1:
	//
	// Identify all institutions with administrators.				
	// The assumption is made that institutions with an administrator has been 
	// approved and may have documents.
	//
	/********************************************************************************/

	$sql = <<<SQL1
		SELECT distinct institution_ref
		FROM users, sec_UserGroups
		WHERE sec_user_ref = user_id 
		AND sec_group_ref = 4
		AND institution_ref NOT IN (1,2)
SQL1;

	$rs = mysqli_query($sql) or die(mysqli_error());
	while ($row = mysqli_fetch_array($rs)){
		$inst_id = $row["institution_ref"];

		if ($inst_id > 0 ){
			$html_str = get_docs($inst_id);
		}
		
		echo "."; // echo $html_str;
	}
	

	//close the connection
	mysqli_close($dbhandle);
?>
