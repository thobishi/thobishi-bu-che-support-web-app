<div class="nr_progressReportDiv">
<table class="table table-bordered table-hover nr_progress">
	<thead>
		<tr>
			<th  class="rg_tableTh" colspan="4">
				<strong>Institution: </strong> <?php echo $institution_name; ?>
			</th>	
		</tr>
		<tr>
			<th  class="rg_tableTh" colspan="4">
				<strong>Programme: </strong> <?php echo $nr_programme_name ; ?>
			</th>	
		</tr>		
		<tr>
			<th>Criteria</th>
			<th>Description</th>
			<th>Institution self-evaluation</th>
			<th>Panel evaluation</th>
<?php		
		if($isNRC_member || $isRgMember){
			echo '<th>Recommendation evaluation</th>';
		}
?>			
		</tr>
	</thead>
	<tbody>
<?php 

	foreach($ratingArr as $rating){
		echo '<tr>';
		echo '<td>' . $rating['criterion_title'] . '</td>';
		echo '<td>' . $rating['short_desc'] . '</td>';
		echo '<td>' . $rating['institutionRatingDesc'] . '</td>';
		echo '<td>' . ($rating['not_applicable'] != 0 ? "Not applicable"  : $rating['panelRatingDesc']) . '</td>';
		if($isNRC_member || $isRgMember){
			echo '<td>' . ($rating['not_applicable'] != 0 ? "Not applicable"  : $rating['recommRatingDesc']) . '</td>';
		}
		echo '</tr>';
	}
?>	
	</tbody>
</table>
</div>