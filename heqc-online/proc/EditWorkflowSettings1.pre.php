<?php 
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		switch ($_POST["cmd"]) {
			case "new":
				$this->saveProgram("template_action", "template_name", $this->formFields["template"]->fieldValue);
				break;
			case "del":
				if (isset($_POST["id"]) && ($_POST["id"]>"")) {
					$this->deleteProgram("template_action", "template_action_id", $_POST["id"]);
				}
				break;
			default:
				break;
		}
	}else {
	}

	$tableHeading = array();
	$tableHeading["Buttons"] = 7;
	
	$fieldsArr = array();
	$fieldsArr["template_action_name"] = "Button Name";
	$fieldsArr["template_action_desc"] = "Button Text";
	$fieldsArr["template_action_type"] = "Button Type";
	$fieldsArr["template_action_dest"] = "Destination";
	$fieldsArr["template_action_img"] = "Image";
	$fieldsArr["template_action_imgAlt"] = "Alt Image";
	$fieldsArr["sec_no"] = "Sec#";

	$program = $this->createAcaStruct("template_name", $this->formFields["template"]->fieldValue, "template_action", "template_action_id", $fieldsArr, 10, 0, $tableHeading);
?>
