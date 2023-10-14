<?php

	$reportGroupProccess = array();
	$report = false;
	foreach($groups as $groupID){
		$groupName = $this->getSecGroupName($groupID);
		$groupProcesses = $this->getGroupProcesses($groupID);
		if(!empty($groupName)){
			if(isset($processRef) && $processRef == '-3'){
				$reportGroupProccess = array_merge($reportGroupProccess, $groupProcesses);
				$report = true;
			}else{
				echo (isset($groupName['template'])) ? $this->element($groupName['template'], compact('processes', 'groupProcesses', 'processRef', 'processHeading')) : '';
			}
		}
	}
	$reportGroupProccess = array_unique($reportGroupProccess);
		
	if($report && !empty($reportGroupProccess)){
		if(!empty($processes)){
			if(in_array($processRef, $reportGroupProccess)){
				echo $this->element('processes_' . $processRef, compact('processes', 'processHeading', 'reportGroupProccess', 'processRef'));
			}
		}
	}

?>