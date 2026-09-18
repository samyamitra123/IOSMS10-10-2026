<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
//header("Content-type : text/xml");

ob_start();
session_start();
set_time_limit(0);
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
include( '../../../all_function/fun_store/ifms_functions.php');

$crypto = new cryptography();

$sftp_benf_id=$crypto->decode($_GET['sftp_benf_id'],4);



set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
include('Net/SFTP.php');

$folder_name_gp='ifms_web_cache/ekuber/ePaymentFiles/gen006';
//$folder_name_gp='apps/ePaymentFiles/gen006';
//$folder_name_ps='apps/ePaymentFiles/gen007';
$folder_name_ps='ifms_web_cache/ekuber/ePaymentFiles/gen007';
//$folder_name_zp='apps/ePaymentFiles/gen008';
$folder_name_zp='ifms_web_cache/ekuber/ePaymentFiles/gen008';

$table_name='prd_sftp_benf_upload_response';

///////////////////////////////////////////////////////////////////////// GP START //////////////////////////////////////////////////////////////////
if($_GET['user']!='ps' && $_GET['user']!='zp')
{
	

	$all_dot_done_file_gp=sftp_benf_dot_done_read($config['sftp_ip_gp'],$config['sftp_user_name_gp'],$config['sftp_user_password_gp'],$folder_name_gp);
	if($all_dot_done_file_gp=='2')
	{
		echo 'Connection Error';
	}
	else
	{//print_r($all_dot_done_file_gp);die;
		if(count($all_dot_done_file_gp) > 0 && $all_dot_done_file_gp!='0')
		{
			
			$flag_gp = false;
			foreach($all_dot_done_file_gp as $arr_element_gp)
			{	
				 $dot_done_file_check_gp=update_benf_dot_done_status($arr_element_gp,$table_name); 
				if($dot_done_file_check_gp=='1')
				{
					$flag_gp = true;
					//break;
				}
			}
			if($flag_gp==true)
			{
				echo 'File Uploaded Successfully';
			}
			else
			{
				echo 'Upload Confirmation Pending';
			}		
		}
		else
		{
			echo 'Upload Confirmation Pending';	
		}
	}
}

///////////////////////////////////////////////////////////////////////// GP END //////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////// PS START //////////////////////////////////////////////////////////////////

if($_GET['user']!='gp' && $_GET['user']!='zp')
{
	
	$all_dot_done_file_ps=sftp_benf_dot_done_read($config['sftp_ip_ps'],$config['sftp_user_name_ps'],$config['sftp_user_password_ps'],$folder_name_ps);
	if($all_dot_done_file_ps=='2')
	{
		echo 'Connection Error';
	}
	else
	{
		//print_r($all_dot_done_file_ps);die;
		if(count($all_dot_done_file_ps) > 0)
		{
			
			$flag_ps = false;
			foreach($all_dot_done_file_ps as $arr_element_ps)
			{	
			
				$dot_done_file_check_ps=update_benf_dot_done_status($arr_element_ps,$table_name);
				
				if($dot_done_file_check_ps=='1')
				{
					$flag_ps = true;
					//break;
				}
			}
			if($flag_ps==true)
			{
				echo 'File Uploaded Successfully';
			}
			else
			{
				echo 'File Upload Fails';
			}		
		}
		else
		{
			echo 'Upload Confirmation Pending';	
		}
	}
}

///////////////////////////////////////////////////////////////////////// PS END //////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////// ZP START //////////////////////////////////////////////////////////////////

if($_GET['user']!='gp' && $_GET['user']!='ps')
{
	$all_dot_done_file_zp=sftp_benf_dot_done_read($config['sftp_ip_zp'],$config['sftp_user_name_zp'],$config['sftp_user_password_zp'],$folder_name_zp);
	
	if($all_dot_done_file_zp=='2')
	{
		echo 'Connection Error';
	}
	else
	{
		
		if(count($all_dot_done_file_zp) > 0)
		{
			//echo 88;die;
			$flag_zp = false;
			foreach($all_dot_done_file_zp as $arr_element_zp)
			{	
			
				$dot_done_file_check_zp=update_benf_dot_done_status($arr_element_zp,$table_name);
				
				if($dot_done_file_check_zp=='1')
				{
					$flag_zp = true;
					//break;
				}
			}
			if($flag_zp==true)
			{
				echo 'File Uploaded Successfully';
			}
			else
			{
				echo 'File Upload Fails';
			}		
		}
		else
		{
			echo 'Upload Confirmation Pending';	
		}
	}
}

///////////////////////////////////////////////////////////////////////// ZP END //////////////////////////////////////////////////////////////////



?>