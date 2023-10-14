<?php
	if(!empty($detailArr)){
?>		
	<table class="table table-hover table-bordered table-striped">
	<thead>
		<tr>
			<th>Setting key</th>
			<th>Setting value</th>
			<th>Setting description</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
<?php
		foreach ($detailArr as $detail){
			echo '<tr>';
				echo '<td>' . $detail['s_key'] . '</td>';
				echo '<td>' . $detail['s_value'] . '</td>';
				echo '<td>' . $detail['s_description'] . '</td>';
				echo '<td>' . $detail['editSetting'] . '</td>';
			echo '</tr>';
			
		}
?>
	</tbody>
	</table>
<?php	
	}else{
		echo "no settings found";
	}
?>