<?php
	require_once ('_systems/contract/contract.php');
	octoDB::connect ();
	writeXMLhead ();

	/* get POST filter data and build restriction in $main_whr */

	$typeSearch = readGET("typeSearch",0);
	$nameSearch = readGET("nameSearch","");
	$companySearch = readGET("companySearch","");
	$statusSearch = readGET("statusSearch",0);
	$cheSupervisor = readGET("cheSupervisor",0);


	$consult_whr = "";
	$contract_whr = "";
	$join = "";

	$consult_whr_arr = array();
	$contract_whr_arr = array();

	if ($typeSearch > 0) array_push($consult_whr_arr,"type = $typeSearch");
	if ($nameSearch > '') array_push($consult_whr_arr,"name like '".$nameSearch."%' OR surname like '".$nameSearch."%'");
	if ($companySearch > '') array_push($consult_whr_arr,"company like '".$companySearch."%'");
	if ($cheSupervisor > 0){
				$join = "LEFT JOIN d_consultant_agreements ON d_consultant_agreements.consultant_ref = d_consultants.consultant_id ";
				array_push($consult_whr_arr,"che_supervisor_user_ref = $cheSupervisor");
	}
	if ($statusSearch > 0) array_push($contract_whr_arr,"status = $statusSearch");

	if (count($consult_whr_arr) > 0) $consult_whr = "WHERE " . implode(" AND ",$consult_whr_arr);
	if (count($contract_whr_arr) > 0) $contract_whr = " AND " . implode(" AND ",$contract_whr_arr);

	$consult_order = "ORDER BY type";



	$xml_main = "";
	$main_whr = 1;
	$main_ord = "type";
	$main_sql = <<<MAINSQL
			SELECT c.*, d.*
			FROM d_consultants as c
			LEFT JOIN d_consultant_agreements as d ON (consultant_ref = consultant_id)
			$consult_whr
			ORDER BY $main_ord
MAINSQL;

	$main_rs = mysqli_query($main_sql) or die(mysqli_error());
	$main_numrec = mysqli_num_rows($main_rs);

	if ($main_numrec > 0){

		while ($main_row = mysqli_fetch_assoc($main_rs)){

			$d = $main_row;

			$d["type"] = dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$d["type"],"lkp_consultant_type_desc");
			$d["title_desc"]= dbConnect::getValueFromTable("lkp_title","lkp_title_id",$d["title"],"lkp_title_desc");
			$d["status"] = dbConnect::getValueFromTable("lkp_agreement_status", "lkp_agreement_status_id", $d["status"], "lkp_agreement_status_desc");
			$d["consultant"] = ($d["type"] == 2) ? $d['company'] : $d['title_desc'] . ' ' . $d['name'] . ' ' . $d['surname']; // service provider or individual
			$eacc = $d["pastel_accnumber"];
			$d["calcexp"] = round(contractRegister::getSumExpenditure($eacc),2);
			$supstr = contractRegister::displaySupervisor($d["che_supervisor_user_ref"]);

			array_walk($d, 'fmt_value');

			include("consultantExpenditureMain.php");

		}
	}


	include("consultantExpenditureCover.php");

	include("consultantExpenditureTemplate.php");


	// Note: Empty cells: <td></td> cells cause an error (undefined offset) on line 1409 in cls_rtf_driver
	// Setting a value to &nbsp; is a workaround. Another workaround is to have a font tag in the cell.
	function fmt_value(&$val, $key){
		$val = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($val == '1970-01-01') $val = "&nbsp;";
	}


?>
