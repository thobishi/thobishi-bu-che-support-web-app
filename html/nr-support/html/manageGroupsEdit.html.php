
<?php
	$groupId = $this->dbTableInfoArray["sec_Groups"]->dbTableCurrentID;
	$groupName = $this->getSecGroupName($groupId);
	


	echo '<h2>Group Name: ' . $groupName['name'] . '</h2><br>';
	echo '<div class= "alert alert-info"><strong>Choose the users you want to add the "' . $groupName['name'] . '" group by clicking on their name on the list of "Available users to select". You may also remove users from this group by click on their name on the "Selected users" list</strong></div>';
	echo '<div class = "row-fluid">';
	$this->manageGroupMemberDropdown($groupId, "selected");
	$this->manageGroupMemberDropdown($groupId, "available");
	echo '</div>';
	
	if($groupId == $this->getGroupId('RC member')){
		echo '<div class="accordion" id="accordionHome"><br><br>';
		echo '<div class="alert alert-info">To add a user programmes restrictions or assignment, go to <strong><a href = "javascript:goto(6);">Manage users</a></strong> and click on the "Edit" link next to that specific user name.</div>';
		echo $this->element('accordian_top', array("accHeader" => "View restricted users' programmes", "collapse" => "restrictions"));
		$restrictionArr = $this->getMeetingRestriction();
		echo $this->element('users_programme_restrictions' , compact('restrictionArr'));
		echo $this->element('accordian_bottom', array());
		
		echo $this->element('accordian_top', array("accHeader" => "View programmes assigned to users", "collapse" => "assignment"));
		$assignmentArr = $this->getMeetingProgAssigned();
		echo $this->element('users_programme_assignment' , compact('assignmentArr'));
		echo $this->element('accordian_bottom', array());		
		
	}
	
	if($groupId == $this->getGroupId('NRC member')){
		echo '<div class="accordion" id="accordionHome"><br><br>';
		echo '<div class="alert alert-info">To add a user programmes restrictions or assignment, go to <strong><a href = "javascript:goto(6);">Manage users</a></strong> and click on the "Edit" link next to that specific user name.</div>';
		echo $this->element('accordian_top', array("accHeader" => "View restricted users' programmes", "collapse" => "restrictions"));
		$restrictionArr = $this->getMeetingRestriction("","nr_meeting_programmes_restrictions");
		echo $this->element('users_programme_restrictions' , compact('restrictionArr'));
		echo $this->element('accordian_bottom', array());
		
		echo $this->element('accordian_top', array("accHeader" => "View programmes assigned to users", "collapse" => "nrc_assignment"));
		$assignmentArr = $this->getMeetingProgAssigned("","nr_meeting_programmes_assignment" );
		echo $this->element('users_programme_assignment' , compact('assignmentArr'));
		echo $this->element('accordian_bottom', array());		
	}
	
	$isAdministrator = $this->sec_partOfGroup(1);
	if($isAdministrator){
		echo '<div class="clear"></div><br><br>';
		echo '<div class= "alert alert-info"><strong>Choose the processes you want to add the "' . $groupName['name'] . '" group by clicking on their description on the list of "Available processes to select". You may also remove processes from this group by click on their description on the "Selected processes" list</strong></div>';
		echo '<div class = "row-fluid">';
		$this->manageGroupProcessDropdown($groupId, "selectedProcess");
		$this->manageGroupProcessDropdown($groupId, "availableProcess");
		echo '</div>';
	}
?>
<script>
function selectAll() {

	sLength = document.defaultFrm.elements['userSelected[]'].length;
	for (i=0; i<sLength; i++) {
		document.defaultFrm.elements['userSelected[]'].options[i].selected = true;
	}
	if (typeof document.defaultFrm.elements['selectedProcess[]'] != "undefined") {
		sLength2 = document.defaultFrm.elements['selectedProcess[]'].length ;
		for (i=0; i<sLength2; i++) {
			document.defaultFrm.elements['selectedProcess[]'].options[i].selected = true;
		}
	
	}

	return true;
}
$(document).ready(function(){
	$('select').change(function(){
		var $this = $(this),
			optionSelected = $this.find(':selected'),
			parentId = optionSelected.parent('select')[0].id,
			newId,
			newOption = new Option(optionSelected.text(), optionSelected.val());
			 if(parentId == 'selected'){
				newId = 'available';
			}else if( parentId == 'available'){
				newId = 'selected';
			}else if(parentId == 'selectedProcess'){
				newId = 'availableProcess';
			}else{
				newId = 'selectedProcess';
			}	

		var s = document.getElementById(newId);
		s.options[s.options.length] = newOption;

		if(optionSelected){
			optionSelected.remove();
		}
	});


	
	// $('#generalActions').click( function(e) {
		// var selectionVar = document.getElementById('selected');
		 // for (var i=0; i<selectionVar.options.length; i++) {
			// selectionVar.options[i].selected = true;
		 // }
		
		// $('#selected').find('option').prop('selected', true);
		// $('#selectedProcess').find('option').prop('selected', true);
	// });
})
</script>