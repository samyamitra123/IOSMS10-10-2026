<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Contet-type : text/xml");
ob_start();
session_start();
set_time_limit(0);
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require '../../../page_visite.php';
require '../../../all_function/fun_store/ifms_functions.php';
$crypto = new cryptography();
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
include('Net/SFTP.php');
$folder_name='apps/ePaymentFiles/gen006';
$table_name='prd_sftp_benf_upload_response';
$all_dot_done_file=sftp_benf_dot_done_read($config['sftp_ip_gp'],$config['sftp_user_name_gp'],$config['sftp_user_password_gp'],$folder_name);
if(count($all_dot_done_file) > 0){
$flag = false;
	foreach($all_dot_done_file as $arr_element)
	{	
		$dot_done_file_check=update_benf_dot_done_status($arr_element,$table_name);
		if($dot_done_file_check==1){
			$flag = true;
			//break;
		}
	}
	if($flag==true){
		echo 'Payment file has been sent successfully';
	}	
}
else{
		echo 'There is no .done file found yet';	
}
?>