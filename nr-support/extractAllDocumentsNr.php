<?php
class NRonlineDocuments{
	
	private $conn;
	
	public function __construct() {
		$this->conn = $this->connectDB("localhost", "nr_master", "4review", "CHE_national_reviews");
	}
	
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
	 function get_doc_fields($table){
		$farr = array();
		$sql = "SHOW FIELDS FROM $table LIKE '%doc'";
		$rs = mysqli_query($this->conn, $sql) or die(mysqli_error());
		while ($row = mysqli_fetch_array($rs)){
			array_push($farr, $row[0]);
		}

		$dsql = "SHOW FIELDS FROM $table LIKE '%document_ref'";
		$drs = mysqli_query($this->conn, $dsql) or die(mysqli_error());
		while ($drow = mysqli_fetch_array($drs)){
			array_push($farr, $drow[0]);
		}
		
		return $farr;
	}
	function strip_chars($str){
		$a_chr = array("'", "&", "%20", "/",",");
		$a_r   = array("", " and ","","-"," ");
		$str  = str_replace($a_chr, $a_r, $str);

		return $str;
	}
	function get_docs($data){			
		$inst_name_dir  = $this->strip_chars($data["hei_name"]);
		$hei_code = $data['hei_code'];
		$nr_national_review_id = $data['nr_national_review_id'];

		// $dest_path = "/tmp/NR-online/$nr_national_review_id/$hei_code $inst_name_dir";
		$dest_path = "/mnt/orange/14 NATIONAL REVIEW MANAGEMENT/14.6 NR-online mirror/$nr_national_review_id/$hei_code $inst_name_dir";
		// $this->pr($dest_path);
		if (!file_exists($dest_path)){
			$mrc =  mkdir ($dest_path);
			if (!$mrc) {
				die("Error trying to create directory: $dest_path" );
			}
		}
		$table = "nr_programmes";
		
		$fld_arr = $this->get_doc_fields($table);
		foreach ($fld_arr as $fld){
			$sql = "SELECT " . $fld . " FROM " . $table ." WHERE hei_code = '$hei_code'";
			$rs = mysqli_query($this->conn, $sql) or die("Error in.." . mysqli_error($this->conn));
			while($row = mysqli_fetch_array($rs)){
				if ($row[0] > 0) {
						if (!file_exists($dest_path)){
							$mrc =  mkdir ($dest_path);
							if (!$mrc) die("Error trying to create directory: $dest_path" );
						}
					$doc_str = $this->get_doc_data($row[0], $dest_path);
					// if($doc_str){
						// $html .= <<<HTML
							// <tr>
							// <td>$table</td>
							// <td>$fld</td>
							// $doc_str
							// </tr>
// HTML;
					// }
				}
			}
		}
		// return $html;
	}
	
	
	function get_doc_data($doc_id, $dest_path){

	$html = "";

	$sql = <<<SQL
		SELECT * 
		FROM documents
		WHERE document_id = $doc_id
SQL;

	$rs = mysqli_query($this->conn, $sql) or die(mysqli_error());
	if ($row = mysqli_fetch_array($rs)){
	
		$filename = "../nrlibs/docs-nr/" . $row["document_url"];

		// Check if physical file exists
		if (file_exists($filename)) {

			$dest_doc_name = ($row["document_name"] > '') ? $row["document_name"] : "Unnamed_" . $row["document_url"];

			$toSpace = array ("\t", ":");
			$cleanDocName = str_replace($toSpace, " ", $dest_doc_name);
			$cleanDocName = iconv("ISO-8859-1","UTF-8//IGNORE",$cleanDocName);

				
			$destfile = $dest_path . "/" . trim($cleanDocName);

		    $rc = copy($filename, $destfile);

			if (!$rc) {
				die ("Error copying: '$filename' to '$destfile'\n");
			}
		} else {
		    echo ("\nThe sourcec file '$filename' does not exist\n");
		}

		
		// record document

		$html = <<<HTML
			<td>$row[document_id]</td>
			<td>$row[creation_date]</td>
			<td>$row[last_update_date]</td>
			<td>$row[document_name]</td>
			<td>$row[document_url]</td>
HTML;
	}

	return $html;
}
	private function connectDB($host, $user, $password, $db){
		// $conn = mysqli_connect("localhost", "nr_master", "Bateleur5", "CHE_national_reviews_dev")
		$conn = new mysqli($host, $user, $password, $db);
		if($conn->connect_error){
			die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
		}
		return $conn;
		
	}
	
	public function getNRdata(){
		$dataArr = array();
		$sql = "SELECT distinct hei_code, nr_national_review_id, hei_name
				FROM nr_programmes
				LEFT JOIN nr_national_reviews ON nr_national_reviews.id = nr_programmes.nr_national_review_id
				";
		$rs = mysqli_query($this->conn, $sql) or die("Error in .." . mysqli_error($this->conn));
		$rowCount = mysqli_num_rows ($rs);
		while($row = mysqli_fetch_array($rs)) {
		  
		 
		  $nr_national_review_id = $row['hei_name'];
			if (!empty($row['hei_code'])){
				array_push($dataArr, $row);
			}
		} 
		if(!empty($dataArr)){
			foreach($dataArr as $data){
				$this->get_docs($data);
			}
		}
	}
}

$testObject = new NRonlineDocuments();
$testObject->getNRdata();
?>
