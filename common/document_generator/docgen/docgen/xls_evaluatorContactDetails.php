<?php
	ini_set("memory_limit","128M");
	require_once("/var/www/html/common/xls_generator/cl_xls_generator.php");
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	octoDB::connect ();

	$search = new evalSearch();
//("Active, Date_entered, Evaluator, Auditor, National_Review_Evaluator, Surname, Names,	Initials, ID_Number, Race, Gender,	Disability,	Address_to_use,	Street_adr1, Street_adr2, Street_suburb, Street_city, Street_post_code, Post_adr1, Post_adr2, Post_suburb, Post_city,	Post_code, Work_Number,	Home_Number, Mobile_Number,	Fax_Number,	E_mail";
	/*$SQL = $search->buildSQL ("Active, Date_entered, Evaluator, Auditor, National_Review_Evaluator, Surname, Names,	Initials, ID_Number, Race, Gender,	Disability,	Address_to_use,	Street_adr1, Street_adr2, Street_suburb, Street_city, Street_post_code, Post_adr1, Post_adr2, Post_suburb, Post_city,	Post_code, Work_Number,	Home_Number, Mobile_Number,	Fax_Number,	E_mail", $_GET);*/
	$SQL = $search->buildSQL ("Active, Date_entered, Evaluator, Auditor, National_Review_Evaluator, Institutional_reviewer, Surname, Names,	Initials, ID_Number, Race, Gender,	Disability, Street_city as City, Work_Number, Mobile_Number, E_mail, Persnr, department as Department, highest_qual as Highest_Qualification", $_GET);	
//echo $SQL;
	$tmpFile = sprintf("/tmp/%07d.xls",rand(0,9999999));
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$RS = mysqli_query ($conn, $SQL);
	
	$xmlString = <<<TXT
<?php xml version="1.0" encoding="UTF-8"?>
<Workbook>
<Styles>
<!-- predefined styles -->
	<style name="plain_cell" font="Arial" bold="0" size="10" color="black" valign="top" align="left" border="thin" />
	<style name="heading" font="Arial" bold="1" size="10" valign="top" align="left" border="thin" color="black"/>
	<style name="date" num_format=" d mmmm yyy hh:mm:ss" />
	<style name="currency" num_format=" R#,##0.00" />
	<style name="percent" num_format=" 0.00%" />
	<style name="rotation" rotation="2" text_wrap="0" />
	<style name="border" align="center" border="dashed" />
	<style name="url" color="dodgerblue" underline="1" />

</Styles>
<Worksheet name="Evaluators Contact Details">
		<Print>
			<papersize index="9" />  
			<orientation landscape="1" />
			<RowTitle start="1" end="1" />
			<margin top="0.50" right="0.1" bottom="0.50" left="0.1" />
			<Header margin="0.2">&LEvaluator Contact Details</Header>
			<Footer margin="0.15">&LSource: HEQC-online&CPage &P of &N</Footer>
		</Print>
	<Table>
	<Row height="26">

TXT;

	for ($i=0; $i<mysqli_num_fields($RS); $i++) {
		$meta = mysqli_fetch_field($RS/*, $i*/);
		//var_dump();
		$colname = (isset($search->post_titles[$meta->name]))?($search->post_titles[$meta->name]):($meta->name);
		$xmlString .= "\t\t<cell width=\"28\" style=\"heading\">".$colname."</cell>\r\n";
	}
	// Add login email address for user
	$xmlString .= "\t\t<cell width=\"28\" style=\"heading\">Login</cell>\r\n";

	// Don't know how to get CESM in the title.  Can't add it to buildSQL list.
	$xmlString .= "\t\t<cell width=\"28\" style=\"heading\">CESM</cell>\r\n";

	$xmlString .= "\t</Row>\r\n";
	while ($row = mysqli_fetch_assoc($RS)) {
		/*echo '<pre>';
		print_r($row);
		echo '</pre>';*/
		$xmlString .= "\t<Row height=\"38\">\r\n";
		$login = $search->getLogin($row['Persnr']);
		$row['Login'] = $login;
		$cesm_arr = $search->getCESM($row['Persnr']);
		$cesm = implode("\n",$cesm_arr);
		$row['CESM'] = $cesm;

		foreach ($row AS $value) {
			if (substr ($value, 0, 1) == '@') {
				$value = " ".$value;
			}
			$xmlString .= "\t\t<cell style=\"plain_cell\">".utf8_encode($value)."</cell>\r\n";
		}
		$xmlString .= "\t</Row>\r\n";
	}
	

	$xmlString .= <<<TXT
	</Table>
</Worksheet>
</Workbook>

TXT;


// die ($xmlString);
	$xls = new xls($xmlString,$tmpFile,"xls_config.inc","/tmp/");
	

	set_time_limit(0);

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"evaluatorsContactDetails.xls\"");

	$f = fopen($tmpFile,"rb");
	while (!feof($f)) {
  	      $buffer = fgets($f, 4096);
  	      echo $buffer;
	}
	fclose ($f);
	unlink ($tmpFile);

	
?>
