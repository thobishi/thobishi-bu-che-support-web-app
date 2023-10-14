<?php

class evalSearch {
	public $searchCrit = array();
	private $query = array();
	private $post_array = array("active","A_rated","Race", "Gender", "Disability", "Province", "Eval_sector_ref", "Organisation_type_ref", "Full_part", "ETQA_ref", "Teaching_experience", "Research_expereince", "qualifications_ref", "employer_ref", "Employer_type_ref", "Auditor", "Evaluator", "National_Review_Evaluator","historical_status_ref","merged_status_ref", "Institutional_reviewer");
	private $title_array = array("Available","A-Rated","Race", "Gender", "Disability", "Province", "Sector", "Organisation type","Full/Part Time", "ETQA", "Teaching Experience", "Research Experience", "Highest Qualification", "Institution", "Institution Type", "Auditor", "Evaluator", "National Review Evaluator","Historical Status", "Merge Status", "Institutional Reviewer");
	public $post_titles;

  public function __construct() {
					$this->post_titles = array();
					$i=0;
					foreach ($this->post_array AS $key) {
						$this->post_titles[$key] = $this->title_array[$i];
						$i++;
					}
  }

	public function getUrlQuery () {
		$queryArr = array();
		foreach ($this->query as $part) {
			
			array_push ($queryArr, key($part)."=".current($part));
		}
		return (implode("&", $queryArr));
	}
	
	public function buildSQL ($columns, $criteria) {
		$searchArr = array();
		$sqlArr = array();

		$tableArray = array();
		$tableArray[0] = "Eval_Auditors";
		
		foreach ($this->post_array AS $key => $value) {
		  if (isset($criteria[$value]) && ($criteria[$value] > 0)) {
				// save the cleaned criteria in query array.
				array_push($this->query, array($value => $criteria[$value])); 
				if (strpos(strtolower($value), "experience")) {
					array_push($sqlArr, $value . ">=" . $criteria[$value]);
				}else {
					array_push($sqlArr, $value . "=" . $criteria[$value]);
				}
				if (isset($this->formFields)) {
					if ($this->formFields[$value]->fieldType == 'CHECKBOX'){
								array_push($this->searchCrit, $this->title_array[$key]);
					}else{
						array_push($this->searchCrit, $this->title_array[$key] . ": " . $this->formFields[$value]->fieldValuesArray[$criteria[$value]]);
					}
				}
			}
		}
	
		if (isset($criteria["searchText"]) && ($criteria["searchText"] > "")) {
			array_push($searchArr, "MATCH(Names, Surname, Initials, ID_Number) AGAINST('".$criteria["searchText"]."') ");
			array_push($this->searchCrit, "Name: " . $criteria["searchText"]);
		}
	
		if (isset($criteria["searchText1"]) && ($criteria["searchText1"] > "")) {
			array_push($searchArr, "Job_title LIKE '%".$criteria["searchText1"]."%'");
			array_push($this->searchCrit, "Job Title: " . $criteria["searchText1"]);
		}

		if (isset($criteria["CESM_code1"]) && $criteria["CESM_code1"] != 0) {
			array_push ($tableArray, "SpecialisationLink");
			array_push ($sqlArr, "Persnr=Persno_ref");
			array_push ($sqlArr, "CESM_code_ref LIKE '".$criteria["CESM_code1"]."%'");
			array_push($this->searchCrit, "CESM Classification: " . $criteria["CESM_code1"]) ;
		}
		if (isset($criteria["CESM_code2"]) && $criteria["CESM_code2"] != 0) {
			array_push ($tableArray, "SpecialisationLink");
			array_push ($sqlArr, "Persnr=Persno_ref");
			array_push ($sqlArr, "CESM_code_ref LIKE '".$criteria["CESM_code2"]."%'");
			array_push($this->searchCrit, "CESM Classification: " . $criteria["CESM_code2"]);
		}
	
		$SQL = "SELECT $columns FROM ".implode (", ", $tableArray)." WHERE 1 ";
		$SQL = (count($sqlArr) > 0)?($SQL." AND (" . implode(" AND ", $sqlArr).")"):($SQL);
		$SQL = ((count($searchArr) > 0) && ((count($sqlArr) > 0)))?($SQL):($SQL);
		$SQL = (count($searchArr) > 0)?($SQL." AND (".implode(" OR ", $searchArr).")"):($SQL);
		$SQL .= " ORDER BY number_evals, Surname,Names";

		return ($SQL);
	}
	
	// NB Robin 11-04-2012
	// SpecialisationCESM_code1: CESM code level 1 - generation 1 and 2
	// SpecialisationCESM_code2: CESM code level 2 - generation 1 only. No 3rd level. Only level 1 and 2 were requested at this stage.
	// SpecialisationCESM_qualifiers: CESM code level 2 and 3 - generation 2 only.
	public function getCESM ($persnr) {
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
            
		$cesm_arr = array();
		$cesm_lookup_arr = array();

		$cesmlists[0] = "SELECT CESM_code1 AS Code, Description FROM SpecialisationCESM_code1";
		$cesmlists[1] = "SELECT CESM_code AS Code, Description FROM CESM_Tree";
		$cesmlists[2] = "SELECT SpecialisationCESM_qualifiers_id AS Code, Description FROM SpecialisationCESM_qualifiers";
		
		foreach($cesmlists AS $list){
			$rs = mysqli_query($conn, $list);
			if ($rs){
				while ($row = mysqli_fetch_array($rs)){
					$cesm_lookup_arr[$row['Code']] = $row['Description'];
				}
			}
		}
		
		$SQL = <<<SQL
			SELECT CESM_code_ref
			FROM SpecialisationLink
			WHERE Persno_ref = ?
SQL;
                $stmt = $conn->prepare($SQL);
                $stmt->bind_param("s", $persnr);
                $stmt->execute();
                $rs = $stmt->get_result(); 
                
		//$rs = mysqli_query($SQL);
		if ($rs){
			while ($row = mysqli_fetch_array($rs)){
				$code = $row['CESM_code_ref'];
				if (array_key_exists ($code,$cesm_lookup_arr)){
					$cesm_arr[$code] = $cesm_lookup_arr[$code];
				}
			}
		}
	
		return $cesm_arr;
	}

	// Richard 10-11-2016
	public function getLogin ($persnr) {
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
            
		$SQL = <<<SQL
			SELECT a.email
			FROM users a INNER JOIN Eval_Auditors b
			ON a.user_id = b.user_ref
			WHERE b.persnr = ?
SQL;
                $stmt = $conn->prepare($SQL);
                $stmt->bind_param("s", $persnr);
                $stmt->execute();
                $rs = $stmt->get_result(); 
                
                $login = '';
		//$rs = mysqli_query($SQL) or die(mysqli_error());
		if (mysqli_num_rows($rs) > 0){
			while($r = mysqli_fetch_array($rs)){
				$login .= $r["email"];
			}
		}
	
		return $login;
	}


	
}
		
	?>
