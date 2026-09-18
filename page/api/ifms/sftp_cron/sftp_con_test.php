<?php

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config_api.php';
require '../../../../includes/library/database.class.php';
include( '../../../all_function/fun_store/ifms_functions.php');

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
include('Net/SFTP.php');

$user=$_GET['user'];

if($user=='gp')
{
	$sftp_con=sftp_login($config['sftp_ip_gp'],$config['sftp_user_name_gp'],$config['sftp_user_password_gp']);

	if($sftp_con!=0)
	{
		echo "Connection Established";
	}
	else
	{
		echo "Coonection Failed";
	}
}

else if($user=='ps')
{
	$sftp_con=sftp_login($config['sftp_ip_ps'],$config['sftp_user_name_ps'],$config['sftp_user_password_ps']);

	if($sftp_con!=0)
	{
		echo "Connection Established";
	}
	else
	{
		echo "Coonection Failed";
	}
}

else if($user=='zp')
{
	$sftp_con=sftp_login($config['sftp_ip_zp'],$config['sftp_user_name_zp'],$config['sftp_user_password_zp']);

	if($sftp_con!=0)
	{
		echo "Connection Established";
	}
	else
	{
		echo "Coonection Failed";
	}
}

?>