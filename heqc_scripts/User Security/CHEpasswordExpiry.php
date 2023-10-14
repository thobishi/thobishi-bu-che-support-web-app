<?php

$checkID = 'aessop';
$warnDays = 20;

require_once ('adLDAP/adLDAP.php');
$connect = array(
	'base_dn'=>'DC=che,DC=ac,DC=za', 
	'account_suffix'=>'@che.ac.za',
	'domain_controllers'=>array('192.168.1.6','192.168.1.4'),
	'admin_username'=>'intranet',
	'admin_password'=>'in@check4che'
);


function CheckUser ($adldap, $userID) {
	global $warnDays;

	$userinfo = $adldap->user()->info($userID, array('cn','givenname','sn','description','distinquishedname','sAMAccountName','mail','displayname'));

	$userName = $userinfo[0]["displayname"][0];
	$userEmail = $userinfo[0]["mail"][0];

	$expiry = $adldap->user()->passwordExpiry($userID);
	$now = time(); // or your date as well
	$exp_date = $expiry['expiryts'];
	$datediff = $exp_date - $now;
	$daysLeft =  floor($datediff/(60*60*24));
	$expNice = date ("jS F Y \a\\t G:i", $exp_date);

	if ($daysLeft > $warnDays) return;

	$daysText = ( ($daysLeft != 1) ? ("{$daysLeft} days") : ("{$daysLeft} day") );

	$emailText = <<<TXT
<font face="arial">
Dear {$userName},<br />
<br />
Please note that your CHE password will expire in <b>{$daysText}</b> on the {$expNice}.<br />
<br />
To change your password over the internet follow these instructions:<br />
<br />
Open the CHE webmail in your browser: <a href="http://webmail.che.ac.za">http://webmail.che.ac.za</a><br />
<ul>
<li>Sign on using your username: <b>{$userID}</b> and use your existing password<br />
<li>At the top right of your screen, click <b>Options</b><br />
<li>On the left-hand menu, click <b>Change Password</b><br />
<li>In the <b>Old Password</b> field, enter your existing password<br />
<li>In the <b>New Password</b> field, enter a new password	<br />
<li>In the <b>Confirm New Password</b> field, re-enter the new password <br />	
<li>At the top left of these fields, click <b>Save</b> <br />
<li>If there is no error message below the save button your password was successfully changed<br />
</ul>
<br />
Kind regards,<br />
CHE support - Octoplus<br />
012 346 4823<br />
<br />
</font>
TXT;

	require_once ('phpmailer/class.phpmailer.php');
	$mail = new PHPMailer();
	$mail->IsSMTP(true); 
	$mail->IsHTML(true);
	$mail->Host = '127.0.0.1';
	$mail->SetFrom('support@che.ac.za', 'CHE Support');
	$mail->Subject = "CHE Support: Your password will expire in  {$daysText}";
	$mail->Body = $emailText;
	$mail->AddAddress($userEmail); // $userEmail
	if(!$mail->Send()) {
		$error = "Mail error ({$userEmail}): ".$mail->ErrorInfo; 
		echo $error;
		return false;
	} else {
		$error = "Message sent to {$userEmail} !";
		echo $error;
		return true;
	}
}


$adldap = new adLDAP($connect);

CheckUser ($adldap, $checkID);

//$adldap->close();

?>
