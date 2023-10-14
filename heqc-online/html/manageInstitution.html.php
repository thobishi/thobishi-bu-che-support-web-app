<?php

	$fc_arr = array();

	$inst = readPost('search_instname');
	if ($inst > 0){
		array_push($fc_arr,'HEI_id = '.$inst);
	}
	
	$filter_criteria = (count($fc_arr) > 0) ? "WHERE ". implode(' AND ',$fc_arr) : "";
	
	$sql = <<<DATA
		SELECT
		HEI_id,
		HEI_code,
		HEI_name,
		lnk_priv_publ_desc,
		lkp_inst_user_role_desc,
		mid(background,1,295) as background
		FROM (HEInstitution)
		LEFT JOIN lnk_priv_publ ON lnk_priv_publ_id  = priv_publ
		LEFT JOIN lkp_inst_user_role ON lkp_inst_user_role_id = inst_user_role_ref
		$filter_criteria
		ORDER BY HEI_name
DATA;

	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	$n_inst = mysqli_num_rows($rs);
?>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="2" align="left" class="special1">
			<br>
			<span class="specialb">
			Manage Institutions
			</span>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td width="30%" align="right">Select institution: </td>
		<td>
			<?php 
			$this->formFields['search_instname']->fieldValue = $inst;
			$this->showField('search_instname');
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td><td><input type="submit" class="btn" id="submitButton" name="submitButton" value="Search" onClick="moveto('stay');"></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>
</table>

		<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	
		<tr>
			<td>
	<?php 		
				$html = <<<HTMLSTR
					<table border='0' width='95%' align='center' cellpadding='2' cellspacing='2'>
					<tr><td align="right" colspan="8"><b>Number of institutions: $n_inst</b></td></tr>
					<tr class="oncolourb">
						<td>Edit<br>Name</td>
						<td>Edit<br>Background</td>
						<td>Institution<br>Code</td>
						<td>Institution Name</td>
						<td>Background</td>
						<td>Public/Private</td>
					</tr>
HTMLSTR;

				while($row = mysqli_fetch_array($rs)){
					$sel = "";
					$bgcolor = "onblue";

					$edit_link = $this->scriptGetForm ('HEInstitution', $row["HEI_id"], 'next');
					$bg_link = $this->scriptGetForm ('HEInstitution', $row["HEI_id"], '_startInstManageBackground');

					$tmpSettings = "DBINF_HEInstitution___HEI_id=".$row["HEI_id"]."&DBINF_institutional_profile___institution_ref=".$row["HEI_id"];
					$inst_name = $row["HEI_name"];
					$inst_code = $row["HEI_code"];
					$link1 = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row["HEI_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$inst_name."</a>";
					$inst_type = $row["lnk_priv_publ_desc"];
					$background = $row["background"];
					//$inst_role = $row["lkp_inst_user_role_desc"]; // Removed because user can see it from H, PR or S in code.

					$html .= <<<HTMLSTR
					<tr class="$bgcolor">
						<td><a href='$edit_link'><img src="images/ico_change.gif"></a></td>
						<td><a href='$bg_link'><img src="images/ico_eval.gif"></a></td>
						<td>$inst_code</td>
						<td>$link1</td>
						<td>$background</td>
						<td>$inst_type</td>
					</tr>
HTMLSTR;
				}
				$html .= <<<HTMLSTR
						</table>
HTMLSTR;
				echo $html;
	?>			
			</td>
		</tr>
		</table>
