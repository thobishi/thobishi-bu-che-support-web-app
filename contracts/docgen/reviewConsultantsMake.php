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
	if ($cheSupervisor > 0)	array_push($consult_whr_arr,"che_supervisor_user_ref = $cheSupervisor");

	if (count($consult_whr_arr) > 0) $consult_whr = "WHERE " . implode(" AND ",$consult_whr_arr);
	if (count($contract_whr_arr) > 0) $contract_whr = " AND " . implode(" AND ",$contract_whr_arr);

	$consult_order = "ORDER BY type";

	/*
	$sql = <<< SQL
		SELECT c.*,
		CONCAT(c.name, " ", c.surname) AS consultant,
		count(d_consultant_agreements.consultant_ref) AS total,
		d_consultant_agreements.status
		FROM d_consultants AS c,
		d_consultant_agreements
		WHERE consultant_id = consultant_ref
		AND d_consultant_agreements.status != 2
		GROUP BY consultant_ref
SQL;
*/
	$type = "";
	if($typeSearch != 0){
		$type = " AND c.type = $typeSearch";
	}

	if ($cheSupervisor > 0){
				$AND = " AND che_supervisor_user_ref = $cheSupervisor";
	}

	$xml_main = "";
	$main_ord = "name";
	$main_sql = <<<MAINSQL
		SELECT c.*,
		CONCAT(c.name, " ", c.surname) AS consultant,c.company,
		count(d_consultant_agreements.consultant_ref) AS total_agreements,
		d_consultant_agreements.status
		FROM d_consultants AS c,
		d_consultant_agreements
		WHERE consultant_id = consultant_ref
		$AND
		AND d_consultant_agreements.status != 2 $type
		GROUP BY consultant_ref
		ORDER BY consultant_ref
MAINSQL;

	$main_rs = mysqli_query($main_sql) or die(mysqli_error());
	$main_numrec = mysqli_num_rows($main_rs);

	if ($main_numrec > 0){

		while ($main_row = mysqli_fetch_assoc($main_rs)){
			$d = $main_row;
			$d["type_desc"] = dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$d["type"],"lkp_consultant_type_desc");


			array_walk($d, 'fmt_value');

			include("reviewConsultantsMain.php");


		}

		   $xml_main = $xml_head . $xml_main;
	}


	include("reviewConsultantsCover.php");

	include("reviewConsultantsTemplate.php");


	// Note: Empty cells: <td></td> cells cause an error (undefined offset) on line 1409 in cls_rtf_driver
	// Setting a value to &nbsp; is a workaround. Another workaround is to have a font tag in the cell.
	function fmt_value(&$val, $key){
		$val = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($val == '1970-01-01') $val = "&nbsp;";
	}


?>
