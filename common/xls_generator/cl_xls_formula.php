<?php 

//		XLS Generator Class
//		(c) 2007 Paggard [paggard@paggard.com]
//		18 March 2007 - build 1.4.1

// ------------------------------------------------------------------------------------------------
class formula {

// ------------------------------------------------------------------------------------------------
	function __construct($byte_order = 0) {
		GLOBAL $TOKENS;
		$this->_tokens =& $TOKENS;
		
		$this->_current_char = 0;
		$this->_current_token = "";
		$this->_formula = "";
		$this->_next = "";
		$this->_parse_tree = "";
		
		$this->_init(); // functions definitions initialization
		
		$this->_byte_order = $byte_order;
		$this->_func_args = 0;
		//	Microsoft Excel usually calculates a function only when it is entered into a cell, 
		//	when one of its precedents changes, or when the cell is calculated during a macro. 
		//	On a worksheet, a function can be volatile, which means that it recalculates 
		//	every time the worksheet recalculates.
		$this->_volatile = 0;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function prepare_formula($formula) {
		$formula = strtoupper($formula);
		$this->_current_char = 0;
		$this->_formula = $formula;
		$this->_next = $formula{1};
		$this->_go_to_the_next_token();
		$this->_parse_tree = $this->_expression();
		return $this->get_RPN();
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
/*
	The tokens of a formula are stored in the Reverse-Polish Notation (RPN).
	This means, first occur all operands of an operation, followed by the respective operator.
	For instance the simple term 1+2 consists of the 3 tokens “1”, “+” and “2”.
	Written in RPN the formula is converted to the token list “1”, “2”, “+”.
	During parsing such an expression, operands are pushed onto a stack. An operator pops
	the needed number of operands from stack, performs the operation and pushes the result
	back onto the stack.
	EXAMPLES:
	Formula		Token array			Parsing result
	2*4+5			2,4,*,5,+			First, 2 and 4 are pushed onto the stack. The * operator
											pops them from the stack and pushes 8. Then the value 5 is pushed.
											The + operator pops 5 and 8 and pushes 40 (the result).
	2+4*5			2,4,5,*,+			First, 2, 4, and 5 are pushed onto the stack. The * operator
											pops 5 and 4 and pushes 20, the + operator pops 20 and 2 and
											pushes 22 (the result).
*/
	function get_RPN($tree = array()) {
		$RPN = "";
		if (empty($tree)) { // first call
			$tree = $this->_parse_tree;
		}
		if (is_array($tree["left"])) {
			$RPN .= $this->get_RPN($tree["left"]);
		}
		else if($tree["left"] != "") { // final node
			$RPN .= $this->_convert($tree["left"]);
		}
		if (is_array($tree["right"])) {
			$RPN .= $this->get_RPN($tree["right"]);
		}
		else if($tree["right"] != "") { // final node
			$RPN .= $this->_convert($tree["right"]);
		}
		$RPN .= $this->_convert($tree["value"]);
		return $RPN;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _convert($token) {
		if(is_numeric($token)) {
			return($this->_convert_number($token));
		}
		// match references like A1
		else if(preg_match("/^([A-I]?[A-Z])(\d+)$/",$token)) {
			return($this->_convert_ref2d($token));
		}
		// match ranges like A1:B2
		else if(preg_match("/^([A-I]?[A-Z])(\d+)\:([A-I]?[A-Z])(\d+)$/",$token)) {
			return($this->_convert_range2d($token));
		}
		// match ranges like A1..B2
		else if(preg_match("/^([A-I]?[A-Z])(\d+)\.\.([A-I]?[A-Z])(\d+)$/",$token)) {
			return($this->_convert_range2d($token));
		}
		// operators (including parentheses)
		else if(isset($this->_tokens[$token])) {
			return(pack("C", $this->_tokens[$token]));
		}
		else if(preg_match("/[A-Z0-9À-Ü\.]+/",$token)) {
			return($this->_convert_function($token,$this->_func_args));
		}
		// if it's an argument, ignore the token (the argument remains)
		else if($token == "arg") {
			$this->_func_args++;
			return "";
		}
		trigger_error("Invalid token $token", E_USER_ERROR);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _convert_number($num) {
		// integer
		if ((preg_match("/^\d+$/",$num)) and ($num <= 65535)) {
			return pack("Cv", $this->_tokens['tInt'], $num);
		}
		// float
		else {
			if($this->_byte_order) { $num = strrev($num); }
			return pack("Cd", $this->_tokens['tNum'], $num);
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _convert_function($token, $num_args) {
		$this->_func_args = 0;
		$args	 = $this->_functions[$token][1];
		$volatile = $this->_functions[$token][3];

		if($volatile) {
			$this->_volatile = 1;
		}
		if ($args >= 0) { // fixed number of args
			return(pack("Cv", $this->_tokens["tFuncV"], $this->_functions[$token][0]));
		}
		if ($args == -1) { // variable number of args
			return(pack("CCv", $this->_tokens["tFuncVarV"], $num_args, $this->_functions[$token][0]));
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _convert_range2d($range) {
		$class = 2; // as far as I know, this is magick.

		if(preg_match("/^([A-I]?[A-Z])(\d+)\:([A-I]?[A-Z])(\d+)$/",$range)) {
			list($cell1, $cell2) = split(':', $range);
		}
		else if(preg_match("/^([A-I]?[A-Z])(\d+)\.\.([A-I]?[A-Z])(\d+)$/",$range)) {
			list($cell1, $cell2) = split('\.\.', $range);
		}
		else {
			trigger_error("Invalid range format [$range]", E_USER_ERROR);
		}

		list($row1, $col1) = $this->_pack_cell_address($cell1);
		list($row2, $col2) = $this->_pack_cell_address($cell2);

		if ($class == 0) {
			$ptgArea = pack("C", $this->_tokens['tAreaR']);
		}
		else if ($class == 1) {
			$ptgArea = pack("C", $this->_tokens['tAreaV']);
		}
		else if ($class == 2) {
			$ptgArea = pack("C", $this->_tokens['tAreaA']);
		}
		else{
			trigger_error("Invalid function class", E_USER_ERROR);
		}

		return($ptgArea.$row1.$row2.$col1.$col2);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _convert_ref2d($cell) {
		$class = 2; // 2do - predefine class to work with VALUE

		list($row, $col) = $this->_pack_cell_address($cell);

		if ($class == 0) {
			$tRef = pack("C", $this->_tokens['tRefR']);
		}
		else if ($class == 1) {
			$tRef = pack("C", $this->_tokens['tRefV']);
		}
		else if ($class == 2) {
			$tRef = pack("C", $this->_tokens['tRefA']);
		}
		else {
			trigger_error("Invalid function class", E_USER_ERROR);
		}
		return $tRef.$row.$col;
	} // end of function
// ------------------------------------------------------------------------------------------------


// ------------------------------------------------------------------------------------------------
	function _pack_cell_address($cell) {
		list($row, $col, $row_rel, $col_rel) = $this->_parce_notation($cell);
		if ($col >= 256) {
			trigger_error("Column index in $cell exceeds the max allowed 255", E_USER_ERROR);
		}
		if ($row >= 16384) {
			trigger_error("Row index in $cell exceeds the max allowed 16384", E_USER_ERROR);
		}

		$row |= $col_rel << 14;
		$row |= $row_rel << 15;

		$row = pack('v', $row);
		$col = pack('C', $col);

		return (array($row, $col));
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _parce_notation($cell) {
		preg_match('/(\$)?([A-I]?[A-Z])(\$)?(\d+)/',$cell,$match);

		$col_rel = empty($match[1]) ? 1 : 0;
		$col_ref = $match[2];
		$row_rel = empty($match[3]) ? 1 : 0;
		$row = $match[4];

		$expn = strlen($col_ref) - 1;
		$col = 0;
		for($i=0; $i<strlen($col_ref); $i++) {
			$col += (ord($col_ref{$i}) - ord('A') + 1) * pow(26, $expn);
			$expn--;
		}
		$row--; $col--;
		return(array($row, $col, $row_rel, $col_rel));
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _match($token) {
		switch($token) {
			case ADD:   return $token; break;
			case SUB:   return $token; break;
			case MUL:   return $token; break;
			case DIV:   return $token; break;
			case OPEN:  return $token; break;
			case CLOSE: return $token; break;
			case COMA:  return $token; break;
			case SEMICOL:  return $token; break;
			case MORE:  //return $token; break;
				if($this->_next != EQUAL) { return $token; }
				else { return ""; }
				break;
			case LESS:  //return $token; break;
				if($this->_next != EQUAL && $this->_next != MORE) { return $token; }
				else { return ""; }
				break;
			case EQUAL:  //return $token; break;
			case MOREEQ:  return $token; break;
			case LESSEQ:  return $token; break;
			case NOTEQUAL:  return $token; break;

			default:
				// reference
				//if(eregi("^[A-I]?[A-Z][0-9]+$",$token) && !ereg("[0-9]",$this->_next) && ($this->_next != ':') && ($this->_next != '.')) {
				if(preg_match("/^[A-I]?[A-Z][0-9]+$/i",$token) && 
					!preg_match("/[0-9]/",$this->_next) && ($this->_next != ":") && ($this->_next != ".")) {
					return $token;
				}
				// range (A1:A2)
				//else if(eregi("^[A-I]?[A-Z][0-9]+:[A-I]?[A-Z][0-9]+$",$token) && !ereg("[0-9]",$this->_next)) {
				else if(preg_match("/^[A-I]?[A-Z][0-9]+:[A-I]?[A-Z][0-9]+$/i",$token) && !preg_match("/[0-9]/",$this->_next)) {
					return($token);
				}
				// range (A1..A2)
				//elseif(eregi("^[A-I]?[A-Z][0-9]+\.\.[A-I]?[A-Z][0-9]+$",$token) && !ereg("[0-9]",$this->_next)) {
				elseif(preg_match("/^[A-I]?[A-Z][0-9]+\.\.[A-I]?[A-Z][0-9]+$/i",$token) && !preg_match("/[0-9]/",$this->_next)) {
					return $token;
				}
				else if(is_numeric($token) && !is_numeric($token.$this->_next)) {
					return($token);
				}
				// function call
				//elseif(eregi("^[A-Z0-9À-Ü\.]+$",$token) && ($this->_next == "(")) {
				else if(preg_match("/^[A-Z0-9À-Ü\.]+$/i",$token) && ($this->_next == "(")) {
					return $token;
				}
				else if($token == "=" && $this->_next != LESS) {
					return $token;
				}
				else if($token == "=" && $this->_next != MORE) {
					return $token;
				}
				return "";
		} // end of switch
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _go_to_the_next_token() {
		$token = "";
		$i = $this->_current_char;
		if($i < strlen($this->_formula)) {
			while($this->_formula{$i} == " ") {
				$i++;
			}
			if($i < strlen($this->_formula) - 1) {
					$this->_next = $this->_formula{$i+1};
			}
			$token = "";
		}
		while($i < strlen($this->_formula)) {
			$token .= $this->_formula{$i};
			if($this->_match($token) != "") {
				if($i < strlen($this->_formula) - 1) {
					$this->_next = $this->_formula{$i+1};
				}
				$this->_current_char = $i + 1;
				$this->_current_token = $token;
				return(1);
			}
			$this->_next = $this->_formula{$i+2};
			$i++;
		}
		$this->_current_token = $token;
		return(1);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _expression() {
		$result = $this->_term();
		while ($this->_current_token == ADD || $this->_current_token == SUB) {
			if ($this->_current_token == ADD) {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tAdd', $result, $this->_term());
			}
			else {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tSub', $result, $this->_term());
			}
		}
		return $result;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _paren_expression() {
		$result = $this->_create_tree('tParen', $this->_expression(), "");
		return($result);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _term() {
//		$result = $this->_factor();
		$result = $this->_comp_strict();
		while ($this->_current_token == MUL || $this->_current_token == DIV) {
			if ($this->_current_token == MUL) {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tMul', $result, $this->_factor());
			}
			else {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tDiv', $result, $this->_factor());
			}
		}
		return($result);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _comp_strict() {
		$result = $this->_comp_ease();

		while ($this->_current_token == MORE || $this->_current_token == LESS) {
			if ($this->_current_token == MORE) {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tGT', $result, $this->_factor());
			}
			else {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tLT', $result, $this->_factor());
			}
		}
		return($result);
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _comp_ease() {
		$result = $this->_comp_equal();
		while ($this->_current_token == MOREEQ || $this->_current_token == LESSEQ) {
			if ($this->_current_token == MOREEQ) {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tGE', $result, $this->_factor());
			}
			else {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tLE', $result, $this->_factor());
			}
		}
		return($result);
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
	function _comp_equal() {
		$result = $this->_factor();
		while ($this->_current_token == EQUAL || $this->_current_token == NOTEQUAL) {
			if ($this->_current_token == EQUAL) {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tEQ', $result, $this->_factor());
			}
			else {
				$this->_go_to_the_next_token();
				$result = $this->_create_tree('tNE', $result, $this->_factor());
			}
		}
		return($result);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _factor() {
		if ($this->_current_token == OPEN) {
			$this->_go_to_the_next_token();
			$result = $this->_paren_expression();

			if ($this->_current_token != CLOSE) {
				trigger_error("Format error: ')' expected [".$this->_current_token."]", E_USER_ERROR);
			}
			$this->_go_to_the_next_token();
			return $result;
		}
		//if (eregi("^[A-I]?[A-Z][0-9]+$",$this->_current_token)) {
		if (preg_match("/^[A-I]?[A-Z][0-9]+$/i",$this->_current_token)) { // reference
			$result = $this->_create_tree($this->_current_token, "", "");
			$this->_go_to_the_next_token();
			return $result;
		}
		//else if (eregi("^[A-I]?[A-Z][0-9]+:[A-I]?[A-Z][0-9]+$",$this->_current_token) ||
		//		eregi("^[A-I]?[A-Z][0-9]+\.\.[A-I]?[A-Z][0-9]+$",$this->_current_token)) {
		else if (preg_match("/^[A-I]?[A-Z][0-9]+:[A-I]?[A-Z][0-9]+$/i",$this->_current_token) ||
					preg_match("/^[A-I]?[A-Z][0-9]+\.\.[A-I]?[A-Z][0-9]+$/i",$this->_current_token)) { // range
			$result = $this->_current_token;
			$this->_go_to_the_next_token();
			return $result ;
		}
		else if (is_numeric($this->_current_token)) {
			$result = $this->_create_tree($this->_current_token, "", "");
			$this->_go_to_the_next_token();
			return $result;
		}
		//elseif (eregi("^[A-Z0-9À-Ü\.]+$",$this->_current_token)) {
		else if (preg_match("/^[A-Z0-9À-Ü\.]+$/i",$this->_current_token)) { // function call
			$result = $this->_function_call();
			return $result;
		}
		trigger_error("Format error: ".$this->_current_token.", next char: ".$this->_next.", current char: ".$this->_current_char, E_USER_ERROR);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _function_call() {
		$result = "";
		$num_args = 0;
		$function = $this->_current_token;
		$this->_go_to_the_next_token();
		$this->_go_to_the_next_token();
		while($this->_current_token != ")") {
			if($num_args > 0) {
				if($this->_current_token == COMA || $this->_current_token == SEMICOL
					) {
					$this->_go_to_the_next_token();
				}
				else {
					trigger_error("Format error: coma expected [".$this->_current_token."] $num_args", E_USER_ERROR);
				}
				$result = $this->_create_tree("arg", $result, $this->_expression());
			}
			else {
				$result = $this->_create_tree("arg", "", $this->_expression());
			}
			$num_args++;
		}
		$args = $this->_functions[$function][1];
		if (($args >= 0) && ($args != $num_args)) {
			trigger_error("Incorrect number of arguments [$args but should be $num_args] in function $function() ", E_USER_ERROR);
		}
		$result = $this->_create_tree($function, $result, "");
		$this->_go_to_the_next_token();
		return $result ;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _create_tree($value, $left, $right) {
		return array("value" => $value, "left" => $left, "right" => $right);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _init() {
		// The array elements are as follow:
		// INDEX:   Index of the function to be recognozed by Excel
		// ARGS:    Number of function arguments 
		//				>=0 fixed number of arguments
		//				-1  variable number of arguments.
		// ARGTYPE: Type of the function argumants
		//				0 - reference
		//				1 - value
		//				2 - array
		// TYPE:    Type of the function [1 - volatile].
		//
		$this->_functions = array(
	/* FUNCTION NAME						INDEX		ARGS		ARGTYPE	TYPE	*/
		"COUNT"				=> array(	0,			-1,		0,			0 ),
		"IF"					=> array(	1,			-1,		1,			0 ),
		"ISNA"				=> array(	2,			1,			1,			0 ),
		"ISERROR"			=> array(	3,			1,			1,			0 ),
		"SUM"					=> array(	4,			-1,		0,			0 ),
		"AVERAGE"			=> array(	5,			-1,		0,			0 ),
		"MIN"					=> array(	6,			-1,		0,			0 ),
		"MAX"					=> array(	7,			-1,		0,			0 ),
		"ROW"					=> array(	8,			-1,		0,			0 ),
		"COLUMN"				=> array(	9,			-1,		0,			0 ),
		"NA"					=> array(	10,		0,			0,			0 ),
		"NPV"					=> array(	11,		-1,		1,			0 ),
		"STDEV"				=> array(	12,		-1,		0,			0 ),
		"DOLLAR"				=> array(	13,		-1,		1,			0 ),
		"FIXED"				=> array(	14,		-1,		1,			0 ),
		"SIN"					=> array(	15,		1,			1,			0 ),
		"COS"					=> array(	16,		1,			1,			0 ),
		"TAN"					=> array(	17,		1,			1,			0 ),
		"ATAN"				=> array(	18,		1,			1,			0 ),
		"PI"					=> array(	19,		0,			1,			0 ),
		"SQRT"				=> array(	20,		1,			1,			0 ),
		"EXP"					=> array(	21,		1,			1,			0 ),
		"LN"					=> array(	22,		1,			1,			0 ),
		"LOG10"				=> array(	23,		1,			1,			0 ),
		"ABS"					=> array(	24,		1,			1,			0 ),
		"INT"					=> array(	25,		1,			1,			0 ),
		"SIGN"				=> array(	26,		1,			1,			0 ),
		"ROUND"				=> array(	27,		2,			1,			0 ),
		"LOOKUP"				=> array(	28,		-1,		0,			0 ),
		"INDEX"				=> array(	29,		-1,		0,			1 ),
		"REPT"				=> array(	30,		2,			1,			0 ),
		"MID"					=> array(	31,		3,			1,			0 ),
		"LEN"					=> array(	32,		1,			1,			0 ),
		"VALUE"				=> array(	33,		1,			1,			0 ),
		"TRUE"				=> array(	34,		0,			1,			0 ),
		"FALSE"				=> array(	35,		0,			1,			0 ),
		"AND"					=> array(	36,		-1,		0,			0 ),
		"OR"					=> array(	37,		-1,		0,			0 ),
		"NOT"					=> array(	38,		1,			1,			0 ),
		"MOD"					=> array(	39,		2,			1,			0 ),
		"DCOUNT"				=> array(	40,		3,			0,			0 ),
		"DSUM"				=> array(	41,		3,			0,			0 ),
		"DAVERAGE"			=> array(	42,		3,			0,			0 ),
		"DMIN"				=> array(	43,		3,			0,			0 ),
		"DMAX"				=> array(	44,		3,			0,			0 ),
		"DSTDEV"				=> array(	45,		3,			0,			0 ),
		"VAR"					=> array(	46,		-1,		0,			0 ),
		"DVAR"				=> array(	47,		3,			0,			0 ),
		"TEXT"				=> array(	48,		2,			1,			0 ),
		"LINEST"				=> array(	49,		-1,		0,			0 ),
		"TREND"				=> array(	50,		-1,		0,			0 ),
		"LOGEST"				=> array(	51,		-1,		0,			0 ),
		"GROWTH"				=> array(	52,		-1,		0,			0 ),
		"PV"					=> array(	56,		-1,		1,			0 ),
		"FV"					=> array(	57,		-1,		1,			0 ),
		"NPER"				=> array(	58,		-1,		1,			0 ),
		"PMT"					=> array(	59,		-1,		1,			0 ),
		"RATE"				=> array(	60,		-1,		1,			0 ),
		"MIRR"				=> array(	61,		3,			0,			0 ),
		"IRR"					=> array(	62,		-1,		0,			0 ),
		"RAND"				=> array(	63,		0,			1,			1 ),
		"MATCH"				=> array(	64,		-1,		0,			0 ),
		"DATE"				=> array(	65,		3,			1,			0 ),
		"TIME"				=> array(	66,		3,			1,			0 ),
		"DAY"					=> array(	67,		1,			1,			0 ),
		"MONTH"				=> array(	68,		1,			1,			0 ),
		"YEAR"				=> array(	69,		1,			1,			0 ),
		"WEEKDAY"			=> array(	70,		-1,		1,			0 ),
		"HOUR"				=> array(	71,		1,			1,			0 ),
		"MINUTE"				=> array(	72,		1,			1,			0 ),
		"SECOND"				=> array(	73,		1,			1,			0 ),
		"NOW"					=> array(	74,		0,			1,			1 ),
		"AREAS"				=> array(	75,		1,			0,			1 ),
		"ROWS"				=> array(	76,		1,			0,			1 ),
		"COLUMNS"			=> array(	77,		1,			0,			1 ),
		"OFFSET"				=> array(	78,		-1,		0,			1 ),
		"SEARCH"				=> array(	82,		-1,		1,			0 ),
		"TRANSPOSE"			=> array(	83,		1,			1,			0 ),
		"TYPE"				=> array(	86,		1,			1,			0 ),
		"ATAN2"				=> array(	97,		2,			1,			0 ),
		"ASIN"				=> array(	98,		1,			1,			0 ),
		"ACOS"				=> array(	99,		1,			1,			0 ),
		"CHOOSE"				=> array(	100,		-1,		1,			0 ),
		"HLOOKUP"			=> array(	101,		-1,		0,			0 ),
		"VLOOKUP"			=> array(	102,		-1,		0,			0 ),
		"ISREF"				=> array(	105,		1,			0,			0 ),
		"LOG"					=> array(	109,		-1,		1,			0 ),
		"CHAR"				=> array(	111,		1,			1,			0 ),
		"LOWER"				=> array(	112,		1,			1,			0 ),
		"UPPER"				=> array(	113,		1,			1,			0 ),
		"PROPER"				=> array(	114,		1,			1,			0 ),
		"LEFT"				=> array(	115,		-1,		1,			0 ),
		"RIGHT"				=> array(	116,		-1,		1,			0 ),
		"EXACT"				=> array(	117,		2,			1,			0 ),
		"TRIM"				=> array(	118,		1,			1,			0 ),
		"REPLACE"			=> array(	119,		4,			1,			0 ),
		"SUBSTITUTE"		=> array(	120,		-1,		1,			0 ),
		"CODE"				=> array(	121,		1,			1,			0 ),
		"FIND"				=> array(	124,		-1,		1,			0 ),
		"CELL"				=> array(	125,		-1,		0,			1 ),
		"ISERR"				=> array(	126,		1,			1,			0 ),
		"ISTEXT"				=> array(	127,		1,			1,			0 ),
		"ISNUMBER"			=> array(	128,		1,			1,			0 ),
		"ISBLANK"			=> array(	129,		1,			1,			0 ),
		"T"					=> array(	130,		1,			0,			0 ),
		"N"					=> array(	131,		1,			0,			0 ),
		"DATEVALUE"			=> array(	140,		1,			1,			0 ),
		"TIMEVALUE"			=> array(	141,		1,			1,			0 ),
		"SLN"					=> array(	142,		3,			1,			0 ),
		"SYD"					=> array(	143,		4,			1,			0 ),
		"DDB"					=> array(	144,		-1,		1,			0 ),
		"INDIRECT"			=> array(	148,		-1,		1,			1 ),
		"CALL"				=> array(	150,		-1,		1,			0 ),
		"CLEAN"				=> array(	162,		1,			1,			0 ),
		"MDETERM"			=> array(	163,		1,			2,			0 ),
		"MINVERSE"			=> array(	164,		1,			2,			0 ),
		"MMULT"				=> array(	165,		2,			2,			0 ),
		"IPMT"				=> array(	167,		-1,		1,			0 ),
		"PPMT"				=> array(	168,		-1,		1,			0 ),
		"COUNTA"				=> array(	169,		-1,		0,			0 ),
		"PRODUCT"			=> array(	183,		-1,		0,			0 ),
		"FACT"				=> array(	184,		1,			1,			0 ),
		"DPRODUCT"			=> array(	189,		3,			0,			0 ),
		"ISNONTEXT"			=> array(	190,		1,			1,			0 ),
		"STDEVP"				=> array(	193,		-1,		0,			0 ),
		"VARP"				=> array(	194,		-1,		0,			0 ),
		"DSTDEVP"			=> array(	195,		3,			0,			0 ),
		"DVARP"				=> array(	196,		3,			0,			0 ),
		"TRUNC"				=> array(	197,		-1,		1,			0 ),
		"ISLOGICAL"			=> array(	198,		1,			1,			0 ),
		"DCOUNTA"			=> array(	199,		3,			0,			0 ),
		"ROUNDUP"			=> array(	212,		2,			1,			0 ),
		"ROUNDDOWN"			=> array(	213,		2,			1,			0 ),
		"RANK"				=> array(	216,		-1,		0,			0 ),
		"ADDRESS"			=> array(	219,		-1,		1,			0 ),
		"DAYS360"			=> array(	220,		-1,		1,			0 ),
		"TODAY"				=> array(	221,		0,			1,			1 ),
		"VDB"					=> array(	222,		-1,		1,			0 ),
		"MEDIAN"				=> array(	227,		-1,		0,			0 ),
		"SUMPRODUCT"		=> array(	228,		-1,		2,			0 ),
		"SINH"				=> array(	229,		1,			1,			0 ),
		"COSH"				=> array(	230,		1,			1,			0 ),
		"TANH"				=> array(	231,		1,			1,			0 ),
		"ASINH"				=> array(	232,		1,			1,			0 ),
		"ACOSH"				=> array(	233,		1,			1,			0 ),
		"ATANH"				=> array(	234,		1,			1,			0 ),
		"DGET"				=> array(	235,		3,			0,			0 ),
		"INFO"				=> array(	244,		1,			1,			1 ),
		"DB"					=> array(	247,		-1,		1,			0 ),
		"FREQUENCY"			=> array(	252,		2,			0,			0 ),
		"ERROR.TYPE"		=> array(	261,		1,			1,			0 ),
		"REGISTER.ID"		=> array(	267,		-1,		1,			0 ),
		"AVEDEV"				=> array(	269,		-1,		0,			0 ),
		"BETADIST"			=> array(	270,		-1,		1,			0 ),
		"GAMMALN"			=> array(	271,		1,			1,			0 ),
		"BETAINV"			=> array(	272,		-1,		1,			0 ),
		"BINOMDIST"			=> array(	273,		4,			1,			0 ),
		"CHIDIST"			=> array(	274,		2,			1,			0 ),
		"CHIINV"				=> array(	275,		2,			1,			0 ),
		"COMBIN"				=> array(	276,		2,			1,			0 ),
		"CONFIDENCE"		=> array(	277,		3,			1,			0 ),
		"CRITBINOM"			=> array(	278,		3,			1,			0 ),
		"EVEN"				=> array(	279,		1,			1,			0 ),
		"EXPONDIST"			=> array(	280,		3,			1,			0 ),
		"FDIST"				=> array(	281,		3,			1,			0 ),
		"FINV"				=> array(	282,		3,			1,			0 ),
		"FISHER"				=> array(	283,		1,			1,			0 ),
		"FISHERINV"			=> array(	284,		1,			1,			0 ),
		"FLOOR"				=> array(	285,		2,			1,			0 ),
		"GAMMADIST"			=> array(	286,		4,			1,			0 ),
		"GAMMAINV"			=> array(	287,		3,			1,			0 ),
		"CEILING"			=> array(	288,		2,			1,			0 ),
		"HYPGEOMDIST"		=> array(	289,		4,			1,			0 ),
		"LOGNORMDIST"		=> array(	290,		3,			1,			0 ),
		"LOGINV"				=> array(	291,		3,			1,			0 ),
		"NEGBINOMDIST"		=> array(	292,		3,			1,			0 ),
		"NORMDIST"			=> array(	293,		4,			1,			0 ),
		"NORMSDIST"			=> array(	294,		1,			1,			0 ),
		"NORMINV"			=> array(	295,		3,			1,			0 ),
		"NORMSINV"			=> array(	296,		1,			1,			0 ),
		"STANDARDIZE"		=> array(	297,		3,			1,			0 ),
		"ODD"					=> array(	298,		1,			1,			0 ),
		"PERMUT"				=> array(	299,		2,			1,			0 ),
		"POISSON"			=> array(	300,		3,			1,			0 ),
		"TDIST"				=> array(	301,		3,			1,			0 ),
		"WEIBULL"			=> array(	302,		4,			1,			0 ),
		"SUMXMY2"			=> array(	303,		2,			2,			0 ),
		"SUMX2MY2"			=> array(	304,		2,			2,			0 ),
		"SUMX2PY2"			=> array(	305,		2,			2,			0 ),
		"CHITEST"			=> array(	306,		2,			2,			0 ),
		"CORREL"				=> array(	307,		2,			2,			0 ),
		"COVAR"				=> array(	308,		2,			2,			0 ),
		"FORECAST"			=> array(	309,		3,			2,			0 ),
		"FTEST"				=> array(	310,		2,			2,			0 ),
		"INTERCEPT"			=> array(	311,		2,			2,			0 ),
		"PEARSON"			=> array(	312,		2,			2,			0 ),
		"RSQ"					=> array(	313,		2,			2,			0 ),
		"STEYX"				=> array(	314,		2,			2,			0 ),
		"SLOPE"				=> array(	315,		2,			2,			0 ),
		"TTEST"				=> array(	316,		4,			2,			0 ),
		"PROB"				=> array(	317,		-1,		2,			0 ),
		"DEVSQ"				=> array(	318,		-1,		0,			0 ),
		"GEOMEAN"			=> array(	319,		-1,			0,			0 ),
		"HARMEAN"			=> array(	320,		-1,			0,			0 ),
		"SUMSQ"				=> array(	321,		-1,			0,			0 ),
		"KURT"				=> array(	322,		-1,			0,			0 ),
		"SKEW"				=> array(	323,		-1,			0,			0 ),
		"ZTEST"				=> array(	324,		-1,			0,			0 ),
		"LARGE"				=> array(	325,		2,			0,			0 ),
		"SMALL"				=> array(	326,		2,			0,			0 ),
		"QUARTILE"			=> array(	327,		2,			0,			0 ),
		"PERCENTILE"		=> array(	328,		2,			0,			0 ),
		"PERCENTRANK"		=> array(	329,		-1,			0,			0 ),
		"MODE"				=> array(	330,		-1,			2,			0 ),
		"TRIMMEAN"			=> array(	331,		2,			0,			0 ),
		"TINV"				=> array(	332,		2,			1,			0 ),
		"CONCATENATE"		=> array(	336,		-1,			1,			0 ),
		"POWER"				=> array(	337,		2,			1,			0 ),
		"RADIANS"			=> array(	342,		1,			1,			0 ),
		"DEGREES"			=> array(	343,		1,			1,			0 ),
		"SUBTOTAL"			=> array(	344,		-1,			0,			0 ),
		"SUMIF"				=> array(	345,		-1,			0,			0 ),
		"COUNTIF"			=> array(	346,		2,			0,			0 ),
		"COUNTBLANK"		=> array(	347,		1,			0,			0 ),
		"ROMAN"				=> array(	354,		-1,			1,			0 )
		);
	} // end of function
// ------------------------------------------------------------------------------------------------


} // END OF CLASS
// ------------------------------------------------------------------------------------------------


?>
