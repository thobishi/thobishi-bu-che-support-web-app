<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
	<br>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="2">
			<?php echo $this->displayReaccredHeader($progID); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="loud">2.4 The programme and its context<hr></td>
	</tr>
	<tr>
		<td><br/><b>2.4.1</b> Describe how the programme aligns with the mission and goals of the institution.<br></td>
	</tr>
	<tr>
		<td><?php $this->showField("mission_goals");?><br><br></td>
	</tr>
	<tr>
		<td><b>2.4.2</b> Describe how the programme fits with national, regional and local priorities.<br></td>
	</tr>
	<tr>
		<td><?php $this->showField("nat_reg_priorities");?><br><br></td>
	</tr>
	<tr>
		<td>
		<hr>
		<b>2.4.3</b> Is this programme offered through distance education? <?php $this->showField("distance_education");?></td>
	</tr>
	<tr>
    	<td>
			<?php $displayStyle = $this->div_reacc($progID, 'distance_education', '2'); ?>
			<div id="is_distance" style="display:<?php echo $displayStyle?>">
			<br>
			<table class="oncolour" align="center">
			<tr>
				<td><b>2.4.3.1</b> Is the programme accredited for delivery by distance education 
				<?php $this->showField("delivery_accre");?>
				<br>
				</td>
			</tr>
			<tr>
				<td><b>2.4.3.2</b> What is the rationale for delivery through distance education to the intended target learners?<br></td>
			</tr>
			<tr>
				<td><?php $this->showField("rationale_for_delivery");?><br></td>
			</tr>
			</table>
			<br>
			</div>
			<hr>
		</td>
	</tr>
	<tr>
		<td><b>2.4.4</b> What is the organizational structure in which the programme is designed, managed, delivered and 
		administered?  Provide this information in narrative form or upload it in the form of an organogram.<br></td>
	</tr>
	<tr>
		<td>
			<table>
			<tr>
			<td><?php $this->showField("info_organogram"); ?></td>
			<td><?php $this->makeLink("info_organogram_doc");?></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><b>2.4.5</b> Describe how the institution's planning, approval, and quality assurance processes ensure the continuing viability of the programme.<br></td>
	</tr>
	<tr>
		<td><?php $this->showField("institution_planning");?><br><br></td>
	</tr>
	<tr>
		<td><b>2.4.6</b> Describe how the institution's resource allocation ensures the continuing viability of the programme. 
		Provide this information in narrative form or upload it in the form of a table that details the allocation of resources 
		to the programme.<br></td>
	</tr>
	<tr>
		<td>
			<table>
			<tr>
			<td><?php $this->showField("resource_allocation");?></td>
			<td><?php $this->makeLink("resource_allocation_doc");?></td>
			</tr>
			</table>
		</td>
	</tr>
</table>
