<?php

//		XLS Generator Class
//		(c) 2007 Paggard [paggard@paggard.com]
//		18 March 2007 - build 1.4.1

//-------------------------------------------------------------------------------------------------
class xls {

// ------------------------------------------------------------------------------------------------
// CONSTRUCTOR
	function xls ($template,$filename,$config_file=false,$temp_dir=false) {
		global $xls_wrap;
		//-----------------------------------------
		$xls_wrap->begin($filename,$config_file,$temp_dir);
		//-----------------------------------------
		if (!($xml_parser = new_xml_parser($template))) {
				die("not a valid XML input");
		}
		//echo "<pre>"; // for debug
		$data = $this->clear_data($template);

		if (!xml_parse($xml_parser, $data)) {
			die(sprintf("XML error: %s at line %d\n",
							xml_error_string(xml_get_error_code($xml_parser)),
							xml_get_current_line_number($xml_parser)));
		}
		//echo "</pre>"; // for debug
		//print "parse complete\n";
		xml_parser_free($xml_parser);

	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function clear_data($data) {
		global $xls_wrap;
		$data = preg_replace("/\xA0/msi"," ",$data);
		$data = preg_replace("/\t+/msi"," ",$data);
		//$data = preg_replace("/\r\n|\n/msi"," ##carret## ",$data);
		$data = preg_replace("/&/msi","##amp##",$data);
		return $data;
	} // end of function
// ------------------------------------------------------------------------------------------------

} // END OF CLASS
//-------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
class xls_wrap { // for wrapping purposes

	public $final_out; // variable to store final output from driver
	public $config_file; // path to configuration file
	public $temp_dir;  // path to store tem files
	public $FileName;

	public $workbook;
	public $sheets;
	public $cur_sheet;
	//------------------------
	public $_cur_row;
	public $_cur_col;
	public $_cur_cell_index=false;
	//------------------------
	public $_styles;
	public $_cur_style=false;
	public $_cur_elt_style=array();

// --- help vars -----------------------------
	public $_debug;
	public $_element_data=false;
	public $_tree_element;
	public $_tree_settings;
	public $_cur_element;
	public $_cur_level;
	public $f_margin;
	public $h_margin;

	/// Abstract functions
//-------------------------------------------------------------------------------------------------
	function begin($filename,$config_file,$temp_dir) {
		$this->_debug = 0;
		$this->config_file = $config_file;
		$this->temp_dir = $temp_dir;
		$this->FileName = $filename;
		$this->sheets = array();
	}
//-------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function end() {

	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _start_child($name) {
		$this->_cur_level++;
		$this->_tree_element[$this->_cur_level] = $name;
		$this->_tree_settings[$this->_cur_level] = $this->_tree_settings[$this->_cur_level-1];
		$this->_cur_element = $name;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _end_child($name) {
		$this->_cur_level--;
		$this->_cur_element = @$this->_tree_element[$this->_cur_level];
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	//function &_get_style() {
	function _get_style() {
		$lvl = $this->_cur_level;
		while ($this->_tree_element[$lvl] != "WORKBOOK") {
			$cur_element = $this->_tree_element[$lvl];
			if (isset($this->_cur_elt_style[$cur_element]) && isset($this->_cur_elt_style[$cur_element]) && $this->_cur_elt_style[$cur_element]) {
				return $this->_styles[$this->_cur_elt_style[$cur_element]];
			}
			if (isset($this->_cur_elt_format) && isset($this->_cur_elt_format[$cur_element]) && $this->_cur_elt_format[$cur_element]) {
				return $this->_cur_elt_format[$cur_element];
			}
			$lvl--;
		}
		if ($this->_cur_style) {
			return $this->_styles[$this->_cur_style];
		}
		return false;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function plain_text($data) {
		//echo $this->_cur_element."\n";
		$data = trim($data);
		$data = preg_replace("/##amp##/msi","&",$data);
		$data = preg_replace("/&BR/msi","\n",$data);
		//$data = preg_replace("/ ##carret## /msi","\r\n",$data);
		//----------- RULES
		$data = preg_replace("/&NOW/msi",Unix2Excel(),$data);
		//-----------------
		if ($this->_cur_element == "CELL") {
//			if ($this->_cur_cell_index) {
//				$this->sheets[$this->cur_sheet]->write($this->_cur_cell_index,$data,$this->_get_style());
//			}
//			else {$this->sheets[$this->cur_sheet]->write($this->_cur_row,$this->_cur_col,$data,$this->_get_style());}

		}
		if ($this->_cur_element == "PASSWORD") {
			$this->sheets[$this->cur_sheet]->protect($data);
		}
		if ($this->_cur_element == "HEADER") {
			$this->sheets[$this->cur_sheet]->set_header($data, $this->h_margin);
		}
		if ($this->_cur_element == "FOOTER") {
			$this->sheets[$this->cur_sheet]->set_footer($data, $this->f_margin);
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
// WRAP FUNCTIONS
// ------------------------------------------------------------------------------------------------
	function _int_WORKBOOK($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			$obj10 = new xls_book($this->FileName,$this->config_file,$this->temp_dir);
			$this->workbook =& $obj10;
			$this->_cur_element = "WORKBOOK";
			$this->_start_child("WORKBOOK");
		}
		else {
			$this->workbook->close();
			$this->_end_child("WORKBOOK");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _int_WORKSHEET($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			$next_sheet = sizeof($this->sheets);
			$this->sheets[$next_sheet] =& $this->workbook->add_sheet($attribs["NAME"]);
			$this->cur_sheet = $next_sheet;
			$this->_cur_element = "WORKSHEET";
			$this->_start_child("WORKSHEET");
		}
		else {
			//$this->cur_sheet = false;
			$this->_end_child("WORKSHEET");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_TABLE($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (isset($attribs["STYLE"])) {
				$this->_cur_elt_style["TABLE"] = strtoupper($attribs["STYLE"]);
			}
			$this->_cur_row = 0;
			$this->_cur_col = 0;
			$this->_cur_element = "TABLE";
			$this->_start_child("TABLE");
		}
		else {
			$this->_cur_elt_style["TABLE"] = false;
			$this->_end_child("TABLE");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_ROW($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (isset($attribs["INDEX"])) {
				if (preg_match("/^-|\+/",$attribs["INDEX"])) {
					$mult = (int) $attribs["INDEX"];
					$this->_cur_row += $mult;
				}
				else {
					$this->_cur_row = $attribs["INDEX"]-1;
				}
			}
			if (isset($attribs["STYLE"])) {
				$this->_cur_elt_style["ROW"] = strtoupper($attribs["STYLE"]);
			}
			if (isset($attribs["HEIGHT"])) {
				$this->sheets[$this->cur_sheet]->set_row_settings($this->_cur_row,$attribs["HEIGHT"],$this->_get_style());
			}
			$this->_cur_element = "ROW";
			$this->_start_child("ROW");
		}
		else {
			$this->_cur_elt_style["ROW"] = false;
			$this->_cur_row++;
			$this->_cur_col = 0;
			$this->_end_child("ROW");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_CELL($attribs,$EndOfTag=false) {
		$attr_check = 0;
		if (!$EndOfTag) {
			$this->_element_data = "";
			if (isset($attribs["INDEX"])) {
				if (preg_match("/^-|\+/",$attribs["INDEX"])) {
					$mult = (int) $attribs["INDEX"];
					$this->_cur_col += $mult;
				}
				else {
					$this->_cur_cell_index = $attribs["INDEX"];
				}
			}
			else { $this->_cur_cell_index = false; }
			if (isset($attribs["WIDTH"])) {
				if ($this->_cur_cell_index) {
					if (preg_match('/^\D/', $this->_cur_cell_index)) {
						list($row,$col) = $this->sheets[$this->cur_sheet]->_parce_notation($this->_cur_cell_index);
					}
					else {
						$col = (int) $this->_cur_cell_index;
					}
					$this->sheets[$this->cur_sheet]->set_column_width($col,$col,$attribs["WIDTH"]);
				}
				else {
					$this->sheets[$this->cur_sheet]->set_column_width($this->_cur_col,$this->_cur_col,$attribs["WIDTH"]);//,$this->_get_style());
				}
				$attr_check++;
			}
			if (isset($attribs["STYLE"])) {
				$this->_cur_elt_style["CELL"] = strtoupper($attribs["STYLE"]);
			}
			else if (sizeof($attribs) > $attr_check) {
				$this->_cur_elt_format["CELL"] =& $this->workbook->add_format($attribs);
			}
			$this->_cur_element = "CELL";
			$this->_start_child("CELL");
		}
		else {
			if ($this->_cur_cell_index) {
				$this->sheets[$this->cur_sheet]->write($this->_cur_cell_index,$this->_element_data,$this->_get_style());
			}
			else {$this->sheets[$this->cur_sheet]->write($this->_cur_row,$this->_cur_col,$this->_element_data,$this->_get_style());}
			$this->_element_data = "";
			$this->_cur_elt_style["CELL"] = false;
			$this->_cur_col++;
			$this->_end_child("CELL");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_PASSWORD($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			$this->_cur_element = "PASSWORD";
			$this->_start_child("PASSWORD");
		}
		else {
			$this->_end_child("PASSWORD");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_STYLES($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			$this->_cur_element = "STYLES";
			$this->_start_child("STYLES");
		}
		else {
			$this->_end_child("STYLES");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_STYLE($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if ($this->_cur_element == "STYLES") {
				if (!isset($attribs["NAME"])) {
					trigger_error("No style name was specified - style creation failed", E_USER_WARNING);
				}
				else {
					$style_name = strtoupper(array_shift($attribs));
					$this->_styles[$style_name] =& $this->workbook->add_format($attribs);
				}
			}
			$this->_cur_element = "STYLE";
			$this->_start_child("STYLE");
		}
		else {
			$this->_end_child("STYLE");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_PRINT($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			$this->_cur_element = "PRINT";
			$this->_start_child("PRINT");
		}
		else {
			$this->_end_child("PRINT");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_ORIENTATION($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (!isset($attribs["LANDSCAPE"])) { $this->sheets[$this->cur_sheet]->set_landscape(); }
			else { $this->sheets[$this->cur_sheet]->set_portrait(); }
			$this->_cur_element = "ORIENTATION";
			$this->_start_child("ORIENTATION");
		}
		else {
			$this->_end_child("ORIENTATION");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_HEADER($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (isset($attribs["MARGIN"])) {
				$this->h_margin = (double) $attribs["MARGIN"];
			}
			$this->_cur_element = "HEADER";
			$this->_start_child("HEADER");
		}
		else {
			$this->_end_child("HEADER");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_FOOTER($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (isset($attribs["MARGIN"])) {
				$this->f_margin = (double) $attribs["MARGIN"];
			}
			$this->_cur_element = "FOOTER";
			$this->_start_child("FOOTER");
		}
		else {
			$this->_end_child("FOOTER");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_PAPERSIZE($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (isset($attribs["INDEX"])) {
				$this->sheets[$this->cur_sheet]->set_paper_size((int) $attribs["INDEX"]);
			}
			$this->_cur_element = "PAPERSIZE";
			$this->_start_child("PAPERSIZE");
		}
		else {
			$this->_end_child("PAPERSIZE");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_MARGIN($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (isset($attribs["TOP"])) { $this->sheets[$this->cur_sheet]->set_margin_top((double) $attribs["TOP"]); }
			if (isset($attribs["RIGHT"])) { $this->sheets[$this->cur_sheet]->set_margin_right((double) $attribs["RIGHT"]); }
			if (isset($attribs["BOTTOM"])) { $this->sheets[$this->cur_sheet]->set_margin_bottom((double) $attribs["BOTTOM"]); }
			if (isset($attribs["LEFT"])) { $this->sheets[$this->cur_sheet]->set_margin_left((double) $attribs["LEFT"]); }
			$this->_cur_element = "MARGIN";
			$this->_start_child("MARGIN");
		}
		else {
			$this->_end_child("MARGIN");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_ROWTITLE($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (isset($attribs["START"])) { $start = (int) $attribs["START"]; }
			else { $start = 0;}
			if (isset($attribs["END"])) { $end = (int) $attribs["END"]; }
			else { $end = $start; }
			$this->sheets[$this->cur_sheet]->repeat_rows(--$start,--$end);
			$this->_cur_element = "ROWTITLE";
			$this->_start_child("ROWTITLE");
		}
		else {
			$this->_end_child("ROWTITLE");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_COLTITLE($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			if (isset($attribs["START"])) { $start = (int) $attribs["START"]; }
			else { $start = 0;}
			if (isset($attribs["END"])) { $end = (int) $attribs["END"]; }
			else { $end = $start; }
			$this->sheets[$this->cur_sheet]->repeat_columns(--$start,--$end);
			$this->_cur_element = "COLTITLE";
			$this->_start_child("COLTITLE");
		}
		else {
			$this->_end_child("COLTITLE");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _int_TEMPLATE($attribs,$EndOfTag=false) {
		if (!$EndOfTag) {
			$this->_cur_element = "TEMPLATE";
			$this->_start_child("TEMPLATE");
		}
		else {
			$this->_end_child("TEMPLATE");
		}
	} // end of function
// ------------------------------------------------------------------------------------------------


} // END OF CLASS
// ------------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------

$xls_wrap = new xls_wrap;

// ------------------------------------------------------------------------------------------------




//-------------------------------------------------------------------------------------------------
	function new_xml_parser($file) {
		global $xls_wrap;
		global $parser_file;
		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
		xml_parser_set_option($xml_parser, XML_OPTION_TARGET_ENCODING, 'ISO-8859-1');
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "characterData");
		xml_set_processing_instruction_handler($xml_parser, "PIHandler");
		xml_set_unparsed_entity_decl_handler($xml_parser, "test_ent");
		xml_set_default_handler($xml_parser, "defaultHandler");
		xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler");
		if ($file == "") { return false; }
		if (!is_array($parser_file)) { settype($parser_file, "array"); }
		$parser_file[$xml_parser] = $file;
		return $xml_parser;
	} // end of function
//-------------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------------
	function startElement($parser, $name, $attribs) {
		global $xls_wrap;
		if ($xls_wrap->_debug) {
			echo "[start_el] <b>".$name."</b>"; // tag name
			if (sizeof($attribs)) {
				echo " {";
				while (list($k, $v) = each($attribs)) {
					print "<font color=\"#00ff00\">$k</font>=\"<font color=\"#990000\">$v</font>\"";
				}
				echo "}";
			}
			reset($attribs);
		}
		if (sizeof($attribs)) {
			reset($attribs);
			
			foreach ($attribs as $k => $v){ 
			//while (list($k, $v) = each($attribs)) {
				$ar_tmp[$k] = ($k!="NUM_FORMAT"&&$k!="NAME") ? strtoupper($v) : $v;
		      }
			$attribs=$ar_tmp;
			reset($attribs);
		}
		switch ($name) {
			case "WORKBOOK":		$xls_wrap->_int_WORKBOOK($attribs);			break;
			case "WORKSHEET":		$xls_wrap->_int_WORKSHEET($attribs);		break;
			case "PRINT":			$xls_wrap->_int_PRINT($attribs);				break;
			case "PAPERSIZE":		$xls_wrap->_int_PAPERSIZE($attribs);		break;
			case "MARGIN":			$xls_wrap->_int_MARGIN($attribs);			break;
			case "ORIENTATION":	$xls_wrap->_int_ORIENTATION($attribs);		break;
			case "HEADER":			$xls_wrap->_int_HEADER($attribs);			break;
			case "FOOTER":			$xls_wrap->_int_FOOTER($attribs);			break;
			case "PASSWORD":		$xls_wrap->_int_PASSWORD($attribs);			break;
			case "STYLES":			$xls_wrap->_int_STYLES($attribs);			break;
			case "STYLE":			$xls_wrap->_int_STYLE($attribs);				break;
			case "TABLE":			$xls_wrap->_int_TABLE($attribs);				break;
			case "ROW":				$xls_wrap->_int_ROW($attribs);				break;
			case "CELL":			$xls_wrap->_int_CELL($attribs);				break;
			case "ROWTITLE":		$xls_wrap->_int_ROWTITLE($attribs);			break;
			case "COLTITLE":		$xls_wrap->_int_COLTITLE($attribs);			break;
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
	function endElement($parser, $name) {
		$attribs = array();
		global $xls_wrap;
		if ($xls_wrap->_debug) {
			echo "[end_el] <b>".$name."</b>\n"; // tag name
		}
		switch ($name) {
			case "WORKBOOK":		$xls_wrap->_int_WORKBOOK($attribs,1);		break;
			case "WORKSHEET":		$xls_wrap->_int_WORKSHEET($attribs,1);		break;
			case "PRINT":			$xls_wrap->_int_PRINT($attribs,1);			break;
			case "PAPERSIZE":		$xls_wrap->_int_PAPERSIZE($attribs,1);		break;
			case "MARGIN":			$xls_wrap->_int_MARGIN($attribs,1);			break;
			case "ORIENTATION":	$xls_wrap->_int_ORIENTATION($attribs,1);	break;
			case "HEADER":			$xls_wrap->_int_HEADER($attribs,1);			break;
			case "FOOTER":			$xls_wrap->_int_FOOTER($attribs,1);			break;
			case "PASSWORD":		$xls_wrap->_int_PASSWORD($attribs,1);		break;
			case "STYLES":			$xls_wrap->_int_STYLES($attribs,1);			break;
			case "STYLE":			$xls_wrap->_int_STYLE($attribs,1);			break;
			case "TABLE":			$xls_wrap->_int_TABLE($attribs,1);			break;
			case "ROW":				$xls_wrap->_int_ROW($attribs,1);				break;
			case "CELL":			$xls_wrap->_int_CELL($attribs,1);			break;
			case "ROWTITLE":		$xls_wrap->_int_ROWTITLE($attribs,1);		break;
			case "COLTITLE":		$xls_wrap->_int_COLTITLE($attribs,1);		break;
		}
	} // end of function
//-------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
	function characterData($parser, $data) { // returns plain text
		global $xls_wrap;
		if ($xls_wrap->_debug) {
			echo "[plain_text]<b>".$data."</b>[[".$xls_wrap->_cur_element."]/plain_text]"; // tag name
		}

		$data = preg_replace("/##amp##/msi","&",$data);
		$data = preg_replace("/&NOW/msi",Unix2Excel(),$data);
		$data = unhtmlentities($data);

		$xls_wrap->_element_data .= $data;
		$xls_wrap->plain_text($data);
	} // end of function
//-------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
	function PIHandler($parser, $target, $data) {
		global $xls_wrap;
		switch (strtolower($target)) {
			case "php":
				global $parser_file;
				if (trustedFile($parser_file[$parser])) {
					//eval($data);
				}
				else {
					//printf("Untrusted PHP code: <i>%s</i>",htmlspecialchars($data));
				}
				break;
		}
	} // end of function
//-------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
	function defaultHandler($parser, $data) {
		global $xls_wrap;
		if (substr($data, 0, 1) == "&" && substr($data, -1, 1) == ";") {
			//printf('[def_handler_1]<font color="#aa00aa">%s[d]</font>',htmlspecialchars($data));
		}
		else {
			//printf('[def_handler_2]<font size="-1">%s</font>',htmlspecialchars($data));
		}
	} // end of function
//-------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
	function externalEntityRefHandler($parser, $openEntityNames, $base, $systemId,$publicId) {
		global $xls_wrap;
		if ($systemId) {
			if (!list($parser, $fp) = new_xml_parser($systemId)) {
					//printf("Could not open entity %s at %s\n", $openEntityNames,$systemId);
					return false;
			}
			while ($data = fread($fp, 4096)) {
					if (!xml_parse($parser, $data, feof($fp))) {
						printf("XML error: %s at line %d while parsing entity %s\n",
								xml_error_string(xml_get_error_code($parser)),
								xml_get_current_line_number($parser), $openEntityNames);
						xml_parser_free($parser);
						return false;
					}
			}
			xml_parser_free($parser);
			return true;
		}
		return false;
	} // end of function
//-------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function test_ent ($parser, $entityName, $base, $systemId, $publicId, $notationName) {
		global $xls_wrap;
	} // end of function
// ------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
	function trustedFile($file) {
		global $xls_wrap;
		// only trust local files owned by ourselves  - ^([a-z]+)://
		if (!preg_match("/^([a-z]+):\/\//", $file)
			&& fileowner($file) == getmyuid()) {
			return true;
		}
		return false;
	} // end of function
//-------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------
	function unhtmlentities($string) {
		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);
		$ret = strtr ($string, $trans_tbl);
		//return preg_replace('/&#(\d+);/me',"chr('\\1')",$ret);
		
		return preg_replace_callback('/&#(\d+);/',
                    function($matches) {
                        foreach($matches as $match) {
                            return "chr('\\1')";
                        }
                    }, $ret
                );
	} // end of function
//-------------------------------------------------------------------------------------------------
?>
