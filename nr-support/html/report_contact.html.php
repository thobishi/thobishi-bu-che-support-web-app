<h3>Institutions contact list</h3>
<?php
	echo $this->element('filters/' . Settings::get('template'), $_POST);
	$details = $this->getInstContactDetails($_POST, Settings::get('template'));
	
	if(!empty($details)){
?>
		<table class="table table-hover table-bordered table-striped">
			<thead>
				<tr>
					<th>
						Institution code
					</th>
					<th>
						Institution name
					</th>
					<th>
						Programme abbr.
					</th>
					<th>
						Contact type
					</th>
					<th>
						Title
					</th>
					<th>
						Name
					</th>
					<th>
						Surname
					</th>
					<th>
						Initials
					</th>
					<th>
						Email
					</th>
					<th>
						Tel.
					</th>
					<th>
						Fax
					</th>
					<th>
						Mobile
					</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach($details as $institution => $instInfo){
					foreach($instInfo as $type => $info){
						echo '<tr>';
						echo '<td>' . ((isset($info['hei_code'])) ? ($info['hei_code']) : '') . '</td>';
						echo '<td>' . ((isset($info['hei_name'])) ? ($info['hei_name']) : '') . '</td>';
						echo '<td>' . ((isset($info['nr_programme_abbr'])) ? ($info['nr_programme_abbr']) : '') . '</td>';
						echo '<td>' . $type . '</td>';
						echo '<td>' . ((isset($info['title'])) ? ($info['title']) : '') . '</td>';
						echo '<td>' . ((isset($info['name'])) ? ($info['name']) : '') . '</td>';
						echo '<td>' . ((isset($info['surname'])) ? ($info['surname']) : '') . '</td>';
						echo '<td>' . ((isset($info['initials'])) ? ($info['initials']) : '') . '</td>';
						echo '<td>' . ((isset($info['email'])) ? ($info['email']) : '') . '</td>';
						echo '<td>' . ((isset($info['tel'])) ? ($info['tel']) : '') . '</td>';
						echo '<td>' . ((isset($info['fax'])) ? ($info['fax']) : '') . '</td>';
						echo '<td>' . ((isset($info['mobile'])) ? ($info['mobile']) : '') . '</td>';
						echo '</tr>';
					}
				}
?>
			</tbody>
		</table>
<?php
	}else{
		echo 'No results found';
	}
?>