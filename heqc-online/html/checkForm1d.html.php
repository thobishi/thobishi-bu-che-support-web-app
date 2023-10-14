<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<?php 
if ($is_at_manager && !$did_payment) {
?>

<b>
This process is ready to go to the screening process.<br>
Use the table above to read through the application form and institutional profile and read the comments in the table below to make your decision  on the appropriate action to take.
<br><br>
Note: The actions on the "Actions" menu.
</b><br><br>
<?php 
}
?>
<table cellpadding="2" cellspacing="2" border="1" width="90%">
<?php 

	$headArr = array();
	array_push($headArr, "AREA");
	array_push($headArr, "");
	array_push($headArr, "COMMENTS");

	$fieldArr = array();
	array_push($fieldArr, "type__radio|name__yes_no|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");
	array_push($fieldArr, "type__textarea|name__comments_text");

	$this->gridShow("screening_completion", "screening_completion_id", "screening_ref__".$this->dbTableInfoArray["screening"]->dbTableCurrentID, $fieldArr, $headArr, "lkp_application_complete", "lkp_application_complete_id", "lkp_application_complete_desc", "lkp_application_complete_ref");

?>
</table>

<br><br>

</td></tr></table>

<?php//echo "debug".$is_at_manager ?>
<script>

	function changeProcUser (num) {
		document.defaultFrm.gotoManager.value = num;
		return true;
	}
	function changeToInst (num) {
		document.defaultFrm.gotoInst.value = num;
		return true;
	}
	function cancelProc (num) {
		document.defaultFrm.doCancelProc.value = num;
		return true;
	}

	function checkTable(obj) {
		var obj = document.defaultFrm;
		count = 0;
		if (obj.MOVETO.value == 'next') {
			for (i=0; i<obj.length; i++) {
				if ((obj[i].type == 'radio') && (obj[i].checked)) {
					count++;
				}
			}
			if (count < 1) {
				alert("Please complete the yes/no questions.");
				return false;
			}
<?php 
if (!$is_at_manager && $did_payment != "") {
?>
			else {
				for (i=0; i<obj.length; i++) {
					if ((obj[i].type == 'radio') && (obj[i].checked)) {
						if (obj[i].value == 1) {
							alert("This process will be handed over to your manager.");
							changeProcUser(1);
							return true;
						}
					}
				}
			}
		}
<?php 
} else {
?>
		} //end if
<?php 
	}
?>
		return true;
	}
</script>
