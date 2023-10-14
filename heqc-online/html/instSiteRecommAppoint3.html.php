<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Portal access letter for recommendation user
		<br>
	</td>
</tr>
<tr>
	<td align="center">
		<br />
		The letter of appointment was sent out to the selected recommendation user.  Indicate whether the recommendation user has accepted to do the directorate recommendation for this application.
		If the user has not answered or declined then please click Previous and assign this application to another user.
	</td>
</tr>
<tr>
	<td align="center">
	<br>
	<table align="center">
	<tr class="oncolour">
		<td>
		<?php 
			$recomm_user_id = $this->GetValueFromTable("inst_site_app_proceedings","inst_site_app_proc_id",$site_proc_id,"recomm_user_ref");
			echo $this->getUserName($recomm_user_id);
		?>
		</td>
		<td><?php $this->showfield('lop_status_confirm'); ?></td>
	</tr>
	</table>
	</td>
</tr>
<tr><td align="center">
<br>
	The recommendation user that has accepted to take part in the programme recommendation will have access to the programme until: <?php $this->showfield('recomm_access_end_date'); ?>
	<br>
	<br>
	</td>
</tr>

</table>