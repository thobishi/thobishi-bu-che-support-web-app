<?php
	require_once ('_systems/contract/contract.php');
	octoDB::connect ();
	writeXMLhead ();

	$typeSearch = readGET("typeSearch",0);
	$nameSearch = readGET("nameSearch","");
	$companySearch = readGET("companySearch","");
	$statusSearch = readGET("statusSearch",0);
	$cheSupervisor = readGET("cheSupervisor",0);


	$consult_whr = "";
	$contract_whr = "";
	$AND = "";


	$consult_whr_arr = array();
	$contract_whr_arr = array();

	if ($typeSearch > 0) array_push($consult_whr_arr,"type = $typeSearch");
	if ($nameSearch > '') array_push($consult_whr_arr,"name like '".$nameSearch."%' OR surname like '".$nameSearch."%'");
	if ($companySearch > '') array_push($consult_whr_arr,"company like '".$companySearch."%'");
	if ($statusSearch > 0) array_push($contract_whr_arr,"status = $statusSearch");
	if ($cheSupervisor > 0) array_push($consult_whr_arr,"che_supervisor_user_ref = $cheSupervisor");

	if (count($consult_whr_arr) > 0) $consult_whr = "WHERE " . implode(" AND ",$consult_whr_arr);
	if (count($contract_whr_arr) > 0) $contract_whr = " AND " . implode(" AND ",$contract_whr_arr);

	$consult_order = "ORDER BY type";

	$type = "";
			if($typeSearch != 0){
				$type = " AND c.type = $typeSearch";
	}

	if ($cheSupervisor > 0) {

		      $AND = " AND che_supervisor_user_ref = $cheSupervisor";

     }
/*
	$sql = <<< SQL
		SELECT c.consultant_id,
			   IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant,
			   a.description,
			   c.email,
			   c.type,
			   a.start_date,
			   a.end_date
	   FROM d_consultant_agreements AS a
	   LEFT JOIN d_consultants AS c
	   ON consultant_ref=consultant_id
	   WHERE end_date < DATE_ADD('$curr_date', INTERVAL 3 MONTH)
	   AND a.status != 2
SQL;
*/
	$type = "";
	if($typeSearch != 0){
		$type = " AND c.type = $typeSearch";
	}
	$xml_main = "";
	$curr_date = date('Y-m-d');
	$main_ord = "name";
	$main_sql = <<<MAINSQL
		SELECT c.consultant_id,
		IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant,
		a.description,
		c.email,
		c.type,
		a.che_supervisor_user_ref,
		a.start_date,
		a.end_date
		FROM d_consultant_agreements AS a
		LEFT JOIN d_consultants AS c
		ON consultant_ref=consultant_id
		WHERE end_date < DATE_ADD('$curr_date', INTERVAL 3 MONTH)
		$AND
		AND a.status != 2 $type
MAINSQL;

	$main_rs = mysqli_query($main_sql) or die(mysqli_error());
	$main_numrec = mysqli_num_rows($main_rs);

	if ($main_numrec > 0){

		while ($main_row = mysqli_fetch_assoc($main_rs)){
			$d = $main_row;
			$d["type_desc"] = dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$d["type"],"lkp_consultant_type_desc");
			$supstr = contractRegister::displaySupervisor($d["che_supervisor_user_ref"]);


			array_walk($d, 'fmt_value');

			include("contractsExpiringMain.php");


		}

			$xml_main = $xml_head . $xml_main;
	}



	include("contractsExpiringCover.php");

	include("contractsExpiringTemplate.php");


	// Note: Empty cells: <td></td> cells cause an error (undefined offset) on line 1409 in cls_rtf_driver
	// Setting a value to &nbsp; is a workaround. Another workaround is to have a font tag in the cell.
	function fmt_value(&$val, $key){
		$val = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($val == '1970-01-01') $val = "&nbsp;";
	}


?>
