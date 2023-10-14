<?
	$userid = $this->currentUserID;
	$count =  ((isset($_POST["count"])&&($_POST["count"]>0))?($_POST["count"]):(0));


	$select = "*";
	$whereArray = array('directorate_ref <> 0','project_full_title <> ""', 'category_ref = 3');
	$tableArray = array('project_detail AS p');
	$leftJoin = "LEFT JOIN lkp_directorate as d ON (p.directorate_ref = d.lkp_directorate_id )";
	$orderArray = array('directorate_ref','proj_code');

	// Users are restricted as to which projects they may see
	$sec = $this->getSecurityAccess($userid);
	if ($sec["filter"] > ""){
		array_push ($whereArray, $sec["filter"]);
	}

	$SQL = 'SELECT '.$select.' FROM '.implode (", ", $tableArray).' '. $leftJoin .' WHERE '. implode (" AND ", $whereArray)." ORDER BY ".implode (", ", $orderArray);

	$rs = mysqli_query($SQL);
	$numOfRows = mysqli_num_rows($rs);

	echo '<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2"><tr>'."\n";
	echo '<tr><td colspan="4">&nbsp;</td>'."\n";
	echo '</tr>';
	echo '<tr class="oncolourb"><td colspan="3">List of Events for CHE</td><td align="right" width="10%"><b>Total: '.$numOfRows.'</b></td></tr>'."\n";

	$prev_directorate = 0;

	while($row = mysqli_fetch_array($rs)){

		$directorate 		= $row["directorate_ref"];
		$directorate_desc 	= $row["directorate_description"];
// 20080922 Robin
// Project code may be different per year and cannot be taken from project record anymore.
//		$proj_code	 		= $row["proj_code"];
		$title				= $row["project_full_title"];

		if ($directorate <> $prev_directorate){
			echo '<tr><td colspan="4">&nbsp;</td></tr>'."\n";
			echo '<tr>'."\n";
			echo '<td colspan="4"><b>Programme: '.$directorate_desc.'</b></td>'."\n";
			echo '</tr>'."\n";
		}
//		echo '<tr class="'. $class . '">'."\n";
		echo '<tr>'."\n";

		$directorate_acronym = $row["directorate_acronym"];
//		$proj_code	 		 = $row["proj_code"];
		$title				 = $row["project_full_title"];

		echo "<td valign='top' width='2%'><a href='javascript:setProj(\"".$row["project_id"]."\");moveto(\"_startEditEventDetailForm\");'><img border=\'0\' src=\"images/ico_change.gif\"></a></td>\n";

//		echo '<td valign="top">'.$proj_code.'</td>'."\n";
		echo '<td valign="top">'.$title.'</td>'."\n";

		$prev_directorate = $directorate;

	} // while

	echo '<tr>'."\n";
	echo '</tr></table>'."\n";

?>
<SCRIPT>

function setProj(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='project_detail|'+val;
}

</SCRIPT>
