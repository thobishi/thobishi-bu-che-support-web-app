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

	$type = "";
	if($typeSearch != 0){
		$type = " AND c.type = $typeSearch";
	}

	if ($cheSupervisor > 0)	{
	      $AND = " AND che_supervisor_user_ref = $cheSupervisor";
	}

	$xml_main = "";
	$main_ord = "description";
/*
	$main_sql = <<<MAINSQL
		SELECT * FROM owners_comments
		SELECT c.*,
		IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant,
		count(d_consultant_agreements.consultant_ref) AS total_agreements,
		d_consultant_agreements.status
		FROM d_consultants AS c,
		d_consultant_agreements
		WHERE consultant_id = consultant_ref
		$AND
		AND d_consultant_agreements.status != 2
		$type
		GROUP BY consultant_ref
MAINSQL;
*/

	$main_sql = <<<MAINSQL

	   SELECT c.*,
	   	   		CONCAT(c.name, " ", c.surname) AS consultant, c.company,
	   	   		users.user_id,d_consultant_agreements.che_supervisor_user_ref,
	   	   		owners_comments.CHEcomment, owners_comments.deliverydate_deadlines, owners_comments.meeting_requirements,
	   	   		owners_comments.quality_work, DATE(owners_comments.comment_date) as date,
	   	   		c.type,users.name, users.surname,
	   	   		users.email, users.contact_nr,
	   	   		d_consultant_agreements.description,
	   	   		d_consultant_agreements.status
	   	   		FROM d_consultants AS c,
	   	   		d_consultant_agreements
	   	   		LEFT JOIN owners_comments ON owners_comments.agreement_ref = d_consultant_agreements.agreement_id
	   	   		LEFT JOIN users ON owners_comments.user_ref = users.user_id
	   	   		WHERE consultant_id = consultant_ref
	   	   		AND owners_comments.deliverydate_deadlines !="0" AND owners_comments.meeting_requirements != "0" AND
	   			owners_comments.quality_work != "0"
	   	   		$AND
	   	   		AND d_consultant_agreements.status != 2
	   	   		$type
	   	        ORDER BY description, date DESC

MAINSQL;


	$main_rs = mysqli_query($main_sql) or die(mysqli_error());
	$main_numrec = mysqli_num_rows($main_rs);


	if ($main_numrec > 0){

		while ($main_row = mysqli_fetch_assoc($main_rs)){
			$d = $main_row;
			$d["type_desc"] = dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$d["type"],"lkp_consultant_type_desc");
            $supstr = contractRegister::displaySupervisor($d["che_supervisor_user_ref"]);

			array_walk($d, 'fmt_value');

			include("reviewRatingsMain.php");



		}


		  $xml_main = $xml_head . $xml_main;
	}


	include("reviewRatingsCover.php");

	include("reviewRatingsTemplate.php");


	// Note: Empty cells: <td></td> cells cause an error (undefined offset) on line 1409 in cls_rtf_driver
	// Setting a value to &nbsp; is a workaround. Another workaround is to have a font tag in the cell.
	function fmt_value(&$val, $key){
		$val = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($val == '1970-01-01') $val = "&nbsp;";
	}


?>
