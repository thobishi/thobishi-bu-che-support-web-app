<?
require_once ('Excel/ExcelData.class.php');

class importData
{
	private $defn;
	private $destTable;
	private $destTablePrimaryKey;
	private $importFileHeader = array();
	private $uniqueCriteriaCols = array();
	private $importColDefn = array();
	private	$importFileErrors = array();
	private	$importFileDataExists = array();
	private	$importFileDataErrors = array();
	private $insertErrors = array();
	private $count_total_records = 0;
	private $count_blank = 0;
	private $count_exists = 0;
	private $count_errors = 0;
	private $count_insert = 0;
	private $count_insert_errors = 0;
	private $error_required = 0;
	public	$data;
	public  $count_import = 0;
	public 	$flag_validateImportFile = false;
	public 	$flag_validateImportDataRow = false;
	public  $importStartRow;
	public $importMode = "X";  // X=>if record exists do not replace; R=>if record exists then replace it with new
	public $importCols;
	public $foreign_key = array();
	public $foreign_key_defn = array();

	function __construct($doc_id){
		if ($doc_id > "") {
			$this->data = new ExcelData ($doc_id,"XLS","");
		}
	}
	
	/***************************************************************************************************
	****************************************************************************************************/
	public function setImportDefn($defn){
		$rc = false;

		$this->defn = $defn;

		$sql = <<<IDEFN
			SELECT * FROM import_data_defn
			WHERE import_defn = "$defn";
IDEFN;

		$rs = mysqli_query($sql);

		if ($rs){
			$n = mysqli_num_rows($rs);
			if ($n == 1){
				$row = mysqli_fetch_array($rs);
				$defn_id = $row["import_data_defn_id"];
				$this->destTable = $row["destTable"];
				$this->destTablePrimaryKey = $row["destTablePrimaryKey"];
				$this->importStartRow = $row["importStartRow"];
				if ($row["uniqueCriteriaCols"] > "") {
					$this->uniqueCriteriaCols = explode("|",$row["uniqueCriteriaCols"]); // must exist in import file and destination table
				} else {
					$this->uniqueCriteriaCols = array();
				}
				if ($row["foreignKey"] > "") {
					$this->foreign_key_defn = explode("|",$row["foreignKey"]); // must exist in import file and destination table
				} else {
					$this->foreign_key_defn = array();
				}
				
				// Get list of fields to import.
				$sql = <<<DEFN
					SELECT * FROM import_data_field_defn
					WHERE import_data_defn_ref = "$defn_id";
DEFN;
				$rs = mysqli_query($sql);

				while ($row = mysqli_fetch_array($rs)){
					$this->importColDefn[$row["dest_table_column_name"]]
						= array("field_desc"=>$row["field_desc"],
								"import_file_header_name"=>$row["import_file_header_name"],
								"v_col_is_required"=>$row["v_col_is_required"],
								"v_data_is_required"=>$row["v_data_is_required"],
								"v_format"=>$row["v_format"]);
				}

				$rc = true;
			}
		}

		return $rc;

	}

	public function printImportDefn($type){
		$html = <<<DEFN
		<table>
DEFN;
	
		switch ($type){
		case 'v_col_is_required':
			foreach ($this->importColDefn as $key=>$val){
				if ($val["v_col_is_required"] == "yes"){
					$html .= <<<DEFN
					<tr><td>$val[field_desc]</td></tr>
DEFN;
				}
			}
			
			break;
		default:
		}

		$html .= <<<DEFN
		</table>
DEFN;
		echo $html;
	}
	
	public function importFile(){
		return $this->validateImportFile($this->data->dataHead);
	}

	public function validateFlag($flag,$col){
		$this->flag_validateImportFile = true;
		if ($this->data->dataHead[$col] != $flag){
			array_push($this->importFileErrors,"Data: $flag to indicate the load period for the file is missing in the first row, first column cell.  This must match the selected load period .  Please check that you are loading the correct file for the correct period.");
			$this->flag_validateImportFile = false;
		}
		
		return $this->flag_validateImportFile;
	}
	
	private function validateImportFile($header){
		$this->flag_validateImportFile = true;

		foreach ($this->importColDefn as $key=>$colDefn) {

			$head_opt_arr = array();
			$head_opt = strtoupper($colDefn["import_file_header_name"]);
			$head_opt1 = explode("|",$head_opt);
			foreach ($head_opt1 as $hoa){
				array_push($head_opt_arr,trim($hoa));
			}

			for ($i=0; $i<count($header); $i++) {
				// if the header field in file matches one defined in the import defn header columns then
				// set importCols to the database column name).

				if (in_array (trim(strtoupper($header[$i])), $head_opt_arr)) {
					$this->importCols[$key] = $i;
					break;
				}
			}
		}

		// All required columns should be in the $this->importCols array
		foreach ($this->importColDefn as $key=>$colDefn) {
			if ($colDefn["v_col_is_required"] == "yes" && !isset($this->importCols[$key])){
				array_push($this->importFileErrors,"Column missing in the import file. Options for the heading for this column are: ".$colDefn["import_file_header_name"]);
				$this->flag_validateImportFile = false;
			}
		}

		return $this->flag_validateImportFile;

	}

	public function displayImportFileReport(){

		// default
		$errReportHead = "The import file is in the expected format and may be imported.";
		$errReportDesc = "";

		// import file validation failed.
		if ($this->flag_validateImportFile == false){
			$errReportHead = "The import file is NOT in the expected format and cannot be imported. The import cannot proceed
			and has been terminated.  Please correct the format of the import file according to the following problems that
			were found and then start the import again.";

			$errors = "";
			if (count($this->importFileErrors > 0)){
				foreach($this->importFileErrors as $err){
					$errors .= "<tr><td>$err</td></tr>";
				}
			}

			$errReportDesc = <<<ERRDESC
				<table>
				<tr>
					<td><b>Problem Description</b></td>
				</tr>
				$errors
				</table>
ERRDESC;
		}

		$html = <<<IFR
			<hr>
			<table border="0" cellpadding="2" cellspacing="2" width="95%" align="center">
			<tr class="oncolourb">
				<td><b>Import File Validation Report</b></td>
			</tr>
			<tr class="oncoloursoft">
				<td><i>$errReportHead</i></td>
			</tr>
			<tr>
				<td>$errReportDesc</td>
			</tr>
			</table>
		<hr>
IFR;
		echo $html;
	}

	/***********************************************************************
	Header is the list of heading columns in the spreadsheet to import
	************************************************************************/

	public function importFileData($writeData=""){
		$currentRow = 1;  // set to one to account for the header. First data row starts at line 2.

		while ($this->data->readNext()) {
			$currentRow++;

			if ($currentRow < $this->importStartRow) continue;  // Only start processing the file from the row that data is expected.

			$this->count_total_records += 1;
			$this->validateDataRow($this->data->dataRow,$currentRow);
			if ($this->flag_validateImportDataRow == true) {
				$this->count_import += 1;
				if ($writeData == "yes") $this->saveDataRow($this->data->dataRow);
			}
		}
	}

	private function validateExists($dataRow){
		$exists = '';

		// Check whether the record already exists in the table based on a definition of identifying uniqeness criteria

		if (count($this->uniqueCriteriaCols) > 0){
			$whereArray = array();
			foreach ($this->uniqueCriteriaCols as $uc){

			// add foreign key unique restrictions (field not in import file)
				if (isset($this->foreign_key[$uc]) && $this->foreign_key[$uc] > ""){
					array_push($whereArray,"$uc = '".$this->foreign_key[$uc]."'");
				} else {  // unique criteria field is on import file.
					array_push($whereArray,"$uc = '".$dataRow[$this->importCols[$uc]]."'");
				}
			}
			$where = implode (" AND ", $whereArray);
//echo $where;
			$sql = <<<SELECT1
				SELECT $this->destTablePrimaryKey
				FROM $this->destTable
				WHERE $where;
SELECT1;

			$rs = mysqli_query($sql);
			while ($row = mysqli_fetch_array($rs)){
				$exists = $row[$this->destTablePrimaryKey];
				//array_push($pk_val_array,$row[$this->destTablePrimaryKey]);  // for later if we want to update records.
			}
		}

		return $exists;
	}

	private function validateRequired($dataRow,$rn){
		$rc = 0;
		// All required columns should be in the $this->importCols array
		foreach ($this->importColDefn as $key=>$colDefn) {
			if ($colDefn["v_data_is_required"] == "yes" && !$dataRow[$this->importCols[$key]] > ""){
				array_push($this->importFileDataErrors,$rn . ",".$colDefn["field_desc"] ." is required.");
				$rc = 1;
			}
		}
		return $rc;
	}

	public function validateDataRow($dataRow,$recnum){

		$valid_record = false;
		$keyArray = array();
		$dataArray = array();
		$pk_val_array = array();
		$existsArray = array();

		// Define the columns to be imported
		foreach ($this->importCols as $key=>$val){
			array_push($keyArray,$key);
			array_push($dataArray,$dataRow[$val]);
			if ($dataRow[$val] > ''){
				$valid_record = true;
			}
		}

		// blank line - no data in any fields
		if ($valid_record == false){
			$this->count_blank += 1;
			$this->flag_validateImportDataRow = false;
			return;
		}

		// validateExists returns the PK of the row in case we want to extend class to handle overwriting existing records.
		$rc = $this->validateExists($dataRow);
		if ($rc > ''){
			$this->count_exists += 1;
			array_push($this->importFileDataExists, $recnum .', '.implode(",",$dataRow));
			$this->flag_validateImportDataRow = false;
			return;
		}

		$rc = $this->validateRequired($dataRow,$recnum);
		if ($rc > ''){
			$this->error_required += 1;
			$this->flag_validateImportDataRow = false;
			return;
		}

		$this->flag_validateImportDataRow = true;
	}


	public function displayImportFileValidationReport($format=""){
		$html = "";
		$reportExists = "";
		$reportHeader = "";
		$errorRequired = "";
		
		switch ($format) {

		case "requiredErrors" :
			if ($this->error_required > 0){
				$errorRequired = <<< ERROR
					<table border='0' cellpadding="2" cellspacing="2" width="95%" align="center">
						<tr>
							<td colspan="2">
								<span class='loud'>Number of records with errors:</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="oncolourb"><b>List of records that are missing required data</b></td>
						</tr>
						<tr class="onblueb">
							<td>ID Number</td>
							<td>Error</td>
						</tr>
ERROR;

				foreach ($this->importFileDataErrors as $e){
					$explodedString = $this->explodeString($e, 2, 0);
					$errorRequired .= <<< ERROR
						<tr class="oncoloursoft">
							$explodedString
						</tr>
ERROR;
				}
				$errorRequired .= "</table>";
			}
			echo $errorRequired;
			break;
			/****/

		case "existingRecords" :
		 	$reportExists = $this->recordsExist($this->importFileDataExists);
		 	echo $reportExists;
			break;
			/***/

		default :
			$foreignKey = "";
			foreach($this->foreign_key as $key=>$val){
				$foreignKey .= "&".$key."=".base64_encode($val);
			}
			$count_exists = ($this->count_exists > 0) ? "<a href='pages/existingRecords.php?doc_id=".base64_encode(readPOST("FLD_document_upload_ref"))."&definition=".base64_encode($this->defn).$foreignKey."' target='_blank'>".$this->count_exists."</a>" : $this->count_exists;
			$error_required = ($this->error_required > 0) ? "<a href='pages/requiredErrors.php?doc_id=".base64_encode(readPOST("FLD_document_upload_ref"))."&definition=".base64_encode($this->defn).$foreignKey."' target='_blank'>".$this->error_required."</a>" : $this->error_required;
			$tot_records = $this->count_total_records - $this->count_blank; // Subtract blank lines because make no sense to a user
			
				$reportHeader = <<<RPTHEAD
					<tr class="oncolourb">
						<td colspan="2"><span class="specialb"><i>List of records that already exist</i></span></td>
					</tr>
					<tr>
						<td class="onblueb" width="50%">Total number of rows in import file</td>
						<td class="oncoloursoft">$tot_records</td>
					</tr>
					<tr>
						<td class="onblueb" width="50%">No. rows: previously imported - ignored</td>
						<td class="oncoloursoft">$count_exists</td>
					</tr>
					<tr>
						<td class="onblueb" width="50%">No. rows: failed validation</td>
						<td class="oncoloursoft">$error_required</td>
					</tr>
					<tr>
						<td class="onblueb" width="50%">No. rows: passed validation and may be imported</td>
						<td class="oncoloursoft">$this->count_import</td>
					</tr>
RPTHEAD;

			$errReportHead = "Please continue with the import if you wish to load the data that	has passed validation into the system.";

			$html = <<<DVR
				<table border="0" cellspacing="2" cellpadding="2" width="95%" align="center">
				<tr class="oncolourb">
					<td colspan="2"><b>Import File Data Validation Report</b></td>
				</tr>
				<tr class="oncoloursoft">
					<td colspan="2"><i>$errReportHead</i></td>
				</tr>
				<tr>
					$reportHeader
				</tr>
				<tr>
					<td>$reportExists</td>
				</tr>
				<tr>
					<td colspan="2">$errorRequired</td>
				</tr>
				</table>
DVR;

			echo $html;
		}
	}

	public function displayImportFileDataReport(){
		$html = "";
		$reportFail = "";

		$reportHeader = <<<RPTHEAD
				<td>Number of rows for this import:</td>
				<td>$this->count_import</td>
RPTHEAD;

		$reportInsert = "<td>Rows successfully imported</td><td>".$this->count_insert."</td>";

		if ($this->count_insert_errors > 0){
			$reportFail = "The following " . $this->count_insert_errors . " records failed to import. Please check the data and try again.<br><br>";
			foreach ($this->insertErrors as $e){
				$reportFail .= "$e<br>";
			}
		}


		$html = <<<DVR
			<table border="0" cellpadding="2" cellspacing="2" width="95%" align="center">
			<tr class="oncolourb">
				<td colspan="2"><b>Import File Status Report</b></td>
			</tr>
			<tr class="oncoloursoft">
				$reportHeader
			</tr>
			<tr class="oncoloursoft">
				$reportInsert
			</tr>
			<tr class="oncoloursoft" colspan="2">
				<td>$reportFail</td>
			</tr>
			</table>
DVR;

		echo $html;
	}

	public function saveDataRow ($dataRow){

		// exit if the file failed validation or if the record failed validation.
		if ($this->flag_validateImportFile == false) return false;
		if ($this->flag_validateImportDataRow == false) return false;

		$keyArray = array();
		$dataArray = array();
		$pk_val_array = array();

		// Define any foreign key values that need to be inserted.
		foreach ($this->foreign_key as $key=>$val){
			array_push($keyArray,$key);
			array_push($dataArray,$val);
		}

		// Define the columns to be imported from the import file.
		foreach ($this->importCols as $key=>$val){
			array_push($keyArray,$key);
			$val = system_escape ($dataRow[$val]);
			array_push($dataArray,$val);
		}

		// Currently are assuming the importMode = X. Only new rows will be inserted. None will be replaced.
		// Generate the INSERT or REPLACE statement.
		$keys = implode(",", $keyArray);
		$data = "'" . implode("','", $dataArray) . "'";

		$sql = <<<INSERT1
				INSERT INTO $this->destTable
				($keys)
				VALUES ($data)
INSERT1;

		$rs = mysqli_query($sql);
		if ($rs){
			$this->count_insert += mysqli_affected_rows();
		} else {
			$this->count_insert_errors += 1;
			array_push($this->insertErrors,$data);
		}
	}

	function recordsExist($importFileDataExists) {
		$numFields = 12;
		$startCol = 0;
		$colHead = "";
		$head = $this->data->dataHead;
		
		// Get Column headings
		for ($i=$startCol;$i<$numFields;$i++){
			$colHead .= <<<CHEAD
				<td>$head[$i]</td>
CHEAD;
		}
		$reportExists = <<< REPORT
		 <table border='0' cellpadding='2' cellspacing='2' width='95%' align='center'>
		 	<tr>
		 		<td colspan="$numFields">
		 			<span class='loud'>Rows from import file that already exist in the system and may not be re-imported:</span>
		 		</td>
		 	</tr>
REPORT;

			$reportExists .= <<< EXISTS
				<tr class="oncolourb">
					<td>Criteria:</td>
					<td colspan="$numFields">Row data</td>
				</tr>
				<tr class="oncolourb">
					<td>Row Number</td>
					$colHead
				</tr>
EXISTS;
		foreach ($importFileDataExists as $e){
			$explodedString = $this->explodeString($e, $numFields, $startCol);
			$reportExists .= <<< EXISTS
				<tr class="oncoloursoft">
					$explodedString
				</tr>
EXISTS;
		}
		$reportExists .= "</table>";
		return $reportExists;
	}

	function explodeString($exStr, $fields, $start) {
		$tableData = "";
		$string = explode(",", $exStr);

		$row_no = array_shift($string);
		$tableData .= <<< TABLEDATA
			 <td>$row_no</td>
TABLEDATA;

		for ($i=$start; $i<$fields; $i++) {
			if (isset($string[$i])){
				$item = $string[$i];
				$tableData .= <<< TABLEDATA
				 <td>$item</td>
TABLEDATA;
			}
		}
		return $tableData;
	}

}


?>