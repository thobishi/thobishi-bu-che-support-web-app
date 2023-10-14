<?php 

class dbTableInfo {
	var $dbTableName, $dbTableCurrentID, $dbTableKeyField;

	function __construct ($name, $keyField="", $currentID="") {
		$this->dbTableName = $name;
			// ons init altyd die 2 vars want ons dink ons
			// gaan nooi isset gebruik nie.
		$this->setdbTableInfo ($keyField, $currentID);
	}

	function setdbTableInfo ($keyField, $currentID) {
		$this->dbTableKeyField = $keyField;
		$this->dbTableCurrentID = $currentID;
	}
}