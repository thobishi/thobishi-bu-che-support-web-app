<h3>Manage active processes</h3>

<?php
	$detailArr = $this->getActiveProcessesDetails();
	if(!empty($detailArr)){
?>
<table class="table table-hover table-bordered table-striped">
	<thead>
		<tr>
			<th>User</th>
			<th>Process</th>
			<th>Institution information</th>
			<th>Last Update</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
<?php 
		foreach($detailArr as $detail){
			$name = $detail['name'] . " ". $detail['surname'] . "\n" . $detail['email'];
			$description = (isset($detail['processDescription'])) ? $detail['processDescription'] : '';
			$institutionInfo = (isset($detail['InstitutionName'])) ? $detail['InstitutionName'] : '';
			$last_updated =  $detail['last_updated'];
?>			
	
		<tr>
			<td><?php echo $name ; ?></td>
			<td><?php echo $description ; ?></td>
			<td><?php echo $institutionInfo ;?></td>
			<td><?php echo $last_updated ;?></td>
			<td><?php echo $detail['changeUserEdit'] ; ?></td>
		</tr>
<?php 
		}
?>
	</tbody>
</table>
<?php
	}else{
		echo "No active processes found";
	}