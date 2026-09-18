<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
set_time_limit(0);
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once'../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
require '../../../page/all_function/fun_store/ifms_functions.php';
$crypto = new cryptography();

$user=$_GET['user'];
$folder=$_GET['folder'];


set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
include('Net/SFTP.php');

if($user=='gp')
{
	$sftp=sftp_login($config['sftp_ip_gp'],$config['sftp_user_name_gp'],$config['sftp_user_password_gp']);
	$remote_directory_002 = '/apps/ePaymentFiles/gen006/ePayment_Files_002/';
	$remote_directory_003 = '/apps/ePaymentFiles/gen006/ePayment_Files_003/';
	$remote_directory_005 = '/apps/ePaymentFiles/gen006/ePayment_Files_005/';
	$remote_directory_006 = '/apps/ePaymentFiles/gen006/ePayment_Files_006/';
}
else if($user=='ps')
{
	$sftp=sftp_login($config['sftp_ip_ps'],$config['sftp_user_name_ps'],$config['sftp_user_password_ps']);
	$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_002/';
	$remote_directory_003 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_003/';
	$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_005/';
	$remote_directory_006 = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/ePayment_Files_006/';
}
else if($user=='zp')
{
	$sftp=sftp_login($config['sftp_ip_zp'],$config['sftp_user_name_zp'],$config['sftp_user_password_zp']);
	$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_002/'; 
	$remote_directory_003 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_003/';
	$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_005/';
	$remote_directory_006 = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/ePayment_Files_006/';
}
//echo $remote_directory_002."       ".$remote_directory_003."       ".$remote_directory_005."     ".$remote_directory_006;die;
	
echo '<div style="width:100%;display:flex;
flex-direction:row;
justify-content: space-around;">';
echo '<div style="float:left;width:40%;display:flex;
flex-direction:column;">';

if($folder=='2')
{
	$file_list_arr=	$sftp->nlist($remote_directory_002);
}
else if($folder=='3')
{
	$file_list_arr=	$sftp->nlist($remote_directory_003);
}
if($folder=='5')
{
	$file_list_arr=	$sftp->nlist($remote_directory_005);
}
if($folder=='6')
{
	$file_list_arr=	$sftp->nlist($remote_directory_006);
}
	
	
echo '<h1>Folder 00'.$folder.' : </h1>';	
$i=1;
echo '<table border="1"><tr><th>Slno.</th><th>Search Result Files</th></tr>';
foreach($file_list_arr as $key => $val)
{
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	echo '<td>'.$val.'</td>';
	echo '</tr>';
	//echo $i.' . '.$val."<br>";
	$i++;
}
echo '</table>';
echo '</div>';	
echo '</div>';	

?>