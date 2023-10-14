<?php
ini_set('memory_limit','-1');
ini_set('max_execution_time', 3000);
//		XLS Generator Class
//		(c) 2007 Paggard [paggard@paggard.com]
//		18 March 2007 - build 1.4.1

$delim = ":";
if (preg_match("/\\\/",$_SERVER["PATH"])) {$delim = ";";}
@ini_set("include_path",ini_get("include_path").$delim.".");

require_once "/var/www/html/common/xls_generator/hdr_xls.php";
require_once "/var/www/html/common/xls_generator/cl_xls_book.php";
require_once "/var/www/html/common/xls_generator/cl_xls_sheet.php";
require_once "/var/www/html/common/xls_generator/cl_xls_format.php";
require_once "/var/www/html/common/xls_generator/cl_xls_formula.php";
require_once "/var/www/html/common/xls_generator/cl_xml2xls.php";
require_once "/var/www/html/common/xls_generator/cl_storage.php";

// ------------------------------------------------------------------------------------------------
class xls_generator {

	public $BIFF_version;
	public $_BigEndian;
	public $_Data;
	public $_DataSize;
	public $_MaxDataSize;

// ------------------------------------------------------------------------------------------------
// CONSTRUCTOR
	function xls_generator() {
		$this->BIFF_version		= 0x0500;
		$this->_BigEndian			= false;
		$this->_Data				= false;
		$this->_DataSize			= 0;
		$this->_MaxDataSize		= 2080;
		$this->_set_ByteOrder();
		$this->_formula = new formula($this->_BigEndian);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _set_ByteOrder() {
		$double = pack("d", 1.2345);
		$char = pack("C8", 0x8D, 0x97, 0x6E, 0x12, 0x83, 0xC0, 0xF3, 0x3F);
		if ($char == $double) {
			$this->_BigEndian = 0; // Little Endian
		} elseif ($char == strrev($double)) {
			$this->_BigEndian = 1; // Big Endian
		}
		
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// appending data to the tail
	function _store_Tail($data) {
		if (strlen($data) > $this->_MaxDataSize) {
			$data = $this->_get_continue($data);
		}
		$this->_Data = $this->_Data.$data;
		$this->_DataSize += strlen($data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// appending data to the head
	function _store_Head($data) {
		if (strlen($data) > $this->_MaxDataSize) {
			$data = $this->_get_continue($data);
		}
		$this->_Data = $data.$this->_Data;
		$this->_DataSize += strlen($data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - BOF
	function _dump_bof($DataType) {
		/*
		Record BOF, BIFF5/BIFF7:
		Offset		Size		Contents
		0				2			Version
		2				2			Type of the following data:
										0005H = Workbook globals
										0006H = Visual Basic module
										0010H = Worksheet
										0020H = Chart
										0040H = BIFF4 Macro sheet
										0100H = Workspace file
		4				2			Build identifier
		6				2			Build year
		*/
		$rcrd_type = RCRD_BOF;
		$rcrd_length = 0x0008;

		$Version = $this->BIFF_version;
		$BuildID = 0x096C; //0xEC15; //0x00;
		$BuildYear = 0xCD07; //0xD007; //0x07C9; //0x00;



		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvv", $Version, $DataType, $BuildID, $BuildYear);

		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - EOF
	function _dump_eof() {

		$rcrd_type = RCRD_EOF;
		$rcrd_length = 0x0000;

		$header = pack("vv", $rcrd_type, $rcrd_length);

		$this->_store_Tail($header);
	} // end of function
// ------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
// RECORD - CONTINUE
	function _get_continue($data) {
		/*
		BIFF version		Maximum data size of a record
		BIFF2-BIFF7			2080 bytes (2084 bytes including record header)
		BIFF8					8224 bytes (8228 bytes including record header)
		
		Record CONTINUE, BIFF2-BIFF8:
		Offset		Size		Contents
		0				var.		Data continuation of the previous record
		
		The records are shown with their headers to make the example clearer.
		Offset		Size		Contents			Description
		0				2								Any record identifier
		2				2			2020H (8224)	Record data size
		4				8214							Any data
		8218			2			000AH (10)		Unicode string character count
		8220			1			00H				Unicode string option flags (8-bit characters)
		8221			7			41H 42H � 47H	8-bit character array �ABCDEFG�
		8228			2			003CH				Record identifier CONTINUE
		8230			2			0007H				(7) Record data size
		8232			1			01H				Unicode string option flags (16-bit characters)
		8233			2			0048H				16-bit character �H�
		8235			2			1234H				16-bit character �ؔ
		8237			2			0049H				16-bit character �I�
		*/

		$rcrd_type = RCRD_CONTINUE;
		$rcrd_data_size = $this->_MaxDataSize;

		$tmp = substr($data, 0, $rcrd_data_size);
		$data = substr($data, $rcrd_data_size);
		$tmp = substr($tmp, 0, 2) . pack ("v", $rcrd_data_size-4) . substr($tmp, 4);

		while (strlen($data) > $rcrd_data_size) {
			$header = pack("vv", $rcrd_type, $rcrd_data_size);
			$tmp .= $header;
			$tmp .= substr($data, 0, $rcrd_data_size);
			$data = substr($data, $rcrd_data_size);
		}

		$header = pack("vv", $rcrd_type, strlen($data));
		$tmp .= $header;
		$tmp .= $data;

		return $tmp;
	}// end of function
//-------------------------------------------------------------------------------------------------

} // END OF CLASS
// ------------------------------------------------------------------------------------------------



?>
