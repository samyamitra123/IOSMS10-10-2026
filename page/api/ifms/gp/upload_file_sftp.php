<?php

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');

include('Net/SFTP.php');

$target_ip='202.61.117.90';
$user_name='gen006';
$password='msgp@321';

$sftp = new Net_SFTP($target_ip);
if (!$sftp->login($user_name, $password)) 
{
	echo 0;
}
else
{
	echo 1;
}


?>