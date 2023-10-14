<?php
	
	if(!empty($detailArr)){
?>
		<table class="table table-hover table-bordered table-striped additionalDocs">
			<thead>
				<tr>
					<th>Title<small> (short)</small></th>
					<th>Description</th>
					<th>Link to doc</th>
					<th>Date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach($detailArr as $detail){
					echo '<tr>';
					echo '<td>'. $detail['additional_doc_title'] .'</td>';
					echo '<td>'. $detail['additional_doc_description'] .'</td>';
					echo '<td>'. $detail['docLink'] .'</td>';
					echo '<td>'. $detail['date_uploaded'] .'</td>';
					echo '<td>'. $detail['editDoc'] . ' '. $detail['deleteDoc'] .'</td>';
					echo '</tr>';
				}
?>				
			</tbody>
		</table>
<?		
	}else{
		echo '<div class= "alert alert-info">No additional documents found. Please click on <strong>"ADD DOC"</strong> icon on the top left action bar to add additional documents</div>';
	}
