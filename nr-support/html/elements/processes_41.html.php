<?php
    $currentUserID = Settings::get('currentUserID');
    echo $this->element('accordian_top', array('accHeader' => $processHeading, 'collapse' => 'National Standard Alignment Report'));
	
?>
 
    <?php
        if(!empty($processes))
		{
            
            foreach($processes as $processCount => $process)
            {
                if(empty($process['active_processes_id']))
                {
                    echo '<table class="table table-hover table-bordered table-striped">';
                    echo '<tbody>';           
                    echo '<tr><td><a href="javascript:goto(' . $process['processes_id'] . ');">' . $process['processes_desc'] . '</td></tr>';
                }
                echo '</tbody>';
                echo '</table>';
            }

        }else{
            echo '<table class="table table-hover table-bordered table-striped">';
            echo '<tbody>';        
            echo '<tr><td>There are no plans</td></tr>';
            echo '</tbody>';
            echo '</table>';            
        }
    ?>
 
<?php
    echo $this->element('accordian_bottom', array());
?>

