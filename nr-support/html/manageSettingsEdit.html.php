<?php
	$s_key = $this->dbTableInfoArray['settings']->dbTableCurrentID;
	$key_desc = $this->db->getValueFromTable('settings','s_key',$s_key,'s_key');
	echo '<div class= "alert alert-info">';
	echo 'Change/Edit the setting value or description for "<strong>' . $key_desc . '</strong>" in the spaces provided below.';
	echo '</div>';	
	
	$this->showBootstrapField('s_value', 'Setting value:');
	$this->showBootstrapField('s_description', 'Setting description');	
	
?>