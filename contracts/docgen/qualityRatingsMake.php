<?php
	require_once ('_systems/contract/contract.php');
	octoDB::connect ();
	writeXMLhead ();

	$typeSearch = readGET("typeSearch",0);
	$nameSearch = readGET("nameSearch","");
	$companySearch = readGET("companySearch","");
	$deliverySearch = readGET("deliverySearch","");
	$meetingSearch = readGET("meetingSearch","");
	$qualitySearch = readGET("qualitySearch","");
	$che_supervisor_user_ref = readGET("che_supervisor_user_ref","");

	$consult_whr = "";
	$contract_whr = "";
	$AND = "";


	$consult_whr_arr = array();
	$contract_whr_arr = array();

	if ($typeSearch > 0) array_push($consult_whr_arr,"type = $typeSearch");
	if ($nameSearch > '') array_push($consult_whr_arr,"name like '".$nameSearch."%' OR surname like '".$nameSearch."%'");
	if ($companySearch > '') array_push($consult_whr_arr,"company like '".$companySearch."%'");
	if ($deliverySearch > '') array_push($consult_whr_arr,"deliverydate_deadlines like '".$deliverySearch."%'");
	if ($meetingSearch > '') array_push($consult_whr_arr,"meeting_requirements like '".$meetingSearch."%'");
	if ($qualitySearch > '') array_push($consult_whr_arr,"quality_work like '".$qualitySearch."%'");
	if ($che_supervisor_user_ref > ''){
		$AND = " AND che_supervisor like '%".$che_supervisor_user_ref."%'";
	}

	if (count($consult_whr_arr) > 0) $consult_whr = "WHERE " . implode(" AND ",$consult_whr_arr);
	if (count($contract_whr_arr) > 0) $contract_whr = " AND " . implode(" AND ",$contract_whr_arr);


    $quality = "";
	if ($qualitySearch > ''){

		$quality = "AND oc.quality_work like '".$qualitySearch."'";
	 }



    $xml_main = "";

		$main_sql = <<<MAINSQL
			 SELECT oc.quality_work, c.type,
			 CONCAT(c.name," ",c.surname) as consultant,
			 CONCAT(c.name, " " , us.surname," ", us.email," ", us.contact_nr) AS supervisor,
			 c.company, ca.description
			 FROM owners_comments oc
			 LEFT JOIN d_consultant_agreements ca ON oc.agreement_ref = ca.agreement_id
			 LEFT JOIN d_consultants c ON ca.consultant_ref = c.consultant_id
			 LEFT JOIN users us ON oc.user_ref = us.user_id
			 WHERE ca.consultant_ref = c.consultant_id
			 AND oc.quality_work != "0"
			 $quality
			 ORDER BY ca.description
MAINSQL;


  	$main_rs = mysqli_query($main_sql) or die(mysqli_error());
	$main_numrec = mysqli_num_rows($main_rs);


	if ($main_numrec > 0){

		while ($main_row = mysqli_fetch_assoc($main_rs)){
			$d = $main_row;
			$d["type_desc"] = dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$d["type"],"lkp_consultant_type_desc");

			array_walk($d, 'fmt_value');

			include("qualityRatingsMain.php");



		}

          $xml_main = $xml_head . $xml_main;
	}

	include("qualityRatingsCover.php");

	include("qualityRatingsTemplate.php");


	// Note: Empty cells: <td></td> cells cause an error (undefined offset) on line 1409 in cls_rtf_driver
	// Setting a value to &nbsp; is a workaround. Another workaround is to have a font tag in the cell.
	function fmt_value(&$val, $key){
		$val = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($val == '1970-01-01') $val = "&nbsp;";
	}



?>
