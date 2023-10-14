<?
class reportGenerator
{
	var $heading;
	var $format;  // freeform or tabular
	var $type;    // xml, html
	var $extract; // word, excel
	var $source;  // workflow, admin, sql
	var $dataArr = array(); // data array
	var $html = "";

	function reportGenerator(){
	}

	function setHeading($heading){
//		$this->heading = $heading;
		$this->html .= "<br><b>$heading</b><hr>";
	}

	function setSubHeading($text){
		$this->html .= "<b>$text</b>";
	}


	function getData($type,$dataSource,$id=""){
		$data = array();

		if (!isset($type) OR !isset($dataSource)) {
			$type = "None";
			$this->dataArr[0] = array("Message"=>"Invalid data parameters.");
			exit;
		}

		switch ($type) {
		case "sql":

			$rs = mysqli_query($dataSource) or die(mysqli_error());
			while ($rs && $row = mysqli_fetch_assoc($rs)){
				array_push($data,$row);
			}
			break;

		default:
		}
		if (empty($data)) $data[0] = array("Message"=>"No data has been found for your selection.");
		$this->dataArr = $data;
	}

	function insertPageBreak(){
		$this->html.= '<p class="pagebreak">&nbsp;</p>';
	}


	function structureData($fmt="1",$pgbreak=FALSE){

		switch ($fmt){
		case "2":  //freeform - detail

			foreach ($this->dataArr as $rec){
				$this->html .= "<table width='95%' align='center' cellpadding='2' cellspacing='5'>";
				foreach($rec as $key=>$val){
					$displayval = ($val > "") ? $val : "&nbsp;";
					$this->html.="<tr><td class='reportlightgreyb' width='30%'>".$key."</td><td class='reportlightgrey'>".$displayval."</td></tr>\n";
				}
				$this->html.="</table>";
				$this->html.="<hr>";
				if ($pgbreak) $this->insertPageBreak();
			}
			break;
		case "1": //tabular - list
			$this->html='<table border="1" width="95%" align="center" cellpadding="2">';
			$header = true;
			foreach ($this->dataArr as $rec){
				if ($header){
					$this->html .= "<tr>";
					foreach($rec as $key=>$val){
						$this->html .= "<td class='reportgreyb'>".$key."</td>\n";
					}
					$this->html .= "</tr>";
					$header = false;
				}
				$this->html .= "<tr>";
				foreach($rec as $key=>$val){
						$displayval = ($val > "") ? $val : "&nbsp;";
						$this->html .= "<td class='reportgrey'>".$displayval."</td>\n";
				}
				$this->html .= "</tr>";
			}
			$this->html.="</table>";
			break;
		default:
		}
	}

	function showData(){
		echo $this->html;
	}

	function writeData($expFormat="txt"){
		// Need to generate a unique file name and delete it later.

		switch ($expFormat){
		case "doc":
			$expFormat = "doc";
			break;
		case "xls": break;
		case "txt": break;
		default:
			$expFormat = "txt";
		}

		$filehtml = "<html><head></head><body>";
		$filehtml .= $this->html;
		$filehtml .= "</body></html>";

		$file = "Report".rand().".".$expFormat;
		$f=fopen(OCTODOC_DIR.$file,"wb");

		fwrite($f,$filehtml);
		fclose($f);

		echo "<a href='/che/docs-prj/".$file."'>Open report</a>";
	}

	// Add custom reports here


	function displayDatasetsData($id, $format="2"){
		// Data from the following datasets merged on a match on name and surname only.
		// 1. SAHO website - People
		// 2. DSL (Dir of Security Legislation)
		// 3. MK
		// 4. Political Prisoners Comprehensive
		// 5. SP Death in Exile
		// 6. MK Deaths in Exile
		// 7. Apartheid Executions
		// 8. SAHO Banned
		// 9. SAHO Treason Trial
		// 10. SAHO Deaths - Disturbances
		// 11. SAHO Deaths - Detention
		// 12. SAHO Deaths - Executions
		// 13. SAHO Deaths - Exile
		// 14. SAHO Sp_info (SIU info minimum of Info - SPNO Surname Init name ID)
		// 15. TRC
		$SQL = <<<sql
			SELECT
			surname as 'Surname:', init as 'Initials:', name as 'Name:',
			mid(x,1,1) as 'SAHO People:',
			bio as 'SAHO Biography: ',
			mid(x,2,1) AS 'DSL:',
			DSL_File_no,
			mid(x,3,1) AS 'MK:',
			MK_Province, MK_Number1, MK_Number2, MK_Number3, MK_Comment, MK_Sequential,
			mid(x,5,1) AS 'Political Prisoners:',
			Pris_Prison_Nr, Pris_Box_Nr, Pris_File_Ref, Pris_Prison_File_period_volumes, Pris_Others,
			mid(x,4,1) AS 'SP Death in Exile:',
			SP_DiE_S_N, Names, SP_DiE_SP_Remarks,
			mid(x,6,1) AS 'MK Deaths in Exile:',
			MK_DiE_No, MK_DiE_Comment, MK_DiE_Main_Event, MK_DiE_Event,
			mid(x,7,1) AS 'Apartheid Executions:',
			AE_No, AE_Sentence, AE_Execution, AE_Area,
			mid(x,8,1) AS 'SAHO Banned:',
			Banned_act as 'Banned Act: ', Banned_Miscellaneous as 'Banned General: ', Banned_end_date as 'Banned End Date: ',
			mid(x,9,1) AS 'SAHO Treason Trial:',
			Treason_trial,
			mid(x,10,1) AS 'SAHO Deaths - Disturbances:',
			disturbance_place as 'Place ', disturbance_age as 'Age ', disturbance_Cause_of_death as 'Cause ',
			mid(x,11,1) AS 'SAHO Deaths - Detention:',
			Detention_age_dead as 'Age died ',Detention_Add_Info as 'Notes ', Detention_date as 'Date ', Detention_date_unedited as 'Date unedited ', Detention_place_Detained_died as 'Place ', Detention_Alleged_Cause as 'Alleged cause ',
			mid(x,12,1) AS 'SAHO Deaths - Executions:',
			Executions_age, EXECUTION_DATE, Executions_Remarks,
			mid(x,13,1) AS 'SAHO Deaths - Exile:',
			Deaths_in_Exile,
			mid(x,14,1) AS 'SIU:',
			SPNO, SP_idnumber, SP_Status,
			mid(x,15,1) AS 'TRC:',
			TRC_Case_S_N, TRC_Individual_Case_Ref, TRC_Research_Remarks
			FROM merged_datasets
			WHERE merged_datasets_id = $id
sql;
		$this->getData("sql",$SQL);        	// retrieve
		$this->structureData($format);	// append to html string
	}

	function displaySPData($id, $format="2"){
		$SQL = <<<sql
			SELECT *
			FROM special_pension_batch_all
			WHERE SPFNO = '$id'
sql;

		$this->getData("sql",$SQL);     // retrieve
		$this->structureData($format);	// append to html string
	}

	function displayDatasetsQuery($where="", $format="2"){
		$SQL = <<<sql
			SELECT
			surname as 'Surname:', init as 'Initials:', name as 'Name:',
			mid(x,1,1) as 'SAHO People',
			mid(x,2,1) AS 'DSL',
			mid(x,3,1) AS 'MK',
			mid(x,4,1) AS 'Political Prisoners',
			mid(x,5,1) AS 'SP Death in Exile',
			mid(x,6,1) AS 'MK Deaths in Exile',
			mid(x,7,1) AS 'Apartheid executions',
			mid(x,8,1) AS 'SAHO Banned',
			mid(x,9,1) AS 'SAHO Treason Trial',
			mid(x,10,1) AS 'SAHO Deaths - Disturbances',
			mid(x,11,1) AS 'SAHO Deaths - Detention',
			mid(x,12,1) AS 'SAHO Deaths - Executions',
			mid(x,13,1) AS 'SAHO Deaths - Exile',
			mid(x,14,1) AS 'SIU',
			mid(x,15,1) AS 'TRC'
			FROM merged_datasets
sql;
		$SQL .= $where;

		$this->getData("sql",$SQL);      // retrieve
		$this->structureData($format);	 // append to html string
	}

	function displayQuery($sql="", $format="1"){
		$this->getData("sql",$sql);        	// retrieve
		$this->structureData($format);	// append to html string
	}

//add function displayCoverPage here


}

?>