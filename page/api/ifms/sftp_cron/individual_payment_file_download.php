<?php

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config_api.php';
require '../../../../includes/library/database.class.php';
include( '../../../all_function/fun_store/ifms_functions.php');

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
include('Net/SFTP.php');

$user=$_GET['user'];


	$sftp_con=sftp_login($config['sftp_ip_ps'],$config['sftp_user_name_ps'],$config['sftp_user_password_ps']);

	if($sftp_con!=0)
	{
		echo "Connection Established";
		
		$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_005/';
		$local_directory_005 = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_005_1/';

		$payment_data_file_name='007DAD0000350072711201801001015_00036.xml';
		if($sftp_con->get($remote_directory_005.$payment_data_file_name, $local_directory_005.$payment_data_file_name))
		{
			echo "Successfully Download";
		}
	}
	else
	{
		echo "Coonection Failed";
	}


?>