<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
<?
	$project_id = $this->dbTableInfoArray["project_detail"]->dbTableCurrentID;
	$category = dbConnect::getValueFromTable("lkp_project_categories", "category_id",3 , "category_desc");
?>
<table width="100%" border=0 align="left" cellpadding="2" cellspacing="2">
<tr><td>
	<table width="90%" border=0 align="left" cellpadding="2" cellspacing="2">

	<tr>
		<th colspan="2" align="left">
			<br>
			Event Details
			<hr>
		</th>
	</tr>
	<tr>
		<td align="right" width="20%"><b>Programme:</b></td>
		<td class="oncolourb">
		<?
			$this->showField("directorate_ref");
		?>
		</td>
	</tr>
	<tr>
		<td align="right"><b>Category:</b></td>
		<td class="oncolourb"><?php echo $category?></td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>Event short title:</b></td>
		<td class="oncolourb">
			<table>
				<tr><td><i>Short event titles are required to enable the finance department to <b>list events on financial statements</b>. These statements are being done on Excel-spreadsheets and long titles take up too much space. Find a short title that will <b>minimise the risk of confusion</b> with other short event titles. Avoid the use of acronyms as far as possible.</i></td></tr>
				<tr><td><?	$this->showField("project_short_title");	?></td></tr>
			</table>
		</td>
	</tr>
	<tr>
			<td colspan="2">
			<?
					$this->formFields["category_ref"]->fieldValue = 3;
					$this->showField("category_ref");
			?>
			</td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>Event full title:</b></td>
		<td class="oncolourb">
			<table>
				<tr><td><i>The event title must reflect the <b>key purpose and scope of the event</b>. It has to be detailed enough to ensure that the event is not easily confused with other CHE events. Avoid the use of acronyms as far as possible.</i></td></tr>
				<tr><td><?	$this->showField("project_full_title");	?></td></tr>
			</table>
		</td>
	</tr>
	<tr>
			<td align="right" width="20%" valign="top"><b>Project Code/s:</b></td>
			<td class="oncolourb">

				<table>
					<tr><td><i>The pastel project codes for this project, assigned by the finance department.  Any expenditure for this 
					project is captured in Pastel against this project code for the particular year.  These may
					change from one year to the next for a particular project.</i>
					</td></tr>
					<tr>
						<td>
						<?
// 2009-04-17: Robin
// Replaced this by a grid so that the user can link the pastel project codes themselves.
//						$projcodes = implode("",$this->getProjectCodes($project_id));
//						if ($projcodes > ""){
//							echo $projcodes;
//						} else {
//							echo "<b>NB NB This project does not appear in the required project list.  Please register it by going to
//							the menu: Admin/List of required projects and adding it. Please note that financial information
//							cannot be extracted for this project until registered.</b>";
//						}
?>
						<table>
<?
						$headArr = array();
						array_push($headArr, "Budget Year");
						array_push($headArr, "Pastel Project Code");
						array_push($headArr, "Pastel Project Name");
						array_push($headArr, "Programme");

						$fieldArr = array();
						array_push($fieldArr, "type__select|name__budget_year|description_fld__lkp_budget_year|fld_key__lkp_budget_year|lkp_table__lkp_budget_year|lkp_condition__1|order_by__lkp_budget_year");
						array_push($fieldArr, "type__text|name__proj_code|size__5");
						array_push($fieldArr, "type__text|name__proj_description|size__30");
						array_push($fieldArr, "type__select|name__directorate_ref|description_fld__directorate_description|fld_key__lkp_directorate_id|lkp_table__lkp_directorate|lkp_condition__1|order_by__directorate_description");

						$this->gridShowRowByRow("project_required_list", "project_required_list_id", "project_ref__".$project_id, $fieldArr, $headArr, 4, 2, "Add financial link",true);
?>
						</table>						
						</td>
					</tr>
					<tr>
						<td style="font:bold italic 8pt">NB NB Deletion of a link means that expenditure information will no longer be drawn from Pastel for that link.  Links
					should only be deleted if erroneously assigned to the project.</td>
					</tr>
				</table>
			</td>
	</tr>
	<tr>
		<td align="right"><b>Role players involved:</b></td>
		<td class="oncolourb"><?php echo $this->showField("role_players_involved") ?></td>
	</tr>
	<tr>
			<td align="right" valign="top"><b>Budget per year:</b></td>
			<td>
			<table>
			<?
				$headArr = array();
				array_push($headArr, "Budget Year");
				array_push($headArr, "Planned budget");
				array_push($headArr, "Revised budget");

				$fieldArr = array();
				array_push($fieldArr, "type__select|name__budget_year|description_fld__lkp_budget_year|fld_key__lkp_budget_year|lkp_table__lkp_budget_year|lkp_condition__1|order_by__lkp_budget_year");
				array_push($fieldArr, "type__text|name__planned_budget|size__20");
				array_push($fieldArr, "type__text|name__revised_budget|size__20");

				$this->gridShowRowByRow("project_budget_per_year", "project_budget_per_year_id", "project_ref__".$project_id, $fieldArr, $headArr, 4, 2, "Add budget",false);
			?>
			</table>
			</td>
	</tr>

<!--

	<tr>
		<td align="right" valign="top">Researcher:</td>
		<td class="oncolourb">
		<?// $this->showField("researcher") ?>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top" width="18%">Status of data:</td>
		<td class="oncolourb"><?php echo //$this->showField("data_status") ?></td>
	</tr
	<tr>
		<td align="right" valign="top">Date name received:</td>
		<td class="oncolourb">
		<?
			//echo $this->showField("date_name_received");
		?>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">Date researched:</td>
		<td class="oncolourb"><?// $this->showField("date_researched") ?></td>
	</tr>

	<tr>
		<td align="right" valign="top">Comments:</td>
		<td class="oncolourb"><?// $this->showField("special_pensions_comments") ?></td>
	</tr
	<tr>
		<td align="right" valign="top">To be uploaded on the web:</td>
		<td class="oncolourb"><?// $this->showField("uploaded") ?></td>
	</tr>
	<?php
	/*
	// Allow administrator to classify if status is researched or higher
	if ($this->sec_partOfGroup(1) && $this->formFields['data_status']->fieldValue >= 2 ){

		$current_user_name = $this->getCurrentUserInfo();
		$current_user_name .= " ".$this->getCurrentUserInfo("surname");
*/	?>
			<th colspan="2" align="left">
				<br>
				If the research for this person has been completed and checked please complete the categorisation information for this person
				<hr>
			</td>
			<tr>
				<td align="right" valign="top"  width="20%">Classification category:</td>
				<td class="oncolourb">
				<?
					//$this->showField("categorised_data_status");
				?>
			</tr>
			<tr>
				<td align="right" valign="top" width="18%">Categorised by:</td>
				<td class="oncolourb">
				<?/*
					echo $current_user_name;
					$this->formFields['name_classifier']->fieldValue = $current_user_name;
					$this->showField("name_classifier");
				*/?>
			</tr>
			<tr>
				<td align="right" valign="top" width="18%">Categorisation date:</td>
				<td class="oncolourb">
				<?/*
					$date_classified = date("Y-m-d h:i:s");
					$this->formFields['date_classified']->fieldValue = $date_classified;
					$this->showField("date_classified");
					echo $date_classified;
				*/?>
			</tr>
			<tr>
				<td align="right" valign="top" width="18%">Notes:</td>
				<td class="oncolourb">
				<?
					//$this->showField("notes_classified");
				?>
		</tr>
-->



	</table>

	<br>
	<br>
	</td>
	</tr>
</table>

<?
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
?>



<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
