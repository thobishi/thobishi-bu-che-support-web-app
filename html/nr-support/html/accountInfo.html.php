<?php

	$sql = 'SELECT * FROM users  LEFT JOIN lkp_title ON (lkp_title.lkp_title_id = users.title_ref) 
	WHERE user_id = '.Settings::get('currentUserID').'';
	$rs = $this->db->query($sql);
	$content = "This User does not exist";
	
	if ($rs->rowCount() > 0){
		$content = "";
		while ($row = $rs->fetch()) {
			$link = $this->scriptGetForm('users', $row["user_id"], '_user_accountInfoEdit');
			$edit = "<a href='" . $link . "'><img src='images/edit.png' alt='Edit' /></a>";

			$content .= <<<CONTENT
				<tr>
					<td>{$row["lkp_title_desc"]}</td>
					<td>{$row["name"]}</td>
					<td>{$row["surname"]}</td>
					<td>{$row["email"]}</td>
					<td>{$row["contact_nr"]}</td>
					<td>{$row["contact_cell_nr"]}</td>
					<td>{$edit}</td>
				</tr>
CONTENT;
		}
	}
	$html = <<<HTML
		<table class="table table-hover table-bordered table-striped ">
			<thead>
				<tr>
					<th>Title</th>
					<th>Name</th>
					<th>Surname</th>
					<th>Email</th>
					<th>Contact Number</th>
					<th>Contact Cell Number</th>
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
