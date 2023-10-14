<?php

class createPage extends security {
	var $htmlStart, $htmlEnd, $title, $styleSheet, $scriptHead, $scriptTail, $scriptTailInt;
	var $bodyStart, $bodyMenu, $bodyHeader, $body, $bodyFooter;
	var $NavigationBar, $template, $scriptFile;
	var $bodyMenuNavigation;
	var $view;

	public function __construct() {
		parent::__construct();

		$this->initTags();
	}

	function initTags() {
		$this->htmlStart = "<!DOCTYPE html>\n<html>\n";
		$this->title					= "";
		$this->styleSheet			= array();
		$this->scriptFile			= array();
		$this->scriptHead			= "";
		$this->bodyStart = "<body>";
		$this->NavigationBar 	= "";
		$this->bodyMenu				= "";
		$this->bodyMenuNavigation		= "";
		$this->bodyHeader			= "";
		$this->body						= "";
		//do we view the page in view mode or normal. Default is normal.
		$this->view 					= 0;
		$this->bodyFooter			= "";
		$this->scriptTail			= "";
		$this->scriptTailInt	= "";
		$this->htmlEnd = "</html>\n";
		$this->menuOptions = array();
	}

	function readTemplate($template = null) {
		$this->verifySecurity();

		if (empty($template)) {
			$template = Settings::get('template');
		}

		if (!empty($template)) {
			$tmplName = Settings::get('relativePath') . "templates/" . $template . ".template.php";
			if (file_exists($tmplName)) {
				include ($tmplName);
			}
			$this->readTemplateSettingsFromDB($template);
		}
	}

	function readTemplateSettingsFromDB($template = null) {
		if (empty($template)) {
			$template = Settings::get('template');
		}		
		$SQL = "SELECT * FROM `work_flows` WHERE template = :template limit 1";
		$RS = $this->db->query($SQL, array('template' => $template));
		if ($row = $RS->fetch()) {
			$this->setFormDBinfo($row["template_dbTableName"], $row["template_dbTableKeyField"]);
		}

		/*
		Create actions from database
		*/
		$SQL = "SELECT * FROM `template_action` WHERE template_name=:template ORDER BY sec_no";
		$RS = $this->db->query($SQL, array('template' => $template));
		$action_obj = array();

		while ($row = $RS->fetch()) {
			array_push($action_obj, array(
				$row["template_action_name"],
				$row["template_action_desc"],
				$row["template_action_type"],
				$row["template_action_dest"],
				$row["template_action_img"],
				$row["template_action_imgAlt"],
				$row["template_action_target"],
				$row["template_action_title"],
				$row["sec_no"],
				!empty($row["template_action_class"]) ? $row["template_action_class"] : '',
				!empty($row["template_action_section"]) ? $row["template_action_section"] : ''
			));
		}

		/*
		if there is no previous action, create a blank action so that the spacing of the actions stays the same.
		Previous should always be on top.
		*/
		$is_prev = false;
		$is_next = false;
		foreach ($action_obj AS $action) {
			if (in_array("previous", $action) || in_array("prev", $action)) {
				$is_prev = true;
			}
			if (in_array("next", $action)) {
				$is_next = true;
			}
		}

		if (!$is_prev) {
			array_unshift($action_obj, array("", "", "blank", "", "blank_action.gif", "", "", "", "", "", ""));
		}

		if (!$is_next) {
			$this->array_insert_item ($action_obj, array("", "", "blank", "", "blank_action.gif", "", "", "", "", "", ""), 1);
		}
		
		//create a action for every action found in the database with the current template name
		foreach ($action_obj as $action) {
			$this->createAction ($action[0], $action[1], $action[2], $action[3], $action[4], $action[5], $action[6], $action[7], $action[9], $action[10]);
		}

		//start reading the template info from the database. This will be overwritten by the template files.
		$SQL = "SELECT * FROM `template_field` WHERE template_name=:template";
		$RS = $this->db->query($SQL, array('template' => $template));
		while ($row = $RS->fetch()) {
			switch ($row["fieldType"]) {
				case "SELECT":
				case "RADIO":
				case "RADIO:VERTICAL":
					if ($row["fieldDBconnected"]) {
						$this->createFieldFromDB($row["fieldName"],$row["fieldType"], $row["fieldSelectTable"], $row["fieldSelectID"], $row["fieldSelectName"],1,$row["fieldSelectWhere"]);
					}else{
						$this->createInputFromDB($row["fieldName"],$row["fieldType"], $row["fieldSelectTable"], $row["fieldSelectID"], $row["fieldSelectName"],1,$row["fieldSelectWhere"]);
					}
					break;
				case "ENUM":
				case "TEXT":
				case "PASSWORD":
				case "ADMINPASSWORD":
				case "FILE":
				case "CHECKBOX":
				case "HIDDEN":
				case "TEXTAREA":
				case "DATE":
					if ($row["fieldDBconnected"]) {
						$this->createField($row["fieldName"], $row["fieldType"]);
					}else{
						$this->createInput($row["fieldName"], $row["fieldType"]);
					}
					break;
				case "MULTIPLE":
					$this->createMultipleRelation ($row["fieldName"], $row["fieldMainTable"], $row["fieldMainFld"], $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, $row["fieldRelationFld"], $row["fieldRelationTable"], $row["fieldRelationKey"], $row["fieldRelationVal"]);
					break;
			}
			$this->formFields[$row["fieldName"]]->fieldStyle = $row["fieldStyle"];
			$this->formFields[$row["fieldName"]]->fieldOnClick = $row["fieldOnClick"];
			$this->formFields[$row["fieldName"]]->fieldClass = $row["fieldClass"];
			$this->formFields[$row["fieldName"]]->fieldSize = $row["fieldSize"];
			$this->formFields[$row["fieldName"]]->fieldMaxFieldSize = $row["fieldMaxFieldSize"];
			$this->formFields[$row["fieldName"]]->fieldCols = $row["fieldCols"];
			$this->formFields[$row["fieldName"]]->fieldRows = $row["fieldRows"];
			$this->formFields[$row["fieldName"]]->fieldOnChange = $row["fieldOnChange"];
			$this->formFields[$row["fieldName"]]->fieldStatus = $row["fieldStatus"];
			$this->formFields[$row["fieldName"]]->fieldDBconnected = $row["fieldDBconnected"];
			$this->formFields[$row["fieldName"]]->fieldNullValue = $row["fieldNullValue"];
			if (!empty($row["fieldPlaceHolder"])) {
				$this->formFields[$row["fieldName"]]->fieldPlaceHolder = $row["fieldPlaceHolder"];
			}
			$this->formFields[$row["fieldName"]]->template_name = $row["template_name"];
			/* fieldOptions should not be set from here. It should be set from the
			   createField() function.
			*/
//			$this->formFields[$row["fieldName"]]->fieldOptions = $row["fieldOptions"];
			/* fieldValuesArray should not be read from this DB. it should be selected from a lookup
			   table in the db.
			*/
//			$this->formFields[$row["fieldName"]]->fieldValuesArray = explode("|", ($row["fieldValuesArray"]));
		}
	}

	function createStyleSheet(){
		if (!empty($this->styleSheet)){
			if (is_string($this->styleSheet)) {
				$this->styleSheet = array($this->styleSheet);
			}
			
			foreach($this->styleSheet as $stylesheet){
				echo '<link rel="stylesheet" type="text/css" href="' . $stylesheet . '" title="Normal Style">' . "\n";
			}
		}
	}

	// 2008-20-08 robin: added type="text/javascript" to SCRIPT tag.
	function createScriptFiles () {
		foreach($this->scriptFile as $f) {
			echo '<script src="'.$f.'" type="text/javascript"></script>'."\n";
		}
	}

	function createScript ($str) {
		if ($str > "") {
			echo "<script type=\"text/javascript\">\n";
			echo "<!-- \n";
			echo $str;
			// 2009-01-05 Robin: Added // before --> to prevent javascript trying to execute it.
			echo "//-->\n";
			echo "</script>\n";
		}
	}

	function runInit ($file) {
		if ($file > "") {
			// if there is a proc_init file we will execute it.
			$codePage = "proc/".$file.".init.php";
			if (file_exists($codePage)) {
				include ($codePage);
			}
		}
	}

	function createHTML($file, $path=""){
		if($file > ""){
			$this->relativePath = ($path != '') ? $path : $this->relativePath;
			// if there is a proc_pre file we will execute it.
			$codePage = $this->relativePath . "proc/" . $file . ".pre.php";
			if(file_exists($codePage)){
				include ($codePage);
			}
			include ($this->relativePath . "html/" . $file . ".html.php");
		}
	}

	function showPage(){
		$this->readTemplate();
		$this->formHidden["FLOW_ID"] = Settings::get('workFlowID');
		$this->formHidden["TMPL_NAME"] = Settings::get('template');
		$this->saveWorkFlowSettings();

		// $this->runInit ($this->body);

		echo $this->htmlStart;
		echo "<head>\n";
		if ($this->title > "") echo "<title>".$this->title."</title>\n";
		$this->createStyleSheet ();
		$this->createScriptFiles ();
		$this->createScript ($this->scriptHead);
		echo '<meta charset="UTF-8">';
		echo "</head>\n";
		echo $this->bodyStart."\n";
		if ($this->bodyMenuNavigation > "") include ("menu/".$this->bodyMenuNavigation.".php");
		$this->createHTML($this->bodyMenu);
		$this->createHTML($this->bodyHeader);
		$this->createHTML($this->body);
		$this->createHTML($this->bodyFooter);
		$this->createScript ($this->scriptTail);
		$this->createScript ($this->scriptTailInt);
		echo "</body>\n";
		echo $this->htmlEnd;
	}

	function popupWindow($link, $title, $html) {
		if ($title == "") $title = $link;

		$html = str_replace ("'", "\'", $html);

		echo "<a href=\"javascript:popupWindow('$title', '$html');\">$link</a>";
	}

	function popupContent($title, $content, $template="", $new=false) {
		if ($template == "") $template = Settings::get('template');
		if ($new == false) {
			echo "<a href=\"javascript:winContentText('$title', '$template', '$content');\">$title</a>";
		} else {
			echo "<a href=\"javascript:winContentTextTMP('$title', '$template', '$content');\">$title</a>";
		}
	}

	function support_popupWindow($width, $height, $top="") {
		$this->scriptHead = $this->scriptHead."\n".

			"function popupWindow(title, html) {\n".
			"	w=$width;\n".
			"	h=$height;\n".
			"	closeScript = '<script>function doClose() { self.close(); }</script>';\n".
			"	leftSide = (screen.width-w)-25;\n";

		if ($top == "") {
			$this->scriptHead = $this->scriptHead."	topSide = (screen.height-h)/2;\n";
		} else {
			$this->scriptHead = $this->scriptHead."	topSide = $top;\n";
		}

		$this->scriptHead = $this->scriptHead.
			"	helpwin = open(\"\",\"\",\"width=\"+w+\",height=\"+h+\", top=\"+topSide+\", left=\"+leftSide+\", scrollbars=yes\");\n".
			"	helpwin.document.write('<html><head>');\n".
			"	helpwin.document.write('".'<link rel=STYLESHEET TYPE="text/css" href="'.$this->styleSheet.'" title="Normal Style">'."');\n".
			"	helpwin.document.write('</head><title>'+title+'</title>');\n".
			"	helpwin.document.write('<body  onblur=\"doClose();\" class=help>');\n".
			"	helpwin.document.write('<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\"><tr><td bgcolor=\"#CC3300\" height=\"2\"></td></tr><tr><td bgcolor=\"#ECF1F6\" align=\"center\"><img src=\"images/help_top.gif\" width=\"255\" height=\"45\"></td></tr></table><br>');\n".
			"	helpwin.document.write(html);\n".
			"	helpwin.document.write(closeScript);\n".
			"	helpwin.document.write('</body></html>');\n".
			"}\n";
	}

	function mis_eval_pre ($line, $file) {
		$this->evalLine = $line;
		$this->evalFile = $file;
		ob_start();
	}

	function mis_eval_post ($eval_code) {
		$output = ob_get_flush();
		if (strpos(strtolower($output), "warning") || strpos(strtolower($output), "notice") || strpos(strtolower($output), "error")) {
			$dbString = "WARNING WAS: \n".$output."\n\nEVALED CODE: \n".$eval_code."\n\n"."POST VARS: \n".var_export($_POST, TRUE)."\n\n"."ACTIVE PROCESS ID: ".Settings::get('active_processes_id');
			$this->AuditLog->writeLogInfo(10, "EVAL - ERROR in ".$this->evalFile." on LINE ".$this->evalLine, mysqli_real_escape_string($dbString), TRUE);
		}
	}

	public function element($elementName, $variables) {
		extract($variables);

		ob_start();
		$path = 'elements/' . $elementName . '.html.php';
		if (file_exists(Settings::get('relativePath') . 'html/' . $path)) {
			include(Settings::get('relativePath') . 'html/' . $path);
		} else {
			include($path);
		}
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}