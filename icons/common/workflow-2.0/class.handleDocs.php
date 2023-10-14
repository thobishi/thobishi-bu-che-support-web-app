<?php

/**
 * class doing the document management
 *
 * this is a generic class that handles all the document uploads, downloads and document generation of the system
 * @author Reyno vd Hoven
*/
class handleDocs extends pageForm {


	/**
	 * default constructor
	 *
	 * This empty constructer can be used when a normal page wants to use some of the functions of this class.
	 * @author Reyno van der Hoven
	*/

	function handleDocs(){ self::__construct();}
	
	function __construct(){}

			/*
	Reyno
	2004/4/21
	This is the hidden field where the ref of the document uploaded is stored in the current table.
	*/
	function makeDocInput($obj,$DBfld){
		echo '<INPUT  TYPE="hidden" NAME="'.$DBfld.$obj->fieldName.'" VALUE="'.$obj->fieldValue.'">';
	}

		/*
	Reyno
	2004/4/21
	Makes the file upload display-table (file, upload, last updated, submitted)
	*/

	function makeLink($field,$text="", $table="", $keyFLD="", $keyVal=""){
		$table = ($table > "")?($table):($this->dbTableCurrent);
		$keyFLD = ($keyFLD > "")?($keyFLD):($this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField);
		$keyVal = ($keyVal > "")?($keyVal):($this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID);
		$SQL = "SELECT ".$field." FROM ".$table." WHERE ".$keyFLD." = '".$keyVal."'";
//echo $SQL;
                $conn = $this->getDatabaseConnection();
		$rs = mysqli_query($conn, $SQL);
		if ($rs && (mysqli_num_rows($rs) > 0)){
			$row = mysqli_fetch_array($rs);
			$doc = new octoDoc($row[0]);
			if ($doc->isDoc()){
				if (! $this->view ) {
					$this->showField($field);
					echo "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
					echo "<tr>";
					echo "<td class='oncolourb' width='40%'>File: </td>";
					echo "<td width='60%'><a href='".$doc->url()."' target='_blank'>".$doc->getFilename()."</a></td>";
					echo "</tr>";
					echo "<td class='oncolourb'>First Uploaded: </td>";
					echo "<td>".$doc->getDateCreated()."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td class='oncolourb'>Last Uploaded: </td>";
					echo "<td>".$doc->getDateUpdated()."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td class='oncolourb'>Replace Uploaded File: </td>";
					echo "<td><a href='javascript:openFileWin(\"pages/uploadFile.php\",".$doc->getDocID().",\"FLD_".$field."\");'>Click here to replace the uploaded file with another file.</a></td>";
					echo "</tr><tr>";
					echo "<td class='oncolourb'>Delete File: </td>";
					echo "<td><a href='javascript:document.defaultFrm.FLD_".$field.".value=0;document.defaultFrm.DELETE_RECORD.value = \"documents|document_id|".$doc->getDocID()."\";moveto(\"stay\");'>Click here to delete the uploaded file.</a></td>";
					echo "</tr>";
					echo "</table>";
				}else {
								echo "<a href='".$doc->url()."' target='_blank'>".$doc->getFilename()."</a>";
				}
			}else{
			//we have a new record, with no documents linked to it yet
				if (! $this->view ) {
					$this->showField($field);
					echo "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
					echo "<tr>";
					echo "<td class='oncolourb' width='40%'>File: </td>";
					echo "<td width='60%'>N/A</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td class='oncolourb'>Date Created: </td>";
					echo "<td>N/A</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td class='oncolourb'>Last Updated: </td>";
					echo "<td>N/A</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td class='oncolourb'>Upload File: </td>";
					echo "<td><a href='javascript:openFileWin(\"pages/uploadFile.php\",0,\"FLD_".$field."\",\"\");'>Click here to select the file that you need to upload</a></td>";
					echo "</tr>";
					echo "</table>";
				}else {
					echo "&nbsp;";
				}
			}
		}
		
		// Robin 30 October 2008
		// If a document field is on the first page of a new entity (i.e. NEW entity) then it doesn't display anything
		// This is to correct this.
		if ($rs && mysqli_num_rows($rs) == 0){
			if (! $this->view ) {
				$this->showField($field);
				echo "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
				echo "<tr>";
				echo "<td class='oncolourb' width='40%'>File: </td>";
				echo "<td width='60%'>N/A</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='oncolourb'>Date Created: </td>";
				echo "<td>N/A</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='oncolourb'>Last Updated: </td>";
				echo "<td>N/A</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='oncolourb'>Upload File: </td>";
				echo "<td><a href='javascript:openFileWin(\"pages/uploadFile.php\",0,\"FLD_".$field."\",\"\");'>Click here to select the file that you need to upload</a></td>";
				echo "</tr>";
				echo "</table>";
			}
		}
	}

	/* Robin 12/5/2008
	   Create a field that may be used for a user to select a file to import it.
	*/
	function makeImport($field,$text="", $table="", $keyFLD="", $keyVal=""){
		$table = ($table > "")?($table):($this->dbTableCurrent);
		$keyFLD = ($keyFLD > "")?($keyFLD):($this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField);
		$keyVal = ($keyVal > "")?($keyVal):($this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID);
		$SQL = "SELECT ".$field." FROM ".$table." WHERE ".$keyFLD." = '".$keyVal."'";
//echo $SQL;
                $conn = $this->getDatabaseConnection();
		$rs = mysqli_query($conn, $SQL);
		if ($rs && (mysqli_num_rows($rs) > 0)){
			$row = mysqli_fetch_array($rs);
			$doc = new octoDoc($row[0]);
			if ($doc->isDoc()){
				if (! $this->view ) {
					$this->showField($field);
					echo "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
					echo "<tr>";
					echo "<td class='oncolourb' width='40%'>File: </td>";
					echo "<td width='60%'><a href='".$doc->url()."' target='_blank'>".$doc->getFilename()."</a></td>";
					echo "</tr>";
					echo "</table>";
				}else {
								echo "<a href='".$doc->url()."' target='_blank'>".$doc->getFilename()."</a>";
				}
			}else{
			//we have a new record, with no documents linked to it yet
				if (! $this->view ) {
					$this->showField($field);
					echo "<table border='1' cellpadding='2' cellspacing='2' width='100%'>";
					echo "<tr>";
					echo "<td class='oncolourb'>Import File: </td>";
					echo "<td><a href='javascript:openFileWin(\"pages/uploadFile.php\",0,\"FLD_".$field."\",\"\");'>No file loaded - click here to select the file that you need to import</a></td>";
					echo "</tr>";
					echo "</table>";
				}else {
					echo "&nbsp;";
				}
			}
		}
	}
	
			/*
	Reyno
	2004/4/21
	Put the output of the function it recieved into a word document, and returns the location of the document,
	one can also specify the extention of the document, defualt is ".doc"
	*/
	function generateReport($callBack="",$extention=".doc"){
		$content = "";

		if ($callBack > "") {
			$callBack = 'return $this->'.$callBack.';';
		}

		//create a temp file, and build the report in this file
		$tmpFile = tempnam($this->TmpDir,"");
		$tmpHandle = fopen($tmpFile, "w");
		// $this->mis_eval_pre(__LINE__, __FILE__);
		$content = eval($callBack);
		// $this->mis_eval_post($callBack);
		fwrite($tmpHandle, $content);
		fclose($tmpHandle);
		copy($tmpFile, $tmpFile.$extention);
		unlink($tmpFile);
		return ($tmpFile.$extention);
	}


		/*
	Reyno
	2004/4/21
	Returns the html for a paper evaluation of a spesific application
	*/
	function makeSinglePaperEval($id){
		$content = "";
		$content .= $this->makeTop($id);
		$SQL2 = "SELECT application_comp_all FROM application_summery_comments WHERE application_ref = ?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL2);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs2 = $sm->get_result();
        
		$rs2 = mysqli_query($SQL2);
		if (mysqli_num_rows($rs2) > 0){
			$row2 = mysqli_fetch_array($rs2);
			$content .= "<b>Paper Evaluation <i>(overall compliance with standards - ".$row2["application_comp_all"]."%)</i>:</b><br><br>";
			$content .=	$this->perQuestion("application_summery_comments","application_comp1","application_comment1","application_ref","1",$id,"PROGRAMME DESIGN");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal1","application_ref","1",$id,"PROGRAMME DESIGN");
			$content .=	$this->perQuestion("application_summery_comments","application_comp2","application_comment2","application_ref","2",$id,"STUDENT RECRUITMENT, ADMISSION AND SELECTION");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal2","application_ref","2",$id,"STUDENT RECRUITMENT, ADMISSION AND SELECTION");
			$content .=	$this->perQuestion("application_summery_comments","application_comp3","application_comment3","application_ref","3",$id,"STAFF QUALIFICATION");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal3","application_ref","3",$id,"STAFF QUALIFICATION");
			$content .=	$this->perQuestion("application_summery_comments","application_comp4","application_comment4","application_ref","4",$id,"STAFF SIZE AND SENIORITY");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal4","application_ref","4",$id,"STAFF SIZE AND SENIORITY");
			$content .=	$this->perQuestion("application_summery_comments","application_comp5","application_comment5","application_ref","5",$id,"TEACHING AND LEARNING STRATEGY");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal5","application_ref","5",$id,"TEACHING AND LEARNING STRATEGY");
			$content .=	$this->perQuestion("application_summery_comments","application_comp6","application_comment6","application_ref","6",$id,"STUDENT ASSESSMENT");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal6","application_ref","6",$id,"STUDENT ASSESSMENT");
			$content .=	$this->perQuestion("application_summery_comments","application_comp7","application_comment7","application_ref","7",$id,"VENUES AND IT INFRASTRUCTURE");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal7","application_ref","7",$id,"VENUES AND IT INFRASTRUCTURE");
			$content .=	$this->perQuestion("application_summery_comments","application_comp8","application_comment8","application_ref","8",$id,"PROGRAMME ADMINISTRATIVE SERVICES");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal8","application_ref","8",$id,"PROGRAMME ADMINISTRATIVE SERVICES");
			$content .=	$this->perQuestion("application_summery_comments","application_comp9","application_comment9","application_ref","9",$id,"POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal9","application_ref","9",$id,"POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS");
			$content .=	"<br><b>Evaluators' Programme Outcome Recommodation</b><br><br>";
			$content .=	$this->perEvaluatoionProgOutcomeRecommend("application_summery_comments","AC_desision_recommend","application_ref",$id,"DECISION");
			$content .=	$this->perEvaluatoionProgOutcomeRecommend("application_summery_comments","AC_conditions_recommend","application_ref",$id,"CONDITIONS");
		}
		return $content;
	}

	/*
	Reyno
	2004/4/21
	Makes a paper eval report containing all the applications that meets the criteria for being on the ac meeting
	*/
	function makePaperEvalReport($id){
		$content = "";
		$SQL = "SELECT application_id FROM Institutions_application WHERE application_status=1 and AC_Meeting_ref=?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs = $sm->get_result();
        
		//$rs = mysqli_query($SQL);
		while ($row = mysqli_fetch_array($rs)){
			$content .= $this->makeSinglePaperEval($row["application_id"]);
		}
		return $content;
	}

/**********************************************************************************/
	function makeSinglePaperEval2($id){
		$content = "";
		$content .= $this->makeTop($id);
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal1","application_ref","1",$id,"PROGRAMME DESIGN");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal2","application_ref","2",$id,"STUDENT RECRUITMENT, ADMISSION AND SELECTION");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal3","application_ref","3",$id,"STAFF QUALIFICATION");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal4","application_ref","4",$id,"STAFF SIZE AND SENIORITY");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal5","application_ref","5",$id,"TEACHING AND LEARNING STRATEGY");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal6","application_ref","6",$id,"STUDENT ASSESSMENT");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal7","application_ref","7",$id,"VENUES AND IT INFRASTRUCTURE");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal8","application_ref","8",$id,"PROGRAMME ADMINISTRATIVE SERVICES");
			$content .=	$this->perQuestion2("application_summery_comments_internal","","application_comment_internal9","application_ref","9",$id,"POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS");
		return $content;
	}


	function perQuestion2($table,$display1,$display2,$key,$ques,$id,$text){
		$tmp ="";
		$SQL = "SELECT ".$display2." FROM ".$table." WHERE ".$key."=?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs = $sm->get_result();
        
		//$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
//			$tmp .= $ques.". ".$text.":<br><br>";
			$tmp .= "<b>Secretriet's comments:</b><br>".$row[$display2]."<br><br>";
		}
		return $tmp;
	}

	function makePaperEvalReport2($id){
		$content = "";
		$SQL = "SELECT application_id FROM Institutions_application WHERE application_status=1 and AC_Meeting_ref=?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
		while ($row = mysqli_fetch_array($rs)){
			$content .= $this->makeSinglePaperEval2($row["application_id"]);
		}
		return $content;
	}

	function perEvaluatoionProgOutcomeRecommend ($table,$display1,$key,$id,$text) {
		$tmp ="";
		$SQL = "SELECT ".$display1." FROM ".$table." WHERE ".$key."=?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs = $sm->get_result();
        
		//$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
			$tmp .= "<b>".$text."</b>:<br>";
			$tmp .= (($display1 == "AC_desision_recommend")?($this->getValueFromTable("lkp_desicion", "lkp_id", $row[$display1], "lkp_title")):($row[$display1]))."<br><br>";
		}
		return $tmp;
	}

/**********************************************************************************/


	/*
	Reyno
	2004/4/21
	Makes a paper eval report containing all the applications that meets the criteria for being on the ac meeting
	*/
	function makeSiteReport($id){
		$content = "";
		$SQL = "SELECT application_id FROM Institutions_application WHERE application_status=1 and AC_Meeting_ref=?";
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs = $sm->get_result();
		
		//$rs = mysqli_query($SQL);
		while ($row = mysqli_fetch_array($rs)){
			$content .= $this->genSiteVisitReportPerApplication($row["application_id"]);
		}
		return $content;
	}

	/*
	Reyno
	2004/4/21
	Returns the output required by a paper evaluation per question
	*/
	function perQuestion($table,$display1,$display2,$key,$ques,$id,$text){
		$tmp ="";
		$SQL = "SELECT ".$display1.",".$display2." FROM ".$table." WHERE ".$key."=?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs = $sm->get_result();
        
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
			$tmp .= $ques.". ".$text.":<br><i>Compliance with standards - ".$row[$display1]."%</i><br>";
			$tmp .= "<b>Chair person's comments:</b><br>".$row[$display2]."<br><br>";
		}
		return $tmp;
	}


	/*
	Reyno
	2004/4/21
	Make the top part of a paper evaluation for the ac meeting
	*/
	function makeTop($id){
		$content = "";
		$SQL = "SELECT lnk_priv_publ_desc,HEI_name,CHE_reference_code,program_name,NQF_ref FROM Institutions_application,HEInstitution,lnk_priv_publ  WHERE lnk_priv_publ_id=priv_publ and  HEI_id=institution_id and application_id = ?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs = $sm->get_result();
        
		//$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
			$content .= "<table align='center' border='1' width='100%'>";
			$content .= "<tr>";
			$content .= "<td width='50%'><strong>INSTITUTION NAME:</strong></td>";
			$content .= "<td>".$row["HEI_name"]."&nbsp;</td>";
			$content .= "</tr>";
			$content .= "<tr>";
			$content .= "<td><strong>INSTITUTION TYPE:</strong></td>";
			$content .= "<td>".$row["lnk_priv_publ_desc"]."&nbsp;</td>";
			$content .= "</tr>";
			$content .= "<td><strong>PROGRAMME NAME:</strong></td>";
			$content .= "<td>".$row["program_name"]."&nbsp;</td>";
			$content .= "</tr>";
			$content .= "<tr>";
			$content .= "<td><strong>NQF Level:</strong></td>";
			$content .= "<td>".$row["NQF_ref"]."&nbsp;</td>";
			$content .= "</tr>";
			$content .= "<tr>";
			$content .= "<td><strong>HEQC - Reference number:</strong></td>";
			$content .= "<td>".$row["CHE_reference_code"]."&nbsp;</td>";
			$content .= "</tr>";
			$content .= "</table>";
			$content .= "<br><br>";
		}
		return $content;

	}

	/*
	Louwtjie: 2004-05-20
	function to generate the siteVisit reports per application.
	*/
	function genSiteVisitReportPerApplication ($application_ref) {
		$html = "";
		$SQL = "SELECT site_ref, site_visit, object_sitevisit_visit FROM `siteVisit` WHERE application_ref=?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $application_ref);
                $sm->execute();
                $RS = $sm->get_result();
        
		//$RS = mysqli_query($SQL);
		while ($row = mysqli_fetch_object($RS)) {
			if ($row->site_visit == 'Yes') {
				$html .= $this->genSiteVisitReport ($application_ref, $row->site_ref);
			}else if (($row->site_visit == 'No') && ($row->object_sitevisit_visit == 1)) {
				$html .= $this->makeTop($application_ref);
				$html .= '<table border="1"><tr>';
				$html .= '<td>No Site Visit for site: '.$this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $row->site_ref, "location").'</td>';
				$html .= '</tr><tr>';
				$html .= '<td> The institution objected to the sitevisit</td>';
				$html .= '</tr></table>';
				$html .= '<br><br><br><br><br>';
			}else {
				$html .= $this->makeTop($application_ref);
				$html .= '<table border="1"><tr>';
				$html .= '<td>No Site Visit for site: '.$this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $row->site_ref, "location").'</td>';
				$html .= '</tr></table>';
				$html .= '<br><br><br><br><br>';
			}
		}
		return $html;
	}

	/*
	Louwtjie: 2004-05-20
	function to generate the siteVisit report.
	*/
	function genSiteVisitReport ($application_ref="", $site_ref="") {
		$html = "";
		if ( !($application_ref > 0) ) {
			$application_ref = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		}
		$html .= $this->makeTop($application_ref);

		$SQL = "SELECT * FROM `evalSiteVisitReport` WHERE application_ref=?";
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $application_ref);
                $sm->execute();
                $RS = $sm->get_result();
        
		//$RS = mysqli_query($SQL);
		if ($RS && ($row=mysqli_fetch_array($RS))) {
			$html .= <<<html
			<b>1. PROGRAMME DESIGN&nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>1.1</b></td><td valign="top"><b>How and to what extent does this programme responds to the mission of the institution and its institutional plan?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["1_eval_question_1"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>1.2</b></td><td valign="top"><b>How and to what extent does the programme meet the needs of its targeted student intake and other stakeholders?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["1_eval_question_2"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>1.3</b></td><td valign="top"><b>How  and to what extent does it articulate with other programmes?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["1_eval_question_3"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td colspan="2" valign="top"><b>Comment:</b><br>
html;
			$html .= $row["evalReport_q1"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
			<b>2. STUDENT RECRUITMENT, ADMISSION AND SELECTION&nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>2.1</b></td><td valign="top"><b>To what extent is the information on the programme requirements  that the institution plans to provide  sufficient for students to make choices?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["2_eval_question_1"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>2.2</b></td><td valign="top"><b>Are the admission requirements appropriate? To what extent do they relate to the requirements for the academic level of the programme?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["2_eval_question_2"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>2.3</b></td><td valign="top"><b>To what extent  does the selection process take into account the optimal number of students to achieve the proposed learning outcomes?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["2_eval_question_3"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>2.4</b></td><td valign="top"><b>To what extend do the programmes  recruitment  and admission policies take into account the objective of widening access to higher education?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["2_eval_question_4"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td colspan="2" valign="top"><b>Comment:</b><br>
html;
			$html .= $row["evalReport_q2"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
			<b>3. STAFF QUALIFICATION&nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>3.1</b></td><td valign="top"><b>Do you think that the qualification and expertise of the academic staff responsible for the programme are sufficient and relevant for the level and focus of the programme? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["3_eval_question_1"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>3.2</b></td><td valign="top"><b>Do you think that the academic staffs teaching and assessment competences are sufficient for the level at which they will be teaching? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["3_eval_question_2"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>3.3</b></td><td valign="top"><b>To what extent does the research profile of the academic staff match the  nature and level of the programme?  </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["3_eval_question_3"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>3.4</b></td><td valign="top"><b>To what extent the documentation submitted indicate that the institution provide for academic staff to enhance their competencies and to support their professional growth and development realistically?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["3_eval_question_4"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
			<b>3. STAFF SIZE AND SENIORITY &nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>3.5</b></td><td valign="top"><b>To what extent is the size  and seniority of the academic and support staff sufficient for the nature and field of the proposed programme and the prospective size of the student body?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["3_eval_question_5"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>3.6</b></td><td valign="top"><b>To what extent is the rate between full-time and part-time staff appropriate to guarantee the sustainability of the programme?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["3_eval_question_6"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>3.7</b></td><td valign="top"><b>How and to what extent does the programme encourage the inclusion of academic staff members who contribute to the diversity of staff complement?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["3_eval_question_7"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td colspan="2" valign="top"><b>Comment:</b><br>
html;
			$html .= $row["evalReport_q3"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
			<b>4. TEACHING AND LEARNING STRATEGY&nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>4.1</b></td><td valign="top"><b>How and to what extent does the programme  actually promote student learning?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["4_eval_question_1"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>4.2</b></td><td valign="top"><b>How and to what extent are  the institutional type (as reflected in the institutions mission), mode(s) of delivery and future student composition taken into account in the teaching and learning strategy? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["4_eval_question_2"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>4.3</b></td><td valign="top"><b>How and to what extent does the teaching and learning strategy ensure that the teaching and learning methods of the programme are appropriate to its contents and learning outcomes? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["4_eval_question_3"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>4.4</b></td><td valign="top"><b>How and to what does the teaching and learning strategy make provision for staff to upgrade their teaching methods? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["4_eval_question_4"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>4.5</b></td><td valign="top"><b>How and to what extent does the teaching and learning strategy provide mechanisms to monitor progress, evaluate impact, and effect improvement of the programme?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["4_eval_question_5"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td colspan="2" valign="top"><b>Comment:</b><br>
html;
			$html .= $row["evalReport_q4"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
			<b>5. STUDENT ASSESSMENT&nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>5.1</b></td><td valign="top"><b>To what extent the policies and procedures for internal assessment; internal and external moderation are appropriate to the mode of delivery of the programme?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["5_eval_question_1"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>5.2</b></td><td valign="top"><b>To what extent is the system proposed to monitoring student progress adequate and appropriate to the type of programme?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["5_eval_question_2"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>5.3</b></td><td valign="top"><b>How does the institution ensure the explicitness, validity and reliability of assessment practices? Are these mechanisms adequate?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["5_eval_question_3"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>5.4</b></td><td valign="top"><b>To what extent is the system proposed to recording assessment results; and settling disputes appropriate and efficient?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["5_eval_question_4"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>5.5</b></td><td valign="top"><b>To what extend is assessment for the recognition prior learning rigorous and secure?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["5_eval_question_5"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>5.6</b></td><td valign="top"><b>To what extent  are the  mechanisms for the development of staff competence in assessment in RPL among academic staff appropriate and sufficient ?</b></td>
				</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["5_eval_question_6"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td colspan="2" valign="top"><b>Comment:</b><br>
html;
			$html .= $row["evalReport_q5"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
			<b>6. VENUES AND IT INFRASTRUCTURE&nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>6.1</b></td><td valign="top"><b>To what extent does the programme have suitable and sufficient lecturing venues? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["6_eval_question_1"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>6.2</b></td><td valign="top"><b>To what extent do the IT infrastructure and library resources available for students and staff match the programme requirements? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["6_eval_question_2"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>6.3</b></td><td valign="top"><b>Are the regulation about maintenance and management of the library appropriate to guarantee that they support students and staff? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["6_eval_question_3"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>6.4</b></td><td valign="top"><b>To what the staff development available for library staff sufficient?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["6_eval_question_4"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td colspan="2" valign="top"><b>Comment:</b><br>
html;
			$html .= $row["evalReport_q6"];
			$html .= <<<html
			</td>
			</tr></table>
			<Br><br>
			<b>7. PROGRAMME ADMINISTRATIVE SERVICES&nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>7.1</b></td><td valign="top"><b>What is your opinion of the programme administrative service? To what extent do you think it can provide information, manage the programme information system, and deal with a diverse student population adequately? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["7_eval_question_1"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>7.2</b></td><td valign="top"><b>To what extent you think that the administrative services ensure the integrity of the processes leading to certification of the qualification obtained through the programme?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["7_eval_question_2"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td colspan="2" valign="top"><b>Comment:</b><br>
html;
			$html .= $row["evalReport_q7"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
			<b>8. POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS &nbsp;</b>
			<br><br>
			<table><tr>
				<td valign="top"><b>8.1</b></td><td valign="top"><b>Do you think that the processes applied for the postgraduate programmes for the admission and selection of students are appropriate to a programme at a postgraduate level? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["8_eval_question_1"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>8.2</b></td><td valign="top"><b>Do you think the method to select supervisors takes into account the quality of the student learning experience? </b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["8_eval_question_2"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td valign="top"><b>8.3</b></td><td valign="top"><b>How and to what extent is the definition of the roles and responsibilities of supervisors and students regulated and managed in such a way that students have some guarantee of receiving  quality  postgraduate education at the appropriate level?</b></td>
			</tr><tr>
				<td colspan="2" valign="top">
html;
			$html .= $row["8_eval_question_3"];
			$html .= <<<html
			</td>
			</tr><tr>
				<td colspan="2" valign="top"><b>Comment:</b><br>
html;
			$html .= $row["evalReport_q8"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
			<b>COMMENTS BY SECRETERIATE: &nbsp;</b>
			<br><br>
			<table><tr>
				<td colspan="2" valign="top"><b>Comments:</b><br>
html;
			$html .= $row["sitevisit_report_comments"];
			$html .= <<<html
			</td>
			</tr></table>
			<br><br>
html;
		}

		$SQL = "SELECT * FROM siteVisit_report_headings";
		$conn = $this->getDatabaseConnection();
		$RS = mysqli_query($conn, $SQL);
		$TDcount = mysqli_num_rows($RS);
		$html .=  '<table border="1"><tr>';
		$html .=  '<td valign="top" colspan="'.($TDcount).'">'.$this->getValueFromTable("institutional_profile_sites","institutional_profile_sites_id", $site_ref,"location").'</td>';
		$html .=  '</tr><tr>';
		while ($row = mysqli_fetch_object($RS)) {
			$html .=  '<td valign="top"><b>'.$row->siteVisit_report_heading_desc.'</b></td>';
		}
		$html .=  '</tr>';

		$SQL = "SELECT * FROM siteVisit_report_areas";
		$RS = mysqli_query($conn, $SQL);
		$heading = $subHeading = "";
		$count = 0;
		while ($row = mysqli_fetch_object($RS)) {
			if ($heading != $row->main_heading) {
				$html .=  '<tr>';
				$html .=  '<td valign="top" colspan="'.($TDcount).'"><b>';
				$html .= $row->main_heading;
				$html .= '</b></td>';
				$html .= '</tr>';
			}
			if (($row->sub_heading > "") && ($row->sub_heading != $subHeading)) {
				$html .= '<tr><td valign="top" colspan="'.($TDcount).'">'.$row->sub_heading.'</td></tr>';
			}
			$html .= '<tr>';
			$html .= '<td valign="top">'.$row->question.'</td>';

			$_site = "site_ref='".$site_ref."' AND ";

			$SQLrow = "SELECT commend, documentation, comments FROM `siteVisit_report` WHERE ".$_site." application_ref=? AND siteVisit_report_areas_ref=?";
			
			$sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $application_ref, $row->siteVisit_report_areas_id);
                        $sm->execute();
                        $RS = $sm->get_result();
			
			//$RSrow = mysqli_query($SQLrow);
			if ($ROWrow = mysqli_fetch_object($RSrow)) {
				switch ($ROWrow->commend) {
					case 1:
						$html .= '<td valign="top" align="center">Yes</td>';
						$html .= '<td>&nbsp;</td>';
						$html .= '<td>&nbsp;</td>';
						$html .= '<td>&nbsp;</td>';
						break;
					case 2:
						$html .= '<td>&nbsp;</td>';
						$html .= '<td valign="top" align="center">Yes</td>';
						$html .= '<td>&nbsp;</td>';
						$html .= '<td>&nbsp;</td>';
						break;
					case 3:
						$html .= '<td>&nbsp;</td>';
						$html .= '<td>&nbsp;</td>';
						$html .= '<td valign="top" align="center">Yes</td>';
						$html .= '<td>&nbsp;</td>';
						break;
					case 4:
						$html .= '<td>&nbsp;</td>';
						$html .= '<td>&nbsp;</td>';
						$html .= '<td>&nbsp;</td>';
						$html .= '<td valign="top" align="center">Yes</td>';
						break;
				}
				switch ($ROWrow->documentation) {
					case 1:
						$html .= '<td valign="top" align="center">No</td>';
						break;
					case 2:
						$html .= '<td valign="top" align="center">Yes</td>';
						break;
				}
//the following 5 rows are for the extra comments column that has been replaced by the last comments row.
//				if (($row->main_heading=="INFRASTRUCTURE") && ($count==0)) $html .= '<td valign="top" rowspan="6">'.$ROWrow->comments.'</td>';
//				if (($row->main_heading=="STAFF") && ($count==6)) $html .= '<td valign="top" rowspan="2">'.$ROWrow->comments.'</td>';
//				if (($row->main_heading=="STUDENTS") && ($count==8)) $html .= '<td valign="top" rowspan="3">'.$ROWrow->comments.'</td>';
//				if (($row->main_heading=="OTHER") && ($count==11)) $html .= '<td valign="top" rowspan="1">'.$ROWrow->comments.'</td>';
			}
			$html .= '</tr>';

			if (($row->main_heading=="COMMENTS") && ($count==13)) $html .= '<tr><td valign="top" colspan="6" rowspan="1">'.$ROWrow->comments.'</td></tr>';

			$count++;
			$heading = $row->main_heading;
			$subHeading = $row->sub_heading;
		}
		$html .= '</table>';
		$html .= '<br><br><hr><br><br>';
		return ($html);
	}

		/*
	Reyno
	2004/4/26
	Makes an agenda for the current ac meeting
	*/
	function makeACAgenda($ddate,$id){
		$content = "";
		$content .="<table align='center' width='90%' cellpadding='2' cellspacing='2' border='1'>";
		$content .="<tr><td colspan='2' align='center'>";
		$content .="<b>ACCREDITATION COMMITTEE MEETING<br>";
		$content .="DATE";
		$content .=", TIME<br>";
		$content .="VENUE</b>";
		$content .="</td></tr>";
		$content .="<tr><td width='30%'>TIME</td><td width='70%'>ACTION</td></tr>";
		$content .="<tr><td width='30%'>&nbsp;</td><td width='70%'>WELCOME<br><br>Minutes of previous meeting<br><br>Maters arising<br><br>General<br><br>Secretariat introduction</td></tr>";
		$content .="<tr><td width='30%'>&nbsp;</td><td width='70%'>ACCREDITATION CANDIDACY PHASE</td></tr>";
		$content .="<tr><td width='30%'>&nbsp;</td><td width='70%'>";
		$SQL = "SELECT HEI_name,program_name,NQF_ref,CHE_reference_code FROM Institutions_application,HEInstitution WHERE HEI_id=institution_id and application_status=1 and AC_Meeting_ref=?";

		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
                $sm->bind_param("s", $id);
                $sm->execute();
                $rs = $sm->get_result();
        
		//$rs = mysqli_query($SQL);
		while ($row = mysqli_fetch_array($rs)){
			$content .= "INSTITUTION: ".$row["HEI_name"]."<br>";
			$content .= "PROGRAMME: ".$row["program_name"]." (".$row["CHE_reference_code"].")<br>";
			$content .= "NQF LEVEL: ".$row["NQF_ref"]."<br>";
			$content .= "Recommendation secretariat:<br>";
			$content .= "Discussion:<br><br>";
		}
		$content .="&nbsp;</td></tr>";
		$content .="<tr><td width='30%'>&nbsp;</td><td width='70%'>Summary of decisions<br>Ratification of decisions</td></tr>";
		$content .="</table>";
		return $content;
	}

}
?>
