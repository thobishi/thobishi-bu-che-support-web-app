<?php 
$run_in_script_mode = true;

require_once ("_systems/contract/contract.php");

$app = new contractRegister (1);
$i=0;
	foreach ($app->public_holidays AS $value) {
?>
		holiday_arr[<?php echo $i?>] = '<?php echo $value?>';
<?php 
		$i++;
	}

?>
		private_docs = '<?php echo echo $app->private_docs?>';
		public_docs  = '<?php echo echo $app->public_docs?>';
