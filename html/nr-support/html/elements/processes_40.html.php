<?php
    $currentUserID = Settings::get('currentUserID');
    echo $this->element('accordian_top', array('accHeader' => $processHeading, 'collapse' => 'Improvement Plan'));
 
    echo '<pre>processes';
    echo '<pre>processes';
print_r($processes);
echo '<br>processHeading';
print_r($processHeading);
echo '<br>groupProcesses';
print_r($groupProcesses);
echo '<br>processRef';
print_r($processRef);
print_r($processes);

?>
 
    <?php
        if(!empty($processes)){
            echo '<table class="table table-hover table-bordered table-striped">';
            echo '<tbody>';            
        if($processes[1]['processes_id'] == 40){ // checking active_process table column hence non string                
          echo '<tr><td><a href="javascript:goto(' . $processes[1]['processes_id'] . ');">' . $processes[1]['processes_desc'] . '</td></tr>';
        }
            echo '</tbody>';
            echo '</table>';
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