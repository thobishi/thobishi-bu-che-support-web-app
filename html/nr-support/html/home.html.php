<div id="home" class="home-content row-fluid">
	<span class="specialrb"><?php echo $this->displayUserMessage; ?></span>
	<h2>Welcome <?php echo $this->getCurrentUserInfo(); ?>, to your National Review home page!</h2>
<?php
echo 'Hello CHE';
	$inbox = array(
		'8' => array(
			'processName' => 'Self-evaluation reports',
			'emptyMessage' => 'There are currently no activities assigned to you.'
		),
		'37' => array(
			'processName' => 'Self-evaluation reports for LLB',
			'emptyMessage' => 'There are currently no activities assigned to you.'
		),
		'10' => array(
			'processName' => 'SER Submissions',
			'emptyMessage' => 'There are currently no SER submissions assigned to you.'
		),
		'15' => array(
			'processName' => 'Manage site visits',
			'emptyMessage' => 'There are currently no completed screenings assigned to you.'
		),
		'16' => array(
			'processName' => 'Desktop Evaluation',
			'emptyMessage' => 'No submissions are assigned to you at this time.'
		),
		'18' => array(
			'processName' => 'Site visit and evaluation - Chair',
			'emptyMessage' => 'No submissions are assigned to you at this time.'
		),
		'19' => array(
			'processName' => 'Site visit and evaluation - Panel member',
			'emptyMessage' => 'No submissions are assigned to you at this time.'
		),
		'22' => array(
			'processName' => 'Manage recommendation writers',
			'emptyMessage' => 'There are currently no completed evaluations assigned to you.'
		),	
		'23' => array(
			'processName' => 'Recommendations to write',
			'emptyMessage' => 'No submissions are assigned to you at this time.'
		),
		'33' => array(
			'processName' => 'Upload Reference Committee report',
			'emptyMessage' => 'There are currently no completed Recommendations assigned to you.'
		),
		'36' => array(
			'processName' => 'Upload National Review Committee report',
			'emptyMessage' => 'There are currently no completed RC assigned to you.'
		),		
		/*'29' => array(
			'processName' => 'Click the link under Reports to view NRC meetings to attend',
			'emptyMessage' => 'No meetings assign to you at this time'
		),	*/	
		'other' => array(
			'processName' => 'Other processes',
			'emptyMessage' => ''
		)
	);
	
	$homeGroup = array(
        '-3' => array(
            'processName' => 'Reports',
            'emptyMessage' => 'There are currently no reports available.'
        ),
        '40' => array(
            'processName' => 'Improvement Plan',
            'emptyMessage' => 'There are currently plans to you.'
        )       
    );
	
	$meeting = array(
		'29' => array(
			'processName' => 'NRC meetings to attend',
			'emptyMessage' => 'No NRC meetings assign to you at this time'
		),
		'32' => array(
			'processName' => 'RC meetings to attend',
			'emptyMessage' => 'No RC meetings assign to you at this time'
		)		
	);
	
	$groups = $this->sec_inGroups();
	$allGroupProcesses = array();
	$currentUserID = Settings::get('currentUserID');
	
	$UserActiveProcessArr = $this->getUserActiveProcess($currentUserID);
	
	foreach($groups as $groupID){
		$groupProcesses = $this->getGroupProcesses($groupID);
		$allGroupProcesses = array_merge($allGroupProcesses, $groupProcesses);
	}
	$processes = $this->getProcess();
	
	$additionalProcesses = $this->getMenuProcesses('menu_perant', array('-3', '16', '18', '19', '23', '29','32', '40'));
	
	$processesSorted = array();
	
	$processes = array_merge($processes, $additionalProcesses);
	$inboxViewArr = array_intersect($allGroupProcesses, array_keys($inbox));

	$noInboxView =  (empty($inboxViewArr))  ? true : false;

	$viewMeetingArr = array_intersect($allGroupProcesses, array_keys($meeting));
	$noMeetingView = empty($viewMeetingArr)  ? true : false;
	
	if(!empty($processes)){
		foreach($processes as $processCount => $process){
			$index = (isset($inbox[$process['processes_ref']]) || isset($homeGroup[$process['processes_ref']]) || isset($meeting[$process['processes_ref']])) ? $process['processes_ref'] : 'other';
			$processesSorted[$index][$processCount] = $process;
		}
	}
	echo '<pre>';print_r($processesSorted);
	echo '<div class="accordion" id="accordionHome">';
	if(!empty($processesSorted)){
		foreach($homeGroup as $processRef => $processInfo){
			if(isset($processesSorted[$processRef])){
				echo $this->element('home_contents', array('processes' => $processesSorted[$processRef], 'groups' => $groups, 'processHeading' => $homeGroup[$processRef]['processName'], 'processRef' => $processRef));
			}
			else{
				if(in_array($processRef, $allGroupProcesses)){
					echo '<h3>' . $processInfo['processName'] . '</h3>' . $processInfo['emptyMessage'] . '<br />';
				}
			}
		}
		// $this->pr($inboxViewArr);
		// $this->pr($UserActiveProcessArr);
		if(!$noInboxView){
			// if(!empty($UserActiveProcessArr)){
				echo $this->element('accordian_top', array('accHeader' => 'Inbox - Processes to attend to ', 'collapse' => 'inbox'));
				foreach($inbox as $processRef => $processInfo){
					if(isset($processesSorted[$processRef])){
						echo $this->element('home_contents', array('processes' => $processesSorted[$processRef], 'groups' => $groups, 'processHeading' => $inbox[$processRef]['processName'], 'processRef' => $processRef));
					}
					else{
						if(in_array($processRef, $allGroupProcesses)){
							// echo '<h3>' . $processInfo['processName'] . '</h3>' . $processInfo['emptyMessage'] . '<br />';
						}
					}
				}
				echo $this->element('accordian_bottom', array());
			// }
		}

		if(!$noMeetingView){
			echo $this->element('accordian_top', array('accHeader' => 'Meetings', 'collapse' => 'meetings'));
		
			foreach($meeting as $processRef => $processInfo){
				if(isset($processesSorted[$processRef])){
					echo $this->element('home_contents', array('processes' => $processesSorted[$processRef], 'groups' => $groups, 'processHeading' => $meeting[$processRef]['processName'], 'processRef' => $processRef));
				}
				else{
					if(in_array($processRef, $allGroupProcesses)){
						echo '<h3>' . $processInfo['processName'] . '</h3>' . $processInfo['emptyMessage'] . '<br />';
					}
				}
			}
			echo $this->element('accordian_bottom', array());
		}
		
	}
	echo '</div>';
	
?>	
</div>
