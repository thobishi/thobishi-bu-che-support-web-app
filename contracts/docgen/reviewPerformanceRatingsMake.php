<?php
	require_once ('_systems/contract/contract.php');
	octoDB::connect ();
	writeXMLhead ();


	$deliverySearch = readGET("deliverySearch","");
	$meetingSearch = readGET("meetingSearch","");
	$qualitySearch = readGET("qualitySearch","");

	$whr = "";

		$whr_arr = array();


		if ($deliverySearch > "0") array_push($whr_arr,"owners_comments.deliverydate_deadlines = '".$deliverySearch."'");
	    if ($meetingSearch > "0") array_push($whr_arr,"owners_comments.meeting_requirements = '".$meetingSearch."'");
		if ($qualitySearch > "0") array_push($whr_arr,"owners_comments.quality_work = '".$qualitySearch."'");

	if (count($whr_arr)>0) $whr =" WHERE "." ". implode (' AND ',$whr_arr);

	$xml_main = "";


	$main_sql = <<<MAINSQL

	   SELECT CONCAT(c.name, " ", c.surname) AS consultant, c.company,c.consultant_id,d_consultant_agreements.che_supervisor_user_ref,
	          owners_comments.CHEcomment, owners_comments.deliverydate_deadlines, owners_comments.meeting_requirements,
	   	   	  owners_comments.quality_work, DATE(owners_comments.comment_date) as date,
	   	   	  users.name, users.surname, users.email, users.contact_nr,c.type,
	   	   	  d_consultant_agreements.description
	   	   	  FROM owners_comments
	   	   	  LEFT JOIN d_consultant_agreements ON owners_comments.agreement_ref = d_consultant_agreements.agreement_id
	   	   	  LEFT JOIN d_consultants c ON d_consultant_agreements.consultant_ref = c.consultant_id
	   	   	  LEFT JOIN users ON owners_comments.user_ref = users.user_id
	   	   	  $whr
	   	   	  ORDER BY d_consultant_agreements.description

MAINSQL;


	$main_rs = mysqli_query($main_sql) or die(mysqli_error());
	$main_numrec = mysqli_num_rows($main_rs);




	if ($main_numrec > 0){


		while ($main_row = mysqli_fetch_assoc($main_rs)){
			$d = $main_row;
			$d["type_desc"] = dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$d["type"],"lkp_consultant_type_desc");
			$supstr = contractRegister::displaySupervisor($d["che_supervisor_user_ref"]);

			array_walk($d, 'fmt_value');


			include("reviewPerformanceRatingsMain.php");



		}


   		  $xml_main = $xml_head . $xml_main;
	}


	include("reviewPerformanceRatingsCover.php");

	include("reviewPerformanceRatingsTemplate.php");


	// Note: Empty cells: <td></td> cells cause an error (undefined offset) on line 1409 in cls_rtf_driver
	// Setting a value to &nbsp; is a workaround. Another workaround is to have a font tag in the cell.
	function fmt_value(&$val, $key){
		$val = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($val == '1970-01-01') $val = "&nbsp;";
	}


?>
