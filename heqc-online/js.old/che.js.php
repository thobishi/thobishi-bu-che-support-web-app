<?php

//header('Content-Type: text/javascript');

$run_in_script_mode = true;

if(strpos(__FILE__, 'wl') !== false) {
	define ('CONFIG', 'WLDEV');
}
$path = dirname(dirname(__FILE__)) . '/';
require_once ("/var/www/html/common/_systems/heqc-online.php");

$app = new HEQConline (1);
$i=0;
	foreach ($app->public_holidays AS $value) {
?>
		holiday_arr[<?php $i ?>] = '<?php $value ?>';
<?php
		$i++;
	}

?>
		private_docs = '<?php echo $app->private_docs ?>';
		public_docs  = '<?php echo $app->public_docs ?>';
