<html>
<head>
	<LINK REL=StyleSheet HREF="../styles.css" TYPE="text/css">
</head>
<body>

<table cellpadding="2" cellspacing="2"><tr><td>
<?php 
	function getValueFromTable($table, $field, $key, $ret) {
		$SQL = "SELECT `$ret` FROM `$table` WHERE `$field` = \"$key\"";
		$rs = mysqli_query ($SQL);
		if ($row = mysqli_fetch_array ($rs)) {
			return ($row[0]);
		}
		return ("");
	}

	function getFullContactName($institution_ref) {
		$contact_title = getValueFromTable("lkp_title", "lkp_title_id", getValueFromTable("institutional_profile_sites", "institution_ref", $institution_ref, "contact_title_ref"), "lkp_title_desc");
		$contact_name = getValueFromTable("institutional_profile_sites", "institution_ref", $institution_ref, "contact_name")." ".getValueFromTable("institutional_profile_sites", "institution_ref", $institution_ref, "contact_surname");
		$contact_full_name = $contact_title." ".$contact_name;
		return $contact_full_name;
	}

$username = "heqc";
$password = "workflow";
$hostname = "localhost";
$dbname = "CHE_heqconline";

require_once ('_systems/heqc-online.php');

//connection to the database
$dbhandle = mysqli_connect($hostname, $username, $password)
  or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysqli_select_db($dbname,$dbhandle)
  or die("Could not select database");

/******************************************************************/
	//$doc = new octoDocGen ("projectBudget", "budget=".$budget_year."&proj_source=".$project_source."&user=".$userid);
	//$doc->url ("Download report as document");
/******************************************************************/


  $SQL = "SELECT * FROM HEInstitution WHERE HEI_id=53 ORDER BY HEI_name";
  $RS = mysqli_query($SQL);

  while ($row = mysqli_fetch_array($RS)) {

	echo "<table border='0' cellpadding='2' cellspacing='2' width='95%' align='center'>";
  	echo "<tr><td><u><b>INSTITUTION DETAILS</b></u></td></tr>";
  	echo "<tr><td>";
		echo "<table border='0' cellpadding='2' cellspacing='2' width='50%'>";

		echo "<tr><td width='10%' valign='top'><b>Institution name</b></td><td width='20%'>".$row["HEI_name"]."</td></tr>";
		echo "<tr><td valign='top'><b>Type</b></td><td>".getValueFromTable("lnk_priv_publ", "lnk_priv_publ_id", getValueFromTable("HEInstitution", "HEI_id", $row["HEI_id"], "priv_publ"), "lnk_priv_publ_desc")."</td></tr>";
		echo "<tr><td valign='top'><b>Mode</b></td><td>".getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", getValueFromTable("institutional_profile", "institution_ref", $row["HEI_id"], "mode_delivery"), "lkp_mode_of_delivery_desc")."</td></tr>";
		echo "<tr><td valign='top'><b>Main site name</b></td><td>".getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "site_name")."</td></tr>";
		echo "<tr><td valign='top'><b>Contact name</b></td><td>".getFullContactName($row["HEI_id"]."AND main_site=1")."</td></tr>";
		echo "<tr><td valign='top'><b>Telephone number</b></td><td>".getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "contact_nr")."</td></tr>";
		echo "<tr><td valign='top' valign='top'><b>Fax no.</b></td><td>".getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "contact_fax_nr")."</td></tr>";
		echo "<tr><td valign='top'><b>Email</b></td><td>".getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "contact_email")."</td></tr>";
		echo "<tr><td valign='top'><b>Physical address</b></td><td>".simple_text2html(getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "address"))."</td></tr>";
		echo "<tr><td valign='top'><b>Postal address</b></td><td>".simple_text2html(getValueFromTable("institutional_profile_sites", "institution_ref", $row["HEI_id"]."AND main_site=1", "postal_address"))."</td></tr>";

		echo "</table>";
	echo "</td></tr>";

  	echo "<tr><td>";
	echo "<b>Additional sites of delivery:</b>";

	$s_SQL = "SELECT * FROM institutional_profile_sites WHERE institution_ref=".$row["HEI_id"]." AND main_site != 1";
    $s_RS = mysqli_query($s_SQL);

	if (mysqli_num_rows($s_RS) > 0) {
		echo "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
		echo "<tr><td colspan='4' align='right'>Total sites of delivery: ".mysqli_num_rows($s_RS)."</td></tr>";
		while ($s_row = mysqli_fetch_array($s_RS)) {
			echo "<tr>";
			echo "<td valign='top' width='25%'><b>Site name</b></td>";
			echo "<td width='15%'><b>Contact</b></td>";
			echo "<td><b>Contact no.</b></td>";
			echo "<td><b>Email</b></td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td rowspan='3' valign='top'>".$s_row["site_name"]."</td>";
			echo "<td>".getFullContactName($s_row["institution_ref"])."</td>";
			echo "<td>".$s_row["contact_nr"]."</td>";
			echo "<td>".$s_row["contact_email"]."</td>";
			echo "</tr>";

			echo "<tr><td valign='top'><b>Physical address:</b></td><td colspan='2'>".$s_row["address"]."</td></tr>";
			echo "<tr><td valign='top'><b>Postal address:</b></td><td colspan='2'>".$s_row["postal_address"]."</td></tr>";
		}
		echo "</table>";
	}
	else {
	 echo "<br><i>No additional sites exist for this institution</i>";
	}

	echo "<br><br>";

	echo "</td></tr>";

	echo "<tr><td><u><b>PROGRAMME DETAILS</b></u></td></tr>";
	echo "<tr><td>";

	$p_SQL = "SELECT * FROM Institutions_application WHERE institution_id = ".$row["HEI_id"]." AND AC_desision IN (1,2)";
  	$p_RS = mysqli_query($p_SQL);

	if (mysqli_num_rows($p_RS) > 0) {
		echo "<table border='1' cellpadding='2' cellspacing='2' width='100%' align='center'>";
		echo "<tr><td colspan='7' align='right'>Total accredited applications: ".mysqli_num_rows($p_RS)."</td></tr>";
		while ($p_row = mysqli_fetch_array($p_RS)) {
			echo "<tr>";
			echo "<td width='15%'><b>Programme name</b></td>";
			echo "<td><b>Designation</b></td>";
			echo "<td><b>Mode</b></td>";
			echo "<td><b>Duration</b></td>";
			echo "<td><b>NQF level</b></td>";
			echo "<td><b>No. of credits</b></td>";
			echo "<td><b>CESM category</b></td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td rowspan='4' valign='top'>".$p_row["program_name"]."<br>- ".$p_row["CHE_reference_code"]."</td>";
			echo "<td>".$p_row["designation"]."</td>";
			echo "<td>".getValueFromTable("lkp_mode_of_delivery", "lkp_mode_of_delivery_id", $p_row["mode_delivery"], "lkp_mode_of_delivery_desc")."</td>";
			echo "<td>".$p_row["expected_min_duration"]."</td>";
			echo "<td>".getValueFromTable("NQF_level", "NQF_id", $p_row["NQF_ref"], "NQF_level")."</td>";
			echo "<td>".$p_row["num_credits"]."</td>";
			echo "<td>".getValueFromTable("SpecialisationCESM_code1", "CESM_code1", $p_row["CESM_code1"], "Description")."</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td valign='top'>Sites of delivery</td>";

			$sites_SQL = "SELECT * FROM lkp_sites, Institutions_application WHERE application_ref = application_id AND application_id=".$p_row["application_id"];
			$sites_rs = mysqli_query($sites_SQL);
			echo "<td colspan='5' valign='top'>";
			$delimiter = '';

			while($sites_row = mysqli_fetch_array($sites_rs))
			{
				echo $delimiter;
				echo getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $sites_row["sites_ref"], "site_name");
				$delimiter = ", ";
			}
			echo "</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td>Status</td>";
			echo "<td>Date</td>";
			echo "<td colspan='4'>Conditions/comments</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td valign='top'>".getValueFromTable("lkp_desicion", "lkp_id", $p_row["AC_desision"], "lkp_title")."</td>";
			echo "<td valign='top'>".$p_row["AC_Meeting_date"]."</td>";
			echo "<td colspan='4'>".simple_text2html($p_row["AC_conditions"])."</td>";
			echo "</tr>";

			echo "<tr><td colspan='7' height='1px' color='#000000'></td></tr>";
		}
		echo "</table>";
	}
	else {
	 echo " <i>This institution has no accredited applications.</i><br>";
	}

	echo "<br>";
	echo "<hr>";

	echo "</td></tr>";
	echo "</table>";

 }
?>



<?php 

//close the connection
mysqli_close($dbhandle);

?>

</td></tr></table>
</body>
</html>

