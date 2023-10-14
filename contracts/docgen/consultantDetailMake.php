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
	$join ="";
	$AND = "";


	$consult_whr_arr = array();
	$contract_whr_arr = array();

	if ($typeSearch > 0) array_push($consult_whr_arr,"type = $typeSearch");
	if ($nameSearch > '') array_push($consult_whr_arr,"name like '".$nameSearch."%' OR surname like '".$nameSearch."%'");
	if ($companySearch > '') array_push($consult_whr_arr,"company like '".$companySearch."%'");
	if ($cheSupervisor > 0)	array_push($consult_whr_arr,"che_supervisor_user_ref = $cheSupervisor");
	if ($statusSearch > 0) array_push($contract_whr_arr,"d_consultant_agreements.status = $statusSearch");

	if (count($consult_whr_arr) > 0) $consult_whr = "AND " . implode(" AND ",$consult_whr_arr);
	if (count($contract_whr_arr) > 0) $contract_whr = " AND " . implode(" AND ",$contract_whr_arr);

	$consult_order = "ORDER BY surname";


	$AND = " AND che_supervisor_user_ref = $cheSupervisor";



/*
	$sql = <<< SQL
		SELECT *
		FROM d_consultants

		$join
		$consult_whr
		$contract_whr
		$consult_order
SQL;
*/
	$xml_main = "";

	$main_sql = <<<MAINSQL
		 SELECT *
				FROM d_consultants,d_consultant_agreements
				WHERE consultant_id = consultant_ref
				$consult_whr
				$contract_whr
				$consult_order
MAINSQL;

	$main_rs = mysqli_query($main_sql) or die(mysqli_error());
	$main_numrec = mysqli_num_rows($main_rs);

	if ($main_numrec > 0){

		while ($main_row = mysqli_fetch_assoc($main_rs)){
			$d = $main_row;
			$d["title"] = dbConnect::getValueFromTable("lkp_title","lkp_title_id",$d["title"],"lkp_title_desc");
			$d["type"] = dbConnect::getValueFromTable("lkp_consultant_type","lkp_consultant_type_id",$d["type"],"lkp_consultant_type_desc");
			$d["status"] = dbConnect::getValueFromTable("lkp_status","lkp_status_id",$d["status"],"lkp_status_desc");
			$d["gender"] = dbConnect::getValueFromTable("lkp_gender","lkp_gender_id",$d["gender"],"lkp_gender_desc");
			$d["race"] = dbConnect::getValueFromTable("lkp_race","lkp_race_id",$d["race"],"lkp_race_desc");
			$d["consultant"] = $d['title'] . ' ' . $d['name'] . ' ' . $d['surname'];
			$d["l_service_delivery"] = dbConnect::getValueFromTable("lkp_service_delivery","lkp_service_delivery_id",$d["service_delivery_ref"],"lkp_service_delivery_desc");
			$d["deliverydate_deadlines_desc"] = dbConnect::getValueFromTable("lkp_quality", "lkp_quality_id", $d["deliverydate_deadlines"], "lkp_quality_desc");
			$d["meeting_requirements_desc"] = dbConnect::getValueFromTable("lkp_quality", "lkp_quality_id", $d["meeting_requirements"], "lkp_quality_desc");
			$d["quality_work_desc"] = dbConnect::getValueFromTable("lkp_quality", "lkp_quality_id", $d["quality_work"], "lkp_quality_desc");
            $supstr = contractRegister::displaySupervisor($d["che_supervisor_user_ref"]);

			array_walk($d, 'fmt_value');

			include("consultantDetailMain.php");

			$xml_main .= build_contract_comments($d["agreement_id"]);
			$xml_main .= build_contract_documents($d["agreement_id"]);
			$xml_main .= build_performance_rating($d["agreement_id"]);
		}
	}


	include("consultantDetailCover.php");

	include("consultantDetailTemplate.php");


	// Note: Empty cells: <td></td> cells cause an error (undefined offset) on line 1409 in cls_rtf_driver
	// Setting a value to &nbsp; is a workaround. Another workaround is to have a font tag in the cell.
	function fmt_value(&$val, $key){
		$val = htmlspecialchars($val);
		$val = ($val > "") ? str_replace("\n","<br />",$val) : "&nbsp;";
		if ($val == '1970-01-01') $val = "&nbsp;";
	}


	function build_contract_comments($id){
		$xml = "";
		$whr = "agreement_ref = " . $id;
		$ord = "comment_date";
		$sql = <<<C1SQL
			SELECT *
			FROM d_agreement_comments
			WHERE $whr
			ORDER BY $ord
C1SQL;

		$rs = mysqli_query($sql) or die(mysqli_error());
		$numrec = mysqli_num_rows($rs);

		if ($numrec > 0){

			while ($row = mysqli_fetch_assoc($rs)){
				$d = $row;

				array_walk($d, 'fmt_value');

				include("consultantDetailContractComments.php");
			}
			$xml = $xml_head . $xml;
		} else {

		   $noData = "-- No comments currently --";
		   include("consultantDetailContractComments.php");
		   $xml = $xml_head . $xml;
		}

		return $xml;
	}

	function build_contract_documents($id){
		$xml = "";
		$whr = "agreement_ref = " . $id;
		$ord = "last_update_date";
		$sql = <<<C1SQL
			SELECT *,DATE(documents.last_update_date) as date
			FROM  d_agreement_docs, documents
			WHERE agreement_doc = document_id
			AND $whr
			ORDER BY $ord
C1SQL;


		$rs = mysqli_query($sql) or die(mysqli_error());
		$numrec = mysqli_num_rows($rs);

		if ($numrec > 0){

			while ($row = mysqli_fetch_assoc($rs)){
				$d = $row;
				$d["document_type"] = dbConnect::getValueFromTable("lkp_document_type","lkp_document_type_id",$d["document_type_ref"],"lkp_document_type_desc");

				array_walk($d, 'fmt_value');

				include("consultantDetailContractDocuments.php");
			}

			$xml = $xml_head . $xml;
		} else {

		   $noData = "-- No documents currently --";
		   include("consultantDetailContractDocuments.php");
		   $xml = $xml_head . $xml;
		}

		return $xml;
	}

	function build_performance_rating($id){
		$xml = "";
		$whr = "agreement_ref = " . $id;
		$p_sql = <<<C1SQL
			SELECT DATE(comment_date) as date,CONCAT(users.name," ",users.surname) as user, deliverydate_deadlines,CHEcomment,
			meeting_requirements, quality_work
			FROM owners_comments,users
			WHERE user_ref = user_id
			AND $whr
			ORDER BY date
C1SQL;

			$rs = mysqli_query($p_sql) or die(mysqli_error());
			$numrec = mysqli_num_rows($rs);

			if ($numrec > 0){

				while ($row = mysqli_fetch_assoc($rs)){
					$d = $row;

					array_walk($d, 'fmt_value');

					include("consultantDetailPerformance.php");
				}
				$xml = $xml_head . $xml;
			} else {

			   $noData = "-- No performance ratings currently --";
			   include("consultantDetailPerformance.php");
			   $xml = $xml_head . $xml;
		}

			return $xml;
	}


?>
