<?php

//		XLS Generator Class
//		(c) 2007 Paggard [paggard@paggard.com]
//		18 March 2007 - build 1.4.1

//-------------------------------------------------------------------------------------------------

define("NODE_TYPE_ROOT", 5);
define("NODE_TYPE_STORAGE", 1);
define("NODE_TYPE_STREAM", 2);
define("DIRS_PER_BLOCK", 4);
define("FAT_START", 0x4c);

define("THIS_BLOCK_SIZE", 0x80);
define("BLOCK_SIZE_BIG", 512);
define("BLOCK_SIZE_SMALL", 64);
define("SIZE_SMALL", 0x1000);


// ------------------------------------------------------------------------------------------------
class storage {
	public $No = false;
	public $NodeName = false;
	public $NodeType = false;
	public $LeftIndex = false;
	public $RightIndex = false;
	public $ChildIndex = false;
	public $CreateTime = false;
	public $ModifyTime = false;
	public $SectStart = false;
	public $Size = false;
	public $Data = false;
	public $Child = false;

	public $_FILE;

// ------------------------------------------------------------------------------------------------
	function file_handle($fh) {
		$this->_FILE = $fh;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _getDataLength() {
		if ($this->Data === false) { return 0; }
		return strlen($this->Data);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _dumpSectionHead(&$FILE) {
		fputs($FILE,
			$this->NodeName.
			str_repeat("\x00", 64 - strlen($this->NodeName)).					//  64
			pack("v", strlen($this->NodeName) + 2).								//  66
			pack("c", $this->NodeType).												//  67
			pack("c", 0x00).																//  68
			pack("V", $this->LeftIndex).												//  72
			pack("V", $this->RightIndex).											//  76
			pack("V", $this->ChildIndex).												//  80
			"\x00\x09\x02\x00".															//  84
			"\x00\x00\x00\x00".															//  88
			"\xc0\x00\x00\x00".															//  92
			"\x00\x00\x00\x46".															//  96
			"\x00\x00\x00\x00".															// 100
			"\x00\x00\x00\x00\x00\x00\x00\x00".										// 108 - Create Time
			"\x00\x00\x00\x00\x00\x00\x00\x00".										// 116 - Modify Time
			pack("V", ($this->SectStart!==false) ? $this->SectStart : 0).	// 120
			pack("V", ($this->Size!==false) ? $this->Size : 0).				// 124
			pack("V", 0)																	// 128
		); // end of fputs
	} // end of function
// ------------------------------------------------------------------------------------------------

} // END OF CLASS
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
class storage_node_stream extends storage {

// ------------------------------------------------------------------------------------------------
	function __construct($sNodeName) {
		$this->No = false;
		$this->NodeName = $sNodeName;
		$this->NodeType = NODE_TYPE_STREAM;
		$this->LeftIndex = false;
		$this->RightIndex = false;
		$this->ChildIndex = false;
		$this->CreateTime = false;
		$this->ModifyTime = false;
		$this->SectStart = false;
		$this->Size = false;
		$this->Data = '';
		$this->Child = false;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function store($data) {
		$this->Data .= $data;
	} // end of function
// ------------------------------------------------------------------------------------------------

} // END OF CLASS
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
class storage_node_root extends storage {

// ------------------------------------------------------------------------------------------------
	function __construct($CreateTime=false, $ModifyTime=false, $raChild=false) {
		$this->No = false;
		$this->NodeName = getUNICODE('Root Entry');
		$this->NodeType = NODE_TYPE_ROOT;
		$this->LeftIndex = false;
		$this->RightIndex = false;
		$this->ChildIndex = false;
		$this->CreateTime = $CreateTime;
		$this->ModifyTime = $ModifyTime;
		$this->SectStart = false;
		$this->Size = false;
		$this->Data = false;
		$this->Child = $raChild;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function dump($FileName) {
		if(is_resource($FileName)) {
			$fh = $FileName;
			$this->file_handle($fh);
		}
		else {
			$fh = fopen("$FileName", "wb");
			$this->file_handle($fh);
		}
		$iBlk = 0;
		$aList=array();
		$list=array(&$this);
		$this->_getSectionStart($list, $aList);
		list($iSBDCount, $iBBCount, $iSECTCount) = $this->_getSize($aList);
		$this->_dumpHeader($iSBDCount, $iBBCount, $iSECTCount);
		$iBBlk = $iSBDCount;
		$this->_dumpBigData($iBBlk, $aList);
		$this->_dumpSection($aList);
		$this->_dumpBbd($iSBDCount, $iBBCount, $iSECTCount); 
		fclose($this->_FILE);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _getSize(&$raList) {
		$iSBDCount=0;
		$iBBCount=0;
		$iSECTCount = 0;
		$iSmallLen = 0;
		$iSBCount = 0;
		for ($c=0;$c<sizeof($raList);$c++) {
			$oSECT=&$raList[$c];
			if($oSECT->NodeType == NODE_TYPE_STREAM) {
				$oSECT->Size = $oSECT->_getDataLength();
				if($oSECT->Size < SIZE_SMALL) {
					$iSBCount += floor($oSECT->Size / BLOCK_SIZE_SMALL) + (($oSECT->Size % BLOCK_SIZE_SMALL) ? 1 : 0);
				}
				else {
					$iBBCount += (floor($oSECT->Size/ BLOCK_SIZE_BIG) + (($oSECT->Size % BLOCK_SIZE_BIG)? 1: 0));
				}
			}
		}
		$iSmallLen = $iSBCount * BLOCK_SIZE_SMALL;
		$iSlCount = floor(BLOCK_SIZE_BIG / DIRS_PER_BLOCK);
		$iSBDCount = floor($iSBCount / $iSlCount)+ (($iSBCount % $iSlCount) ? 1 : 0);
		$iBBCount += (floor($iSmallLen/ BLOCK_SIZE_BIG) + (( $iSmallLen % BLOCK_SIZE_BIG) ? 1 : 0));
		$iCount = sizeof($raList);
		$iBdCount = BLOCK_SIZE_BIG / THIS_BLOCK_SIZE;
		$iSECTCount = (floor($iCount/$iBdCount) + (($iCount % $iBdCount) ? 1 : 0));
		return array($iSBDCount, $iBBCount, $iSECTCount);
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _dumpHeader($iSBDCount, $iBBCount, $iSECTCount) {
		$iSBDCount = 0;
		$FILE = $this->_FILE;
		$iBlCount = BLOCK_SIZE_BIG / DIRS_PER_BLOCK;
		$i1stBdL = (BLOCK_SIZE_BIG - FAT_START) / DIRS_PER_BLOCK;
		$iBdExL = 0;
		$iAll = $iBBCount + $iSECTCount + $iSBDCount;
		$iAllW = $iAll;
		$iBdCountW = floor($iAllW / $iBlCount) + (($iAllW % $iBlCount) ? 1 : 0);
		$iBdCount = floor(($iAll + $iBdCountW) / $iBlCount) + ((($iAllW+$iBdCountW) % $iBlCount) ? 1 : 0);
		if ($iBdCount > $i1stBdL) {
			do {
				$iBdExL++;
				$iAllW++;
				$iBdCountW = floor($iAllW / $iBlCount) + (($iAllW % $iBlCount) ? 1 : 0);
				$iBdCount = floor(($iAllW + $iBdCountW) / $iBlCount) + ((($iAllW+$iBdCountW) % $iBlCount) ? 1 : 0);
			} while($iBdCount > ($iBdExL*$iBlCount+ $i1stBdL));
		}
		fputs($FILE,
					"\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1".		// MAGIC
					"\x00\x00\x00\x00".
					"\x00\x00\x00\x00".
					"\x00\x00\x00\x00".
					"\x00\x00\x00\x00".
					pack("v", 0x3b).
					pack("v", 0x03).
					pack("v", -2).
					pack("v", 9).
					pack("v", 6).
					pack("v", 0).
					"\x00\x00\x00\x00".
					"\x00\x00\x00\x00".
					pack("V", $iBdCount).						// num_FAT_blocks
					pack("V", $iBBCount+$iSBDCount).			// ROOT START
					pack("V", 0).
					pack("V", 0x1000).
					pack("V", 0).
					pack("V", 1)
		);
		if($iBdCount < $i1stBdL) {
			fputs($FILE,pack("V", -2).pack("V", 0));
		}
		else {
			fputs($FILE,pack("V", $iAll+$iBdCount).pack("V", $iBdExL));
		}
		for ($i=0;($i<$i1stBdL) && ($i < $iBdCount); $i++) {
			fputs($FILE, pack("V", $iAll+$i));
		}
		if ($i<$i1stBdL) {
			fputs($FILE, str_repeat((pack("V", -1)), ($i1stBdL-$i)));
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _dumpBigData(&$iStBlk, &$raList) {
		$iStBlk = 0;
		$iRes = 0;
		$FILE = $this->_FILE;
		for ($c=0;$c<sizeof($raList);$c++) {
			$oSECT=&$raList[$c];
			if($oSECT->NodeType != NODE_TYPE_STORAGE) {
				$oSECT->Size = $oSECT->_getDataLength();
				if(($oSECT->Size >= SIZE_SMALL) || (($oSECT->NodeType == NODE_TYPE_ROOT) && $oSECT->Data!==false)) {
					fputs($FILE, $oSECT->Data);
					if ($oSECT->Size % BLOCK_SIZE_BIG) {
						fputs($FILE, str_repeat("\x00", BLOCK_SIZE_BIG - ($oSECT->Size % BLOCK_SIZE_BIG)));
					}
					$oSECT->SectStart = $iStBlk;
					$iStBlk += (floor($oSECT->Size/ BLOCK_SIZE_BIG) + (($oSECT->Size % BLOCK_SIZE_BIG) ? 1 : 0));
				}
			}
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _dumpSection(&$raList) {
		$FILE = $this->_FILE;
		for ($c=0;$c<sizeof($raList);$c++) {
			$oItem=&$raList[$c];
			$oItem->_dumpSectionHead($FILE);
		}
		$iCount = sizeof($raList);
		$iBCount = BLOCK_SIZE_BIG / THIS_BLOCK_SIZE;
		if($iCount % $iBCount) {
			fputs($FILE, str_repeat("\x00", (($iBCount - ($iCount % $iBCount)) * THIS_BLOCK_SIZE)));
		}
		return (floor($iCount / $iBCount) + (($iCount % $iBCount) ? 1 : 0));
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _getSectionStart(&$aThis, &$raList) {
		if (!is_array($aThis) || sizeof($aThis)==0) {
			return 0xFFFFFFFF;
		}
		elseif (sizeof($aThis)==1) {
			//array_push($raList, &$aThis[0]);
			array_ref_push($raList, $aThis[0]);
			$aThis[0]->No = sizeof($raList)-1;
			$aThis[0]->LeftIndex = 0xFFFFFFFF;
			$aThis[0]->RightIndex = 0xFFFFFFFF;
			$aThis[0]->ChildIndex = $this->_getSectionStart($aThis[0]->Child, $raList);
			return $aThis[0]->No;
		}
		else {
			$iCount = sizeof($aThis);
			$iPos = floor($iCount/2);
			array_push($raList, $aThis[$iPos]);
			$aThis[$iPos]->No = sizeof($raList)-1;
			$aWk = $aThis;
			$aPrev = splice($aWk, 0, $iPos);
			$aNext = splice($aWk, 1, $iCount - $iPos - 1);
			$aThis[$iPos]->LeftIndex = $this->_getSectionStart($aPrev, $raList);
			$aThis[$iPos]->RightIndex = $this->_getSectionStart($aNext, $raList);
			$aThis[$iPos]->ChildIndex = $this->_getSectionStart($aThis[$iPos]->Child, $raList);
			return $aThis[$iPos]->No;
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function _dumpBbd($iSbdSize, $iBsize, $iSECTCount) {
		$iSbdSize = 0;
		$FILE = $this->_FILE;
		$iBbCount = BLOCK_SIZE_BIG / DIRS_PER_BLOCK;
		$i1stBdL = (BLOCK_SIZE_BIG - FAT_START) / DIRS_PER_BLOCK;
		$iBdExL = 0;
		$iAll = $iBsize + $iSECTCount + $iSbdSize;
		$iAllW = $iAll;
		$iBdCountW = floor($iAllW / $iBbCount) + (($iAllW % $iBbCount) ? 1 : 0);
		$iBdCount = floor(($iAll + $iBdCountW) / $iBbCount) + ((($iAllW+$iBdCountW) % $iBbCount)? 1: 0);
		if ($iBdCount >$i1stBdL) {
			do {
				$iBdExL++;
				$iAllW++;
				$iBdCountW = floor($iAllW / $iBbCount) + (($iAllW % $iBbCount) ? 1 : 0);
				$iBdCount = floor(($iAllW + $iBdCountW) / $iBbCount) + ((($iAllW+$iBdCountW) % $iBbCount) ? 1 : 0);
			} while ($iBdCount > ($iBdExL*$iBbCount+$i1stBdL));
		}
		if($iSbdSize > 0) {
			for ($i = 0; $i<($iSbdSize-1); $i++) {
				fputs($FILE, pack("V", $i+1));
			}
			fputs($FILE, pack("V", -2));
		}
		for ($i = 0; $i<($iBsize-1); $i++) {
			fputs($FILE, pack("V", $i+$iSbdSize+1));
		}
		fputs($FILE, pack("V", -2));
		for ($i = 0; $i<($iSECTCount-1); $i++) {
			fputs($FILE, pack("V", $i+$iSbdSize+$iBsize+1));
		}
		fputs($FILE, pack("V", -2));
		for ($i=0; $i<$iBdCount;$i++) {
			fputs($FILE, pack("V", 0xFFFFFFFD));
		}
		for ($i=0; $i<$iBdExL;$i++) {
			fputs($FILE, pack("V", 0xFFFFFFFC));
		}
		if(($iAllW + $iBdCount) % $iBbCount) {
			fputs($FILE, str_repeat(pack("V", -1), ($iBbCount - (($iAllW + $iBdCount) % $iBbCount))));
		}
		if($iBdCount > $i1stBdL)  {
			$iN=0;
			$iNb=0;
			for ($i=$i1stBdL;$i<$iBdCount; $i++, $iN++) {
				if($iN>=($iBbCount-1)) {
					$iN = 0;
					$iNb++;
					fputs($FILE, pack("V", $iAll+$iBdCount+$iNb));
				}
				fputs($FILE, pack("V", $iBsize+$iSbdSize+$iSECTCount+$i));
			}
			if(($iBdCount-$i1stBdL) % ($iBbCount-1)) {
				fputs($FILE, str_repeat(pack("V", -1), (($iBbCount-1) - (($iBdCount-$i1stBdL) % ($iBbCount-1)))));
			}
			fputs($FILE, pack("V", -2));
		}
	} // end of function
// ------------------------------------------------------------------------------------------------


} // END OF CLASS
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function getUNICODE($str) {
		return implode("\x00", (preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY)))."\x00";
	} // end of function
// ------------------------------------------------------------------------------------------------

function array_ref_push(&$array,&$ref){  $array[] =& $ref; }

?>
