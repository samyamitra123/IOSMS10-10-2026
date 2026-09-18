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

$user=$_POST['user'];
$folder=$_POST['folder'];
$month=$crypto->decode($_POST['month'],4);
$monthname=date('F', mktime(0,0,0,$month));
$year=$crypto->decode($_POST['year'],4);


set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
include('Net/SFTP.php');

if($user=='gp')
{
	$sftp=sftp_login($config['sftp_ip_gp'],$config['sftp_user_name_gp'],$config['sftp_user_password_gp']);
	
	
	$remote_directory_002 = '/ifms_web_cache/ekuber/ePaymentFiles/gen006/ePayment_Files_002/';
	
	$remote_directory_003 = '/ifms_web_cache/ekuber/ePaymentFiles/gen006/ePayment_Files_003/';
	$remote_directory_005 = '/ifms_web_cache/ekuber/ePaymentFiles/gen006/ePayment_Files_005/';
	$remote_directory_006 = '/ifms_web_cache/ekuber/ePaymentFiles/gen006/ePayment_Files_006/';
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

	
echo '<div style="width:100%;display:flex;
flex-direction:row;
justify-content: space-around;">';
echo '<div style="float:left;width:40%;display:flex;
flex-direction:column;">';

if($folder=='20' || $folder=='21')
{
	
	$file_list_arr=	$sftp->nlist($remote_directory_002);
//print_r($file_list_arr); die;
	if($folder=='20')
	{
		
		$folder_name=".DONE FILE LIST";
	}
	else if($folder=='21')
	{
		$folder_name="ACK FILE LIST";
	}
	
}
else if($folder=='30' || $folder=='31')
{
	$file_list_arr=	$sftp->nlist($remote_directory_003);
	if($folder=='30')
	{
		$folder_name="WRONG FORMAT LIST";
	}
	else if($folder=='31')
	{
		$folder_name="WRONG DATA LIST";
	}
}
if($folder=='5')
{
	$file_list_arr=	$sftp->nlist($remote_directory_005);
	$folder_name="PAYMENT FILE LIST";
}

	
	
echo '<h1>'.$folder_name.' FOR '.strtoupper($monthname).', '.$year.' : </h1>';	
$i=1;
echo '<table border="1"><tr><th>Slno.</th><th>Search Result Files</th></tr>';
foreach($file_list_arr as $key => $val)
{
	$name_length=strlen($val);
	if($folder=='21' && substr($val,0,3)=='ACK' && substr($val,17,6)==$month.$year)
	{
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$val.'</td>';
		echo '</tr>';
		$i++;
	}
	else if($folder=='20' && substr($val,-4)=='done' && substr($val,14,6)==$month.$year)
	{
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$val.'</td>';
		echo '</tr>';
		$i++;
	}
	else if($folder=='30' && substr($val,28,1)=='_' && substr($val,14,6)==$month.$year)
	{
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$val.'</td>';
		echo '</tr>';
		$i++;
	}
	else if($folder=='31' && substr($val,28,1)=='D' && substr($val,14,6)==$month.$year)
	{
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$val.'</td>';
		echo '</tr>';
		$i++;
	}
	else if($folder=='5')
	{
		if($name_length=='39' && substr($val,15,6)==$month.$year)
		{
			echo '<tr>';
			echo '<td>'.$i.'</td>';
			echo '<td>'.$val.'</td>';
			echo '</tr>';
			$i++;
		}
		else if($name_length=='41' && substr($val,17,6)==$month.$year)
		{
			echo '<tr>';
			echo '<td>'.$i.'</td>';
			echo '<td>'.$val.'</td>';
			echo '</tr>';
			$i++;
		}
	}
	
	//echo $i.' . '.$val."<br>";
	
}
echo '</table>';
echo '</div>';	
echo '</div>';	

?>