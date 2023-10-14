<?php 
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	$this->getACMeetingTableTop($ac_meeting_id);

	echo "<span class='loud'>Notify AC members about the meeting</span><br>";
	echo "NOTE: The email below will only be sent out to the checked AC members.  By default NO AC members are checked to prevent emails being sent repeatedly.";
	echo "<hr>";
?>

<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>

<tr>
	<td valign="top" width="50%">
	<span class="special">In order for the following email:  
	<ul>
		<li>to be sent to a particular AC member click on the checkbox next to the AC members name.</li>  
		<li>to be sent to all the AC members click on the Email All checkbox.</li>
	</ul>
	</span>

	<?php 
		$this->formFields['ACmemberNotification']->fieldValue = $this->getTextContent("scheduleACMeeting", "Confirm AC Date");
		$this->showfield('ACmemberNotification');
	?>
	</td>

	<td valign="top">
	
	<span class="specialb">Check</span> to email message: <span class="specialb">Email All:</span> <input type='checkbox' name='checkall' onclick='checkedAll("defaultFrm");'>

	<table width="95%" border=0 align="center">
	<?php 


	$SQL  = "SELECT *
				FROM lnk_ACMembers_ACMeeting
				LEFT JOIN AC_Members ON lnk_ACMembers_ACMeeting.ac_member_ref = AC_Members.ac_mem_id
				LEFT JOIN AC_Meeting ON lnk_ACMembers_ACMeeting.ac_meeting_ref = AC_Meeting.ac_id
				WHERE lnk_ACMembers_ACMeeting.ac_meeting_ref = ?";
	//$SQL .= $ac_meeting_id;
        $conn = $this->getDatabaseConnection();
        $stmt = $conn->prepare($SQL);
        $stmt->bind_param("s", $ac_meeting_id);
        $stmt->execute();
        $rs = $stmt->get_result();
        
	//$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
	if (mysqli_num_rows($rs) > 0){
		while ($row = mysqli_fetch_array($rs)){
			echo "<tr><td width='5%'>";
			echo '<input name="overrideEmail_'.$row["ac_mem_id"].'" type="Checkbox">';
			echo "</td>";
			echo "<td>";
			echo $row["ac_mem_name"]." ".$row["ac_mem_surname"]." (".$row["ac_mem_email"].")";
			echo "</td></tr>";

		}

	}
	?>
	</table>

	</td>
	</tr></table>

</td></tr>
</table>
<br>

<?php 
/*

<!--script>
	function overrideACemail (obj) {
		if (obj.checked) {
			document.all.override_div.style.display = 'Block';
			//document.defaultFrm.FLD_override_sitevisit_decision_comment.focus();
		}else {
			document.all.override_div.style.display = 'none';
		}
	}

</script-->
*/
?>
<!--        Script by hscripts.com          -->
<!--        copyright of HIOX INDIA         -->
<!-- Free javascripts @ http://www.hscripts.com -->
<script type="text/javascript">
checked=false;
function checkedAll (frm1) {
	var aa= document.getElementById(frm1);
	 if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 aa.elements[i].checked = checked;
	}
      }
</script>
<!-- Script by hscripts.com -->

