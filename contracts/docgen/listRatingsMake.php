<?php
	require_once ('_systems/contract/contract.php');
	octoDB::connect ();
	writeXMLhead ();

	    $typeSearch = readGET("typeSearch",0);
		$companySearch = readGET("companySearch","");
		$deliverySearch = readGET("deliverySearch",0);
		$meetingSearch = readGET("meetingSearch","");
		$qualitySearch = readGET("qualitySearch","");
		$che_supervisor_user_ref = readGET("che_supervisor_user_ref","");

		$consult_whr = "";
		$contract_whr = "";
		$join ="";
		$AND = "";


   		$consult_whr_arr = array();
		$contract_whr_arr = array();

		if ($typeSearch > 0) array_push($consult_whr_arr,"type = $typeSearch");
		if ($companySearch > '') array_push($consult_whr_arr,"company like '".$companySearch."%'");
		if ($deliverySearch > 0) array_push($consult_whr_arr,"deliverydate_deadlines = $deliverySearch");
		if ($meetingSearch > '') array_push($consult_whr_arr,"meeting_requirements like '".$meetingSearch."%'");
		if ($qualitySearch > '') array_push($consult_whr_arr,"quality_work like '".$qualitySearch."%'");
		if ($che_supervisor_user_ref > ''){
				$AND = " AND che_supervisor like '%".$che_supervisor_user_ref."%'";
			}


		if (count($consult_whr_arr) > 0) $consult_whr = "WHERE " . implode(" AND ",$consult_whr_arr);
		if (count($contract_whr_arr) > 0 && count($consult_whr_arr) > 0)
		{
			$contract_whr = " AND " . implode(" AND ",$contract_whr_arr);
		}

        $delivery = "";
			 	if($deliverySearch != 0){
			 		$delivery = " AND oc.deliverydate_deadlines = $deliverySearch";
	    }
	$xml_main = "";

		$main_sql = <<<MAINSQL
			 SELECT oc.deliverydate_deadlines, c.type,
			 CONCAT(c.name," ",c.surname) as consultant,
			 c.company,
			 CONCAT(us.name, " " , us.surname," ", us.email," ", us.contact_nr) AS supervisor,
			 ca.description
			 FROM owners_comments oc
			 LEFT JOIN d_consultant_agreements ca ON oc.agreement_ref = ca.agreement_id
			 LEFT JOIN d_consultants c ON ca.consultant_ref = c.consultant_id
			 LEFT JOIN users us ON oc.user_ref = us.user_id
		     WHERE oc.agreement_ref = ca.agreement_id AND oc.deliverydate_deadlines !="0"
		     $delivery
		     ORDER BY oc.deliverydate_deadlines
MAINSQL;


	$main_rs = mysqli_query($main_sql) or die(mysqli_error());
	$main_numrec = mysqli_num_rows($main_rs);


	if ($main_numrec > 0){

		while ($main_row = mysqli_fetch_assoc($main_rs)){

			$d = $main_row;
			$d["type_desc"] = dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$d["type"],"lkp_consultant_type_desc");
			$d["deliverydate_deadlines"] = dbConnect::getValueFromTable("lkp_quality","lkp_quality_id",$d["deliverydate_deadlines"],"lkp_quality_desc");

            array_walk($d, 'fmt_value');

			include("listRatingsMain.php");



		}

          $xml_main = $xml_head . $xml_main;
	}

	include("listRatingsCover.php");

	include("listRatingsTemplate.php");


	// Note: Empty cells: <td></td> cells cause an error (undefined offset) on line 1409 in cls_rtf_driver
	// Setting a value to &nbsp; is a workaround. Another workaround is to have a font tag in the cell.
	function fmt_value(&$val, $key){
		$val = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($val == '1970-01-01') $val = "&nbsp;";
	}



?>
