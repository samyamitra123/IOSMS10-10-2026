<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');

include('Net/SFTP.php');

//$target_ip='202.61.117.90';
//$user_name='gen006';
//$password='msgp@321';

$sftp = new Net_SFTP('202.61.117.90');
if (!$sftp->login('gen006', 'msgp@321')) 
{
	echo 'Failed';
}
else
{
	echo 'Success6666';
}


?>