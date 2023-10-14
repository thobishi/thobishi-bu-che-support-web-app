<?
$this->title		= "CHE Project Register - Project Detail";
$this->bodyHeader	= "formHead";
$this->body		= "reportProjectDetail";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Reports > Project Detail</span>";

$js_array = $this->mkDropdownArray ('mA', "project_detail", "category_ref, project_id, project_short_title", "category_ref, project_short_title");

$scriptTail = <<<SCRIPTAIL
	var curDropdown = new Array();

	$js_array

SCRIPTAIL;

$this->scriptTail = $scriptTail;
?>
