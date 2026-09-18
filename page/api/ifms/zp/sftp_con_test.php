<?php

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config_api.php';
require '../../../../includes/library/database.class.php';
include( '../../../all_function/fun_store/ifms_functions.php');

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
include('Net/SFTP.php');

$sftp_con=sftp_login($config['sftp_ip_ps'],$config['sftp_user_name_ps'],$config['sftp_user_password_ps']);

if($sftp_con!=0)
{
	echo 1;
}
else
{
	echo 0;
}
?>