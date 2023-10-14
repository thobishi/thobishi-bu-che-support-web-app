<?php 

class dbTableInfo {
	var $dbTableName, $dbTableCurrentID, $dbTableKeyField;

	function dbTableInfo ($name, $keyField="", $currentID="") {
		$this->dbTableName = $name;
			// ons init altyd die 2 vars want ons dink ons
			// gaan nooi isset gebruik nie.
		$this->setDbTableInfo ($keyField, $currentID);
	}

	function setDbTableInfo ($keyField, $currentID) {
		$this->dbTableKeyField = $keyField;
		$this->dbTableCurrentID = $currentID;
	}

}

?>
