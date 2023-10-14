<?php
$run_in_script_mode = true;

if(strpos(__FILE__, 'wl') !== false) {
	define ('CONFIG', 'WLDEV');
}
$path = dirname(dirname(__FILE__)) . '/';
require_once ("/var/www/common/_systems/nr-online.php");

$app = new NRonline (1);
$i=0;
	foreach ($app->public_holidays AS $value) {
?>
		holiday_arr[<?php echo $i; ?>] = '<?php echo $value; ?>'
<?php
		$i++;
	}

?>
