<?php

define ('ACCOUNTDISABLE', 2);
define ('LOCKOUT', 8);
define ('PASSWD_NOTREQD', 32);
define ('PASSWD_CANT_CHANGE', 64);
define ('NORMAL_ACCOUNT', 512);
define ('DONT_EXPIRE_PASSWORD', 65536);
define ('PASSWORD_EXPIRED', 8388608);

$noUseDays = 45;

$skipUsers = array('exadmin','Guest', 'krbtgt','Administrator','beuser','SQLDebugger','ASPNET','alerts','accreditation','heqcis','nod32','SqlDedicated','IUSR_INSTITUTIO','IWAM_INSTITUTIO','privatehesurvey','HRAdmin','main','HEQC-online','ibmdirector','enquiries','afreedom','ed','intranet','toshiba','NAVMSE','surveysc');

$passwordNotExpire = array();
$passwordExpired = array();
$accDisabled = array();
$accLockout = array();
$accNoPass = array();
$accNotUsed = array();

function connect_CHE() {
	$ldap_server = "ldap://delego.che.ac.za" ;
	$ldap_user   = "intranet@che.ac.za";
	$ldap_pass   = "in@check4che" ;

	$ad = ldap_connect($ldap_server) ;
	ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3) ;
	$bound = ldap_bind($ad, $ldap_user, $ldap_pass);

	return $ad ;
}

function showList ($name, $list) {
				if (! (count($list)>0) ) return ('');
				$ret = "{$name}:\n";
				foreach ($list as $k => $v ) {
								$ret .= "\t{$v} ($k)\n";
				}
				$ret .= "\n\n";
				return ($ret);
}

$che = connect_CHE();

$base_dn = 'CN=Users,DC=che,DC=ac,DC=za';
$filter = '(cn=*)';
$attr = array('cn','givenname','sn','description','distinquishedname','sAMAccountName','mail','userAccountControl','lastLogon');
$result = ldap_search($che, $base_dn, $filter, $attr);
 
$resultArray = array();
if ($result) {
	$entry = ldap_first_entry($che, $result);
	while ($entry) {
		$disabled = false;
		$row = array();
		$attr = ldap_first_attribute($che, $entry);
		while ($attr) {
			$val = ldap_get_values_len($che, $entry, $attr);
			if (array_key_exists('count', $val) AND $val['count'] == 1) {
				$row[strtolower($attr)] = $val[0];
			} else {
				$row[strtolower($attr)] = $val;
			}
			$attr = ldap_next_attribute($che, $entry);
		}

		if (isset($row['useraccountcontrol']) && ($row['useraccountcontrol'] & NORMAL_ACCOUNT) && (!in_array($row['samaccountname'], $skipUsers) ) ) {

			if ( $row['useraccountcontrol'] & DONT_EXPIRE_PASSWORD )
				$passwordNotExpire[$row['samaccountname']] = $row['cn'];

			if ( $row['useraccountcontrol'] & PASSWORD_EXPIRED )
				$passwordExpired[$row['samaccountname']] = $row['cn'];

			if ( $row['useraccountcontrol'] & ACCOUNTDISABLE ) {
				$accDisabled[$row['samaccountname']] = $row['cn'];
				$disabled = true;
			}

			if ( $row['useraccountcontrol'] & LOCKOUT )
				$accLockout[$row['samaccountname']] = $row['cn'];

			if ( $row['useraccountcontrol'] & PASSWD_NOTREQD )
				$accNoPass[$row['samaccountname']] = $row['cn'];

			if ( isset($row['lastlogon'] ) && !$disabled) {
        $dateLargeInt=$row['lastlogon']; // nano seconds (yes, nano seconds) since jan 1st 1601
        $secsAfterADEpoch = $dateLargeInt / (10000000); // seconds since jan 1st 1601
        $ADToUnixConvertor=((1970-1601) * 365.242190) * 86400; // unix epoch - AD epoch * number of tropical days * seconds in a day
        $unixTsLastLogon=intval($secsAfterADEpoch-$ADToUnixConvertor); // unix Timestamp version of AD timestamp
				$lastlogon=date("Y-m-d", $unixTsLastLogon); // formatted date

				$now = time(); // or your date as well
				$datediff = $now - $unixTsLastLogon;
				$daysSince =  floor($datediff/(60*60*24));

				if ($daysSince > $noUseDays) {
					$accNotUsed[$row['samaccountname']] = "{$row['cn']} {$lastlogon}";
				}
				
			}

			$resultArray[] = $row;
		}
		$entry = ldap_next_entry($che, $entry);
	}
}

echo showList ("Password Never Expires", $passwordNotExpire);
echo showList ("Password Expired", $passwordExpired);
echo showList ("Account Disabled", $accDisabled);
echo showList ("Account Lockout", $accLockout);
echo showList ("Account has no password", $accNoPass);
echo showList ("Account Not Used in the past {$noUseDays} days", $accNotUsed);

?>
