<?php
	if($login){
		if($message > ''){
?>
		<div class="alert alert-block login-alert">
			<?php
				echo $message;
			?>
		</div>
<?php
		}
	}else{
?>
		<script>
			$(function(){
				loginAttemptsError('<div class="alert alert-error"><?php echo $message; ?></div>');
				
				setTimeout(function() {
					goto(1);
				}, 7500);
			});
		</script>
<?php
	}
?>