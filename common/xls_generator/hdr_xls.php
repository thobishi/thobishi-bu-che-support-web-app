<?php 

//		XLS Generator Class
//		(c) 2007 Paggard [paggard@paggard.com]
//		18 March 2007 - build 1.4.1

//--------------------------
// Header File XLS Generator
//--------------------------

// --- WORK BOOK RECORDS ----------------------------------
define('RCRD_BOF', 0x0809);
define('RCRD_BOUNDSHEET', 0x0085);
define('RCRD_CONTINUE', 0x003C);
define('RCRD_DATEMODE', 0x0022);
define('RCRD_EOF', 0x000A);
define('RCRD_EXTERNCOUNT', 0x0016);
define('RCRD_EXTERNSHEET', 0x0017);
define('RCRD_STYLE', 0x0293);
define('RCRD_WINDOW1', 0x003D);
define('RCRD_WINDOW2', 0x023E);

// --- WORK SHEET RECORDS ---------------------------------
define('RCRD_BLANK', 0x0201);
define('RCRD_BOTTOMMARGIN', 0x0029);
define('RCRD_COLINFO', 0x007D);
define('RCRD_DEFCOLWIDTH', 0x0055);
define('RCRD_DIMENSIONS', 0x0200);
//define('RCRD_EXTERNCOUNT', 0x0016);
//define('RCRD_EXTERNSHEET', 0x0017);
define('RCRD_FOOTER', 0x0015);
define('RCRD_FORMULA', 0x0006);
define('RCRD_HCENTER', 0x0083);
define('RCRD_HEADER', 0x0014);
define('RCRD_HLINK', 0x01B8);
define('RCRD_LABEL', 0x0204);
define('RCRD_LEFTMARGIN', 0x0026);
define('RCRD_MERGEDCELLS', 0x00E5);
define('RCRD_NAME', 0x0018);
define('RCRD_NUMBER', 0x0203);
define('RCRD_PALETTE', 0x0092);
define('RCRD_PASSWORD', 0x0013);
define('RCRD_PRINTGRIDLINES', 0x002b);
define('RCRD_PRINTHEADERS', 0x002a);
define('RCRD_PROTECT', 0x0012);
define('RCRD_RIGHTMARGIN', 0x0027);
define('RCRD_ROW', 0x0208);
define('RCRD_SCL', 0x00A0);
define('RCRD_SELECTION', 0x001D);
define('RCRD_SETUP', 0x00A1);
define('RCRD_STRING', 0x0207);
define('RCRD_TOPMARGIN', 0x0028);
define('RCRD_VCENTER', 0x0084);
define('RCRD_WSBOOL', 0x0081);


// --- WORK FORMAT RECORDS --------------------------------
define('RCRD_FONT', 0x31);
define('RCRD_XF', 0x00E0);

// --- FORMULA SUPPORT DEFINES ----------------------------
define('ADD',"+");
define('SUB',"-");
define('EQUAL',"=");
define('NOTEQUAL',"<>");
define('MUL',"*");
define('DIV',"/");
define('OPEN',"(");
define('CLOSE',")");
define('COMA',",");
define('SEMICOL',";");
define('MORE',">");
define('LESS',"<");
define('MOREEQ',">=");
define('LESSEQ',"<=");

// --- FORMULA TOKENS -------------------------------------
$TOKENS = Array (
//--Unary Operator Tokens
"tUplus"			=> 0x12,  // Unary plus
"tUminus"		=> 0x13,  // Unary minus
"tPercent"		=> 0x14,  // Percent sign
"tParen"			=> 0x15,  // Parentheses
//-----------------------------------------------

//--Binary Operator Tokens
"tAdd"			=> 0x03,  // Addition
"tSub"			=> 0x04,  // Subtraction
"tMul"			=> 0x05,  // Multiplication
"tDiv"			=> 0x06,  // Division
"tPower"			=> 0x07,  // Exponentiation
"tConcat"		=> 0x08,  // Concatenation
"tLT"				=> 0x09,  // Less than
"tLE"				=> 0x0A,  // Less than or equal
"tEQ"				=> 0x0B,  // Equal
"tGE"				=> 0x0C,  // Greater than or equal
"tGT"				=> 0x0D,  // Greater than
"tNE"				=> 0x0E,  // Not equal
"tIsect"			=> 0x0F,  // Cell range intersection
"tList"			=> 0x10,  // Cell range list
"tRange"			=> 0x11,  // Cell range
//-----------------------------------------------

//--Function Operator Tokens
	// Function with fixed number of arguments
"tFuncR"			=> 0x21,  // [REFERENCE]
"tFuncV"			=> 0x41,  // [VALUE]
"tFuncA"			=> 0x61,  // [ARRAY]
	// Function with variable number of arguments
"tFuncVarR"		=> 0x22,  // [REFERENCE]
"tFuncVarV"		=> 0x42,  // [VALUE]
"tFuncVarA"		=> 0x62,  // [ARRAY]
	// Macro call
"tFuncCER"		=> 0x38,  //  [REFERENCE]
"tFuncCEV"		=> 0x58,  //  [VALUE]
"tFuncCEA"		=> 0x78,  //  [ARRAY]
//-----------------------------------------------

//--Constant Operand Tokens
"tMissArg"		=> 0x16,  // Missing argument
"pgStr"			=> 0x17,  // String constant
"tErr"			=> 0x1C,  // Error constant
"tBool"			=> 0x1D,  // Boolean constant
"tInt"			=> 0x1E,  // Integer constant
"tNum"			=> 0x1F,  // Floating-point constant
//-----------------------------------------------

//--Operand Tokens
	// Array constant
"tArrayR"		=> 0x20,  // {REFERENCE]
"tArrayV"		=> 0x40,  // [VALUE]
"tArrayA"		=> 0x60,  // [ARRAY]
	// Internal defined name
"tNameR"			=> 0x23,  // {REFERENCE]
"tNameV"			=> 0x43,  // [VALUE]
"tNameA"			=> 0x63,  // [ARRAY]
	// 2D cell reference
"tRefR"			=> 0x24,  // {REFERENCE]
"tRefV"			=> 0x44,  // [VALUE]
"tRefA"			=> 0x64,  // [ARRAY]
	// 2D area reference
"tAreaR"			=> 0x25,  // {REFERENCE]
"tAreaV"			=> 0x45,  // [VALUE]
"tAreaA"			=> 0x65,  // [ARRAY]

"tMemAreR"		=> 0x26,  // {REFERENCE]
"tMemAreV"		=> 0x46,  // [VALUE]
"tMemAreA"		=> 0x66,  // [ARRAY] 
"tMemErR"		=> 0x27,  // {REFERENCE]
"tMemErV"		=> 0x47,  // [VALUE]
"tMemErA"		=> 0x67,  // [ARRAY] 
"tMemNoMeR"		=> 0x28,  // {REFERENCE]
"tMemNoMeV"		=> 0x48,  // [VALUE]
"tMemNoMeA"		=> 0x68,  // [ARRAY] 
"tMemFunR"		=> 0x29,  // {REFERENCE]
"tMemFunV"		=> 0x49,  // [VALUE]
"tMemFunA"		=> 0x69,  // [ARRAY] 

	// Deleted 2D cell reference
"tRefErrR"		=> 0x2A,  // {REFERENCE]
"tRefErrV"		=> 0x4A,  // [VALUE]
"tRefErrA"		=> 0x6A,  // [ARRAY]
	// Deleted 2D area reference
"tAreaErrR"		=> 0x2B,  // {REFERENCE]
"tAreaErrV"		=> 0x4B,  // [VALUE]
"tAreaErrA"		=> 0x6B,  // [ARRAY]
	// Relative 2D cell reference
"tRefNR"			=> 0x2C,  // {REFERENCE]
"tRefNV"			=> 0x4C,  // [VALUE]
"tRefNA"			=> 0x6C,  // [ARRAY]
	// Relative 2D area reference
"tAreaNR"		=> 0x2D,  // {REFERENCE]
"tAreaNV"		=> 0x4D,  // [VALUE]
"tAreaNA"		=> 0x6D,  // [ARRAY]

"tMemAreaR"		=> 0x2E,  // {REFERENCE]
"tMemAreaV"		=> 0x4E,  // [VALUE]
"tMemAreaA"		=> 0x6E,  // [ARRAY] 
"tMemNoMemR"	=> 0x2F,  // {REFERENCE]
"tMemNoMemV"	=> 0x4F,  // [VALUE]
"tMemNoMemA"	=> 0x6F,  // [ARRAY] 

	// External name
"tNameXR"		=> 0x39,  // {REFERENCE]
"tNameXV"		=> 0x59,  // [VALUE]
"tNameXA"		=> 0x79,  // [ARRAY]
	// 3D cell reference
"tRef3dR"		=> 0x3A,  // {REFERENCE]
"tRef3dV"		=> 0x5A,  // [VALUE]
"tRef3dA"		=> 0x7A,  // [ARRAY]
	// 3D area reference
"tArea3dR"		=> 0x3B,  // {REFERENCE]
"tArea3dV"		=> 0x5B,  // [VALUE]
"tArea3dA"		=> 0x7B,  // [ARRAY]
	// Deleted 3D cell reference
"tRefErr3dR"	=> 0x3C,  // {REFERENCE]
"tRefErr3dV"	=> 0x5C,  // [VALUE]
"tRefErr3dA"	=> 0x7C,  // [ARRAY]
	// Deleted 3D area reference
"tAreaErr3dR"	=> 0x3D,  // {REFERENCE]
"tAreaErr3dV"	=> 0x5D,  // [VALUE]
"tAreaErr3dA"	=> 0x7D,  // [ARRAY]
//-----------------------------------------------

//--Control Tokens
"tExp"			=> 0x01,  // Matrix formula or shared formula
"tTbl"			=> 0x02,  // Multiple operation table
"tExtende"		=> 0x18,  // 
"tAtt"			=> 0x19,  // 
"tShee"			=> 0x1A,  // 
"tEndShee"		=> 0x1B   // 
//-----------------------------------------------

); // end of tokens array



//-------------------------------------------------------------------------------------------------
// support functions

// ------------------------------------------------------------------------------------------------
// convert UNIX time_stamp to Excel time number
	function Unix2Excel($time=false) {
		$UNIX_Start = 25569.125;
		$unix = ($time) ? $time : time();
		$days = floor($unix / 86400);
		$seconds = ($unix - ($days * 86400));
		$excel = $days + $UNIX_Start + ((round((999999 / 86400) * $seconds)) * 0.000001);
		return $excel;
	} // end of function
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

?>
