<?php

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib0.3.0');

include('Net/SFTP.php');
$sftp = new Net_SFTP('192.168.1.21');
if (!$sftp->login('dddd', 'ffff')) 
{
	echo 'Not Working';
} 
else
{
	echo 'Its working';
	/*$local_directory = '../../../readwrite/xml_file/';  // task list #1
	$remote_directory = './prd/ePayment_Files_006/';  // task list #2
	$file2 = 'TISTTS0010010705201800000004.xml';
	if($sftp->put($remote_directory . $file2,$local_directory . $file2,NET_SFTP_LOCAL_FILE))
	{
		echo 'File transfer success';
	}*/
 	
}


?>