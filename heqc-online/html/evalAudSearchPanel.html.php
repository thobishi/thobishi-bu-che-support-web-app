<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2" align="center">To <b>Add or Edit data </b> for an existing 
	Evaluator or Auditor please search for him using the fields below and then click on his name.</td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td align="right">CESM classification 1:</td><td><?php $this->showField("CESM_code1") ?></td>
</tr><tr>
	<td align="right">Search ( Name ):</td><td><?php $this->showField("searchText") ?>&nbsp;&nbsp;
	<input type="button" class="btn" value="Search" onClick="moveto('stay');"></td> 
</tr></table>
<br><br>
</td></tr></table>
<?php 
	$count =  ((isset($_POST["count"])&&($_POST["count"]>0))?($_POST["count"]):(0));
	if (isset($_POST["searchText"]) || ((isset($_POST["CESM_code1"])) && ($_POST["CESM_code1"] > 0))) {
		$selectArray = array();
		$whereArray = array();
		$tableArray = array();
		$tableArray[0] = $this->dbTableCurrent;
		$tableArray[1] = "lkp_title";
		array_push ($whereArray, "lkp_title_id = Title_ref");
// All evaluators should display in this search because it is a search to edit. RTN 14 Aug 2006
//		array_push ($whereArray, "active = 1");
		if ($_POST["CESM_code1"] != 0) {
			array_push ($tableArray, "SpecialisationLink");
			array_push ($whereArray, "Persnr=Persno_ref");
			array_push ($whereArray, "CESM_code_ref LIKE '".$_POST["CESM_code1"]."%'");
		}

		if ($_POST["searchText"] > "") {
			array_push ($whereArray, "MATCH(Names, Surname, Initials, ID_Number) AGAINST('".$_POST["searchText"]."')");

		}

		$SQL = 'SELECT '.$this->dbTableCurrent.'.*, CONCAT(Surname,", ",Names, " (", lkp_title_desc, ")") AS Fullname FROM '.implode (", ", $tableArray).' WHERE '. implode (" AND ", $whereArray)." ORDER BY Surname, Names";

//		$SQL .= " LIMIT ".$count.", 10";
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$rs = mysqli_query($conn, $SQL);
		$numOfRows = mysqli_num_rows($rs);
		echo '<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2"><tr>'."\n";
		echo '<td><b>Name</b></td>'."\n";
		echo '<td><b>Phone number</b></td>'."\n";
		echo '<td><b>E-mail</b></td>'."\n";
		echo '</tr><tr>'."\n";
//		createPrevNext($count, $numOfRows, 10);
		echo '</tr>';				
		while($row = mysqli_fetch_array($rs)){
			echo '<tr>'."\n";
//			echo '<td><a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value=\'Eval_Auditors|'.$row["Persnr"].'\'; goto(\'next\');">'.$row["Fullname"].'</a></td>'."\n";
			echo "<td><a href='javascript:setEvalAud(".$row["Persnr"].")'>".$row["Fullname"]."</a></td>\n";
			echo '<td>'.$row["Work_Number"].'</td>'."\n";
			echo '<td>'.$row["E_mail"].'</td>'."\n";
			echo '</tr>';
		} // while
		echo '<tr>'."\n";
//		createPrevNext($count, $numOfRows, 10);
		echo '</tr></table>'."\n";
	}

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

function setEvalAud(val){
	document.defaultFrm.CHANGE_TO_RECORD.value = val;
	moveto('next');
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

<?php/*  Diederik 20050627: MAKE SURE WE STAY ON THE SAME PAGE */ ?>
document.defaultFrm.MOVETO.value = 'stay';

</SCRIPT>
