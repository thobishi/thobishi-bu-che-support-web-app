<?php

define ("FLD_STATUS_DEFAULT", 0);
define ("FLD_STATUS_ENABLED", 1);
define ("FLD_STATUS_DISABLED", 2);
define ("FLD_STATUS_TEXT", 3);
define ("CHK_DEFAULT_TRUE", 1);
define ("CHK_DEFAULT_FALSE", 0);

class pageForm extends createPage {
	var $formName, $formAction, $formMethod, $formHidden, $formTarget, $formFields;
	var $formOnSubmit;
	var $formActions, $db_settingsKey;
	var $formStatus;
	var $beginYear, $endYear;

	public function __construct() {
		parent::__construct();

		$this->formInit();
	}

	function formInit () {
		$this->formName = "defaultFrm";
		// $this->formAction = $selfArr[count($selfArr)-1];
		$this->formAction = "?";
		$this->formMethod = "POST";
		$this->formHidden = array ();
		$this->formActions = array ();
		$this->formStatus = 0;
		$this->formTarget = "";
	}

	function createForm ($class = '') {
		$ONSUBMIT = "";
		if ( isset($this->formOnSubmit) && ($this->formOnSubmit > "") ) {
			$ONSUBMIT = ' onSubmit="'.$this->formOnSubmit.'"';
		}
		echo '<form ';

		echo 'name="' . $this->formName . '" id="' . $this->formName . '" action="' . $this->formAction . '" method="' . $this->formMethod . '"'.$ONSUBMIT;

		if (!empty($this->formTarget)) {
			echo ' target="' . $this->formTarget . '"';
		}
		if (!empty($class)) {
			echo ' class="' . $class . '"';
		}		
		
		echo '><div class="formContent">';
		foreach ($this->formHidden as $key => $val) {
			// 2010-03-08 Robin: Need ID for getElementbyID for firefox.  Replace document.All javascript syntax.
			//echo '<INPUT TYPE=HIDDEN NAME="'.$key.'" VALUE="'.$val.'">'."\n";
			echo '<input type="hidden" name="' . $key . '" id="' . $key . '" value="' . $val . '">' . "\n";
		}
	}

	function createAction ($name, $desc, $type = "", $dest = "", $img = "", $imgAlt = "", $target = "", $title = "", $class = "", $section = "") {
		$this->formActions[$name] = new formActions ($name);
		$this->formActions[$name]->actionDesc = $desc;
		if ($type > "") $this->formActions[$name]->actionType = $type;
		if ($dest > "") $this->formActions[$name]->actionDest = $dest;
		if ($img > "") {
			$img = "<img src='images/".$img."' alt='".$imgAlt."' border=0>";
			$this->formActions[$name]->actionImg = $img;
		}
		if ($target > "") $this->formActions[$name]->target = $target;
		if ($title > "") $this->formActions[$name]->title = $title;
		if ($class > "") $this->formActions[$name]->actionClass = $class;
		if ($section > "") $this->formActions[$name]->section = $section;
	}

	function readDefaultActions () {
		$inst_code = $this->db->getValueFromTable("users", "user_id", Settings::get('currentUserID'), "institution_ref");
		if (($inst_code != 1) && ($inst_code != 2))
		{
			$SQL = "SELECT * FROM template_text WHERE template_ref = :template AND text_type_ref = 3";

			$rs = $this->db->query($SQL, array(
				'template' => Settings::get('template')
			));
			while ($row = $rs->fetch()) {
				$this->createAction ("PageHelp", $row["template_text_desc"], "href", "javascript:winContentText('".$row["template_text_desc"]."', '".Settings::get('template')."', '".$row["text_actual"]."');", "ico_help.gif", $row["template_text_desc"]);
			}
		}
	}

	function showActions () {
		// first check if we do not need any default action (from the database)
		$this->readDefaultActions ();

		foreach ($this->formActions as $key => $obj) {
			if ($obj->actionMayShow) {
				switch ($obj->actionType) {
					case 'login':
						$action = "submit";
					case 'button':
						$action = $obj->actionType;
						echo '<tr><td width="28" valign="top"><div id="action_'.$obj->actionName.'Img" style="display:Block">' . $obj->actionImg.'</div></td><td valign="top"><div id="action_'.$obj->actionName.'" style="display:Block"><INPUT CLASS="'.$obj->actionClass.'" TYPE="'.$action.'" NAME="'.$obj->actionName.'" VALUE="'.$obj->actionDesc.'"></div></td></tr>'."\n";
						break;
					case 'submit':
						echo '<tr><td height=22 width="28" valign="top"><div id="action_'.$obj->actionName.'_Img" style="display:Block">'."<a title='".$obj->title."' target='".$obj->target."' CLASS='".$obj->actionClass."' href=\"javascript:moveto('".$obj->actionName."');\">". $obj->actionImg. '</a></div></td><td height=22 valign="top"><div id="action_'.$obj->actionName.'" style="display:Block">'."<a title='".$obj->title."' target='".$obj->target."' CLASS='".$obj->actionClass."'  href=\"javascript:moveto('".$obj->actionName."');\">".$obj->actionDesc."</a></div></td></tr>\n";
						break;
					case 'href':
						echo '<tr><td height=22 width="28" valign="top"><div id="action_'.$obj->actionName.'_Img" style="display:Block">'."<a title='".$obj->title."' target='".$obj->target."' CLASS='".$obj->actionClass."' href=\"".$obj->actionDest."\">".$obj->actionImg. '</a></div></td><td height=22 valign="top"><div id="action_'.$obj->actionName.'" style="display:Block">'."<a title='".$obj->title."'  target='".$obj->target."' CLASS='".$obj->actionClass."' href=\"".$obj->actionDest."\">".$obj->actionDesc."</a></div></td></tr>\n";
						break;
					case 'blank':
						echo '<tr><td height=22 width="28" valign="top"><div id="action_'.$obj->actionName.'_Img" style="display:Block">'.$obj->actionImg.'</div>'.'</td><td height=22 valign="top"><div id="action_'.$obj->actionName.'" style="display:Block">'."<a CLASS='".$obj->actionClass."' href=\"".$obj->actionDest."\">".$obj->actionDesc."</a></div></td></tr>\n";
						break;
				}
			}
		}
	}
	
	
	function showUpdatedActions($class = ""){
		// first check if we do not need any default action (from the database)
		$defaultClass = 'panel ';
		$this->readDefaultActions ();
		$return = array();
		
		foreach($this->formActions as $key => $obj){
			$arrayClass = (isset($obj->actionClass)) ? explode(' ', $obj->actionClass) : array();
			if($obj->actionMayShow && in_array($class, $arrayClass)){
				$output = '';
				$heading = (isset($obj->section)) ? $obj->section : '';
				$tooltip = ($class == "actions") ? ' data-toggle="tooltip" data-placement="top" ' : '';
				switch($obj->actionType){
					case 'login':
						$action = "submit";
					case 'button':
						$action = $obj->actionType;

						$output = '<div class="action action_button" id="action_'.$obj->actionName.'">';
						$output .= '<button class="' . $defaultClass . $obj->actionClass . '" type="' . $action . '" name="' . $obj->actionName . '">';
						$output .= $obj->actionImg . $obj->actionDesc;
						$output .= '</button>';
						$output .= '</div>';
						break;
					case 'submit':
						$output = '<div class="action action_submit" id="action_'.$obj->actionName.'">';
						$output .= '<a class="' . $defaultClass . 'tooltipImg ' . $obj->actionClass . '" ' . $tooltip . " title='" . $obj->title . "' target='" . $obj->target . "' class='" . $defaultClass . $obj->actionClass . "' href=\"javascript:moveto('" . $obj->actionName . "');\">";
						$output .= $obj->actionImg . $obj->actionDesc;
						$output .= '</a>';
						$output .= '</div>';
						break;
					case 'href':
						$output = '<div class="action action_link" id="action_'.$obj->actionName.'">';
						$output .= '<a title="' . $obj->title . '" target="' . $obj->target . '" class="' . $defaultClass . $obj->actionClass . '" href="' . $obj->actionDest . '">';
						$output .= $obj->actionImg . $obj->actionDesc;
						$output .= '</a>';
						$output .= '</div>';
						break;
				}

				if (!empty($heading)) {
					$return[$heading][$key]['link'] = $output;
					$return[$heading][$key]['class'] = $obj->actionClass;
				} else {
					$return[$key]['link'] = $output;
					$return[$key]['class'] = $obj->actionClass;
				}
			}
		}
		
		return $return;
	}

	function setFieldProperties ($name, $type="", $size="", $class="") {
		$obj = &$this->formFields[$name];
		if ($type > "") $obj->fieldType = $type;
		if ($size > "") $obj->fieldSize = $size;
		if ($class > "") $obj->fieldClass = $class;
	}

	function fieldToDB($properties) {
		if (count($properties) > 0) {
			$keys = array();
			$values = array();
			foreach ($properties AS $key=>$value) {
				if (!(($key == "fieldValue") || ($key == "fieldValuesArray"))) {
					array_push ($keys, $key);
					if (is_array($value)) {
						$value = implode("|", $value);
					}
					array_push ($values, $value);
				}
			}
			$values = str_replace("'", "\\'", $values);
			$SQL = "REPLACE INTO `template_field` (template_name, ".implode(",",$keys).") VALUES ('".Settings::get('template')."', '".implode ("','", $values)."')";
		//	$RS = mysqli_query($SQL) or die($SQL);
		}
	}

	function templateToDB($template) {
		// if (count($template) > 0) {
		if (is_array($template) && !empty($template)) {
			$keys = array();
			$values = array();
			foreach ($template AS $key=>$value) {
				if ($key != "dbTableCurrentID") {
					array_push ($keys, "template_".$key);
					array_push ($values, $value);
				}
			}
		}
		//$SQL = "REPLACE INTO `template_info` (template_name, ".implode(",",$keys).") VALUES ('".Settings::get('template')."', '".implode ("','", $values)."')";
		//$RS = mysqli_query($SQL);
	}

	function createInput ($name, $type="", $val="", $size="", $status=0, $class="") {
		$this->formFields[$name] = new formFields ($name);
		$obj = &$this->formFields[$name];

		switch ($type) {
			case "RADIO":
			case "RADIO:VERTICAL":
			case "SELECT":
			case "MULTIPLE":
				if ($val > "") {
					$obj->fieldValuesArray = $val;
				}
				break;
			case "CHECKBOX":
				if ($val > "") {
					$obj->fieldValue = $val;
				} else {
					$obj->fieldValue = CHK_DEFAULT_TRUE;
				}
				break;
			default:
				$obj->fieldValue = $val;
				break;
		}
		
		$this->setFieldProperties ($name, $type, $size, $class);
		$obj->fieldStatus = $status;
	}

	function createField ($name, $type="", $val="", $size="") {

		$this->createInput ($name, $type, $val, $size);
		$obj = &$this->formFields[$name];
		$obj->fieldDBconnected = true;

		// if it is a CHECKBOX insert/add to an array in the hidden values.
		if (strtoupper($obj->fieldType) == "CHECKBOX") {
			$this->addHiddenArray("SHOULDSAVE", $name);
			
		}
		$this->getFieldSettingsFromDB($name);
		$this->setFieldProperties ($name, $type, $size);   // run eintlik al vir die 2de keer

		if (isset($this->dbTableCurrent) && isset($this->dbTableInfoArray[$this->dbTableCurrent]) && $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID != "NEW") {
			$SQL = "SELECT ".$name." FROM ".$this->dbTableCurrent." WHERE ".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableKeyField." = '".$this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID."'";
			$rs = $this->db->query($SQL);
			if ($rs) {
				if ($row = $rs->fetch()) {
					if ($obj->fieldType != "CHECKBOX") {
						$obj->fieldValue = $row[0];
					} else {
						if ($row[0]) {
							$obj->fieldOptions = "CHECKED";
						}
					}
				}
			}
		}
	}

	function createFieldFromDB ($name, $type, $table, $key="", $val="", $size="",$where="1") {
		$SQL = "SELECT * FROM ".$table." WHERE ".$where;
	//	echo '<br> HELLO </br>'.$SQL; 
		$valArr = $this->db->makeArrayFromSQL($SQL, $key, $val);
		$this->createField($name, $type, $valArr, $size);
		$this->formFields[$name]->fieldSelectTable = $table;
		$this->formFields[$name]->fieldSelectID = $key;
		$this->formFields[$name]->fieldSelectName = $val;
	}

	function createForeignFieldFromDB ($name, $fTable, $fKeyField, $fKeyValue, $type, $lkpTable, $lkpKeyField="", $lkpKeyValue="", $returnField="", $returnDesc="", $size="") {
		$SQL = "SELECT * FROM ".$lkpTable;
		if ($lkpKeyField > "") {
					 $SQL .= " WHERE $lkpKeyField = '$lkpKeyValue'";
		}
		$valArr = $this->db->makeArrayFromSQL($SQL, $returnField, $returnDesc);
		$this->createForeignField($name, $fTable, $fKeyField, $fKeyValue, $type, $valArr, $size);
	}

	function createForeignField ($name, $fTable, $fKeyField, $fKeyValue, $type="", $val="", $size="") {
		if ($val > "") {
			$defaultValue = $val;
		} else {
			$defaultValue = $this->db->getValueFromTable($fTable, $fKeyField, $fKeyValue, $name);
		}
		$this->createField($name, $type, $defaultValue, $size);
		$this->formHidden["INFFT_".$name] = $fTable."_|_".$fKeyField."_|_".$fKeyValue."_|_".$name;
	}

	function createMultipleRelation ($name, $mainTable, $mainFld, $mainVal, $relationFld, $relationTable, $relationKey, $relationVal, $size="") {
		$SQL = "SELECT $relationTable.$relationKey, $relationTable.$relationVal ".
					 "FROM $mainTable, $relationTable ".
					 "WHERE $mainTable.$relationFld = $relationTable.$relationKey ".
					 "AND $mainTable.$mainFld = '$mainVal'";

		$valArr = $this->db->makeArrayFromSQL($SQL);
		$this->createField($name, "MULTIPLE", $valArr, $size);
		$this->formHidden["MRINF_".$name] = $mainTable."_|_".$mainFld."_|_".$mainVal."_|_".$relationFld;
		$this->formFields[$name]->fieldMainTable = $mainTable;
		$this->formFields[$name]->fieldMainFld = $mainFld;
		$this->formFields[$name]->fieldMainVal = $mainVal;
		$this->formFields[$name]->fieldRelationFld = $relationFld;
		$this->formFields[$name]->fieldRelationTable = $relationTable;
		$this->formFields[$name]->fieldRelationKey = $relationKey;
		$this->formFields[$name]->fieldRelationVal = $relationVal;
	}

	function createInputFromDB ($name, $type, $table, $key="", $val="", $size="", $where="", $orderby="") {
		$SQL = "SELECT * FROM ".$table;
		if ($where > "") $SQL .= " WHERE ".$where;
		if ($orderby > "") $SQL .= " ORDER BY ".$orderby;
		$valArr = $this->db->makeArrayFromSQL($SQL, $key, $val);
		$this->createInput($name, $type, $valArr, $size);
		$this->formFields[$name]->fieldSelectTable = $table;
		$this->formFields[$name]->fieldSelectID = $key;
		$this->formFields[$name]->fieldSelectName = $val;
	}

	function showField ($name, $multiGrid = false) {
	
	//echo $name .'test';
		$style = "";
		$onclick = "";
		$onchange = "";
		$options = "";
		$placeholder = "";
     print_r ($this->formFields);
		if ( isset($this->formFields[$name]) ) {
		
//		    echo "hi";
			$obj = $this->formFields[$name];
			// $this->pr($obj);
			$DBfld = ( ($obj->fieldDBconnected)?("FLD_"):("") );
			if ($obj->fieldDBconnected && $obj->fieldType == "MULTIPLE") {
				$DBfld = "FLDS_";
			}
			if ($obj->fieldDBconnected && $obj->fieldType == "ADMINPASSWORD") {
				$DBfld = "PWA_";
			}
			if (isset($obj->fieldStyle) && ($obj->fieldStyle > "") ){
				$style = ' style="'.$obj->fieldStyle.'"';
			}
			if (isset($obj->fieldOptions) && ($obj->fieldOptions > "") ){
				$options = ' '.$obj->fieldOptions.' ';
			}
			if (isset($obj->fieldOnClick) && ($obj->fieldOnClick > "") ){
				$onclick = ' onclick="'.$obj->fieldOnClick.'"';
			}
			if (isset($obj->fieldOnChange) && ($obj->fieldOnChange > "") ){
				$onchange = ' onchange="'.$obj->fieldOnChange.'"';
			}
			if (isset($obj->fieldPlaceHolder) && ($obj->fieldPlaceHolder > "") ){
				$placeholder = $obj->fieldPlaceHolder;
			}
			 
			
			$obj->validation = (isset($obj->template_name)) ? $this->fetchValidations($obj->template_name, $name) : '';
			$status = FLD_STATUS_ENABLED; //default status  enabled
			if ($this->formStatus > FLD_STATUS_DEFAULT) $status = $this->formStatus;
			if ($obj->fieldStatus > FLD_STATUS_DEFAULT) $status = $obj->fieldStatus;
			
			//echo "<br> Test  </br>" .$status;
			
			switch ($status) {
				case FLD_STATUS_ENABLED:
						$status = "";
						if($multiGrid){
							return $this->doReturnField($obj, $DBfld, $style, $onclick, $onchange, $status, $options, $placeholder);
						}else{
							$this->doPrintField($obj, $DBfld, $style, $onclick, $onchange, $status, $options, $placeholder);
						}
						break;
				case FLD_STATUS_DISABLED:
						$status = ' disabled="disabled"';
						if($multiGrid){
							return $this->doReturnField($obj, $DBfld, $style, $onclick, $onchange, $status, $options, $placeholder);
						}else{
							$this->doPrintField($obj, $DBfld, $style, $onclick, $onchange, $status, $options, $placeholder);
						}
						break;
				case FLD_STATUS_TEXT:

						$fieldValue = simple_text2html ($obj->fieldValue);
						// Commented out by Robin 11/1/2007. Replaced by code below it.
						// Reason:  Some radio buttons from e.g. grid slip through this condition. Their $obj->fieldSelectTable
						// 			is blank.  The fieldValuesArray seems to pickup everything that has a lookup.
						//for selects and radios etc.
						//if ($obj->fieldSelectTable > "") {
						//	$fieldValue = $this->db->getValueFromTable ($obj->fieldSelectTable, $obj->fieldSelectID, $obj->fieldValue, $obj->fieldSelectName);
						//}

						// for any field that has a lookup array of values (radio, select) - Robin 11/1/2007
						
						if (isset($obj->fieldValuesArray[$obj->fieldValue])){
							$fieldValue = $obj->fieldValuesArray[$obj->fieldValue];
						}
						
						// 2012-04-30 Robin: If lookup tables change by disabling historic values and adding more relevant values then 
						// historic values should still display. Fix for codes displaying as e.g. 6 because option 6 in the list was disabled.
						// Exclude default blank values: 0
						// Currently only adding for a SELECT input - could consider including RADIO.
						if ($obj->fieldType == 'SELECT'){
							if ($obj->fieldValue > 0 AND (!isset($obj->fieldValuesArray[$obj->fieldValue]))){  // ones in array are picked up in preceding statement. Want to catch the ones falling through the cracks.
								$fieldValue = $this->db->getValueFromTable($obj->fieldSelectTable, $obj->fieldSelectID, $obj->fieldValue, $obj->fieldSelectName);
							}
						}

						//for MULTIPLE selects etc.
						if (($obj->fieldMainTable > "") && ($obj->fieldRelationTable > "")) {
							foreach ($obj->fieldValuesArray AS $fValue) {
								$fieldValue .= "<br>".$fValue."\n";
							}
						}

						if ($obj->fieldType != "HIDDEN" AND $obj->fieldType != "FILE") {
							echo $fieldValue."<br>\n";
						}
						break;
			}
		}
	}
	
	function doReturnField($obj, $DBfld="", $style="", $onclick="", $onchange="", $status="", $options="", $placeholder=""){
		$validationData = (!empty($obj->validation)) ? json_encode($obj->validation) : '';
		$dataHolder = (!empty($validationData)) ? ' data-validation="' . system_htmlspecialchars($validationData) .'" ' : '';
		$return = '';
		
		switch (strtoupper($obj->fieldType)){
			case "TEXT":
			case "HIDDEN":
				$return = '<input class="' . $obj->fieldClass . '" type="' . strtolower($obj->fieldType) . '" name="' . $DBfld . $obj->fieldName . '" value="' . system_htmlspecialchars($obj->fieldValue) . '" size="' . $obj->fieldSize . '" maxlength="' . $obj->fieldMaxFieldSize . '"' . $style . $onclick . $onchange . $status . $options . ' placeholder="' . $placeholder . '" ' . $dataHolder . '>';
				break;
		}
		
		return $return;
	}

	function doPrintField($obj, $DBfld="", $style="", $onclick="", $onchange="", $status="", $options="", $placeholder="") {
		$validationData = (!empty($obj->validation)) ? json_encode($obj->validation) : '';
		$dataHolder = (!empty($validationData)) ? ' data-validation="' . system_htmlspecialchars($validationData) .'" ' : '';
		switch (strtoupper($obj->fieldType)) {
			case "TEXT":
			case "HIDDEN":
			case "PASSWORD":
				echo '<input class="' . $obj->fieldClass . '" type="' . strtolower($obj->fieldType) . '" name="' . $DBfld . $obj->fieldName . '" id="' . $DBfld . $obj->fieldName . '" value="' . system_htmlspecialchars($obj->fieldValue) . '" size="' . $obj->fieldSize . '" maxlength="' . $obj->fieldMaxFieldSize . '"' . $style . $onclick . $onchange . $status . $options . ' placeholder="' . $placeholder . '" ' . $dataHolder . '>';
				break;
			case "ADMINPASSWORD":
				echo '<input class="' . $obj->fieldClass . '" type="password" name="' . $DBfld . $obj->fieldName . '" id="' . $DBfld . $obj->fieldName . '" value="" size="' . $obj->fieldSize . '" maxlength="' . $obj->fieldMaxFieldSize . '"' . $style . $onclick . $onchange . $status . $options . '>';
				break;
			case "FILE":
				$this->makeDocInput($obj,$DBfld);
				break;
			case "TEXTAREA":
				echo '<textarea class="' . $obj->fieldClass.'" name="' . $DBfld . $obj->fieldName .'" id="' . $DBfld . $obj->fieldName .'" cols="' . $obj->fieldCols .'" rows="' . $obj->fieldRows . '"' . $style . $onclick . $onchange . $status . $options . $dataHolder . '>' . $obj->fieldValue . '</textarea>';
				break;
			case "RADIO":
			echo '<table><tr>';
				foreach ($obj->fieldValuesArray as $key => $val) {
					$SEL = "";
					if ($obj->fieldValue == $key) $SEL = ' checked="checked"';
					echo '<td class= "nowrap"><input class="' . $obj->fieldClass . '" type="radio" name="' . $DBfld . $obj->fieldName . '" id="' . $key . '" value="' . $key . '"' . $style . $onclick . $onchange . $SEL . $status . $options . $dataHolder . '>' . $val . " </td> ";
				}
			echo '</tr></table>';
				break;
			case "RADIO:VERTICAL":
			echo '<table>';
				foreach ($obj->fieldValuesArray as $key => $val) {
					echo '<tr>';
					$SEL = "";
					if ($obj->fieldValue == $key) $SEL = " checked=\"checked\"";
					echo '<td valign="top"><input class="' . $obj->fieldClass . '" type="radio" name="' . $DBfld . $obj->fieldName . '" ID="' . $key . '" value="' . $key . '"' . $style . $onclick . $onchange . $SEL . $status . $options . $dataHolder . '></td><td valign="top">' . $val . '</td>';
					echo '</tr>';
				}
			echo '</table>';
				break;
			case "ENUM":
				foreach ($obj->fieldValuesArray as $key => $val) {
					$SEL = "";
					if ($obj->fieldValue == $key) $SEL = " CHECKED";
					echo '<input class="' . $obj->fieldClass . '" type="radio" name="' . $DBfld . $obj->fieldName . '" ID="' . $key . '" value="' . $key . '"' . $style . $onclick . $onchange . $SEL . $status . $options . $dataHolder . '>' . $val . " &nbsp; ";
				}
				break;
			case "CHECKBOX":
				echo '<input class="' . $obj->fieldClass . '" type="' . strtolower($obj->fieldType) . '" name="' . $DBfld . $obj->fieldName . '" value="' . $obj->fieldValue . '" ' . $style . $onclick . $onchange . $status . $options . $dataHolder . '>';
				break;
			case "SELECT":
				$this->makeSelectField($obj->fieldName, $obj->fieldValuesArray, $obj, $obj->fieldValue, $DBfld, $style, $onclick, $onchange, $status, $options . $dataHolder);
				break;
			case "MULTIPLE":
				$this->makeSelectField($obj->fieldName."[]", $obj->fieldValuesArray, $obj, $obj->fieldValue, $DBfld, $style, $onclick, $onchange, $status, "MULTIPLE");
				break;
			case "DATE":
				$this->makeDateFields($obj, $DBfld, $style, $onclick, $onchange, $status, $options);
				break;
			case "TIME":
				$this->makeTimeFields($obj, $DBfld, $style, $onclick, $onchange, $status, $options);
				break;
		}
	}

	function makeSelectField($fieldName, $arr, $obj, $defaultVal, $DBfld="", $style="", $onclick="", $onchange="", $status="", $options="") {
		echo '<select class="'.$obj->fieldClass.'" name="'.$DBfld.$fieldName.'" id="'.$DBfld.$fieldName.'" '.$style." ".$onclick." ".$onchange." ".$status." ".$options.'>';
		// $this->pr($obj->fieldNullValue);
		if ($obj->fieldNullValue > "") {
			echo '<option value="0">'.$obj->fieldNullValue.'</option>'."\n";
		}
			foreach ($arr as $key => $val) {
				$SEL = "";
				if ($defaultVal == $key) $SEL = ' selected="selected"';
				echo '<option value="'.$key.'"'.$SEL.' >'.$val.'</option>'."\n";
			}
		echo "</select>\n";
	}


	//Reyno van der Hoven
	//2004/4/2
	//Maak dat popup calender werk vir alle date fields
	function makeDateFields($obj, $DBfld="", $style="", $onclick="", $onchange="", $status="", $options="") {
		echo '<INPUT readonly CLASS="'.$obj->fieldClass.'" TYPE="TEXT" NAME="'.$DBfld.$obj->fieldName.'" VALUE="'.$obj->fieldValue.'" SIZE="'.$obj->fieldSize.'" MAXLENGTH="'.$obj->fieldMaxFieldSize.'"'.$style.$onclick.$onchange.$status.'>';
		?>
		<a href="javascript:show_calendar('defaultFrm.<?php echo $DBfld.$obj->fieldName?>');"><img src="images/icon_calendar.gif" border=0></a>
		<?php 
	}

	//Louwtjie
	//2004/4/20
	//Maak dat popup calender werk vir alle time fields
	function makeTimeFields($obj, $DBfld="", $style="", $onclick="", $onchange="", $status="", $options="") {
		echo '<INPUT readonly CLASS="'.$obj->fieldClass.'" TYPE="TEXT" NAME="'.$DBfld.$obj->fieldName.'" VALUE="'.$obj->fieldValue.'" SIZE="'.$obj->fieldSize.'" MAXLENGTH="'.$obj->fieldMaxFieldSize.'"'.$style.$onclick.$onchange.$status.'>';
		?>
		<a href="javascript:showTime('','defaultFrm.<?php echo $DBfld.$obj->fieldName?>');"><img src="images/icon_time.gif" border=0></a>
		<?php 
	}

/**
 * Gets the length of a database-native column description, or null if no length. Borrowed from CakePHP
 *
 * @param string $real Real database-layer column type (i.e. "varchar(255)")
 * @return mixed An integer or string representing the length of the column, or null for unknown length.
 */
	private function __length($real) {
		if (!preg_match_all('/([\w\s]+)(?:\((\d+)(?:,(\d+))?\))?(\sunsigned)?(\szerofill)?/', $real, $result)) {
			$col = str_replace(array(')', 'unsigned'), '', $real);
			$limit = null;

			if (strpos($col, '(') !== false) {
				list($col, $limit) = explode('(', $col);
			}
			if ($limit !== null) {
				return intval($limit);
			}
			return null;
		}

		$types = array(
			'int' => 1, 'tinyint' => 1, 'smallint' => 1, 'mediumint' => 1, 'integer' => 1, 'bigint' => 1
		);

		list($real, $type, $length, $offset, $sign, $zerofill) = $result;
		$typeArr = $type;
		$type = $type[0];
		$length = $length[0];
		$offset = $offset[0];

		$isFloat = in_array($type, array('dec', 'decimal', 'float', 'numeric', 'double'));
		if ($isFloat && $offset) {
			return $length . ',' . $offset;
		}

		if (($real[0] == $type) && (count($real) === 1)) {
			return null;
		}

		if (isset($types[$type])) {
			$length += $types[$type];
			if (!empty($sign)) {
				$length--;
			}
		} elseif (in_array($type, array('enum', 'set'))) {
			$length = 0;
			foreach ($typeArr as $key => $enumValue) {
				if ($key === 0) {
					continue;
				}
				$tmpLength = strlen($enumValue);
				if ($tmpLength > $length) {
					$length = $tmpLength;
				}
			}
		}
		return intval($length);
	}

	function getFieldSettingsFromDB ($name) {

		$obj = &$this->formFields[$name];
		if (isset($this->dbTableCurrent)) {
			// If the next line gives an error the following are not filled in:
			// for your template in db: work_flows
			// cols:template_dbTableName & template_dbTableKeyField
			$fields = $this->db->getFieldDetails($this->dbTableCurrent);
			
			foreach ($fields as $fieldDetails) {
				if($fieldDetails['Field'] == $name){
					$obj->fieldSize = $this->__length($fieldDetails['Type']);
					$obj->fieldMaxFieldSize = $obj->fieldSize;
					if (strstr($fieldDetails['Type'], "enum") ) {
						$vals = explode ("','", substr ($row["Type"], 6, strlen($row["Type"])-8) );
						foreach ($vals as $val) {
							$obj->fieldValuesArray[$val] = $val;
						}
					}
				}
			}
			
			/*
			for ($i = 0; $i < $columns; $i++) {
				if (mysqli_field_name ($fields, $i) == $name) {
					$obj->fieldSize = mysqli_field_len ($fields, $i);
					$obj->fieldMaxFieldSize = mysqli_field_len ($fields, $i);
					if ( strstr(mysqli_field_flags ($fields, $i), "enum") ) {
						$SQL = "SHOW FIELDS FROM ".$this->dbTableCurrent;
						$rs = mysqli_query ($SQL);
						while ($row = mysqli_fetch_assoc ($rs)) {
							if ($row["Field"] == $name) {
								$vals = explode ("','", substr ($row["Type"], 6, strlen($row["Type"])-8) );
								foreach ($vals as $val) {
									$obj->fieldValuesArray[$val] = $val;
								}
							}
						}
					}
				}
			}*/
		}
	}

	function getFieldValue ($name) {
		if ( isset($this->formFields[$name]) ) {
			$obj = $this->formFields[$name];
			return ($obj->fieldValue);
		}
		return ("");
	}

	function addHiddenArray($name, $val) {
		if (isset($this->formHidden[$name])) {
			$this->formHidden[$name] .= "_|_".$val;
		} else {
			$this->formHidden[$name] = $val;
		}
	}
}