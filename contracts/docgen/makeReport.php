<?php

	function showRegCode ($n) {
		global $frm;

		return $frm->getRegCode($n);
	}

	function showCountry ($c) {
			return ucwords(strtolower($c));
		}

	$hostname="localhost";
	$username="fanrpan_agm2008";
	$password="reg4rm";
	$database="fanrpan_agm2008";
	mysqli_connect($hostname, $username , $password);
	mysqli_select_db($database);

	$SQL = "SELECT * FROM main_table LEFT JOIN country ON countrycode = country_code WHERE reg_id = '".$id."'";
	$rs = mysqli_query ($SQL);
	if (!($data = mysqli_fetch_assoc($rs))) die ('File not found.');

	require_once "document_generator/cl_xml2driver.php";

	function mkSearch(&$val, $key) {
		$val = '|_'.$val.'_|';
	}

	$file = "individual.php";
	ob_start();
	include($file);
	$xml_template = ob_get_contents();
	ob_end_clean();
	
	$keys = array_keys ($data);
	array_walk($keys, 'mkSearch');
//	print_r($keys); die();
	$xml_template = str_replace ($keys, $data, $xml_template);

	// this will be the name of our RTF file:
	$file_rtf = "fanrpan2008-".sprintf("%04d", $data['reg_id']).".doc";
	
	// creating class object specifying the driver type - "RTF"

	$xml = new nDOCGEN($xml_template,"RTF");

	$file_data = $xml->get_result_file();

?>
