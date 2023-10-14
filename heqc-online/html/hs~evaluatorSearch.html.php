<?php
print_r($_POST);
	$s_cesm = readPost('CESM_code1');
	$s_name = readPost('searchText');
	$s_mail = readPost('searchEmail');

	$whereArray = array();

	if ($s_cesm != 0) {
			// 2010-06-10 Robin: Cannot join to Specialisation link because of one to many relationship with Eval_Auditors
			array_push ($whereArray, "Persnr IN (SELECT Persno_ref FROM SpecialisationLink WHERE CESM_code_ref LIKE '".$s_cesm."%'");
			$this->formFields["CESM_code1"]->fieldValue = $s_cesm;
		}

	if ($s_name > "") {
		// 2010-06-10 Robin: Removed Match because does not match part of a name unless you specify a wildcard. Not correct context here.
		//array_push ($whereArray, "MATCH(Names, Surname, Initials, ID_Number) AGAINST('".$_POST["searchText"]."' IN BOOLEAN MODE)");
		array_push ($whereArray, "UPPER(Names) LIKE '".strtoupper($s_name)."%' OR UPPER(Surname) LIKE '".strtoupper($s_name)."%'");
		$this->formFields["searchText"]->fieldValue = $s_name;
	}

	if ($s_mail > ""){
		array_push ($whereArray, "UPPER(E_mail) LIKE '%".strtoupper($s_mail)."%'");
		$this->formFields["searchEmail"]->fieldValue = $s_mail;
	}

	$filter_criteria = "";
	if (count($whereArray) > 0) $filter_criteria = "WHERE ". implode (" AND ", $whereArray);

?>
	<br>
	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td class="loud">Evaluator administration:</td>
		</tr>
		<tr>
			<td>
				<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
				<tr>
					<td align="right">
					Name or surname:  
					</td>
					<td>
						<?php $this->showField('searchText');	?>
					</td>
					<td rowspan="3" valign="bottom" align="left">
						<input type="button" class="btn" name="submitButton" value="Search" onClick="moveto('stay');">
					</td>
				</tr>
				<tr>
					<td align="right">
					Email:  
					</td>
					<td>
						<?php $this->showField('searchEmail');	?>
					</td>
				</tr>
				<tr>
					<td align="right">
					CESM category:
					</td>
					<td>
						<?php $this->showField('CESM_code1');	?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
	</table>
	<hr>

<?php 
	//$count =  ((isset($_POST["count"])&&($_POST["count"]>0))?($_POST["count"]):(0));

	if ($filter_criteria > "" OR isset($_POST['submitButton'])){

		$SQL = <<<SQL
			SELECT Eval_Auditors.*, 
					lkp_yes_no.lkp_yn_desc
					CONCAT(Eval_Auditors.Surname,", ",Eval_Auditors.Names, " (", lkp_title.lkp_title_desc, ")") AS Fullname 
			FROM Eval_Auditors
			LEFT JOIN lkp_title ON  lkp_title.lkp_title_id = Eval_Auditors.Title_ref
			LEFT JOIN lkp_yes_no ON lkp_yn_id = Eval_Auditors.active
			$filter_criteria
			ORDER BY Surname, Names;
SQL;

// echo $SQL;
//		$SQL .= " LIMIT ".$count.", 10";
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$rs = mysqli_query($conn, $SQL);
		$numOfRows = mysqli_num_rows($rs);
?>

	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr class="Loud">
		<td class="Loud">
			List of Evaluators
		</td>
		<td class="Loud" align="right"><?php echo "Number of evaluators: " . $numOfRows; ?>
		</td>
	</tr>
	<tr>
	<td colspan="2">
		<table class="saphireframe" width="100%" border=0  cellpadding="2" cellspacing="0">
			<tr class="doveblox">
				<td class="doveblox">Edit</td>
				<td class="doveblox">Enabled?</td>
				<td class="doveblox">Name</td>
				<td class="doveblox">Telephone no.</td>
				<td class="doveblox">Email</td>
				<td class="doveblox">Evaluator / Auditor / National Review evaluator</td>
				<td class="doveblox">Employer<br>number</td>
<?php
				// If user is the Capacity Building Group Administrator
				if ($this->sec_partOfGroup(9)){
					echo '<td width="5%"><strong>Click to Delete</strong></td>';
				}
?>
			</tr>

<?php
		if (mysqli_num_rows($rs) > 0) {
			while($row = mysqli_fetch_array($rs)){

			$edit_link = $this->scriptGetForm ('Eval_Auditors', $row["Persnr"], '_gotoEvaluatorForm2');

			$isEval 		= ($row["Evaluator"]) ? "Evaluator" : "";
			$isAud			= ($row["Auditor"]) ? "Auditor" : "";
			$isNatReviewer 	= ($row["National_Review_Evaluator"]) ? "National Review evaluator" : "";

			$cheJob  = $isEval;
			$cheJob .= (($isEval) && ($isAud)) ? "<br>".$isAud : $isAud;
			$cheJob .= ((($isEval) || ($isAud)) && ($isNatReviewer)) ? "<br>".$isNatReviewer." " : $isNatReviewer;

			// If user is the Capacity Building Group Administrator
			$del_link = "";
			if ($this->sec_partOfGroup(9)){
				$del_link =  '<td><a href="javascript:delEval('. $row["Persnr"] .',\''. $row["Fullname"] .'\')">[delete]</a></td>';
			}
			
			$html = <<<HTML
				<tr>
				<td class="saphireframe"><a href='$edit_link'><img src="images/ico_change.gif"></a></td>
				<td class="saphireframe">$row[lkp_yn_desc]</td>
				<td class="saphireframe">$row[Fullname]</td>
				<td class="saphireframe">$row[Work_Number]</td>
				<td class="saphireframe">$row[E_mail]</td>
				<td class="saphireframe">$cheJob</td>
				<td class="saphireframe">$row[Empoloyer]</td>
				$del_link
				</tr>
HTML;
				echo $html;

			} // while
		}
		else {
			echo '<tr>'."\n";
			echo '<td align="center" colspan="10">- No names match the criteria you have specified -</td>'."\n";
			echo '</tr>';
		}
		echo '<tr>'."\n";
//		createPrevNext($count, $numOfRows, 10);
		echo '</tr></table>'."\n";
	
?>
	</td>
	</tr>
	</table>	
<?php
	function createPrevNext($count, $numOfRows, $inc){
		if ($count > 0) {
			echo '<td colspan="2"><a href="javascript:pagePrevious('.$inc.');">Previous</a></td>'."\n";
		}else{
			echo '<td colspan="2">&nbsp;</td>';
		}
		if (($count+$inc) <= ($numOfRows)) {
			echo '<td align="right" colspan="2"><a href="javascript:pageNext('.$inc.');">Next</a></td>'."\n";
		}else{
			echo '<td colspan="2">&nbsp;</td>';
		}
	}

?>
<SCRIPT>

function delEval(val,ename){

	if (confirm("Are you sure that you would like to delete "+ ename)) {
		document.defaultFrm.DELETE_RECORD.value = 'Eval_Auditors|Persnr|'+val;
		moveto('stay');
	}
}

<?php/********
	function pageNext(n){
		var count = <?php echo $count?>;
		count += n;
		document.all.count.value = count;
		document.all.searchText.value = "<?php echo (isset($_POST['searchText']))?($_POST['searchText']):("")?>";
		moveto('stay');
	}

	function pagePrevious(n){
		var count = <?php echo $count?>;
		count -= n;
		if (count < 0) count = 0;
		document.all.count.value = count;
		document.all.searchText.value = "<?php echo (isset($_POST['searchText']))?($_POST['searchText']):("")?>";
		moveto('stay');
	}
********/ ?>

<?php/* Diederi 20050627: MAKE SURE WE STAY ON THE SAME PAGE */ ?>
document.defaultFrm.MOVETO.value = 'stay';

</SCRIPT>
