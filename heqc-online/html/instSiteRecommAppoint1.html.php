<?php
	$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_app_proc_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Appoint user to do the site visit recommendation
	</td>
</tr>
<tr>
	<td>
<?php 
		$ru_id = "";

		$ru = $this->getSelectedRecommUserForSiteApplication($site_app_proc_id);
		$html_users = <<<MESSAGE
			<b>No user has been assigned to the Directorate Recommendation Portal for the above institution and site visits.  
			Please indicate the required user below by clicking on the required radio button in Assign.</b>
			<br>
			<br>
MESSAGE;

		if (count($ru) > 0){
			$ru_id = $ru["user_id"];
			$html_users = <<<USERS
				<b>The following user has been assigned to the Directorate Recommendation Portal for this application.</b>
				<table align="center" width="90%" border=0  cellpadding="2" cellspacing="0">
					<tr class="oncolour"><td>Name</td><td>Email</td><td>Phone numbers</td></tr>
USERS;
			$html_users .= <<<USERS
				<tr><td class="saphireframe">$ru[user_name]</td><td class="saphireframe">$ru[email]</td><td class="saphireframe">$ru[contact_nr] $ru[contact_cell_nr]</td></tr>
USERS;
			$html_users .= <<<USERS
				</table>
USERS;
		}
		
		$dir_users = $this->getRecommendationUsers($ru_id);

?>
	</td>
</tr>
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
				Please select the user that will complete the directorate recommendation for the institution and site visits above. 
				A user must be an active HEQC-online user that belongs to the Directorate Recommendation security group.  
				Please contact the HEQC-online administrator if the user you require does not appear in the list
				and request them to add the user as a recommendation user.<br><br>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo $html_users; ?>				
			</td>
		</tr>
		<tr>
			<td>
				<br><br><hr>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo $dir_users; ?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
