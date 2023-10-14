<?php

$conn = $this->getDatabaseConnection();

	$this->formFields["search_name"]->fieldValue = readPost('search_name');
	$this->formFields["search_institution"]->fieldValue = readPost('search_institution');
	$this->formFields["search_active"]->fieldValue = readPost('search_active');
	$this->showField("search_name");
	$this->showField("search_institution");
	$this->showField("search_active");

	//var uemail=$this->formFields["email"]->fieldValue;

	

		
	//createUservalidation($uemail); 
	



?>


<br>

<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	<tr>
		<td>Title:</td>
		<td><?php echo $this->showField("title_ref")?></td>
	</tr>
	<tr>
		<td>Name:</td>
		<td><?php echo $this->showField("name")?></td>
	</tr>
	<tr>
		<td>Surname:</td>
		<td><?php echo $this->showField("surname")?></td>
	</tr>
	<tr>
		<td>E-mail:</td>
		<td><?php echo $this->showField("email");
		
		
		?></td>

		
		
	
	</tr>

	<tr>
		<td></td>

		<td><span id = "email_status"></span></td>
		
	
	</tr>
	<tr>
	<td ></td>
	<td><span id = "email_status"></span></td>
</tr>
	<tr>
		<td>Institution:</td>
		<td><?php echo $this->showField("institution_ref")?></td>
	</tr>
	<tr>
		<td>Contact Number:</td>
		<td><?php echo $this->showField("contact_nr")?></td>
	</tr>
	<tr>
		<td>Mobile number:</td>
		<td><?php echo $this->showField("contact_cell_nr")?></td>
	</tr>
	<tr>
		<td>Login status:<br><span class="specials"><i>(whether user can login or not)</i></span>:</td>
		<td><?php echo $this->showField("active")?></td>
		
	</tr>
	</table>
</td></tr></table>


<script>
$(document).ready(function(){ 
	$('[name="FLD_email"]').bind('keyup change', function() {
		var userEmail = $(this).val();
		$('#email_status').text('Searching database.');

		if(userEmail != ''){
			var data = checkEmail(userEmail);			
			var message = (data == 'true') ?  "E-mail " + userEmail +" does not exist,You may continue" : "A user with the E-mail " + userEmail +" already exist!!";  
			addCss(data);
			$('#email_status').text(message);			
			
		} else {
			$('#email_status').text('');
		}

	});
	$('#action_stay > a, #action_previous > a').click(function(e) {
		var emailValue = $('[name="FLD_email"]').val();
		var data = checkEmail(emailValue);		
		var message = "A User with the E-mail " + emailValue +" already exist.Please change it.";
		addCss(data);		
		if (data == 'false'){
			console.log("data output in if "+data);
			alert(message);
		//e.preventDefault();
			$('#email_status').text(message);
			alert(message);
		}
	});
	function addCss(data){
		if(data == 'true'){
			
			$('#email_status').css("color","green");
				
		document.getElementById('action_next').style.display = "block"; 
  		document.getElementById('action_next_Img').style.display = "block"; 
		}else{
			$('#email_status').css("color","red");

			document.getElementById('action_next').style.display = "none";
  			document.getElementById('action_next_Img').style.display = "none";
		}
	}
	function checkEmail(userEmail){
console.log(userEmail);
		var retval;
		$.ajax({
			 type: 'POST',
			  url: 'https://heqc-online-1.che.ac.za/pages/checkDuplicateUserEmail.php',
			  data: { userEmail: userEmail },
			  success: function(data){retval = data;},
			  async:false
		});
		return retval;
		
	}


});
</script>

