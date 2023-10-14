<?php

//		XLS Generator Class
//		(c) 2007 Paggard [paggard@paggard.com]
//		18 March 2007 - build 1.4.1

//-------------------------------------------------------------------------------------------------
class xls_book extends xls_generator {

	public $_tempdir;

	public $_filename;
	public $_config_file;
	public $_activesheet;
	public $_firstsheet;
	public $_selected;
	public $_xf_index;
	public $_fileclosed;
	public $_biffsize;
	public $_sheetname;
	public $_tmp_format;
	public $_url_format;
	public $_worksheets;
	public $_sheetnames;
	public $_formats;
	public $ColorPalette;


// ------------------------------------------------------------------------------------------------
// CONSTRUCTOR
	function xls_book($filename,$config_file=false,$temp_dir=false) {
		$this->xls_generator();

		$this->_tempdir = $temp_dir;

		$this->_filename = $filename;
		$this->_config_file = $config_file;
		$this->_sheetname = "Sheet";
		$this->_xf_index = 16;
		$this->_activesheet = 0;
		$this->_firstsheet = 0;
		$this->_selected = 0;
		$this->_fileclosed = 0;
		$this->_biffsize = 0;
		$this->_tmp_format = new xls_format($this->_config_file);
		$this->_worksheets = array();
		$this->_sheetnames = array();
		$this->_formats = array();
		$this->_url_format =& $this->add_format(array('color' => 'blue', 'underline' => 1));
		if ($this->_filename == '') { return; }
		$this->ColorPalette = array();
		$this->set_ColorPalette();
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// DESTRUCTOR
	function close() {
		if ($this->_fileclosed) {
			return;
		}
		$this->_dump_workbook();
		$this->_fileclosed = 1;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function &get_sheets() {
		return $this->_worksheets;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// USER - adding new sheet to the given book
	function &add_sheet($name="") {
		if (strlen($name) > 31) {
			trigger_error("SheetName '$name' is too long, need to be <= 31 chars", E_USER_ERROR);
		}
		$index = sizeof($this->_worksheets);
		$sheetname = $this->_sheetname;
		if ($name == "") {
			$name = $sheetname . ($index+1);
		}
		foreach ($this->_worksheets as $tmp) {
			if ($name == $tmp->_name) {
				trigger_error("Sheet '$name' already exists in the WorkBook", E_USER_ERROR);
			}
		}
		$obj0 = new xls_sheet(	$this->_config_file,
												$name, 
												$index, 
												$this->_activesheet,
												$this->_firstsheet,
												$this->_url_format, 
												$this->_tempdir);
		$worksheet =& $obj0;
		$this->_worksheets[$index] = &$worksheet;
		$this->_sheetnames[$index] = $name;

		return $worksheet;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function &add_format($para=false) {
		if($para===false) {
			$obj1 = new xls_format($this->_config_file,$this->_xf_index);
			$format =& $obj1;
		} else {
			$obj2 = new xls_format($this->_config_file,$this->_xf_index, $para);
			$format =& $obj2;
		}
		$this->_xf_index += 1;

		$this->_formats[]=&$format;
		return $format;
		
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// USER - adding new color to ColorPalette
	function add_color($index, $red, $green, $blue) {
		if ($index < 8 or $index > 64) {
			trigger_error("Illegal Color Index = '$index' - should be 8 <= index <= 64", E_USER_ERROR);
			return;
		}
		if (  ($red   < 0 || $red   > 255) ||
				($green < 0 || $green > 255) ||
				($blue  < 0 || $blue  > 255) )
		{
			trigger_error("Illegal Color Value  - should be 0 <= color <= 255", E_USER_ERROR);
			return;
		}
		$index -=8;
		$this->ColorPalette[$index] = array($red, $green, $blue, 0);

		return $index +8;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// sets default palette for User Colors
	function set_ColorPalette() {
		$this->ColorPalette = array(
			/*    RED  GRN  BLUE					NEX		NAME					INDEX  */
			array(0x00,0x00,0x00,0x00),	// 000000	black					// 8
			array(0xff,0xff,0xff,0x00),	// FFFFFF	white					// 9
			array(0xff,0x00,0x00,0x00),	// FF0000	red					// 10
			array(0x00,0x00,0xff,0x00),	// 0000FF	blue					// 11
			array(0xff,0xff,0x00,0x00),	// FFFF00	yellow				// 12
			array(0x80,0x00,0x00,0x00),	// 800000	maroon				// 13
			array(0x80,0x00,0x80,0x00),	// 800080	purple				// 14
			array(0x00,0xff,0xff,0x00),	// 00FFFF	aqua					// 15
			array(0x00,0x80,0x00,0x00),	// 008000	green					// 16
			array(0x00,0x00,0x80,0x00),	// 000080	navy					// 17
			array(0x80,0x80,0x00,0x00),	// 808000	olive					// 18
			array(0x00,0x80,0x80,0x00),	// 008080	teal					// 19
			array(0x80,0x80,0x80,0x00),	// 808080	gray					// 20
			array(0xc0,0xc0,0xc0,0x00),	// C0C0C0	silver				// 21
			array(0xFA,0xF0,0xE6,0x00),	// FAF0E6	linen					// 22
			array(0xFF,0xFF,0xE0,0x00),	// FFFFE0	lightyellow			// 23
			array(0xF5,0xDE,0xB3,0x00),	// F5DEB3	wheat					// 24
			array(0xD2,0xB4,0x8C,0x00),	// D2B48C	tan					// 25
			array(0xFF,0xD7,0x00,0x00),	// FFD700	gold					// 26
			array(0xFF,0x63,0x47,0x00),	// FF6347	tomato				// 27
			array(0xDC,0x14,0x3C,0x00),	// DC143C	crimson				// 28
			array(0x8B,0x45,0x13,0x00),	// 8B4513	saddlebrown			// 29
			array(0xD2,0x69,0x1E,0x00),	// D2691E	chocolate			// 30
			array(0xFF,0x7F,0x50,0x00),	// FF7F50	coral					// 31
			array(0x6B,0x8E,0x23,0x00),	// 6B8E23	olivedrab			// 32
			array(0x00,0x64,0x00,0x00),	// 006400	darkgreen			// 33
			array(0xAD,0xFF,0x2F,0x00),	// ADFF2F	greenyellow			// 34
			array(0x9A,0xCD,0x32,0x00),	// 9ACD32	yellowgreen			// 35
			array(0x32,0xCD,0x32,0x00),	// 32CD32	limegreen			// 36
			array(0x90,0xEE,0x90,0x00),	// 90EE90	lightgreen			// 37
			array(0x7F,0xFF,0xD4,0x00),	// 7FFFD4	aquamarine			// 38
			array(0x2E,0x8B,0x57,0x00),	// 2E8B57	seagreen				// 39
			array(0x48,0xD1,0xCC,0x00),	// 48D1CC	mediumturquoise	// 40
			array(0x5F,0x9E,0xA0,0x00),	// 5F9EA0	cadetblue			// 41
			array(0xE0,0xFF,0xFF,0x00),	// E0FFFF	lightcyan			// 42
			array(0x00,0x8B,0x8B,0x00),	// 008B8B	darkcyan				// 43
			array(0xAD,0xD8,0xE6,0x00),	// ADD8E6	lightblue			// 44
			array(0x87,0xCE,0xFA,0x00),	// 87CEFA	lightskyblue		// 45
			array(0x41,0x69,0xE1,0x00),	// 4169E1	royalblue			// 46
			array(0x1E,0x90,0xFF,0x00),	// 1E90FF	dodgerblue			// 47
			array(0x4B,0x00,0x82,0x00),	// 4B0082	indigo				// 48
			array(0x19,0x19,0x70,0x00),	// 191970	midnightblue		// 49
			array(0xDA,0x70,0xD6,0x00),	// DA70D6	orchid				// 50
			array(0x93,0x70,0xDB,0x00),	// 9370DB	mediumpurple		// 51
			array(0x8B,0x00,0x8B,0x00),	// 8B008B	darkmagenta			// 52
			array(0xD8,0xBF,0xD8,0x00),	// D8BFD8	thistle				// 53
			array(0xC7,0x15,0x85,0x00),	// C71585	mediumvioletred	// 54
			array(0xFF,0x69,0xB4,0x00),	// FF69B4	hotpink				// 55
			array(0xFF,0xC0,0xCB,0x00),	// FFC0CB	pink					// 56
			array(0xB0,0xC4,0xDE,0x00),	// B0C4DE	lightsteelblue		// 57
			array(0x46,0x82,0xB4,0x00),	// 4682B4	steelblue			// 58
			array(0x8A,0x2B,0xE2,0x00),	// 8A2BE2	blueviolet			// 59
			array(0xF5,0xF5,0xF5,0x00),	// F5F5F5	whitesmoke			// 60
			array(0xD3,0xD3,0xD3,0x00),	// D3D3D3	lightgrey			// 61
			array(0xA9,0xA9,0xA9,0x00),	// A9A9A9	darkgray				// 62
			array(0x69,0x69,0x69,0x00)		// 696969	dimgray				// 63
		);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// creating the final WorkBook
	function _dump_workbook() {
		if ($this->_activesheet == 0) {
			$this->_worksheets[0]->_selected = 1;
		}
		// finalize all the Sheets
		for ($i=0;$i<sizeof($this->_worksheets);$i++) {
			$sheet=&$this->_worksheets[$i];
			if ($sheet->_selected) {
				$this->_selected++;
			}
			$sheet->_close($this->_sheetnames);
		}

		$this->_dump_bof(0x0005);

		$this->_dump_externs();
		$this->_dump_names();
		$this->_dump_window1();
		$this->_dump_DateMode();
		$this->_dump_all_fonts();
		$this->_dump_all_num_formats();
		$this->_dump_all_xfs();
		$this->_dump_style();
		$this->_dump_ColorPalette();
		$this->_calc_sheet_offsets();

		for ($c=0;$c<sizeof($this->_worksheets);$c++) {
			$sheet = &$this->_worksheets[$c];
			$this->_dump_boundsheet($sheet->_name, $sheet->_offset);
		}

		$this->_dump_eof();

		// Store created WorkBook
		$this->_dump_XLS();
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// storing the result XLS file
	function _dump_XLS() {
		$temp_size = 0;
		$file = new storage_node_stream(getUNICODE("Book"));
		$AllDataSize = strlen($this->_Data);
		$file->store($this->_Data);
		for ($c=0;$c<sizeof($this->_worksheets);$c++) {
				$worksheet=&$this->_worksheets[$c];
				while ($data=$worksheet->get_Data()) {
					$AllDataSize += strlen($data);
					$file->store($data);
				}
		}
		if ($AllDataSize < 4096) {
			$must = ceil((4096 - $temp_size) / 8);
			$dummy = pack("H*", "0000000000000000");
			for ($i=0;$i<$must;$i++) {
				$file->store($dummy);
			}
		}
		$ole = new storage_node_root(false, false, array($file));
		$ole->dump($this->_filename);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// Calculate offsets BOF records.
	function _calc_sheet_offsets() {
		$BOF = 11;
		$EOF = 4;
		$offset = $this->_DataSize;
		foreach ($this->_worksheets as $sheet) {
			$offset += $BOF + strlen($sheet->_name);
		}
		$offset += $EOF;
		for ($i=0;$i<sizeof($this->_worksheets);$i++) {
			$sheet=&$this->_worksheets[$i];
			$sheet->_offset = $offset;
			$offset += $sheet->_DataSize;
		}
		$this->_biffsize = $offset;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// storing font used fonts
	function _dump_all_fonts() {
		$format = $this->_tmp_format;
		$font = $format->get_font();
		for ($i=0;$i<5;$i++) { $this->_store_Tail($font); }
		$FontIndex = 6;
		$key = $format->get_font_key();
		$fonts[$key] = 0;
		for ($c=0;$c<sizeof($this->_formats);$c++) {
			$format=&$this->_formats[$c];
			$key = $format->get_font_key();
			if (isset($fonts[$key])) { $format->_font_index = $fonts[$key]; }
			else {
				// new font
				$fonts[$key] = $FontIndex;
				$format->_font_index = $FontIndex;
				$FontIndex++;
				$font = $format->get_font();
				$this->_store_Tail($font);
			}
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// storing user-defined number formats
	function _dump_all_num_formats() {
		$num_formats_list = array();
		$FormatIndex = 164;
		for ($c=0;$c<sizeof($this->_formats);$c++) {
			$format=&$this->_formats[$c];
			$num_format = $format->_num_format;
			if (!preg_match('/^0+\d/', $num_format)) {
				if (preg_match('/^\d+$/', $num_format)) {
					continue;
				}
			}
			if (isset($num_formats[$num_format])) { $format->_num_format = $num_formats[$num_format]; }
			else {
				// new format
				$num_formats[$num_format] = $FormatIndex;
				$format->_num_format = $FormatIndex;
				array_push($num_formats_list, $num_format);
				$FormatIndex++;
			}
		}
		$FormatIndex = 164;
		foreach ($num_formats_list as $num_format) {
			$this->_dump_num_format($num_format, $FormatIndex);
			$FormatIndex++;
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// storing all XF records used
	function _dump_all_xfs() {
		$format = $this->_tmp_format;
		$XF;
		for ($c=0;$c<15;$c++) {
			$XF = $format->get_xf('style');
			$this->_store_Tail($XF);
		}
		$XF = $format->get_xf('cell');
		$this->_store_Tail($XF);
		
		foreach ($this->_formats as $format) {
			$XF = $format->get_xf('cell');
			$this->_store_Tail($XF);
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// store EXTERNCOUNT and EXTERNSHEET records
	function _dump_externs() {
		$this->_dump_externcount(sizeof($this->_worksheets));
		foreach ($this->_sheetnames as $sheetname) {
			$this->_dump_externsheet($sheetname);
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// store used NAME records
	function _dump_names() {
		foreach ($this->_worksheets as $worksheet) {
			if ($worksheet->_print_row_end!==false) { // Print Area Defined
				$this->_dump_name(
					$worksheet->_index,
					0x06, // type = Print Area
					$worksheet->_print_row_end,
					$worksheet->_print_row_start,
					$worksheet->_print_col_end,
					$worksheet->_print_col_start
				);
			}
		}
		// Print Title
		foreach ($this->_worksheets as $worksheet) {
			$StartRow = $worksheet->_title_row_end;
			$EndRow = $worksheet->_title_row_start;
			$StartCol = $worksheet->_title_col_end;
			$EndCol = $worksheet->_title_col_start;
			if ($StartRow!==false && $StartCol!==false) { // Row Title + Col Title
				$this->_dump_name(
					$worksheet->_index,
					0x07, // type = Print Title
					$StartRow,
					$EndRow,
					$StartCol,
					$EndCol,
					true
				);
			}
			else if ($StartRow!==false) { // Row Title
				$this->_dump_name(
					$worksheet->_index,
					0x07, // type = Print Title
					$StartRow,
					$EndRow,
					0x00,
					0xff
				);
			}
			else if ($StartCol!==false) { // Col Title
				$this->_dump_name(
					$worksheet->_index,
					0x07, // type = Print Title
					0x0000,
					0x3fff,
					$StartCol,
					$EndCol
				);
			}
			else {
				// having some qwick rest
			}
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - WINDOW1
	function _dump_window1() {
		/*
		WINDOW1
		This record contains general settings for the document window (BIFF2-BIFF4) 
		or workbook global settings (BIFF5-BIFF8). In BIFF4W this record occurs in every worksheet.

		Record WINDOW1, BIFF5-BIFF8:
		Offset		Size		Contents
		0				2			Horizontal position of the document window (in twips = 1/20 of a point)
		2				2			Vertical position of the document window (in twips = 1/20 of a point)
		4				2			Width of the document window (in twips = 1/20 of a point)
		6				2			Height of the document window (in twips = 1/20 of a point)
		8				2			Option flags:
										Bits		Mask		Contents
										0			0001H		0 = Window is visible				1 = Window is hidden
										1			0002H		0 = Window is open					1 = Window is minimised
													3 0008H	0 = Horizontal scroll bar hidden 1 = Horizontal scroll bar visible
													4 0010H	0 = Vertical scroll bar hidden	1 = Vertical scroll bar visible
													5 0020H	0 = Worksheet tab bar hidden		1 = Worksheet tab bar visible
		10				2			Index to active (visible) worksheet
		12				2			Index of first visible tab in the worksheet tab bar
		14				2			Number of selected worksheets (highlighted in the worksheet tab bar)
		16				2			Width of worksheet tab bar (in 1/1000 of window width). 
									The remaining space is used by the horizontal scrollbar.
		*/

		$rcrd_type = RCRD_WINDOW1;
		$rcrd_length = 0x0012;

		$HorPosition = 0x0000;
		$VerPosition = 0x0000;
		$Width = 0x25BC;
		$Height = 0x1572;

		$OptionFlags = 0x0038;
		$ActiveSheet = $this->_activesheet;
		$FirstTab = $this->_firstsheet;
		$SelectedNum = $this->_selected;
		$TabBarWidth = 0x0294;


		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvvvvvvv", $HorPosition, $VerPosition, $Width, $Height, $OptionFlags, 
										$ActiveSheet, $FirstTab, $SelectedNum, $TabBarWidth);

		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - BOUNDSHEET
	function _dump_boundsheet($sheetname, $offset) {
		/*
		BOUNDSHEET

		This record is located in the workbook globals area and represents a sheet 
		inside of the workbook. For each sheet a BOUNDSHEET record is written. 
		It stores the sheet name and a stream offset to the BOF record (.5.8) within the
		workbook stream. The record is also known as BUNDLESHEET.

		Record BOUNDSHEET, BIFF5-BIFF8:
		Offset		Size		Contents
		0				4			Absolute stream position of the BOF record of the sheet 
									represented by this record. This field is never encrypted in protected files.
		4				1			Visibility:		00H = Visible
														01H = Hidden
														02H = Strong hidden (see below)
		5				1			Sheet type:		00H = Worksheet
														02H = Chart
														06H = Visual Basic module
		6				var.		Sheet name:		BIFF5/BIFF7: Byte string, 8-bit string length (.2.2)
														BIFF8: Unicode string, 8-bit string length (.2.3)
		
		The strong hidden flag can only be set and cleared with a Visual Basic macro. 
		It is not possible to make such a sheet visible via the user interface.
		*/
		$rcrd_type = RCRD_BOUNDSHEET;

		$SheetType = 0x0000; // Worksheet
		$NameLengh = strlen($sheetname);

		$rcrd_length = 0x07 + $NameLengh;

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("VvC", $offset, $SheetType, $NameLengh);

		$this->_store_Tail($header.$data.$sheetname);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - STYLE
function _dump_style() {
		/*
		STYLE

		This record stores the name of a user-defined cell style or specific options 
		for a built-in cell style. All STYLE records occur together behind the XF record list (.5.113).
		Each STYLE record refers to a style XF record, which contains the formatting 
		attributes for the cell style.
		
		User-Defined Cell Styles
		STYLE record for user-defined cell styles, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			Bit		Mask		Contents
									11-0		0FFFH		Index to style XF record (.5.113)
									15			8000H		Always 0 for user-defined styles
		2				var.		BIFF2-BIFF7: Non-empty byte string, 8-bit string length (.2.2)
									BIFF8: Non-empty Unicode string, 16-bit string length (.2.3)
		
		Built-In Cell Styles
		STYLE record for built-in cell styles, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			Bit		Mask		Contents
									11-0		0FFFH		Index to style XF record (.5.113)
									15			8000H		Always 1 for built-in styles
		2				1			Identifier of the built-in cell style:
										00H = Normal
										01H = RowLevel_lv (see next field)
										02H = ColLevel_lv (see next field)
										03H = Comma
										04H = Currency
										05H = Percent
										06H = Comma [0] (BIFF4-BIFF8)
										07H = Currency [0] (BIFF4-BIFF8)
										08H = Hyperlink (BIFF8)
										09H = Followed Hyperlink (BIFF8)
		3				1				Level for RowLevel or ColLevel style (zero-based, lv), FFH otherwise
		
		The RowLevel and ColLevel styles specify the formatting of subtotal cells in a specific 
		outline level. The level is specified by the last field in the STYLE record. 
		Valid values are 0...6 for the outline levels 1...7.
		*/

		$rcrd_type = RCRD_STYLE;
		$rcrd_length = 0x0004;

		$IndexXF = 0x8000;
		$BuiltIn = 0x00;
		$Level = 0xff;

		$header = pack("vv",  $rcrd_type, $rcrd_length);
		$data = pack("vCC", $IndexXF, $BuiltIn, $Level);

		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _dump_num_format($num_format, $index) {
		$rcrd_type = 0x041E;
		$rcrd_length = 0x03 + strlen($num_format);
		$format = $num_format;
		$FormatIndex = $index;
		$len = strlen($format);
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vC", $FormatIndex, $len);
		$this->_store_Tail($header.$data.$format);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - DATEMODE
	function _dump_DateMode() {
		/*
		DATEMODE

		This record specifies the base date for displaying date values. 
		All dates are stored as count of days past this base date. In
		BIFF2-BIFF4 this record is part of the Calculation Settings Block (.4.3). 
		In BIFF5-BIFF8 it is stored in the Workbook Globals Substream.
		
		Record DATEMODE, BIFF2-BIFF8:
		Offset		Size		Contents
		0				2			0 = Base date is 1899-Dec-31 (the cell value 1 represents 1900-Jan-01)
									1 = Base date is 1904-Jan-01 (the cell value 1 represents 1904-Jan-02)
		*/
		$rcrd_type = RCRD_DATEMODE;
		$rcrd_length = 0x0002;
		$_DateMode = 0; // all time values will start from 1900
		$header = pack("vv",  $rcrd_type, $rcrd_length);
		$data = pack("v", $_DateMode);
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - EXTERNCOUNT
	function _dump_externcount($Number) {
		/*
		EXTERNCOUNT

		This record contains the number of following EXTERNSHEET records. 
		In BIFF8 this record is omitted because there occurs only one EXTERNSHEET record. 
		See .4.9.1 for details about external references in BIFF2-BIFF4 and .4.9.2 for BIFF5/BIFF7.
		
		Record EXTERNCOUNT, BIFF2-BIFF7:
		Offset		Size		Contents
		0				2			Number of following EXTERNSHEET records (.5.39)
		*/
		$rcrd_type = RCRD_EXTERNCOUNT;
		$rcrd_length = 0x0002;
		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("v", $Number);
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - EXTERNSHEET
	function _dump_externsheet($sheetname) {
		/*
		EXTERNSHEET in BIFF2-BIFF7
		In the file format versions up to BIFF7 this record stores 
		the name of an external document and a sheet name inside of
		this document. See .4.9.1 for details about external references 
		in BIFF2-BIFF4 and .4.9.2 for BIFF5/BIFF7.
		
		Record EXTERNSHEET, BIFF2-BIFF7:
		Offset		Size		Contents
		0				var.		Encoded document and sheet name (.2.8). 
									Byte string, 8-bit string length (.2.2).
		
		!!The string length field is decreased by 1, if the EXTERNSHEET stores a reference 
		to one of the own sheets (first character is 03H). Example: The formula =Sheet2!A1 
		contains a reference to an EXTERNSHEET record with the string "<03H>Sheet2". 
		The string consists of 7 characters but the string length field contains the value 6. !
		If a formula uses an add-in function, a special EXTERNSHEET record will occur, 
		followed by an EXTERNNAME record with the name of the function.
		*/
		$rcrd_type = RCRD_EXTERNSHEET;

		$NameLength = strlen($sheetname);
		$Location = 0x03;

		$rcrd_length = 0x02 + $NameLength;

		$header = pack("vv",  $rcrd_type, $rcrd_length);
		$data = pack("CC", $NameLength, $Location);

		$this->_store_Tail($header.$data.$sheetname);
	} // end of function
// ------------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------------
// RECORD - PALETTE
	function _dump_ColorPalette() {
		/*
		PALETTE

		This record contains the definition of all user-defined colours available for cell and object formatting.
		
		Record PALETTE, BIFF3-BIFF8:
		Offset		Size		Contents
		0				2			Number of following colours (nm). 
									Contains 16 in BIFF3-BIFF4 and 56 in BIFF5-BIFF8.
		2				4·nm		List of nm RGB colours (.2.4)
		
		The following table shows how colour indexes are used in other records:
		Colour index		Resulting colour or internal list index
		00H					Black		(R = 00H, G = 00H, B = 00H)
		01H					White		(R = FFH, G = FFH, B = FFH)
		02H					Red		(R = FFH, G = 00H, B = 00H)
		03H					Green		(R = 00H, G = FFH, B = 00H)
		04H					Blue		(R = 00H, G = 00H, B = FFH)
		05H					Yellow	(R = FFH, G = FFH, B = 00H)
		06H					Magenta	(R = FFH, G = 00H, B = FFH)
		07H					Cyan		(R = 00H, G = FFH, B = FFH)
		08H					First user-defined colour from the PALETTE record 
								(entry 0 from internal colour list)
		...
		...
		17H or 3FH			Last user-defined colour from the PALETTE record 
								(entry 15 or 55 from internal colour list)
		40H					System window text colour for border lines 
								(used in records XF .5.113, CF .5.16, and WINDOW2 (BIFF8 only), .5.107)
		41H					System window background colour for pattern background 
								(used in records XF, and CF)
		7FFFH					System window text colour for fonts 
								(used in records FONT .5.43, EFONT .5.35, and CF)
		*/
		$data = "";
		$rTmp = &$this->ColorPalette;
		$rcrd_type = RCRD_PALETTE;
		$rcrd_length = 2 + 4 * sizeof($rTmp);
		$NumberOfColors = sizeof($rTmp);

		foreach($rTmp as $color) {
			$data .= pack("CCCC", $color[0],$color[1],$color[2],$color[3]);
		}
		$header = pack("vvv", $rcrd_type, $rcrd_length, $NumberOfColors);
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - NAME
	function _dump_name($index, $type, $StartRow, $EndRow, $StartCol, $EndCol, $nmln=false) {
		/*
		NAME

		This record is part of a Link Table. It contains the name and the token array 
		of an internal defined name. Token arrays of defined names contain tokens 
		with aberrant token classes.

		Record NAME, BIFF5/BIFF7:
		Offset		Size		Contents
		0				2			Option flags, see below
		2				1			Keyboard shortcut
		3				1			Length of the name (character count, ln)
		4				2			Size of the formula data (sz)
		6				2			Not used
		8				2			0 = Global name, otherwise index to sheet (one-based)
		10				1			Length of menu text (character count, lm)
		11				1			Length of description text (character count, ld)
		12				1			Length of help topic text (character count, lh)
		13				1			Length of status bar text (character count, ls)
		14				ln			Character array of the name
		14+ln			sz			Formula data (RPN token array without size field)
		14+ln+sz		lm			Character array of menu text
		var.			ld			Character array of description text
		var.			lh			Character array of help topic text
		var.			ls			Character array of status bar text

			Option Flags
			Bit			Mask			Contents
			0				0001H			1 = Name is hidden
			1				0002H			1 = Name is a function
			2				0004H			1 = Name is a command
			3				0008H			1 = Function macro or command macro
			4				0010H			1 = Complex function (array formula or user defined)
			5				0020H			1 = Built-in name (see table below)
			11-6			0FC0H			BIFF4-BIFF8 only: Index to function group
			12				1000H			BIFF5-BIFF8 only: 1 = Name contains binary data
		*/

		$rcrd_type = RCRD_NAME;
		$rcrd_length = ($nmln) ? 0x003d : 0x0024;

		$SheetIndex = $index;
		$NameType = $type;

		$options = 0x0020; // Built In Name

		$Key = 0x00;
		$LengthName = 0x01;
		$FormulaSize = ($nmln) ? 0x002e : 0x0015;
		$curSheetIndex = $SheetIndex +1;
		$MenuLength = 0x00;
		$DescriptionLength = 0x00;
		$HelpLength = 0x00;
		$StatusLength = 0x00;

		// some preset code
		if ($nmln) {
			$magic1 = 0x29;
			$magic2 = 0x002b;
		}
		$magic3 = 0x3b;
		$magic4 = 0xffff-$SheetIndex;
		$magic5 = 0x0000;
		$magic6 = 0x0000;
		$magic7 = 0x1087;
		$magic8 = ($nmln) ? 0x8008 : 0x8005;

		$header = pack("vv",  $rcrd_type, $rcrd_length);

		$data = pack("vCCvvvCCCCC", $options, $Key, $LengthName, $FormulaSize,
								$curSheetIndex, $curSheetIndex,
								$MenuLength, $DescriptionLength, $HelpLength, $StatusLength, $NameType);
		if ($nmln) {
			$data .= pack("CvCvvvvvvvvvCC", $magic1, $magic2, $magic3, $magic4, $magic5, $magic6, $magic7, $magic8,
								$SheetIndex, $SheetIndex, 0x0000, 0x3fff, $StartCol, $EndCol);
		}
		$data .= pack("Cvvvvvvvvv", $magic3, $magic4, $magic5, $magic6, $magic7, $magic8,
								$SheetIndex, $SheetIndex, $StartRow, $EndRow);
		if ($nmln) {
			$data .= pack("CCC", 0x00, 0xff, 0x10);
		}
		else {
			$data .= pack("CC", $StartCol, $EndCol);
		}
		$this->_store_Tail($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

} // END OF CLASS
// ------------------------------------------------------------------------------------------------

?>
