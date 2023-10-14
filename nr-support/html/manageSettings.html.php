<?php
	$detailArr = $this->getSettingsDetails();
	if(!empty($detailArr)){
		echo $this->element('manageSettings',compact('detailArr'));;
	}else{
		echo "No settings found";
	}
?>