<?php

//		XLS Generator Class
//		(c) 2007 Paggard [paggard@paggard.com]
//		18 March 2007 - build 1.4.1

// ------------------------------------------------------------------------------------------------
class xls_sheet extends xls_generator {

		public $_tempdir;

		public $_name;
		public $_index;
		public $_activesheet;
		public $_firstsheet;
		public $_url_format;

		public $_PaperSizeIndex;
		public $_orientation;
		public $_start_page_number;
		public $Header;
		public $Footer;
		public $_hor_centered;
		public $_vert_centered;
		public $_margin_head;
		public $_margin_foot;
		public $_margin_left;
		public $_margin_right;
		public $_margin_top;
		public $_margin_bottom;

		public $_ext_sheets;
		public $_using_tmpfile;
		public $_filehandle;
		public $_fileclosed;
		public $_offset;
		public $_xls_row_start;
		public $_xls_col_start;
		public $_xls_strmax;
		public $_dim_row_end;
		public $_dim_row_start;
		public $_dim_col_end;
		public $_dim_col_start;
		public $_title_row_end;
		public $_title_row_start;
		public $_title_col_end;
		public $_title_col_start;
		public $_print_row_end;
		public $_print_row_start;
		public $_print_col_end;
		public $_print_col_start;

		public $_print_gridlines;
		public $_view_gridlines;
		public $_GridColor;
		public $_print_headers;

		public $_fit_page;
		public $_fit_width;
		public $_fit_height;

		public $_protected;
		public $_password;

		public $_zoom;
		public $_print_scale;

		public $_col_sizes;
		public $_row_sizes;
		public $_col_formats;
		public $_row_formats;
		public $_colinfo;
		public $_selection;
		public $_active_pane;
		public $_frozen;
		public $_selected;


// ------------------------------------------------------------------------------------------------
// CONSTRUCTOR - creates new sheet in a given workbook
	function __construct($c_file, $name, $index, &$activesheet, &$firstsheet, &$url_format, $tempdir) {
		if ($c_file) {
			include $c_file;
		}

		$this->xls_generator();

		$this->_tempdir				= $tempdir;

		$this->_name					= $name;
		$this->_index					= $index;
		$this->_activesheet			= &$activesheet;
		$this->_firstsheet			= &$firstsheet;
		$this->_url_format			= &$url_format;

		$this->_ext_sheets			= array();
		$this->_using_tmpfile		= 1;
		$this->_filehandle			= false;
		$this->_fileclosed			= 0;
		$this->_offset					= 0;
		$this->_xls_row_start		= 65536; // maxnumber of rows
		$this->_xls_col_start		= 256; // max number of colomns
		$this->_xls_strmax			= 255; // max number of chars in the string
		$this->_dim_row_end			= (isset($rowmax)) ? $rowmax +1 : 1;
		$this->_dim_row_start		= 0;
		$this->_dim_col_end			= (isset($colmax)) ? $colmax +1 : 1;
		$this->_dim_col_start		= 0;
		$this->_colinfo				= array();
		$this->_selection				= array(0, 0);
		$this->_active_pane			= 3;
		$this->_frozen					= 0;
		$this->_selected				= 0;

		$this->_PaperSizeIndex = ($PaperSizeIndex) ? $PaperSizeIndex : 0;
		$this->_orientation = ($PageOrientation) ? 1 : 0;
		$this->_start_page_number = ($StartPageNumber) ? $StartPageNumber : 1;
		$this->Header = $Header;
		$this->Footer = $Footer;
		$this->_margin_head = ($margin_head) ? $margin_head : 0.40;
		$this->_margin_foot = ($margin_foot) ? $margin_foot : 0.40;

		$this->_hor_centered = ($H_Centered) ? 1 : 0;
		$this->_vert_centered = ($V_centered) ? 1 : 0;
		$this->_margin_top = ($margin_top) ? $margin_top : 0.75;
		$this->_margin_bottom = ($margin_bottom) ? $margin_bottom : 10.75;
		$this->_margin_left = ($margin_left) ? $margin_left : 0.75;
		$this->_margin_right = ($margin_right) ? $margin_right : 0.75;

		$this->_print_gridlines = ($PrintGridlines) ? $PrintGridlines : 0;
		$this->_view_gridlines = ($ViewGridlines) ? $ViewGridlines : 0;
		$this->_GridColor = ($GridColor) ? $this->set_grid_color($GridColor) : $this->set_grid_color("#C0C0C0");
		$this->_print_headers = ($PrintHeaders) ? $PrintHeaders : 0;

		$this->_fit_page = ($FitPage) ? $FitPage : 0;
		$this->_fit_width = ($FitWidth) ? $FitWidth : 0;
		$this->_fit_height = ($FitHeight) ? $FitHeight : 0;

		$this->_protected = ($Password) ? 1 : 0;
		$this->_password = ($Password) ? $this->_encode_password($Password) : false;

		$this->_zoom = ($Zoom) ? $Zoom : 100;
		$this->_print_scale = ($PrintScale) ? $PrintScale : 100;

		$this->_title_row_end = false;
		$this->_title_row_start = false;
		$this->_title_col_end = false;
		$this->_title_col_start = false;
		$this->_print_row_end = false;
		$this->_print_row_start = false;
		$this->_print_col_end = false;
		$this->_print_col_start = false;

		$this->_col_sizes = array();
		$this->_row_sizes = array();
		$this->_col_formats = array();
		$this->_row_formats = array();

		$this->_init();
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _init() {
		$_TempFileName = tempnam($this->_tempdir, "xls_genrator".rand());
		$handle = @fopen($_TempFileName, "w+b");
		if ($handle) {
			// data will be stored in a temp file on disk
			$this->_filehandle = $handle;
			$this->_TempFileName = $_TempFileName;
		}
		else {
			// data will be stored in memory (slow)
			$this->_using_tmpfile = 0;
			if ($this->_index == 0) {
				$dir = $this->_tempdir;
			}
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _close($sheetnames) {
		// heading
		$this->_dump_dimensions();
		$this->_dump_password();
		$this->_dump_protect();
		$this->_dump_setup();
		$this->_dump_margin_bottom();
		$this->_dump_margin_top();
		$this->_dump_margin_right();
		$this->_dump_margin_left();
		$this->_dump_vcenter();
		$this->_dump_hcenter();
		$this->_dump_footer();
		$this->_dump_header();
		$this->_dump_wsbool();
		$this->_dump_gridset();
		$this->_dump_print_gridlines();
		$this->_dump_print_headers();

		$num_sheets = sizeof($sheetnames);
		for ($i=$num_sheets; $i>0; $i--) {
			$sheetname = $sheetnames[$i-1];
			$this->_dump_externsheet($sheetname);
		}

		$this->_dump_externcount($num_sheets);

		if (sizeof($this->_colinfo)>0){
			while (sizeof($this->_colinfo)>0) {
				$arrayref = array_pop ($this->_colinfo);
				$this->_dump_colinfo($arrayref);
			}
			$this->_dump_defcol();
		}

		$this->_dump_bof(0x0010);
		// tailing
		$this->_dump_window2();
		$this->_dump_zoom();
		$this->_dump_selection($this->_selection);
		$this->_dump_eof();
		// attempting to clear temp file
		@unlink($this->_TempFileName);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function get_Data() {
		$buffer = 4096;
		// checking memory
		if ($this->_Data!==false) {
			$tmp = $this->_Data;
			$this->_Data=false;
			$fh = $this->_filehandle;
			if ($this->_using_tmpfile) {
				fseek($fh, 0, SEEK_SET);
			}
			return $tmp;
		}
		// checking temp file
		if ($this->_using_tmpfile) {
			if ($tmp=fread($this->_filehandle, $buffer)) {
				return $tmp;
			}
		}
		// empty
		return false;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function select() {
		$this->_selected = 1;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function activate() {
		$this->_selected = 1;
		$this->_activesheet = $this->_index;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_first_sheet() {
		$this->_firstsheet = $this->_index;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// protect sheet from editing - password must be no more than 9 chars
	function protect($password) {
		$this->_protected = 1;
		$this->_password = $this->_encode_password($password);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: set_column_width($firstcol, $lastcol, $width, $format, $hidden)
function set_column_width() {
		$Args = func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$cell = array_shift($Args);
			$Args = array_merge($this->_parce_notation($cell), $Args);
		}
		array_push($this->_colinfo, $Args);
		if (sizeof($Args)<3) {
			return;
		}
		$width = (isset($Args[4]) && $Args[4]) ? 0 : $Args[2];
		$format = (isset($Args[3]) && $Args[3]) ? $Args[3] : "";
		list($firstcol, $lastcol) = $Args;
		for ($col=$firstcol;$col<=$lastcol;$col++) {
			$this->_col_sizes[$col] = $width;
			if ($format) {
				$this->_col_formats[$col] = $format;
			}
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: set_selection()
// possible args:
//						A - equal to 0
//						B2 - equal to 1,1
//						B2:C4 - equal to 1,1,4,2
//		col is always first
	function set_selection() {
		$Args = func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$Args = $this->_parce_notation($Args[0]);
		}
		$this->_selection = $Args;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_portrait() {
		$this->_orientation = 0;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_landscape() {
		$this->_orientation = 1;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_paper_size($PaperSizeIndex) {
		$this->_PaperSizeIndex = $PaperSizeIndex;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_header($string, $margin=false) {
		if (strlen($string) >= 255) {
			trigger_error("Header string is more than 255 chars!", E_USER_WARNING);
			return;
		}
		$this->Header = $string;
		if ($margin) {$this->_margin_head = $margin;}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_footer($string, $margin=false) {
		if (strlen($string) >= 255) {
			trigger_error("Footer string string is more than 255 chars!", E_USER_WARNING);
			return;
		}
		$this->Footer = $string;
		if ($margin) { $this->_margin_foot = $margin; }
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function center_hor($val=1) {
		$this->_hor_centered = $val;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function center_vertically($val=1) {
		$this->_vert_centered = $val;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_margins_all($margin) {
		$this->set_margin_left($margin);
		$this->set_margin_right($margin);
		$this->set_margin_top($margin);
		$this->set_margin_bottom($margin);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_margins_LR($margin) {
		$this->set_margin_left($margin);
		$this->set_margin_right($margin);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_margins_TB($margin) {
		$this->set_margin_top($margin);
		$this->set_margin_bottom($margin);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_margin_left($margin=0.75) {
		$this->_margin_left = $margin;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_margin_right($margin=0.75) {
		$this->_margin_right = $margin;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_margin_top($margin=1.00) {
		$this->_margin_top = $margin;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_margin_bottom($margin=1.00) {
		$this->_margin_bottom = $margin;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// rows will be repeated on each printed page
	function repeat_rows($row_start, $row_end=false) {
		$this->_title_row_end = $row_start;
		$this->_title_row_start = ($row_end) ? $row_end : $row_start;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// repeat_columns($col_start, $col_end)
// colomns will be repeated on each printed page
	function repeat_columns() {
		$Args=func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$cell = array_shift($Args);
			$Args = $this->_parce_notation($cell);
		}
		$this->_title_col_end = $Args[0];
		$this->_title_col_start = isset($Args[1]) ? $Args[1] : $Args[0];
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: set_print_area($Start_row, $Start_col, $End_row, $End_col)
	function set_print_area() {
		$Args=func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$cell = array_shift($Args);
			$Args = array_merge($this->_parce_notation($cell), $Args);
		}
		if (sizeof($Args) != 4) { return; }
		$this->_print_row_end = $Args[0];
		$this->_print_col_end = $Args[1];
		$this->_print_row_start = $Args[2];
		$this->_print_col_start = $Args[3];
	} // end of function
// ------------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------------
// user func ::	option == 0 - all gridlines are shown
//						option == 1 - gridlines are hidden when printing
//						option == 2 - gridlines are hidden both in the sheet view and when printing
	function hide_gridlines($option=1) {
		if ($option == 0) {
			$this->_print_gridlines  = 1;
			$this->_view_gridlines = 1;
		}
		else if ($option == 1) {
			$this->_print_gridlines  = 0;
			$this->_view_gridlines = 1;
		}
		else {
			$this->_print_gridlines  = 0;
			$this->_view_gridlines = 0;
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: set manual grid color
	function set_grid_color() {
		$Args = func_get_args();
		if (sizeof($Args) == 1) {
			$color = array_shift($Args);
			$color = preg_replace("/#/","",$color);
			$red = hexdec(substr($color, 0, 2));
			$green = hexdec(substr($color, 2, 2));
			$blue = hexdec(substr($color, 4, 2));
			if (  ($red   < 0 || $red   > 255) ||
					($green < 0 || $green > 255) ||
					($blue  < 0 || $blue  > 255) )
			{
				trigger_error("Illegal Color Value  - should be 0 <= color <= 255", E_USER_ERROR);
				return;
			}
		}
		else if (sizeof($Args) < 3) {
			return pack ("CCCC",0x00,0x00,0x00,0x00);
		}
		else {
			$red = $Args[0];
			$green = $Args[0];
			$blue = $Args[0];
		}
		return pack ("CCCC",$red,$green,$blue,0x00);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function print_headers($headers=1) {
		$this->_print_headers = $headers;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: write($row, $col, $value, $format)
// universal function to handle insertin values of different types
	function write() {
		$Args=func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$cell = array_shift($Args);
			$Args = array_merge($this->_parce_notation($cell), $Args);
		}

		$value = $Args[2];

//		// if num_format is '@' - text - write string
//		if ($Args[3]->_num_format == "@") {
//			return call_user_func_array(array(&$this, "write_string"), $Args);
//		}
		// Array
		if (is_array($value)) {
			return call_user_func_array(array(&$this, "write_row"), $Args);
		}
		// Number
		if (preg_match('/^([+-]?)(?=\d|\.\d)\d*(\.\d*)?([Ee]([+-]?\d+))?$/', $value)
		&&
			(isset($Args[3]->_num_format) && $Args[3]->_num_format != '@')
		) {
			return call_user_func_array(array(&$this, "write_number"), $Args);
		}
		// URL
		else if (preg_match('|^[fh]tt?ps?://|', $value)) {
			return call_user_func_array(array(&$this, "write_url"), $Args);
		}
		// mailto: link
		else if (preg_match('/^mailto:/', $value)) {
			return call_user_func_array(array(&$this, "write_url"), $Args);
		}
		// formula
		else if (preg_match("/^=/",$value)) {
			return call_user_func_array(array(&$this, "write_formula"), $Args);
		}
		// formula
		else if (preg_match("/^@/",$value)) {
			return call_user_func_array(array(&$this, "write_formula"), $Args);
		}
		// Blank
		else if ($value == '') {
			array_splice($Args, 2, 1);
			return call_user_func_array(array(&$this, "write_blank"), $Args);
		}
		// if num_format is '@' - text - write string
		if (isset($Args[3]) && isset($Args[3]->_num_format) && $Args[3]->_num_format == "@") {
			return call_user_func_array(array(&$this, "write_string"), $Args);
		}
		// String
		else {
			return call_user_func_array(array(&$this, "write_string"), $Args);
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: write_row($row, $col, $array_ref, $format)
// fills several row cells in one pass
	function write_row() {
		$Args=func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$cell = array_shift($Args);
			$Args = array_merge($this->_parce_notation($cell), $Args);
		}
		if (!is_array($Args[2])) {
			trigger_error("Not an array ref in call to write_row()!", E_USER_ERROR);
		}
		list($row, $col, $Values)=array_splice($Args, 0, 3);
		$options = $Args[0];
		$error = 0;
		foreach ($Values as $Value) {
			if (is_array($Value)) {
				$ret = $this->write_col($row, $col, $Value, $options);
			}
			else {
				$ret = $this->write($row, $col, $Value, $options);
			}
			$error = $error || $ret;
			$col++;
		}
		return $error;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: write_col($row, $col, $array_ref, $format)
// fills several col cells in one pass
	function write_col() {
		$Args=func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$cell = array_shift($Args);
			$Args = array_merge($this->_parce_notation($cell), $Args);
		}
		if (!is_array($Args[2])) {
			trigger_error("Not an array ref in call to write_row()!", E_USER_ERROR);
		}
		$row = array_shift($Args);
		$col = array_shift($Args);
		$Values = array_shift($Args);
		$options = $Args;
		$error = 0;
		foreach ($Values as $Value) {
			$ret = $this->write($row, $col, $Value, $options[0]);
			$error = $error || $ret;
			$row++;
		}
		return $error;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// --- PRIVATE PARTS
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// overload function of the parent class
	function _store_Tail($data) {
		if ($this->_using_tmpfile) {
			if (strlen($data) > $this->_MaxDataSize) {
				$data = $this->_get_continue($data);
			}
			fputs($this->_filehandle, $data);
			$this->_DataSize += strlen($data);
		}
		else { parent::_store_Tail($data); }
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _parce_notation($cell) {
		$cell = strtoupper($cell);
		if (preg_match('/([A-I]?[A-Z]):([A-I]?[A-Z])/', $cell, $reg)) {
			list($dummy, $col1) =  $this->_get_row_col($reg[1] .'1');
			list($dummy, $col2) =  $this->_get_row_col($reg[2] .'1');
			return array($col1, $col2);
		}
		if (preg_match('/\$?([A-I]?[A-Z]\$?\d+):\$?([A-I]?[A-Z]\$?\d+)/', $cell, $reg)) {
			list($row1, $col1) =  $this->_get_row_col($reg[1]);
			list($row2, $col2) =  $this->_get_row_col($reg[2]);
			return array($row1, $col1, $row2, $col2);
		}
		if (preg_match('/\$?([A-I]?[A-Z]\$?\d+)/', $cell, $reg)) {
			list($row1, $col1) =  $this->_get_row_col($reg[1]);
			return array($row1, $col1);
		}
		trigger_error("Unknown cell reference $cell", E_USER_ERROR);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _encode_password($password) {
		$chars = preg_split('//', $password, -1, PREG_SPLIT_NO_EMPTY);
		$count = sizeof($chars);
		if ($count > 9) {
			trigger_error("Password is too long - must be no more than 9 chars", E_USER_ERROR);
		}
		$hash = 0;
		$char_index = 0;
		do {
			$char = &$chars[$char_index];				// 2
			$char_index++;									// 3
			$char = ord($char) << $char_index;		// 4
			$hash = $hash ^ $char;						// 5
		} while ($char_index < $count);

		return $hash ^ $count ^ 0xCE4B;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _get_xf_index($row=false, $col=false, $format=false) {
		if (is_object($format)) {
			return $format->get_xf_index();
		}
		elseif (isset($this->_row_formats[$row]) && $this->_row_formats[$row] !== false) {
			return $this->_row_formats[$row]->get_xf_index();
		}
		elseif (isset($this->_col_formats[$col]) && $this->_col_formats[$col] !== false) {
			return $this->_col_formats[$col]->get_xf_index();
		}
		else { return 0x0F; }
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _get_row_col($cell) {
		preg_match('/\$?([A-I]?[A-Z])\$?(\d+)/', $cell, $reg);
		$col = $reg[1]; $row = $reg[2];
		$chars = preg_split('//', $col, -1, PREG_SPLIT_NO_EMPTY);
		$expn = 0; $col = 0;
		while (sizeof($chars)) {
			$char = array_pop($chars);
			$col += (ord($char) -ord('A') +1) * pow(26, $expn);
			$expn++;
		}
		return array(--$row, --$col);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - NUMBER
// Returns	 0 : normal termination
//				-1 : out of range
	function write_number($row, $col, $num, $format=false) {
		/*
		NUMBER
		This record represents a cell that contains a floating-point value.

		Record NUMBER, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			Index to row
		2				2			Index to column
		4				2			Index to XF record (.5.113)
		6				8			IEEE floating-point value
		*/

		$rcrd_type = RCRD_NUMBER;
		$rcrd_length = 0x000E;

		$Index_row = $row;
		$Index_col = $col;
		$Index_XF = $format;
		$dNumber = $num;

		$Index_XF = $this->_get_xf_index($Index_row, $Index_col, $Index_XF);

		if ($Index_row >= $this->_xls_row_start) { return -1; }
		if ($Index_col >= $this->_xls_col_start) { return -1; }
		if ($Index_row <  $this->_dim_row_end) { $this->_dim_row_end = $Index_row; }
		if ($Index_row >  $this->_dim_row_start) { $this->_dim_row_start = $Index_row; }
		if ($Index_col <  $this->_dim_col_end) { $this->_dim_col_end = $Index_col; }
		if ($Index_col >  $this->_dim_col_start) { $this->_dim_col_start = $Index_col; }

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvv", $Index_row, $Index_col, $Index_XF);
		$dNumber = pack("d", $dNumber);

		if ($this->_BigEndian) {
			$dNumber = strrev($dNumber);
		}
		$this->_store_Tail($header.$data.$dNumber);
		return 0;
	} // end of function
// ------------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------------
// RECORD - LABEL
// Returns	 0 : success
//				-1 : out of range
//				-2 : string is truncated
	function write_label($row, $col, $string, $format=false) {
		/*
		LABEL
		This record represents a cell that contains a string.
		In BIFF8 it is replaced by the LABELSST record (.5.60). Nevertheless
		Excel can import a LABEL record contained in a BIFF8 file.

		Record LABEL, BIFF3-BIFF7:
		Offset		Size		Contents
		0				2			Index to row
		2				2			Index to column
		4				2			Index to XF record (.5.113)
		6				var.		Byte string, 16-bit string length (.2.2)

		Record LABEL, BIFF8:
		Offset		Size		Contents
		0				2			Index to row
		2				2			Index to column
		4				2			Index to XF record (.5.113)
		6				var.		Unicode string, 16-bit string length (.2.3)
		*/

		$rcrd_type = RCRD_LABEL;
		$rcrd_length = 0x0008 + strlen($string);

		$Index_row = $row;
		$Index_col = $col;
		$Index_XF = $this->_get_xf_index($row, $col, $format);
		$String = $string;
		$strlen = strlen($String);

		$str_error = 0;

		if ($Index_row >= $this->_xls_row_start) { return -1; }
		if ($Index_col >= $this->_xls_col_start) { return -1; }
		if ($Index_row <  $this->_dim_row_end) { $this->_dim_row_end = $Index_row; }
		if ($Index_row >  $this->_dim_row_start) { $this->_dim_row_start = $Index_row; }
		if ($Index_col <  $this->_dim_col_end) { $this->_dim_col_end = $Index_col; }
		if ($Index_col >  $this->_dim_col_start) { $this->_dim_col_start = $Index_col; }

		if ($strlen > $this->_xls_strmax) { // String length limit - 255 chars
			$String = substr($String, 0, $this->_xls_strmax);
			$rcrd_length = 0x0008 + $this->_xls_strmax;
			$strlen = $this->_xls_strmax;
			$str_error = -2;
		}

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvv", $Index_row, $Index_col, $Index_XF, $strlen);

		$this->_store_Tail($header.$data.$String);

		return $str_error;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: write_string ($row, $col, $string, [$format])
function write_string($row, $col, $string, $format=false) {
		// TODO - STRING RECORD
		/*
		STRING
		This record stores the result of a string formula. It occurs
		directly after a string formula (.5.46).

		Record STRING, BIFF3-BIFF7:
		Offset		Size		Contents
		0				var.		Byte string, 16-bit string length (.2.2)

		In BIFF8 files no STRING record occurs, if the result string is empty.
		Record STRING, BIFF8:
		Offset		Size		Contents
		0				var.		Non-empty Unicode string, 16-bit string length (.2.3)
		*/

		// this is a friendly alias to write_label() function
		// currently - RECORD 'LABEL' is used to store strings
		$this->write_label($row, $col, $string, $format);
		return;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - BLANK
// Return	 0 : success
//				-1 : out of range
	function write_blank($row, $col, $format) {
		/*
		BLANK
		This record represents an empty cell.
		It contains the cell address and formatting information.

		Record BLANK, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			Index to row
		2				2			Index to column
		4				2			Index to XF record (.5.113)
		*/

		if (!isset($format)) { return 0; }

		$rcrd_type = RCRD_BLANK;
		$rcrd_length = 0x0006;

		$Index_row = $row;
		$Index_col = $col;
		$Index_XF = $this->_get_xf_index($row, $col, $format);

		if ($Index_row >= $this->_xls_row_start) { return -2; }
		if ($Index_col >= $this->_xls_col_start) { return -2; }
		if ($Index_row <  $this->_dim_row_end) { $this->_dim_row_end = $Index_row; }
		if ($Index_row >  $this->_dim_row_start) { $this->_dim_row_start = $Index_row; }
		if ($Index_col <  $this->_dim_col_end) { $this->_dim_col_end = $Index_col; }
		if ($Index_col >  $this->_dim_col_start) { $this->_dim_col_start = $Index_col; }

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvv", $Index_row, $Index_col, $Index_XF);

		$this->_store_Tail($header.$data);

		return 0;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: write_url($row, $col, $url, $string, $format)
	function write_url() {
		$Args=func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$cell = array_shift($Args);
			$Args = array_merge($this->_parce_notation($cell), $Args);
		}
		if (sizeof($Args) < 3) { return -1; }
		return call_user_func_array(array(&$this, 'write_url_range'), array_merge(array($Args[0], $Args[1]), $Args));
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// write url to a range of cells
// write_url_range($Start_row, $Start_col, $End_row, $End_col, $url, $string, $format)
	function write_url_range() {
		$Args=func_get_args();
		if (preg_match('/^\D/', $Args[0])) {
			$cell = array_shift($Args);
			$Args = array_merge($this->_parce_notation($cell), $Args);
		}
		if (sizeof($Args) < 5) { return -1; }
		$url = $Args[4];
		return call_user_func_array(array(&$this, '_dump_hlink'), $Args);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - HLINK
// _dump_hlink($Start_row, $Start_col, $End_row, $End_col, $url, $string, $format)
	function _dump_hlink() {
		$Args = func_get_args();
		$str_error = 0;
		$rcrd_type = RCRD_HLINK;

		$Start_row = $Args[0];
		$Start_col = $Args[1];
		$End_row = $Args[2];
		$End_col = $Args[3];
		$url = $Args[4];
		if (isset($Args[5])) { $str = $Args[5]; }
		$xf = (isset($Args[6]) && $Args[6]) ? $Args[6] : $this->_url_format;

		//if(!isset($str)) { 
		$str = preg_replace("/^mailto:/","",$url); 
		//}

		if ($this->write_string($Start_row, $Start_col, $str, $xf)) { return $str_error; }

		$magic1 = pack("H*", "D0C9EA79F9BACE118C8200AA004BA90B02000000");
		$magic2 = pack("H*", "E0C9EA79F9BACE118C8200AA004BA90B");
		$options = pack("V", 0x03);
		$url = join("\0", preg_split("''", $url, -1, PREG_SPLIT_NO_EMPTY));
		$url = $url."\0\0\0";
		$url_len = pack("V", strlen($url));

		$rcrd_length = 0x34 + strlen($url);

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvv", $Start_row, $End_row, $Start_col, $End_col);

		$this->_store_Tail($header.$data.$magic1.$options.$magic2.$url_len.$url);
		return $str_error;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - ROW
// set_row_settings($row_num, $row_height, $format)
	function set_row_settings() {
		/*
		ROW
		This record contains the properties of a single row in a sheet. Rows and cells
		in a sheet are divided into blocks of 32 rows.

		Record ROW, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			Index of this row
		2				2			Index to column of the first cell which is described by a cell record
		4				2			Index to column of the last cell which is described by a cell record, increased by 1
		6				2				Bit		Mask		Contents
										14-0		7FFFH		Height of the row, in twips = 1/20 of a point
										15			8000H		0 = Row has custom height;
																1 = Row has default height
		8				2			Not used
		10				2			In BIFF3-BIFF4 this field contains a relative offset to
									calculate stream position of the first cell record for this row (.4.6.1).
									In BIFF5-BIFF8 this field is not used anymore,
									but the DBCELL record (.5.26) instead.
		12				4			Option flags and default row formatting:
										Bit		Mask			Contents
										2-0		00000007H	Outline level of the row
										4			00000010H	1 = Outline group starts or ends here
																	(depending on where the outline buttons are located,
																	see WSBOOL record, .5.111), and is collapsed
										5			00000020H	1 = Row is hidden (manually, or by a filter or outline group)
										6			00000040H	1 = Row height and default font height do not match
										7			00000080H	1 = Row has explicit default format (fl)
										8			00000100H	Always 1
										27-16		0FFF0000H	If fl = 1: Index to default XF record (.5.113)
										28			10000000H	1 = Additional space above the row
										29			20000000H	1 = Additional space below the row
		*/
		$Args = func_get_args();

		$rcrd_type = RCRD_ROW;
		$rcrd_length = 0x0010;

		$Row_number = $Args[0];
		$Start_col = 0x0000;
		$End_col = 0x0000;
		$notused1 = 0x0000;
		$notused1 = 0x0000;
		$options = 0x01C0;
		if (isset($Args[2]) && $Args[2] !== false) { $format = $Args[2]; }
		if (isset($Args[2]) && $Args[2] !== false) {
			$IndexXF = $format->get_xf_index();
		}
		else { $IndexXF = 0x0F; }

		if (isset($Args[1])) {
			$RowHeight = $Args[1] *20;
		}
		else { $RowHeight = 0xff; }

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvvvvvv", $Row_number, $Start_col, $End_col, $RowHeight, $notused1,$notused1, $options, $IndexXF);

		$this->_store_Tail($header.$data);

		if (sizeof($Args) < 2) { return; }

		$this->_row_sizes[$Args[0]]  = $Args[1];
		if (isset($Args[2])) {
			$this->_row_formats[$Args[0]] = $Args[2];
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - DIMENSIONS
	function _dump_dimensions() {
		/*
		DIMENSIONS
		This record contains the range address of the used area in the current sheet.

		Record DIMENSIONS, BIFF3-BIFF7:
		Offset		Size		Contents
		0				2			Index to first used row
		2				2			Index to last used row, increased by 1
		4				2			Index to first used column
		6				2			Index to last used column, increased by 1
		8				2			Not used

		Record DIMENSIONS, BIFF8:
		Offset		Size		Contents
		0				4			Index to first used row
		4				4			Index to last used row, increased by 1
		8				2			Index to first used column
		10				2			Index to last used column, increased by 1
		12				2			Not used
		*/
		$rcrd_type = RCRD_DIMENSIONS;
		$rcrd_length = 0x000A;

		$FirstRow = $this->_dim_row_end;
		$LastRow = $this->_dim_row_start;
		$FirstCol = $this->_dim_col_end;
		$LastCol = $this->_dim_col_start;
		$NotUsed = 0x0000;

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvvv", $FirstRow, $LastRow, $FirstCol, $LastCol, $NotUsed);
		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - WINDOW2
	function _dump_window2() {
		/*
		WINDOW2

		This record contains additional settings for the window of a specific
		worksheet (BIFF4W-BIFF8).

		Record WINDOW2, BIFF3-BIFF7:
		Offset		Size		Contents
		0				2			Option flags (see below)
		2				2			Index to first visible row
		4				2			Index to first visible column
		6				4			Grid line RGB colour (.2.4)

		Record WINDOW2, BIFF8:
		Offset		Size		Contents
		0				2			Option flags (see below)
		2				2			Index to first visible row
		4				2			Index to first visible column
		6				2			Colour index of grid line colour (.5.69).
									Note that in BIFF2-BIFF7 an RGB colour is
									written instead.
		8				2			Not used
		10				2			Cached magnification factor in page break preview (in percent);
																										0 = Default (60%)
		12				2			Cached magnification factor in normal view (in percent);
																										0 = Default (100%)
		14				4			Not used

		In BIFF8 this record stores used magnification factors for page break preview and normal view.
		These values are used to restore the magnification, when the view is changed.
		The real magnification of the currently active view is stored in the SCL record (.5.85).
		The type of the active view is stored in the option flags field (see below).

		Option flags, BIFF3-BIFF8:
		Bits		Mask		Contents
		0			0001H		0 = Show formula results					1 = Show formulas
		1			0002H		0 = Do not show grid lines					1 = Show grid lines
		2			0004H		0 = Do not show sheet headers				1 = Show sheet headers
		3			0008H		0 = Panes are not frozen					1 = Panes are frozen (freeze)
		4			0010H		0 = Show zero values as empty cells		1 = Show zero values
		5			0020H		0 = Manual grid line colour				1 = Automatic grid line colour
		6			0040H		0 = Columns from left to right			1 = Columns from right to left
		7			0080H		0 = Do not show outline symbols			1 = Show outline symbols
		8			0100H		0 = Keep splits if							1 = Remove splits if
										pane freeze is removed						pane freeze is removed
		9			0200H		0 = Sheet not selected						1 = Sheet selected (BIFF5-BIFF8)
		10			0400H		0 = Sheet not visible						1 = Sheet visible (BIFF5-BIFF8)
		11			0800H		0 = Show in normal view						1 = Show in page break preview (BIFF8)

		The freeze flag specifies, if a following PANE record (.5.70) describes unfrozen or frozen panes.
		*/

		$rcrd_type = RCRD_WINDOW2;
		$rcrd_length = 0x000A;

		$Options = 0x00B6;
		$FirstRow = 0x0000;
		$FirstCol = 0x0000;
		$GridColor = $this->_GridColor;
															// Bits
		$ShowFormulas = 0;							// 0
		$ShowGrid = $this->_view_gridlines;		// 1
		$ShowHeaders = 1;								// 2
		$PanesFrosen = $this->_frozen;			// 3
		$ShowZeroVals = 1;							// 4
		$ManualGridColor = 1;						// 5
		$ColsReversed = 0;							// 6
		$ShowOutlineSyms = 1;						// 7
		$KeepSplits = 0;								// 8
		$SheetSelected = $this->_selected;		// 9
		$SheetVisible = 1;							// 10

		$Options = $ShowFormulas;
		$Options |= $ShowGrid << 1;
		$Options |= $ShowHeaders << 2;
		$Options |= $PanesFrosen << 3;
		$Options |= $ShowZeroVals << 4;
		$Options |= $ManualGridColor << 5;
		$Options |= $ColsReversed << 6;
		$Options |= $ShowOutlineSyms << 7;
		$Options |= $KeepSplits << 8;
		$Options |= $SheetSelected << 9;
		$Options |= $SheetVisible << 10;

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvv", $Options, $FirstRow, $FirstCol);

		$this->_store_Tail($header.$data.$GridColor);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - DEFCOLWIDTH
	function _dump_defcol() {
		/*
		DEFCOLWIDTH

		This record specifies the default column width for columns
		that do not have a specific width set using the record COLINFO (.5.18)
		or COLWIDTH (.5.20). This record has no effect, if a STANDARDWIDTH record (.5.95) is
		present in the file.

		Record DEFCOLWIDTH, BIFF2-BIFF8:
		Offset		Size		Contents
		0				2			Column width in characters, using the width of
									the zero character from default font (first FONT record in the file)
		*/
		$rcrd_type = RCRD_DEFCOLWIDTH;
		$rcrd_length = 0x0002;

		$colwidth = 0x0008;

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $colwidth);

		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - COLINFO
	function _dump_colinfo($Args) {
		// $Args -> array ($Start_col, $End_col, $width, $format, $hidden)
		/*
		COLINFO

		This record specifies the width for a given range of columns. If a column does not have
		a corresponding COLINFO record, the width specified in the record STANDARDWIDTH is used.
		If this record is also not present, the contents of the record DEFCOLWIDTH is used instead.
		This record also specifies a default XF record (.5.113) to use for cells in the columns
		that are not described by any cell record (which contain the XF index for that cell).
		Additionally, the option flags field contains hidden, outline, and
		collapsed options applied at the columns.

		Record COLINFO, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			Index to first column in the range
		2				2			Index to last column in the range
		4				2			Width of the columns in 1/256 of the width of the zero character,
									using default font (first FONT record in the file)
		6				2			Index to XF record for default column formatting
		8				2			Option flags:
										Bits		Mask		Contents
										0			0001H		1 = Columns are hidden
										10-8		0700H		Outline level of the columns (0 = no outline)
										12			1000H		1 = Columns are collapsed
		10				2			Not used
		*/

		$rcrd_type = RCRD_COLINFO;
		$rcrd_length = 0x000B;

		$Start_col = $Args[0] ? $Args[0] : 0;
		$End_col = $Args[1] ? $Args[1] : 0;
		$ColWidth = $Args[2] ? $Args[2] : 8.43;

		$ColWidth += 0.72;
		$ColWidth *= 256;

		$options = (isset($Args[4]) && $Args[4]) ? $Args[4] || 0 : 0 || 0;
		$notused = 0x00;
		$format = (isset($Args[3]) && $Args[3]) ? $Args[3] : 0;

		if (isset($Args[3])) {
			$IndexXF = $format->get_xf_index();
		}
		else { $IndexXF = 0x0F; }

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvvvC", $Start_col, $End_col, $ColWidth, $IndexXF, $options, $notused);
		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - SELECTION
// _dump_selection($FirstRow, $FirstCol, $LastRow, $LastCol)
	function _dump_selection($Args) {
		/*
		SELECTION

		This record contains the addresses of all selected cell ranges and the position of
		the active cell for a pane in the current sheet. There is one SELECTION record
		for each pane in the sheet.

		Record SELECTION, BIFF2-BIFF8:
		Offset		Size		Contents
		0				1			Pane identifier (see PANE record, .5.70)
		1				2			Index to row of the active cell
		3				2			Index to column of the active cell
		5				2			Index into the following cell range list to the entry
									that contains the active cell
		7				2			Number of following ADDR structures (nm)
		9				6 nm		List of nm ADDR structures. Each ADDR contains the address
									of a selected cell range:
										Offset		Size		Contents
										0				2			Index to first row
										2				2			Index to last row
										4				1			Index to first column
										5				1			Index to last column
		*/
		$rcrd_type = RCRD_SELECTION;
		$rcrd_length = 0x000F;

		$Pane = $this->_active_pane;
		$FirstRow = $Args[0];
		$FirstCol = $Args[1];
		$CellRange = 0;
		$ADDR_Num = 1;

		$FirstRowIndex = $Args[0];
		$FirstColIndex = $Args[1];
		$LastRowIndex = (isset($Args[2]) && $Args[2]) ? $Args[2] : $FirstRowIndex;
		$LastColIndex = (isset($Args[3]) && $Args[3]) ? $Args[3] : $FirstColIndex;

		if ($FirstRowIndex > $LastRowIndex) {
			list($FirstRowIndex, $LastRowIndex) = array($LastRowIndex, $FirstRowIndex);
		}
		if ($FirstColIndex > $LastColIndex) {
			list($FirstColIndex, $LastColIndex) = array($LastColIndex, $FirstColIndex);
		}

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("CvvvvvvCC", $Pane, $FirstRow, $FirstCol, $CellRange, $ADDR_Num, $FirstRowIndex, $LastRowIndex, $FirstColIndex, $LastColIndex);

		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - EXTERNCOUNT
	function _dump_externcount($rcrd_number) {
		/*
		EXTERNCOUNT

		This record contains the number of following EXTERNSHEET records.
		In BIFF8 this record is omitted because there occurs only one EXTERNSHEET record.

		Record EXTERNCOUNT, BIFF2-BIFF7:
		Offset		Size		Contents
		0				2			Number of following EXTERNSHEET records
		*/

		$rcrd_type = RCRD_EXTERNCOUNT;
		$rcrd_length = 0x0002;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $rcrd_number);
		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - EXTERNSHEET
	function _dump_externsheet($sheetname) {
		/*
		EXTERNSHEET

			EXTERNSHEET in BIFF2-BIFF7
		In the file format versions up to BIFF7 this record stores the name of an external document
		and a sheet name inside of this document.

		Record EXTERNSHEET, BIFF2-BIFF7:
		Offset		Size		Contents
		0				var.		Encoded document and sheet name. Byte string, 8-bit string length.

		|	The string length field is decreased by 1, if the EXTERNSHEET stores a reference
		|	to one of the own sheets (first character is 03H).
		|	Example: The formula =Sheet2!A1 contains a reference to an EXTERNSHEET record with the
		|	string "<03H>Sheet2". The string consists of 7 characters but the string
		|	length field contains the value 6. !
		If a formula uses an add-in function, a special EXTERNSHEET record will occur,
		followed by an EXTERNNAME record with the name of the function.

		Record EXTERNSHEET for add-in functions, BIFF2-BIFF7:
		Offset		Size		Contents
		0				2			01H 34H (byte string, 8-bit string length, containing "#")
		*/

		$rcrd_type = RCRD_EXTERNSHEET;
		if ($this->_name == $sheetname) { // current
			$sheetname = '';
			$rcrd_length = 0x02;
			$byte = 1;
			$ref = 0x02;
		}
		else { // external
			$rcrd_length = 0x02 + strlen($sheetname);
			$byte = strlen($sheetname);
			$ref = 0x03;
		}
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("CC", $byte, $ref);
		$this->_store_Head($header.$data.$sheetname);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - SETUP
	function _dump_setup() {
		/*
		SETUP

		This record is part of the Page Settings Block. It stores the page format settings
		of the current sheet. The pages may be scaled in percent or by using an absolute
		number of pages. This setting is located in the WSBOOL record. If pages are scaled
		in percent, the scaling factor in this record is used, otherwise the "Fit to pages" values.
		One of the "Fit to pages" values may be 0. In this case the sheet is scaled
		to fit only to the other value.

		Record Contents
		Record SETUP, BIFF5-BIFF8:
		Offset		Size		Contents
		0				2			Paper size
		2				2			Scaling factor in percent
		4				2			Start page number
		6				2			Fit worksheet width to this number of pages (0 = use as many as needed)
		8				2			Fit worksheet height to this number of pages (0 = use as many as needed)
		10				2			Option flags:
										Bit		Mask		Contents
										0			0001H		0 = Print pages in columns 1 = Print pages in rows
										1			0002H		0 = Landscape 1 = Portrait
										2			0004H		1 = Paper size, scaling factor, paper orientation
																(portrait/landscape), print resolution and
																number of copies are not initialised
										3			0008H		0 = Print coloured 1 = Print black and white
										4			0010H		0 = Default print quality 1 = Draft quality
										5			0020H		0 = Do not print cell notes 1 = Print cell notes
										6			0040H		0 = Paper orientation setting is valid
																1 = Paper orientation setting not initialised
										7			0080H		0 = Automatic page numbers 1 = Use start page number

		12				2			Print resolution in dpi
		14				2			Vertical print resolution in dpi
		16				8			Header margin (IEEE floating-point value)
		24				8			Footer margin (IEEE floating-point value)
		32				2			Number of copies to print
		*/

		$rcrd_type = RCRD_SETUP;
		$rcrd_length = 0x0022;

		$PaperSize = $this->_PaperSizeIndex;
		$Scale = $this->_print_scale;
		$StartPageNum = $this->_start_page_number;
		$FitSheetWidth = $this->_fit_width;
		$FitSheetHeight = $this->_fit_height;
		$options = 0x00;
		$PrintRes = 0x0258;
		$VertPrintRes = 0x0258;
		$HeaderMargin = $this->_margin_head;
		$FooterMargin = $this->_margin_foot;
		$CopiesNum = 0x01;

		$options |= $this->_orientation    << 1;
		$options |= 0x0 << 2;
		$options |= 0x0 << 3;
		$options |= 0x0 << 4;
		$options |= 0x0 << 5;
		$options |= 0x0 << 6;
		$options |= $this->_start_page_number << 7;

		$HeaderMargin = pack("d", $HeaderMargin);
		$FooterMargin = pack("d", $FooterMargin);

		if ($this->_BigEndian) {
			$HeaderMargin = strrev($HeaderMargin);
			$FooterMargin = strrev($FooterMargin);
		}

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvvvvvv", $PaperSize, $Scale, $StartPageNum, $FitSheetWidth, $FitSheetHeight,
											$options, $PrintRes, $VertPrintRes).$HeaderMargin.$FooterMargin.
											pack("v", $CopiesNum);
		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - HEADER
	function _dump_header() {
		$rcrd_type = RCRD_HEADER;
		$Header = $this->Header;
		$len = strlen($Header);
		$rcrd_length = 1 + $len;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("C", $len);
		$this->_store_Tail($header.$data.$Header);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - FOOTER
	function _dump_footer() {
		$rcrd_type = RCRD_FOOTER;
		$Footer = $this->Footer;
		$len = strlen($Footer);
		$rcrd_length = 1 + $len;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("C", $len);
		$this->_store_Tail($header.$data.$Footer);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - HCENTER
	function _dump_hcenter() {
		/*
		HCENTER

		This record is part of the Page Settings Block.
		It specifies if the sheet is centred horizontally when printed.

		Record HCENTER, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			0 = Print sheet left aligned
									1 = Print sheet centred horizontally
		*/
		$rcrd_type = RCRD_HCENTER;
		$rcrd_length = 0x0002;
		$HAlign = $this->_hor_centered;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $HAlign);
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - VCENTER
	function _dump_vcenter() {
		/*
		VCENTER

		This record is part of the Page Settings Block.
		It specifies if the sheet is centred vertically when printed.

		Record VCENTER, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			0 = Print sheet aligned at top page border
									1 = Print sheet vertically centred
		*/
		$rcrd_type = RCRD_VCENTER;
		$rcrd_length = 0x0002;
		$VAlign = $this->_vert_centered;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $VAlign);
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - LEFTMARGIN
	function _dump_margin_left() {
		$rcrd_type = RCRD_LEFTMARGIN;
		$rcrd_length = 0x0008;
		$margin  = $this->_margin_left; // inches
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("d", $margin);
		if ($this->_BigEndian) {
			$data = strrev($data);
		}
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - RIGHTMARGIN
	function _dump_margin_right() {
		$rcrd_type = RCRD_RIGHTMARGIN;
		$rcrd_length = 0x0008;
		$margin = $this->_margin_right; // inches
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("d", $margin);
		if ($this->_BigEndian) {
			$data = strrev($data);
		}
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - TOPMARGIN
	function _dump_margin_top() {
		$rcrd_type = RCRD_TOPMARGIN;
		$rcrd_length = 0x0008;
		$margin  = $this->_margin_top; // inches
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("d", $margin);
		if ($this->_BigEndian) {
			$data = strrev($data);
		}
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - BOTTOMMARGIN
	function _dump_margin_bottom() {
		$rcrd_type = RCRD_BOTTOMMARGIN;
		$rcrd_length = 0x0008;
		$margin = $this->_margin_bottom; // inches
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("d", $margin);
		if ($this->_BigEndian) {
			$data = strrev($data);
		}
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------------
// RECORD - PRINTHEADERS
	function _dump_print_headers() {
		/*
		PRINTHEADERS

		This record stores if the row and column headers
		(the areas with row numbers and column letters) will be printed.

		Record PRINTHEADERS, BIFF2-BIFF8:
		Offset		Size		Contents
		0				2			0 = Do not print row/column headers; 1 = Print row/column headers
		*/
		$rcrd_type = RCRD_PRINTHEADERS;
		$rcrd_length = 0x0002;
		$Value = $this->_print_headers;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $Value);
		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - PRINTGRIDLINES
	function _dump_print_gridlines() {
		/*
		PRINTGRIDLINES

		This record stores if sheet grid lines will be printed.
		Record PRINTGRIDLINES, BIFF2-BIFF8:
		Offset		Size		Contents
		0				2			0 = Do not print sheet grid lines; 1 = Print sheet grid lines
		*/
		$rcrd_type = RCRD_PRINTGRIDLINES;
		$rcrd_length = 0x0002;
		$Value = $this->_print_gridlines;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $Value);
		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - GRIDSET
	function _dump_gridset() {
		/*
		GRIDSET

		This record specifies if the option to print sheet grid lines (record PRINTGRIDLINES)
		has ever been changed.

		Record GRIDSET, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			0 = Print grid lines option never changed
									1 = Print grid lines option changed
		*/
		$rcrd_type = 0x0082;
		$rcrd_length = 0x0002;
		$Value = !$this->_print_gridlines;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $Value);
		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - WSBOOL
	function _dump_wsbool() {
		$options = "";
		/*
		WSBOOL

		This record stores a 16-bit value with Boolean options for the current sheet. From BIFF5 on the ¡°Save external linked
		values¡± option is moved to the record BOOKBOOL (.5.9).

		Option flags of record WSBOOL, BIFF3-BIFF8:
		Bit		Mask			Contents
		0			0001H			0 = Do not show automatic page breaks 1 = Show automatic page breaks
		4			0010H			0 = Standard sheet 1 = Dialogue sheet (BIFF5-BIFF8)
		5			0020H			0 = No automatic styles in outlines 1 = Apply automatic styles to outlines
		6			0040H			0 = Outline buttons above outline group 1 = Outline buttons below outline group
		7			0080H			0 = Outline buttons left of outline group 1 = Outline buttons right of outline group
		8			0100H			0 = Scale printout in percent 1 = Fit printout to number of pages
		9			0200H			0 = Save external linked values		1 = Do not save external linked values
									(BIFF3-BIFF4 only, .4.9)				(BIFF3-BIFF4 only, .4.9)
		10			0400H			0 = Do not show row outline symbols 1 = Show row outline symbols
		11			0800H			0 = Do not show column outline symbols 1 = Show column outline symbols
		13-12		3000H			These flags specify the arrangement of windows. They are stored in BIFF4 only.
											002 = Arrange windows tiled
											012 = Arrange windows horizontal
											102 = Arrange windows vertical
											112 = Arrange windows cascaded
		The following flags are valid for BIFF4-BIFF8 only:
		14			4000H			0 = Standard expression evaluation 1 = Alternative expression evaluation
		15			8000H			0 = Standard formula entries 1 = Alternative formula entries
		*/
		$rcrd_type = RCRD_WSBOOL;
		$rcrd_length = 0x0002;
		// only fit to page is important
		if ($this->_fit_page) { $data = pack("v", 0x05c1); }
		else { $data = pack("v", 0x04c1); }
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $options);
		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------------
// RECORD - PROTECT
	function _dump_protect() { // not working yet - TODO
		/*
		PROTECT
		BIFF2		BIFF3		BIFF4S	BIFF4W	BIFF5		BIFF7		BIFF8		BIFF8X
		0012H		0012H		0012H		0012H		0012H		0012H		0012H		0012H
		This record is part of the worksheet/workbook protection (.4.17).
		It specifies whether a worksheet or a workbook is protected against modification.
		Protection is not active, if this record is omitted.

		Record PROTECT, BIFF2-BIFF8:
		Offset		Size		Contents
		0				2			0 = Not protected; 1 = Protected
		*/
		if (!$this->_protected) { return; }

		$rcrd_type = RCRD_PROTECT;
		$rcrd_length = 0x0002;

		$State = $this->_protected;

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $State);

		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - PASSWORD
	function _dump_password() { // not working yet - TODO
		/*
		PASSWORD
		BIFF2		BIFF3		BIFF4S	BIFF4W	BIFF5		BIFF7		BIFF8		BIFF8X
		0013H		0013H		0013H		0013H		0013H		0013H		0013H		0013H
		This record is part of the worksheet/workbook protection (.4.17).
		It stores a 16-bit hash value, calculated from the worksheet or workbook protection password.

		Record PASSWORD, BIFF2-BIFF8:
		Offset		Size		Contents
		0				2			16-bit hash value of the password (.4.17.4)
		*/
		if (!$this->_protected || !$this->_password) {
			return;
		}

		$rcrd_type = RCRD_PASSWORD;
		$rcrd_length = 0x0002;

		$wPassword = $this->_password; // Encoded password

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $wPassword);

		$this->_store_Head($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - SCL
	function _dump_zoom() {
		/*
		SCL

		This record stores the magnification of the active view of the current worksheet. 
		In BIFF8 this can be either the normal view or the page break preview. 
		This is determined in the WINDOW2 record.
		
		Record SCL, BIFF4-BIFF8:
		Offset		Size		Contents
		0				2			Numerator of the view magnification fraction (num)
		2				2			Denumerator of the view magnification fraction (den)
		
		The magnification is stored as reduced fraction. The magnification results from num/den.
		*/
		if ($this->_zoom == 100) { return; }
		$rcrd_type = RCRD_SCL;
		$rcrd_length = 0x0004;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vv", $this->_zoom, 100);
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - FORMULA
// write_formula($row, $col, $formula, [$format])
	function write_formula($row, $col, $formula, $format = 0) {

		$rcrd_type = RCRD_FORMULA;

		$Index_XF = $this->_get_xf_index($row, $col, $format);
		$StartValue = 0x00;
		$options = 0x03;
		$optional = 0x0000;

		if (preg_match("/^=/",$formula)) {
			$formula = preg_replace("/^=/","",$formula);
		}
		else if(preg_match("/^@/",$formula)) {
			$formula = preg_replace("/^@/","",$formula);
		}
		else {
			trigger_error("Wrong formula identifier. All formulas should start from either '=' or '@'.", E_USER_ERROR);
		}

		$formula = $this->_formula->prepare_formula($formula);
		$f_len = strlen($formula);
		$rcrd_length = 0x16 + $f_len;

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvdvVv", $row, $col, $Index_XF, $StartValue, $options, $optional, $f_len);

		$this->_store_Tail($header.$data.$formula);
	} // end of function
// ------------------------------------------------------------------------------------------------
} // END OF CLASS
// ------------------------------------------------------------------------------------------------

?>
