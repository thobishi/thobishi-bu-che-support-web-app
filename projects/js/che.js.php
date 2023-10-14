<?
$run_in_script_mode = true;

require_once ("_systems/che_projects.php");

$app = new CHEprojects (1);
$i=0;
	foreach ($app->public_holidays AS $value) {
?>
		holiday_arr[<?php echo $i?>] = '<?php echo $value?>';
<?
		$i++;
	}

?>
		private_docs = '<?php echo echo $app->private_docs?>';
		public_docs  = '<?php echo echo $app->public_docs?>';
