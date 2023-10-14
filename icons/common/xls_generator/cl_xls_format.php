<?php

//		XLS Generator Class
//		(c) 2007 Paggard [paggard@paggard.com]
//		18 March 2007 - build 1.4.1

// ------------------------------------------------------------------------------------------------
class xls_format {

	public $_xf_index;
	public $_font_index;
	public $_font_family;
	public $_font_charset;
	public $_font;
	public $_size;
	public $_bold;
	public $_italic;
	public $_color;
	public $_underline;
	public $_strikeout;
	public $_outline;
	public $_shadow;
	public $_script;

	public $_fg_color;
	public $_bg_color;
	public $_num_format;
	public $_hidden;
	public $_locked;
	public $_TextAlign;
	public $_TextWrap;
	public $_TextVAlign;
	public $_TextRotation;
	public $_pattern;
	public $_bottom;
	public $_top;
	public $_left;
	public $_right;
	public $_bottom_color;
	public $_top_color;
	public $_left_color;
	public $_right_color;

// ------------------------------------------------------------------------------------------------
// CONSTRUCTOR - Formatter class
	function __construct() {
		$Args = func_get_args();
		$c_file = array_shift($Args);
		if ($c_file) { include $c_file; }

		$this->_xf_index = (sizeof($Args) > 0) ? array_shift($Args) : 0;
		$this->_font_index = 0;
		$this->_font_family = 0;
		$this->_font_charset = (isset($FontCharset) && $FontCharset) ? $this->_get_font_charset($font_charset) : 0;
		$this->_font = ($FontName) ? $FontName : 'Arial';
		$this->_size = ($FontSize) ? $FontSize : 10;
		list($this->_bold,$this->_italic,$this->_underline,$this->_strikeout,$this->_outline,$this->_shadow,$this->_script) = ($FontStyle) ? $this->set_font_style($FontStyle) : array(0x190,0,0,0,0,0,0);

		$this->_num_format = ($NumberFormat) ? $NumberFormat : 0;

		$this->_hidden = 0;
		$this->_locked = 1;

		$this->_TextAlign = $this->set_align($TextAlign);
		$this->_TextVAlign = $this->set_valign($TextVAlign);
		$this->_TextWrap = ($TextWrap) ? $TextWrap : 0;
		$this->_TextRotation = ($TextRotation) ? $TextRotation : 0;

		$this->_color = ($FontColor) ? $this->_get_color($FontColor) : 0x7FFF;
		$this->_fg_color = ($ForegroundColor) ? $this->_get_color($ForegroundColor) : 0x40;
		$this->_bg_color = ($BackgroundColor) ? $this->_get_color($BackgroundColor) : 0x41;

		$this->_pattern = ($Pattern) ? $Pattern : 0;

		list($this->_top,$this->_right,$this->_bottom,$this->_left) = $this->_set_borders($BorderTop,$BorderRight,$BorderBottom,$BorderLeft);
		$this->_top_color = ($BorderTopColor) ? $this->_get_color($BorderTopColor) : 0x40;
		$this->_right_color = ($BorderRightColor) ? $this->_get_color($BorderRightColor) : 0x40;
		$this->_bottom_color = ($BorderBottomColor) ? $this->_get_color($BorderBottomColor) : 0x40;
		$this->_left_color = ($BorderLeftColor) ? $this->_get_color($BorderLeftColor) : 0x40;

		if (sizeof($Args) > 0) {
			$this->set_properties($Args);
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function copy_format($format_object) {
		$xf = $this->_xf_index;
		
		// PHP 4.x.x
		//$this = $format_object;
		
		// PHP 5.0.0 support
		foreach (get_object_vars($format_object) as $key => $value)
			$this->$key = $value;
		
		$this->_xf_index = $xf;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function get_xf($xf_type) {
		/*
		XF – Extended Format

		This record contains formatting information for cells, rows, columns or styles.
		From BIFF3 on, some of the elements occur unchanged in every BIFF version. These elements
		are described in the following using a specific name for each element. In the description
		of the record structure the names are used to reference to these tables.

		XF Record Contents
		Record XF, BIFF5/BIFF7:
		Offset		Size		Contents
		0				2			Index to FONT record
		2				2			Index to FORMAT record
		4				2			Bit		Mask			Contents
									2-0		0007H			XF_TYPE_PROT . XF type, cell protection
									15-4		FFF0H			Index to parent style XF (always FFFH in style XFs)
		6				1			Bit		Mask			Contents
									2-0		07H			XF_HOR_ALIGN . Horizontal alignment
									3			08H			1 = Text is wrapped at right border
									6-4		70H			XF_VERT_ALIGN . Vertical alignment
		7				1			Bit		Mask			Contents
									1-0		03H			XF_ORIENTATION . Text orientation
									7-2		FCH			XF_USED_ATTRIB . Used attributes
		8				4			Cell border lines and background area:
										Bit		Mask			Contents
										6-0		0000007FH	Colour index for pattern colour
										13-7		00003F80H	Colour index for pattern background
										21-16		003F0000H	Fill pattern
										24-22		01C00000H	Bottom line style
										31-25		FE000000H	Colour index for bottom line colour
		12				4			Bit		Mask			Contents
									2-0		00000007H	Top line style
									5-3		00000038H	Left line style
									8-6		000001C0H	Right line style
									15-9		0000FE00H	Colour index for top line colour
									22-16		007F0000H	Colour index for left line colour
									29-23		3F800000H	Colour index for right line colour
		===========================
		REFERENCES
		----------

		XF_TYPE_PROT – XF Type and Cell Protection (3 Bits), BIFF3-BIFF8
		These 3 bits are part of a specific data byte.
			Bit		Mask		Contents
			0			01H		1 = Cell is locked
			1			02H		1 = Formula is hidden
			2			04H		0 = Cell XF; 1 = Style XF
		>>-----------------------------------------
		XF_USED_ATTRIB – Attributes Used from Parent Style XF (6 Bits), BIFF3-BIFF8
		Each bit describes the validity of a specific group of attributes. In cell XFs a cleared bit
		means the attributes of the parent style XF are used (but only if the attributes
		are valid there), a set bit means the attributes of this XF are used. In style XFs a cleared bit
		means the attribute setting is valid, a set bit means the attribute should be ignored.
			Bit		Mask		Contents
			0			01H		Flag for number format
			1			02H		Flag for font
			2			04H		Flag for horizontal and vertical alignment, text wrap, indentation,
									orientation, rotation, and text direction
			3			08H		Flag for border lines
			4			10H		Flag for background area style
			5			20H		Flag for cell protection (cell locked and formula hidden)
		>>-----------------------------------------
		XF_HOR_ALIGN – Horizontal Alignment (3 Bits), BIFF2-BIFF8
		The horizontal alignment consists of 3 bits and is part of a specific data byte.
			Value		Horizontal alignment
			00H		General
			01H		Left
			02H		Centred
			03H		Right
			04H		Filled
			05H		Justified (BIFF4-BIFF8X)
			06H		Centred across selection (BIFF4-BIFF8X)
			07H		Distributed (BIFF8X)
		>>-----------------------------------------
		XF_VERT_ALIGN – Vertical Alignment (2 or 3 Bits), BIFF4-BIFF8
		The vertical alignment consists of 2 bits (BIFF4) or 3 bits (BIFF5-BIFF8)
		and is part of a specific data byte. Vertical alignment is not available in BIFF2 and BIFF3.
			Value		Vertical alignment
			00H		Top
			01H		Centred
			02H		Bottom
			03H		Justified (BIFF5-BIFF8X)
			04H		Distributed (BIFF8X)
		>>-----------------------------------------
		XF_ORIENTATION – Text Orientation (2 Bits), BIFF4-BIFF7
		In the BIFF versions BIFF4-BIFF7, text can be rotated in steps of 90 degrees or stacked.
		The orientation mode consists of 2 bits and is part of a specific data byte.
		In BIFF8 a rotation angle occurs instead of these flags.
			Value		Text orientation
			00H		Not rotated
			01H		Letters are stacked top-to-bottom, but not rotated
			02H		Text is rotated 90 degrees counterclockwise
			03H		Text is rotated 90 degrees clockwise
		*/

		$rcrd_type = RCRD_XF;
		$rcrd_length = 0x0010;

		if ($xf_type == "style") { $Style = 0xFFF5; }
		else {
				$Style = $this->_locked;
				$Style |= $this->_hidden << 1;
		}
		// Checking colors
		if ($this->_fg_color     == 0x7FFF) $this->_fg_color     = 0x40;
		if ($this->_bg_color     == 0x7FFF) $this->_bg_color     = 0x41;
		if ($this->_bottom_color == 0x7FFF) $this->_bottom_color = 0x41;
		if ($this->_top_color    == 0x7FFF) $this->_top_color    = 0x41;
		if ($this->_left_color   == 0x7FFF) $this->_left_color   = 0x41;
		if ($this->_right_color  == 0x7FFF) $this->_right_color  = 0x41;
		if ($this->_bottom == 0) { $this->_bottom_color = 0; }
		if ($this->_top    == 0) { $this->_top_color = 0; }
		if ($this->_right  == 0) { $this->_right_color = 0; }
		if ($this->_left   == 0) { $this->_left_color = 0; }
		// XF_USED_ATTRIB Flags
		$Flag_num = ($this->_num_format != 0) ? 1 : 0;
		$Flag_fnt = ($this->_font_index != 0) ? 1 : 0;
		$Flag_alc =  $this->_TextWrap ? 1 : 0;
		$Flag_bdr = ($this->_bottom || $this->_top || $this->_left || $this->_right) ? 1 : 0;
		$Flag_pat = ($this->_fg_color != 0x41 || $this->_bg_color != 0x41 || $this->_pattern != 0x00) ? 1 : 0;
		$Flag_prot = 0;

		// Helping out with filling colors and patterns
		if ($this->_pattern <= 0x01 && $this->_bg_color != 0x41 && $this->_fg_color == 0x40 ){
			$this->_fg_color = $this->_bg_color;
			$this->_bg_color = 0x40;
			$this->_pattern = 1;
		}
		if ($this->_pattern <= 0x01 && $this->_bg_color == 0x41 && $this->_fg_color != 0x40 ){
			$this->_bg_color = 0x40;
			$this->_pattern = 1;
		}

		$IndexFont = $this->_font_index;
		$IndexFormat = $this->_num_format;

		$Align = $this->_TextAlign;
		$Align |= $this->_TextWrap << 3;
		$Align |= $this->_TextVAlign << 4;
		$Align |= 0 << 7;
		$Align |= $this->_TextRotation << 8;
		$Align |= $Flag_num << 10;
		$Align |= $Flag_fnt << 11;
		$Align |= $Flag_alc << 12;
		$Align |= $Flag_bdr << 13;
		$Align |= $Flag_pat << 14;
		$Align |= $Flag_prot << 15;

		$Color_Fg_Bg = $this->_fg_color;
		$Color_Fg_Bg |= $this->_bg_color << 7;

		$Border_Fill = $this->_pattern;
		$Border_Fill |= $this->_bottom << 6;
		$Border_Fill |= $this->_bottom_color << 9;

		$Border_st1 = $this->_top;
		$Border_st1 |= $this->_left << 3;
		$Border_st1 |= $this->_right << 6;
		$Border_st1 |= $this->_top_color << 9;

		$Border_st2 = $this->_left_color;
		$Border_st2 |= $this->_right_color << 7;

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvvvvvv", $IndexFont, $IndexFormat, $Style, $Align,
													$Color_Fg_Bg, $Border_Fill,
													$Border_st1, $Border_st2);
		return($header.$data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// RECORD - FONT
	function get_font() {
		/*
		Offset	Size Contents
		0			2 Height of the font (in twips = 1/20 of a point)
		2			2 Option flags:
							Bit Mask Contents
							0 0001H 1 = Characters are bold (redundant, see below)
							1 0002H 1 = Characters are italic
							2 0004H 1 = Characters are underlined (redundant, see below)
							3 0008H 1 = Characters are struck out
		4			2 Colour index (.5.69)
		6			2 Font weight (100-1000). Standard values are 0190H (400) for normal text and 02BCH
						(700) for bold text.
		8			2 Escapement type:	0000H = None
												0001H = Superscript
												0002H = Subscript
		10			1 Underline type:		00H = None
												01H = Single 21H = Single accounting
												02H = Double 22H = Double accounting
		11			1 Font family:			00H = None (unknown or don't care)
												01H = Roman (variable width, serifed)
												02H = Swiss (variable width, sans-serifed)
												03H = Modern (fixed width, serifed or sans-serifed)
												04H = Script (cursive)
												05H = Decorative (specialised, i.e. Old English, Fraktur)
		12			1 Character set:		00H = 0 = ANSI Latin
												02H = 2 = Symbol
												4DH = 77 = Apple Roman
												80H = 128 = ANSI Japanese Shift-JIS
												81H = 129 = ANSI Korean (Hangul)
												82H = 130 = ANSI Korean (Johab)
												86H = 134 = ANSI Chinese Simplified GBK
												88H = 136 = ANSI Chinese Traditional BIG5
												A1H = 161 = ANSI Greek
												A2H = 162 = ANSI Turkish
												A3H = 163 = ANSI Vietnamese
												B1H = 177 = ANSI Hebrew
												B2H = 178 = ANSI Arabic
												BAH = 186 = ANSI Baltic
												CCH = 204 = ANSI Cyrillic
												DEH = 222 = ANSI Thai
												EEH = 238 = ANSI Latin II (Central European)
												FFH = 255 = OEM Latin I
		13			1 Not used
		14			var. Font name:	BIFF5/BIFF7: Byte string, 8-bit string length (.2.2)
											BIFF8: Unicode string, 8-bit string length (.2.3)
		*/
		$rcrd_type = RCRD_FONT;

		$FontHeight = $this->_size * 20;
		$OptionFlags = 0x00;
		$ColorIndex = $this->_color;
		$FontWeight = $this->_bold;
		$EscType = $this->_script;
		$UnderlType = $this->_underline;
		$FontFamily = $this->_font_family;
		$CharacterSet = $this->_font_charset;
		$NotUsed = 0x00;
		$FontName = strlen($this->_font);

		$rcrd_length = 0x0F + $FontName;

		if ($this->_italic) { $OptionFlags |= 0x02; }
		if ($this->_strikeout) { $OptionFlags |= 0x08; }
		if ($this->_outline) { $OptionFlags |= 0x10; }
		if ($this->_shadow) { $OptionFlags |= 0x20; }

		$header = pack("vv", $rcrd_type, $rcrd_length);
		$data = pack("vvvvvCCCCC",
							$FontHeight, $OptionFlags, $ColorIndex, $FontWeight,
							$EscType, $UnderlType, $FontFamily, $CharacterSet, $NotUsed, $FontName);

		return($header.$data.$this->_font);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// generating unique font key value
	function get_font_key() {
		$key = $this->_font.$this->_size.
				$this->_script.$this->_underline.
				$this->_strikeout.$this->_bold.$this->_outline.
				$this->_font_family.$this->_font_charset.
				$this->_shadow.$this->_color.$this->_italic;
		$key = preg_replace('/ /', '_', $key);
		return $key;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function get_xf_index() {
		return $this->_xf_index;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _get_color($color=false) {
		$colors = array(
			"black" => 8,"white" => 9,"red" => 10,"blue" => 11,
			"yellow" => 12,"maroon" => 13,"purple" => 14,"aqua" => 15,
			"green" => 16,"navy" => 17,"olive" => 18,"teal" => 19,
			"gray" => 20,"silver" => 21,"linen" => 22,"lightyellow" => 23,
			"wheat" => 24,"tan" => 25,"gold" => 26,"tomato" => 27,
			"crimson" => 28,"saddlebrown" => 29,"chocolate" => 30,"coral" => 31,
			"olivedrab" => 32,"darkgreen" => 33,"greenyellow" => 34,"yellowgreen" => 35,
			"limegreen" => 36,"lightgreen" => 37,"aquamarine" => 38,"seagreen" => 39,
			"mediumturquoise" => 40,"cadetblue" => 41,"lightcyan" => 42,"darkcyan" => 43,
			"lightblue" => 44,"lightskyblue" => 45,"royalblue" => 46,"dodgerblue" => 47,
			"indigo" => 48,"midnightblue" => 49,"orchid" => 50,"mediumpurple" => 51,
			"darkmagenta" => 52,"thistle" => 53,"mediumvioletred" => 54,"hotpink" => 55,
			"pink" => 56,"lightsteelblue" => 57,"steelblue" => 58,"blueviolet" => 59,
			"whitesmoke" => 60,"lightgrey" => 61,"darkgray" => 62,"dimgray" => 63
		);

		if ($color===false) { return 0x7FFF; }
		if (isset($colors[strtolower($color)])) {
			return $colors[strtolower($color)];
		}
		if (preg_match('/\D/', $color)) { return 0x7FFF; }
		if ($color<8) { return $color + 8; }
		if ($color>63) { return 0x7FFF; }
		return $color;
	} // end of function
// ------------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------------
// general wrapper for format setting functions
	function set_properties($Args) {
		$values = array();
		foreach($Args as $val) {
			if (is_array($val)) { $values = array_merge($values, $val); }
			else { $values[] = $val; }
		}
		foreach ($values as $key => $value) {
			if (preg_match('/\W/', $key)) { trigger_error("Illegal format value: $key.", E_USER_ERROR); }
			if (method_exists ($this,"set_".$key)) {
				eval("\$this->set_".$key."('".$value."');");
			}
			
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _get_border_index($name) {
		$border_index = 0;
		switch (strtolower($name)) {
			case "no": $border_index = 0; break;
			case "thin": $border_index = 1; break;
			case "medium": $border_index = 2; break;
			case "dashed": $border_index = 3; break;
			case "dotted": $border_index = 4; break;
			case "thick": $border_index = 5; break;
			case "double": $border_index = 6; break;
			case "hair": $border_index = 7; break;
		}
		return $border_index;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _set_borders($top,$right,$bottom,$left) {
		$top = $this->_get_border_index($top);
		$right = $this->_get_border_index($right);
		$bottom = $this->_get_border_index($bottom);
		$left = $this->_get_border_index($left);
		return array($top,$right,$bottom,$left);
	} // end of function
// ------------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------------
// FORMAT SETTING FUNCTIONS
//-------------------------------------------------------------------------------------------------
	// FONT SETTINGS
	function set_font($font) { $this->_font = $font; }
	function set_font_family($font_family=1) { $this->_font_family = $font_family; }
	function set_font_charset($font_charset=0) { $this->_font_charset = $this->_get_font_charset($font_charset); }
	function set_size($size) { $this->_size = $size; }
	function set_italic($italic=1) { $this->_italic = $italic; }
	function set_color($color) { $this->_color = $this->_get_color($color); }
	function set_underline($underline=1) { $this->_underline = $underline; }
	function set_strikeout($font_strikeout=1) { $this->_strikeout = $font_strikeout; }
	function set_outline($font_outline=1) { $this->_outline = $font_outline; }
	function set_shadow($font_shadow=1) { $this->_shadow = $font_shadow; }
	function set_script($font_script=1) { $this->_script = $font_script; }

// ------------------------------------------------------------------------------------------------
	function set_bold($weight=1) {
		if ($weight == 1) { // Bold
			$weight = 0x2BC;
		}
		if ($weight == 0 || $weight < 0x064 || $weight > 0x3E8) {// Normal
			$weight = 0x190;
		}
		$this->_bold = $weight;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _get_font_charset($charset) {
		switch ($charset) {
			case 0   : $_font_charset = 0x00; break; // = ANSI Latin
			case 2   : $_font_charset = 0x02; break; // = Symbol
			case 77  : $_font_charset = 0x4D; break; // = Apple Roman
			case 128 : $_font_charset = 0x80; break; // = ANSI Japanese Shift-JIS
			case 129 : $_font_charset = 0x81; break; // = ANSI Korean (Hangul)
			case 130 : $_font_charset = 0x82; break; // = ANSI Korean (Johab)
			case 134 : $_font_charset = 0x86; break; // = ANSI Chinese Simplified GBK
			case 136 : $_font_charset = 0x88; break; // = ANSI Chinese Traditional BIG5
			case 161 : $_font_charset = 0xA1; break; // = ANSI Greek
			case 162 : $_font_charset = 0xA2; break; // = ANSI Turkish
			case 163 : $_font_charset = 0xA3; break; // = ANSI Vietnamese
			case 177 : $_font_charset = 0xB1; break; // = ANSI Hebrew
			case 178 : $_font_charset = 0xB2; break; // = ANSI Arabic
			case 186 : $_font_charset = 0xBA; break; // = ANSI Baltic
			case 204 : $_font_charset = 0xCC; break; // = ANSI Cyrillic
			case 222 : $_font_charset = 0xDE; break; // = ANSI Thai
			case 238 : $_font_charset = 0xEE; break; // = ANSI Latin II (Central European)
			case 255 : $_font_charset = 0xFF; break; // = OEM Latin I
			default: $_font_charset = 0; break;
		}
		return $_font_charset;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_font_style($style) {
		$style = strtolower($style);
		$this->_bold = (preg_match("/b/",$style)) ? 0x2BC : 0x190;
		$this->_italic = (preg_match("/i/",$style)) ? 1 : 0;
		$this->_underline = (preg_match("/u/",$style)) ? 1 : 0;
		$this->_strikeout = (preg_match("/r/",$style)) ? 1 : 0;
		$this->_outline = (preg_match("/o/",$style)) ? 1 : 0;
		return array($this->_bold,$this->_italic,$this->_underline,$this->_strikeout,$this->_outline);
	} // end of function
// ------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
	// CELL SETTINGS
// ------------------------------------------------------------------------------------------------
	function set_align($align) {
		if (preg_match('/\d/', $align)) { return; }
		switch (strtolower($align)) {
			case 'left': $this->set_text_align(1); break;
			case 'centre':
			case 'center': $this->set_text_align(2); break;
			case 'right': $this->set_text_align(3); break;
			case 'fill': $this->set_text_align(4); break;
			case 'justify': $this->set_text_align(5); break;
			case 'merge': $this->set_text_align(6); break;
			case 'distributed': $this->set_text_align(7); break;
			default: $this->set_text_align(1);
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function set_merge() {
		$this->set_text_align(6);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: seetting vertical alignment
	function set_valign($valign) {
		if (preg_match('/\d/', $valign)) { return; }
		switch (strtolower($valign)) {
			case 'top': $this->set_text_valign(0); break;
			case 'middle': $this->set_text_valign(1); break;
			case 'bottom': $this->set_text_valign(2); break;
			case 'justify': $this->set_text_valign(3); break;
			case 'distributed': $this->set_text_valign(4); break;
			default: $this->set_text_valign(1);
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

	function set_num_format($num_format=1) { $this->_num_format=$num_format; }
	function set_hidden($hidden=1) { $this->_hidden=$hidden; }
	function set_locked($locked=1) { $this->_locked=$locked; }
	function set_text_align($align) { $this->_TextAlign=$align; }
	function set_text_wrap($wrap=1) { $this->_TextWrap=$wrap; }
	function set_text_valign($align) { $this->_TextVAlign=$align; }
	function set_rotation($rotation=1) { $this->_TextRotation=$rotation; }
	function set_fg_color($color) { $this->_fg_color=$this->_get_color($color); }
	function set_bg_color($color) { $this->_bg_color=$this->_get_color($color); }
	function set_pattern($pattern=1) { $this->_pattern=$pattern; }
	function set_bottom($bottom=1) { $this->_bottom=$this->_get_border_index($bottom); }
	function set_top($top=1) { $this->_top=$this->_get_border_index($top); }
	function set_left($left=1) { $this->_left=$this->_get_border_index($left); }
	function set_right($right=1) { $this->_right=$this->_get_border_index($right); }
	function set_bottom_color($color) { $this->_bottom_color=$this->_get_color($color); }
	function set_top_color($color) { $this->_top_color=$this->_get_color($color); }
	function set_left_color($color) { $this->_left_color=$this->_get_color($color); }
	function set_right_color($color) { $this->_right_color=$this->_get_color($color); }

// ------------------------------------------------------------------------------------------------
// user func :: setting all borders in one function
	function set_border($style) {
		$this->set_bottom($style);
		$this->set_top($style);
		$this->set_left($style);
		$this->set_right($style);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
// user func :: setting all borders colors in one function
	function set_border_color($color) {
		$this->set_bottom_color($color);
		$this->set_top_color($color);
		$this->set_left_color($color);
		$this->set_right_color($color);
	} // end of function
// ------------------------------------------------------------------------------------------------

// END OF FORMAT SETTING FUNCTIONS

} // END OF CLASS
// ------------------------------------------------------------------------------------------------


?>
