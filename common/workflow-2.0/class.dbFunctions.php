<?php

class dbFunctions extends dbConnect {

        function __construct(){}

	function updateField($table,$key,$field,$value,$id){
		$SQL = "UPDATE $table SET $field='$value' WHERE $key=$id";
		$conn = $this->getDatabaseConnection();
                
		$rs = mysqli_query($conn, $SQL);
	}

	function insertRow($table,$arr){
		$fields = array();
		$vals = array();
		foreach ($arr as $key=>$val){
			array_push($fields,"`$key`");
			array_push($vals,"'$val'");
		}
		$SQL = "INSERT INTO $table (".implode($fields,", ").") VALUES (".implode($vals,", ").")";
		//$rs = mysqli_query($SQL);
	}

	function updateMultiplePipedValuesInTable ($fieldName, $fieldValuesPiped, $table, $keyName, $keyVal, $changeFld, $changeVal, $resetVal) {
		$SQL = "UPDATE `".$table."` SET ".$changeFld."=".$resetVal." WHERE `".$keyName."`='".$keyVal."'";
		$conn = $this->getDatabaseConnection();
		$RS = mysqli_query($conn, $SQL);
		$fieldArr = array();
		if ($fieldValuesPiped > "") $fieldArr = explode("|", $fieldValuesPiped);
		foreach ($fieldArr AS $val) {
			$SQL = "UPDATE `".$table."` SET ".$changeFld."=".$changeVal." WHERE `".$fieldName."`='".$val."' AND `".$keyName."`='".$keyVal."'";
			$RS = mysqli_query($conn, $SQL);
		}
	}

	function saveMultipleRowsInTable ($fieldArray, $table, $keyName, $keyVal, $uniqueKey="", $uniqueVal="") {
		$unique = (($uniqueKey > "") && ($uniqueVal > ""))?($uniqueKey."='".$uniqueVal."' AND"):("");
		if (count($fieldArr) > 0) {
			foreach ($fieldArr AS $fieldKey=>$fieldVal) {
				$SQL = "UPDATE `".$table."` SET ".$fieldKey."='".$fieldVal."' WHERE ".$unique." ".$keyName."='".$keyVal."'";
                                $conn = $this->getDatabaseConnection();
				$RS = mysqli_query($conn, $SQL);
			}
		}
	}

	function getMultipleFieldsFromTable ($table, $keyName, $keyVal, $options="") {
                $conn = $this->getDatabaseConnection();
		$SQL = "SELECT * FROM ".$table." WHERE ".$keyName."='".$keyVal."'";
		if ($options > "") {
			$SQL .= $options;
		}
		$RS = mysqli_query($conn, $SQL);
		$fieldArr = array();
		while ($row = mysqli_fetch_assoc($RS)) {
			array_push($fieldArr, $row);
		}
		return $fieldArr;
	}

	// make GRID on form
	//Reyno
	//2004/3/26
	//(soureTable,sourceDispayArray,sourceID,where,destiantionTable,destinationID,destinationRef1,destinationRef2,Ref2Value,destinationFields,tableHeadings)
	function makeGRID($srcTable,$srcDispArray,$srcID,$srcWhere,$destTable,$destID,$destRef1,$destRef2,$destRef2Value,$destDispArray,$headingArray,$onclick = "", $sizeOfField="10", $cols="40", $rows="4", $disabled="",$href_link1="",$href_link2="", $srcDisp_cols=1, $createFileUpload=0){

		/*
			We have implemented a new version of this function. It is called "gridShow" and therefore we want to know when this function is still used.
		*/
		$message = 'A $this->makeGrid has been used on template: '.$this->template."\n\n".'Please update with new function: $this->gridShow'."\n\n";
		$this->misMailByName ("heqc@octoplus.co.za", "OLD MAKEGRID USED", $message);



		echo "<tr>";
		foreach ($headingArray as $key=>$value){
			$style = "";
			if (stristr($value,":vertical")) {
				$value = substr($value, 0, strpos($value,":vertical"));
				$style = " filter: flipv fliph; writing-mode: tb-rl; text-align:left";
			}
			echo "<td style='".$style."' class='oncolourb' align='center'>";
			echo $value;
			echo "</td>";
		}
		echo "</tr>";

                $conn = $this->getDatabaseConnection();
		$SQL = "SELECT ";
		foreach ($srcDispArray as $key=>$value){
			$SQL .= $value.",";
		}
		$SQL .= $srcID." FROM ".$srcTable." WHERE ".$srcWhere;
//		echo $SQL."<br>";
		$rs = mysqli_query($$conn, SQL);

		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				echo "<tr>";

				$TMPSQL = "SELECT * FROM ".$destTable." WHERE ".$destRef1." = ".$row[$srcID]." and ".$destRef2." = '".$destRef2Value."'";
//echo $TMPSQL;
				$TMPRS = mysqli_query($conn, $TMPSQL);
				if (mysqli_num_rows($TMPRS) == 0){
					$TMPSQL = "INSERT INTO ".$destTable." (".$destRef1.",".$destRef2.") VALUES (".$row[$srcID].",".$destRef2Value.")";
					$TMPRS = mysqli_query($conn, $TMPSQL);
				}
				$S = "SELECT ";
				foreach ($destDispArray as $key=>$value){
					$S .= $value.",";
				}
				$S .= $destID." FROM ".$destTable." WHERE ".$destRef1." = ".$row[$srcID]." and ".$destRef2." = '".$destRef2Value."'";
//echo $S;
				$r = mysqli_query($conn, $S);
				$rrow = mysqli_fetch_array($r);
				$count = 1;
				for ($i=0; $i < (count($destDispArray)+1); $i++) {
					if ($count == $srcDisp_cols) {
						echo "<td valign=top>";
						foreach ($srcDispArray as $key=>$value){
							echo $row[$value]." ";
						}
						if ($href_link1 == "changeEvaluator") {
							echo '<br>[<a href="javascript:changeEvalID('.$row[$srcID].');moveto(\'next\');">Change evaluator</a>]';
						}
						if ($href_link2 == "contactEvaluator") {
							echo '<br>[<a href="javascript:makeContact('.$row[$srcID].');moveto(\'next\');">Contact evaluator</a>]';
						}
						echo "</td>";
						$i--;
					}else {
						if ($i < count($destDispArray)) {
							if(stristr($destDispArray[$i],"confirm")){
								echo "<td nowrap valign=top>";
								echo "<input type='radio' onclick='".$onclick."' value='-1' name='GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable."'";
								echo "checked";
								echo " ".$disabled;
								echo ">not answered<br>";
								echo "<input type='radio' onclick='".$onclick."' value='0' name='GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable."'";
								if ($rrow[$destDispArray[$i]] == 0) echo "checked";
								echo " ".$disabled;
								echo ">declined<br>";
								echo "<input type='radio' onclick='".$onclick."' value='1' name='GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable."'";
								if ($rrow[$destDispArray[$i]] == 1) echo "checked";
								echo " ".$disabled;
								echo ">accepted<br>";
								echo "</td>";
							}else if (stristr($destDispArray[$i],"yes_no")){
								echo "<td valign=top>";
								echo "<input type='radio' onclick='".$onclick."' value='1' name='GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable."'";
								if ($rrow[$destDispArray[$i]] == 1) echo "checked";
								echo " ".$disabled;
								echo ">No<br>";
								echo "<input type='radio' onclick='".$onclick."' value='2' name='GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable."'";
								if ($rrow[$destDispArray[$i]] == 2) echo "checked";
								echo " ".$disabled;
								echo ">Yes";
								echo "</td>";
							}else if (stristr($destDispArray[$i],"_checkbox")){
								echo "<td valign=top>";
								echo "<input type='checkbox' onclick='".$onclick."' value='1' name='GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable."'";
								if ($rrow[$destDispArray[$i]] == 1) echo "checked";
								echo " ".$disabled;
								echo ">";
								$SQL_del = "UPDATE ".$destTable." SET ".$destDispArray[$i]."=0 WHERE ".$destID."=".$rrow[$destID];
								$RS = mysqli_query($SQL_del);
							}else if (stristr($destDispArray[$i],"_text")){
								echo "<td valign=top>";
								echo "<textarea cols='".$cols."' rows='".$rows."' name='GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable."' ".$disabled.">".$rrow[$destDispArray[$i]]."</textarea>";
								echo "</td>";
							}else {
								if (($rrow[$destDispArray[$i]] == '1970-01-01') || ($rrow[$destDispArray[$i]] == '00:00:00')) $rrow[$destDispArray[$i]] = '';
									echo "<td valign='top'>";
									echo "<input size='".$sizeOfField."' type='TEXT'  name='GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable."' value='".$rrow[$destDispArray[$i]]."'";
									echo " ".$disabled;
									if (strpos($destDispArray[$i],"date") != false){
										echo " readonly>";
										?>
										<a href="javascript:show_calendar('defaultFrm.<?php echo "GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable ?>');"><img src="images/icon_calendar.gif" border=0></a>
										<?php 
										echo "</td>";
									}else if(strpos($destDispArray[$i],"time") != false){
										echo " readonly>";
										?>
										<a href="javascript:showTime('<?php echo "GRID_".$rrow[$destID]."$".$destID."$".$destDispArray[$i]."$".$destTable ?>','<?php echo $rrow[$destDispArray[$i]] ?>');"><img src="images/icon_time.gif" border=0></a>
										<?php 
										echo "</td>";
									}else{
										echo "></td>";
									}
							}//if fields
						}//if $i < count(array)
					}//if count cols
					$count++;
				}//for
					if ($createFileUpload) {
						echo '<td valign="top">';
						$this->createTableFileUpload("GRID_".$rrow[$destID]."$".$destID."$"."inst_uploadDoc"."$".$destTable, "inst_uploadDoc", $destTable, $destID, $rrow[$destID]);
						echo '</td>';
					}
					//make the field for saving purposes. Look at workflow.class.php - checkSaveFieldsPost ()
					echo "<td valign='top'><input type='HIDDEN'  name='GRID_save_".$rrow[$destID]."' value='1'></td>";
				echo "</tr>";
			}
		}
	}

	//Reyno van der Hoven
	//2004/3/29
	//Add relational Radiobutton input grid to form
	//table,id,disp,where,lnk_table,lnk_id,lnk_ref1,lnk_ref2,lnk_ref3,lnk_ref1_value,lnk_ref3_def,ref_table,ref_id,tableSort

	function makeRelRadioTable($table,$id,$disp,$where,$lnk_table,$lnk_id,$lnk_ref1,$lnk_ref2,$lnk_ref3,$lnk_ref1_value,$lnk_ref3_def,$ref_table,$ref_id,$tableSort = "",$onChange,$editable=true){
		$orderBy = $id;
		$optionsArray = array();

		$conn = $this->getDatabaseConnection();
		$TMPSQL = "SELECT ".$ref_id." FROM ".$ref_table." WHERE 1";
		$TMPRS = mysqli_query($conn, $TMPSQL);
		while ($tmprow = mysqli_fetch_array($TMPRS)){
			array_push($optionsArray,$tmprow[$ref_id]);
		}

		if ($tableSort > "") $orderBy = $tableSort;
		$SQL = "SELECT ".$id.",".$disp." FROM ".$table." WHERE ".$where." ORDER BY ".$orderBy;
		$rs = mysqli_query($conn, $SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				echo "<tr>";
				echo "<td>".$row[$disp]."</td>";
				$TMPSQL = "SELECT * FROM ".$lnk_table." WHERE ".$lnk_ref1." = ".$lnk_ref1_value." and ".$lnk_ref2." = ".$row[$id];
				$TMPRS = mysqli_query($conn, $TMPSQL);
				if ($TMPRS && (mysqli_num_rows($TMPRS) == 0) ){
					$TMPSQL = "INSERT INTO ".$lnk_table." (".$lnk_ref1.",".$lnk_ref2.",".$lnk_ref3.") VALUES (".$lnk_ref1_value.",".$row[$id].",".$lnk_ref3_def.")";
					$TMPRS = mysqli_query($conn, $TMPSQL);
				}
				$TMPSQL = "SELECT * FROM ".$lnk_table." WHERE ".$lnk_ref1." = ".$lnk_ref1_value." and ".$lnk_ref2." = ".$row[$id];
				$TMPRS = mysqli_query($conn, $TMPSQL);
				$TMPROW = mysqli_fetch_array($TMPRS);
				foreach ($optionsArray as $key=>$value){
					echo "<td align='center'>";
					echo "<input type='Radio' onclick='".$onChange."' name='GRID_".$TMPROW[$lnk_id]."$".$lnk_id."$".$lnk_ref3."$".$lnk_table."' value='".$value."'";
					if ($TMPROW[$lnk_ref3] == $value) echo " checked ";
					if (!$editable) echo " disabled ";
					echo "></input>";
					echo "</td>";

				}

				echo "</tr>";
			}
			//make the field for saving purposes. Look at workflow.class.php - checkSaveFieldsPost ()
			echo "<tr><td valign='top'><input type='HIDDEN'  name='GRID_save' value='1'></td></tr>";
		}
	}


} //end class
?>
