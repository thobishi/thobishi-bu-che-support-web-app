

<?php
    $conn = $this->getDatabaseConnection();
function submittedApplications($not_institution_type="", $type, $cols='*', $outcome=0)
{

	switch ($not_institution_type)
	{
		case "all":		$not_institution_type = ""; break;
		case "private": $not_institution_type = "2"; break;
		case "public":	$not_institution_type = "1"; break;
		default :		$not_institution_type = "";
	}


	$SQL  = "SELECT ".$cols." FROM Institutions_application as a, HEInstitution as b";
	$SQL .= " WHERE a.institution_id = b.HEI_id";
	$SQL .= " AND (a.submission_date > '1970-01-01') ";
	$SQL .= " AND (a.CHE_reference_code > '') ";
	$SQL .= " AND institution_id NOT IN (1, 2)";
	$SQL .= " AND b.priv_publ != '".$not_institution_type."'";
	$SQL .= ($type == "accredited") ? " AND a.AC_desision != ''" : "";
	$SQL .= ($type == "without") ? " AND a.AC_desision = '' AND a.application_status != -1" : "";
	$SQL .= ($type == "cancelled") ? " AND a.application_status = -1" : "";
	$SQL .= ($outcome) ? " AND a.AC_desision = '".$outcome."'" : "";

//echo $SQL;
	return $SQL;
}

function linkForTotals($total, $reportType, $reportParam, $tabs, $title_desc, $outcome="") {
		switch ($outcome) {
			case "Provisional Accreditation" : 	$outcome = "prov";
												break;
			case "Provisional Accreditation with Conditions" : 	$outcome = "provCond";
																break;
			case "Not Accredited" :	$outcome = "not";
									break;
			case "Deferred Accreditation" :	$outcome = "def";
											break;
			default : $outcome = "";
		}
		if ($total != 0) {
			echo $tabs;
			echo "<a href='javascript:void window.open(\"pages/reportAccreditedApplications.php?reportType=".$reportType."&reportParam=".$reportParam."&outcome=".$outcome."\");'>";
			echo $title_desc;
			echo "</a>";
		}
		else {echo $tabs.$title_desc;}
}

function priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs='', $category, $outcome="") {
		$reportParam = ($reportParam == "all") ? "" : $reportParam;
		echo "<tr class='onblue'>";
		echo '<td>';
		linkForTotals($row['total'], $reportType, $reportParam, $tabs,'Total '.$reportParam." ".$category, $outcome);

		for ($i=0; $i<$yearsSize; $i++)
		{
			echo "<td>".$row['year_'.$years[$i]]."</td>";
		}


		echo "<td class='oncolourb'>".$row['total']."</td>";
		echo "</tr>";
}

?>

	<br>

<?php 
	$ycol = array('count(*) as total');
	$years = array();

	$ySQL = "SELECT DISTINCT year(submission_date) as yearTotal FROM Institutions_application WHERE submission_date > '1970-01-01' ORDER BY yearTotal";
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$yRS = mysqli_query($conn, $ySQL);
	while ($yRow = mysqli_fetch_array($yRS))

	{
		$year_range   = $yRow['yearTotal'];
		$year_range_2 = $yRow['yearTotal']+1;

		array_push($ycol, "sum(IF((a.submission_date >= '".$year_range."-03-01') AND (a.submission_date <= '".$year_range_2."-02-29'), 1, 0)) as year_".$yRow['yearTotal']);
		array_push($years, $yRow['yearTotal']);
	}

	$yearsSize = sizeof($years);

	$reportCols = implode(', ', $ycol);


/*----------END OF FUNCTIONS--------------------*/



	echo '<table width="98%" border=0 align="center" cellpadding="0" cellspacing="0">';
	echo '<tr><td>';

	echo '<table width="100%" border=0 align="left" cellpadding="2" cellspacing="2">
			<tr>
				<td><span class="loud">Accreditation Application Overview Report:</span></td>
			</tr>';

	echo '<tr class="onblueb">
				<td>Submitted applications:</td>';
	for ($i=0; $i<$yearsSize; $i++)
	{
		$secondaryRange = substr($years[$i], 2, 2)+1;
		$dateRange = $years[$i]."/".$secondaryRange;
		echo "<td align='center'>".$dateRange."</td>";
	}

	echo "<td align='center'>Total</td>";
	echo '</tr>';
	echo '<tr><td>';


	$reportType = "submitted";
	$reportParam = "all";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tabs = '';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'submitted applications');
	}


	$reportType = "submitted";
	$reportParam = "private";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tot_rows = mysqli_num_rows($rs);
		$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'submitted applications');
	}


	$reportParam = "public";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'submitted applications');
	}


	echo "</td></tr>";

/*------------------------------*/
	echo '<tr><td>&nbsp;</td></tr>';
/*------------------------------*/

	echo '<tr class="onblueb">
				<td>Unprocessed submitted applications:</td>';
	for ($i=0; $i<$yearsSize; $i++)
	{
		$secondaryRange = substr($years[$i], 2, 2)+1;
		$dateRange = $years[$i]."/".$secondaryRange;
		echo "<td align='center'>".$dateRange."</td>";
	}

	echo "<td align='center'>Total</td>";
	echo '</tr>';
	echo '<tr><td>';


	$reportType = "without";
	$reportParam = "all";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tabs = '';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'unprocessed applications');
	}


	$reportType = "without";
	$reportParam = "private";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tot_rows = mysqli_num_rows($rs);
		$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'unprocessed applications');
	}


	$reportType = "without";
	$reportParam = "public";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'unprocessed applications');
	}


	echo "</td></tr>";

/*------------------------------*/
	echo '<tr><td>&nbsp;</td></tr>';
/*------------------------------*/

	echo '<tr class="onblueb">
				<td>Applications with AC outcomes:</td>';
	for ($i=0; $i<$yearsSize; $i++)
	{
		$secondaryRange = substr($years[$i], 2, 2)+1;
		$dateRange = $years[$i]."/".$secondaryRange;
		echo "<td align='center'>".$dateRange."</td>";
	}

	echo "<td align='center'>Total</td>";
	echo '</tr>';

	echo '<tr><td>';


	$reportType = "accredited";
	$reportParam = "all";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tabs = '';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'with outcomes');
	}

	$reportType = "accredited";
	$reportParam = "private";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'with outcomes');
	}

	$dSQL  = "SELECT * FROM lkp_desicion";
	$dSQL .= " WHERE  lkp_id != -1";

	$dRs = mysqli_query($conn, $dSQL);

	$reportType = "accredited";
	$reportParam = "private";

	while ($d_row = mysqli_fetch_array($dRs))
	{
		$SQL = submittedApplications($reportParam, $reportType, $reportCols, $d_row['lkp_id']);

		$rs = mysqli_query($conn, $SQL);

		while ($row = mysqli_fetch_array($rs))
		{
			$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'with outcomes: '.$d_row['lkp_title'], $d_row['lkp_title']);
		}

	}


	$reportType = "accredited";
	$reportParam = "public";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);
	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'with outcomes');
	}

	$dSQL  = "SELECT * FROM lkp_desicion";
	$dSQL .= " WHERE  lkp_id != -1";

	$dRs = mysqli_query($conn, $dSQL);

	$reportType = "accredited";
	$reportParam = "public";

	while ($d_row = mysqli_fetch_array($dRs))
	{
		$SQL = submittedApplications($reportParam, $reportType, $reportCols, $d_row['lkp_id']);

		$rs = mysqli_query($conn, $SQL);

		while ($row = mysqli_fetch_array($rs))
		{
			$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'with outcomes: '.$d_row['lkp_title'], $d_row['lkp_title']);
		}
	}


/*------------------------------*/
	echo '<tr><td>&nbsp;</td></tr>';
/*------------------------------*/


	echo '<tr class="onblueb">
				<td>Cancelled applications:</td>';
	for ($i=0; $i<$yearsSize; $i++)
	{
		$secondaryRange = substr($years[$i], 2, 2)+1;
		$dateRange = $years[$i]."/".$secondaryRange;
		echo "<td align='center'>".$dateRange."</td>";
	}

	echo "<td align='center'>Total</td>";
	echo '</tr>';
	echo '<tr><td>';


	$reportType = "cancelled";
	$reportParam = "all";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tabs = '';
		priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'cancelled applications');
	}


	$reportType = "cancelled";
	$reportParam = "private";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
		$tot_rows = mysqli_num_rows($rs);
			$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;';
			priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'cancelled applications');
	}


	$reportType = "cancelled";
	$reportParam = "public";
	$SQL = submittedApplications($reportParam, $reportType, $reportCols);

	$rs = mysqli_query($conn, $SQL);

	while ($row = mysqli_fetch_array($rs))
	{
			$tabs = '&nbsp;&nbsp;&nbsp;&nbsp;';
			priv_publDisplay($reportParam, $row, $reportType, $yearsSize, $years, $tabs, 'cancelled applications');
	}

	echo "</td></tr>";

/*------------------------------*/
	echo '<tr><td>&nbsp;</td></tr>';
/*------------------------------*/


	echo "</table>";




?>

