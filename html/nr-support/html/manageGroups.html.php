<?php
	$sql = "SELECT * FROM sec_Groups WHERE group_show <> 0 ORDER BY sec_group_desc";
	$rs = $this->db->query($sql);
	$content = "No security groups have been created yet. Click on Add to add a new security group.";
	if ($rs->rowCount() > 0){
		$content = "";
		while ($row = $rs->fetch()) {
			$link = $this->scriptGetForm('sec_Groups', $row["sec_group_id"], '_admin_manageGroupsEdit');
			$edit = "<a href='" . $link . "'><img src='images/edit.png' alt='Edit' /></a>";

			$content .= <<<CONTENT
				<tr>
					<td>{$row["sec_group_id"]}</td>
					<td>{$row["sec_group_desc"]}</td>
					<td>{$row["sec_group_type"]}</td>
					<td>{$row["goal"]}</td>
					<td>{$edit}</td>
				</tr>
CONTENT;
		}
	}
	$html = <<<HTML
		<table class="table table-hover table-bordered table-striped ">
			<thead>
				<tr>
					<th>Id</th>
					<th>Description</th>
					<th>Type</th>
					<th>Goal</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				{$content}
			</tbody>
		</table>
HTML;
	echo $html;
?>
