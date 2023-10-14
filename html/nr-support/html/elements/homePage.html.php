<?php

	if(isset($groupProcesses)){
		if(!empty($processes)){
			if(in_array($processRef, $groupProcesses)){
				echo $this->element('processes_' . $processRef, compact('processes', 'processHeading', 'groupProcesses', 'processRef'));
			}
		}
	}
?>