<?php 
//	CONFIGURATION FILE FOR XLS GENERATOR CLASS
//	SOME OF THESE SETTINGS COULD BE OVERWRITTEN BY USING SPECIAL TAGS
//	IN CASE OF OMMITTING THE TAGS, THE DOCUMENT WILL  BE FORMATTED USING THESE SETTINGS

// AVAILABLE COLORS
//		the list of available colors is given in the "available_colors.html"


//-------------------------------------------------------------------------------------------------
// --- WORKSHEET

$PaperSizeIndex = 11;
		/*
		Paper Size Table
		Index	Paper type		Paper size			|	Index		Paper type					Paper size
		---------------------------------------+-----------------------------------------------------
		0		Undefined								|	21			#11 Envelope				 4 1/2" x 10 3/8"
		1		Letter			8 1/2" x 11"		|	22			#12 Envelope				 4 3/4" x 11"
		2		Letter small	8 1/2" x 11"		|	23			#14 Envelope				     5" x 11 1/2"
		3		Tabloid			   11" x 17"		|	24			C Sheet						    17" x 22"
		4		Ledger			   17" x 11"		|	25			D Sheet						    22" x 34"
		5		Legal				8 1/2" x 14"		|	26			E Sheet						    34" x 44"
		6		Statement		5 1/2" x 8 1/2"	|	27			DL Envelope					  110mm x 220mm
		7		Executive		7 1/4" x 10 1/2"	|	28			C5 Envelope					  162mm x 229mm
		8		A3					 297mm x 420mm		|	29			C3 Envelope					  324mm x 458mm
		9		A4					 210mm x 297mm		|	30			C4 Envelope					  229mm x 324mm
		10		A4 small			 210mm x 297mm		|	31			C6 Envelope					  114mm x 162mm
		11		A5					 148mm x 210mm		|	32			C65 Envelope				  114mm x 229mm
		12		B4					 250mm x 354mm		|	33			B4 Envelope					  250mm x 353mm
		13		B5					 182mm x 257mm		|	34			B5 Envelope					  176mm x 250mm
		14		Folio				8 1/2" x 13"		|	35			B6 Envelope					  125mm x 176mm
		15		Quarto			 215mm x 275mm		|	36			Italy Envelope				  110mm x 230mm
		16							   10" x 14"		|	37			Monarch Envelope			 3 7/8" x 7 1/2"
		17							   11" x 17"		|	38			6 3/4 Envelope				 3 5/8" x 6 1/2"
		18		Note				8 1/2" x 11"		|	39			US Standard Fanfold		14 7/8" x 11"
		19		#9 Envelope		3 7/8" x 8 7/8"	|	40			German Std. Fanfold		 8 1/2" x 12"
		20		#10 Envelope	4 1/8" x 9 1/2"	|	41			German Legal Fanfold		 8 1/2" x 13"
		*/

$H_Centered = 1;					// default horizontal alignment on printed page
										// 1 - centered; 0 - left aligned
$V_centered = 0;					// default vertical alignment on printed page
										// 1 - centered; 0 - top aligned
$PageOrientation = 0;			// default page orientation [1 - portrait, 0 - landscape]

$StartPageNumber = 1;			// start page number

$margin_top = 0.75;				// default top margin (inches)
$margin_bottom = 0.75;			// default bottom margin (inches)
$margin_right = 0.75;			// default right margin (inches)
$margin_left = 0.75;				// default left margin (inches)


$Header = "&P Page of &N"; // default print header text
$Footer = "This is bottom header"; // default print footer text
$margin_head = 0.40;				// margin from the top of the page to header
$margin_foot = 0.40;				// margin from bottom of the page to footer
		/*
		Header and Footer may contain special commands. You can incert command symbol 
		wherever you need whthin the Header/Footer text.
		Command							Description
		--------------------------------------
		&L									Start of the left section
		&C									Start of the centred section
		&R									Start of the right section
		&P									Current page number
		&N									Page count
		&D									Current date
		&T									Current time
		&A									Sheet name (BIFF5-BIFF8)
		&F									File name without path
		&Z									File path without file name (BIFF8X)
		&G									Picture (BIFF8X)
		&B									Bold on/off (BIFF2-BIFF4)
		&I									Italic on/off (BIFF2-BIFF4)
		&U									Underlining on/off
		&E									Double underlining on/off (BIFF5-BIFF8)
		&S									Strikeout on/off
		&X									Superscript on/off (BIFF5-BIFF8)
		&Y									Subscript on/off (BIFF5-BIFF8)
	MAY NOT WORK:
		&"<fontname>"					Set new font <fontname>
		&"<fontname>,<fontstyle>"	Set new font with specified style <font style>. 
											The style <fontstyle> is in most cases one of “Regular”, “Bold”, 
											“Italic”, or “Bold Italic”. But this setting is dependent 
											on the used font, it may differ (localised style names, 
											or “Standard”, “Oblique”, ...). (BIFF5-BIFF8)
		&<fontheight>					Set font height in points (<fontheight> is a decimal value). 
											If this command is followed by a plain number to be printed 
											in the header, it will be separated from the font height with a space character.
		*/

$PrintGridlines = 1;					// 0 - gridlines are hidden in a printed page; 1 - shown
$ViewGridlines = 1;					// 0 - gridlines will be hidden in a view window; 1 - shown
$GridColor = "#C0C0C0";				// default gridline color
$PrintHeaders = 0;					// 1 - colomn indexes and row numbers 
											// will be included into a printed page

$FitPage = 0;							// undocumented
$FitWidth = 0;							// undocumented
$FitHeight = 0;						// undocumented

$Password = "";					// all the sheets in the WorkBook will be protected with this 
											// password, if you will need to protect sheets separately 
											// use <Password>...</Password> element.
											// password should not be more than 9 symbols

$Zoom = 100;					// initial zoom setting (in percents)
$PrintScale = 100;			// printing scape (in percents)

//-------------------------------------------------------------------------------------------------
// --- FORMATTER
// IMPORTANT!!! - THESE SETTINGS WILL BE USED EVERYWHERE IN THE WORKBOOK!

$FontName = "Times New Roman";		// default font to be used in cells
$FontSize = 10;			// default font size
$FontStyle = "";			// default font style 
								//					"b" - boldl;
								//					"i" - italic;
								//					"u" - underlined
								//					"r" - strikeout
								//					"o" - outline
								// "bi" - means "bold and italic" etc.
$FontColor = "midnightblue"; // default font color

$TextAlign = "center";			// default horizontal alignment
								// "left"		- left laigned
								// "right"		- right aligned
								// "center"		- center aligned
								// "fill"		- 
								// "justify"	- 
								// "merge"		- centered across the selection
$TextVAlign = "";			// default vertical alignment
								// "top"			- top aligned
								// "bottom"		- bottom aligned
								// "middle"		- middle aligned
								// "justify"	- 
$TextWrap = 1;				// default text wrapping [0 - not wrapped; 1 - wrapped]
$TextRotation = 0;		// default text rotation
								// 0 - Not rotated
								// 1 - Letters are stacked top-to-bottom, but not rotated
								// 2 - Text is rotated 90 degrees counterclockwise
								// 3 - Text is rotated 90 degrees clockwise

$ForegroundColor = "";	// default cell foreground color
$BackgroundColor = "";	// default cell background color

$BorderTop = "";			// default style for top cell border
$BorderRight = "";		// default style for right cell border
$BorderBottom = "";		// default style for bottom cell border
$BorderLeft = "";			// default style for left cell border
								// BORDER STYLES
								// "no"			- No line (0)
								// "thin"		- Thin (1)
								// "medium"		- Medium (2)
								// "dashed"		- Dashed (3)
								// "dotted"		- Dotted (4)
								// "thick"		- Thick (5)
								// "double"		- Double (6)
								// "hair"		- Hair (7)
$BorderTopColor = "black";	// default color for top cell border
$BorderRightColor = "black";	// default color for right cell border
$BorderBottomColor = "black";// default color for bottom cell border
$BorderLeftColor = "black";	// default color for left cell border

$Pattern = 0;				// default background fill pattern
								// from 0 to 18


$NumberFormat = 0;
	/*
	The built-in number formats, BIFF5-BIFF8:
	Index		Type		Format string								Index			Type			Format string
	0			General	General										18				Time			h:mm AM/PM
	1			Decimal	0												19				Time			h:mm:ss AM/PM
	2			Decimal	0.00											20				Time			h:mm
	3			Decimal	#,##0											21				Time			h:mm:ss
	4			Decimal	#,##0.00										22				Date/Time	M/D/YY h:mm
	5		Currency		"$"#,##0_);("$"#,##0)					37				Account.		_(#,##0_);(#,##0)
	6		Currency		"$"#,##0_);[Red]("$"#,##0)				38				Account.		_(#,##0_);[Red](#,##0)
	7		Currency		"$"#,##0.00_);("$"#,##0.00)			39				Account.		_(#,##0.00_);(#,##0.00)
	8		Currency		"$"#,##0.00_);[Red]("$"#,##0.00)		40				Account.		_(#,##0.00_);[Red](#,##0.00)
	9		Percent		0%												41				Currency		_("$"* #,##0_);_("$"* (#,##0);_("$"* "-"_);_(@_)
	10		Percent		0.00%											42				Currency		_(* #,##0_);_(* (#,##0);_(* "-"_);_(@_)
	11		Scientific	0.00E+00										43				Currency		_("$"* #,##0.00_);_("$"* (#,##0.00);_("$"* "-"??_);_(@_)
	12		Fraction		# ?/?											44				Currency		_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)
	13		Fraction		# ??/??										45				Time			mm:ss
	14		Date			M/D/YY										46				Time			[h]:mm:ss
	15		Date			D-MMM-YY										47				Time			mm:ss.0
	16		Date			D-MMM											48				Scientific	##0.0E+0
	17		Date			MMM-YY										49				Text			@
	*/

?>
