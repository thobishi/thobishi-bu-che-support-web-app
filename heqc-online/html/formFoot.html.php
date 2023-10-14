  <td valign=top align="left" width="3" style="background: url(images/clear.gif); background-repeat: repeat-y;">
  <img src="images/blank.gif" width="1" height="1" alt="">
  </td>
  <td bgcolor="#C1D1E0" width="200" valign="top">
    <table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	  <tr>
	    <td bgcolor="#CC3300" align="center">
		<span class="whiteb">Actions</span>
		</td>
	  </tr>
	</table>
    <table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
			<td>
				<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
				<?php $this->showActions () ?>
				</table>
			</td>

	</tr>
	</table>
	<?php
		if($this->sec_loggedIn () && $this->userGroup('CHE', $this->currentUserID)){
			echo  $this->displayComments ();
		}
	?>
  </td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
  <td bgcolor="#90A8BF" height="1"></td>
</tr>
</table>
</form>

<div id="comment-dialog-form" title="Create new comment" class= "hidden">
	<!-- <p class="validateTips">Comment field is required.</p> -->
	<?php 
		$appRef = $this->dbTableInfoArray['Institutions_application']->dbTableCurrentID;

		
		$coment_date = date("Y-m-d");
		
		$user_ref = $this->currentUserID ;		
		$active_processes_id =  $this->active_processes_id; 
	?>
  <form>

    <label for="comment">Comment</label>
    <textarea type="text" name="comment" id="comment" rows="13" cols="45"> </textarea>
    <input type="hidden" name="application_ref" id="application_ref"  value = "<?php echo $appRef; ?>">
	<input type="hidden" name="coment_date" id="coment_date"  value = "<?php echo $coment_date; ?>" >
	<input type="hidden" name="user_ref" id="user_ref"  value = "<?php echo $user_ref;?>" >
	<input type="hidden" name="currentProcess" id="currentProcess"  value = "<?php echo $this->getCommentProcessDetails($active_processes_id);?>" >
	<input type="hidden" name="userName" id="userName"  value = "<?php echo $this->getUserName($user_ref, 2);?>">


  </form>
   
</div>

<div id="ViewAllcomment-dialog-form" title="View all comments" class= "hidden">

<div>
