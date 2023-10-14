<?php

class formFields {
	var $fieldName, $fieldType, $fieldValue, $fieldValuesArray, $fieldClass;
	var $fieldSize, $fieldMaxFieldSize, $fieldCols, $fieldRows;
	var $fieldStyle, $fieldOnclick, $fieldOnChange, $fieldStatus;
	var $fieldDBconnected;
	var $fieldNullValue;
	var $fieldOptions;
	var $fieldSelectTable, $fieldSelectID, $fieldSelectName;  // fieldSelectWhere not added because it cannot be manipulated before a showField.  Manipulate fieldValuesArray instead.
	var $fieldMainTable, $fieldMainFld, $fieldMainVal, $fieldRelationFld, $fieldRelationTable, $fieldRelationKey, $fieldRelationVal;

	function formFields ($name) {
		$this->fieldInit ();
		$this->fieldName = $name;
	}

	function fieldInit () {
		$this->fieldType		= "TEXT";
		$this->fieldClass		= "std";
		$this->fieldCols		= "70";
		$this->fieldRows		= "10";
		$this->fieldSize		= "15";
		$this->fieldMaxFieldSize 	= "255";
		$this->fieldValue		= "";
		$this->fieldValuesArray 	= array();
		$this->fieldStyle		= "";		
		$this->fieldOnClick 		= "";
		$this->fieldOnChange 		= "";
		$this->fieldStatus		= 0;
		$this->fieldDBconnected		= false;
		$this->fieldNullValue	= false;
		$this->fieldOptions		= "";
		$this->fieldSelectTable = "";
		$this->fieldSelectID = "";
		$this->fieldSelectName = "";
		$this->fieldMainTable = "";
		$this->fieldMainFld = "";
		$this->fieldMainVal = "";
		$this->fieldRelationFld = "";
		$this->fieldRelationTable = "";
		$this->fieldRelationKey = "";
		$this->fieldRelationVal = "";
	}

}

?>
