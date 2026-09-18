<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
session_start();
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Origin");
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

function dateshow_slash($dateval)
{	
	$date=substr($dateval,0,10);
	if($date=='')
	{
		return '01/01/1900';
	}
	if($date=='0001-01-01')
	{
		return '01/01/1900';
	}
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'/'.$datearr['1'].'/'.$datearr['0'];
	return $dob=='01/01/1900'?'':$dob;
}


$crypto = new cryptography();
$db = new database();

include('sftp_index.php');die;

$user=$_SESSION['user_info']['stake_user'];

$ddo_code_fetch=$db->fetch_table("SELECT ddo_code FROM prd_dise_admin WHERE block_code='".$user."'");

$ddo_code=$ddo_code_fetch[0]['ddo_code'];

$total_benf=$db->fetch_table("select count(distinct(emp.emp_id_pk)) as total_benf
								from prd_location_master_block block
								inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
								inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
								left join prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and CAST(block.block_code as character varying)=sal.block_code)
								where 
								trim(sal.salary_monthyear)='".date('Ym')."'
								AND emp.emp_status in('1','9') 
								AND sal.delete_status='1' 
								AND sal.status_flag='3'
								AND sal.is_saved='1'
								AND sal.block_code='".$_SESSION['user_info']['stake_user']."' 
								AND sal.requisition_type='".$crypto->decode($_GET['bill_type'],4)."'
								");



/*$bill_details_fetch=$db->fetch_table(" SELECT bill_no,bill_entry_time FROM prd_block_bill_details WHERE block_code='".$user."' AND salary_monthyear='".date('Ym')."' AND status='1' ");*/


//$sftp_xml_filename = date('mY')."31".$ddo_code.$billno.$billdate."_Benf.xml";

$fileSystemIterator = new FilesystemIterator($_SERVER['DOCUMENT_ROOT'].'/prd/readwrite/xml_file');

$entries = array();
foreach ($fileSystemIterator as $fileInfo){
	$entries[] = $fileInfo->getFilename();
}


foreach($entries as $key=>$value)
{
	if(strlen($value)=='32' && substr($value,0,9)==$ddo_code && substr($value,14,6)==date('mY'))
	{
		$existing_seq=substr($value,20,8);
	}
}


if($total_benf[0]['total_benf']<11)
{
	include('index.php');
	if($data_track)
	{
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Bill has been sent successfully</strong></div>';
		header('location:'.$config['base_url'].'page/ifms_inte.php');
		exit(0);
	}
	else
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Bill has not been sent. Try again.</strong></div>';
		header('location:'.$config['base_url'].'page/ifms_inte.php');
		exit(0);
	}
}
else
{
	if($existing_seq!="")
	{
		include('sftp_index.php');
		
		if($update_sftp_details && $update_sftp_details_getting_done)
		{
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Bill has been sent successfully</strong></div>';
			header('location:'.$config['base_url'].'page/ifms_inte.php');
			exit(0);
		}
		else
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Bill has not been sent. Try again.</strong></div>';
			header('location:'.$config['base_url'].'page/ifms_inte.php');
			exit(0);
		}
	}
	else
	{
		include('index.php');
		include('sftp_index.php');
		
		if($data_track && $update_sftp_details && $update_sftp_details_getting_done)
		{
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Bill has been sent successfully</strong></div>';
			header('location:'.$config['base_url'].'page/ifms_inte.php');
			exit(0);
		}
		else
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Bill has not been sent. Try again.</strong></div>';
			header('location:'.$config['base_url'].'page/ifms_inte.php');
			exit(0);
		}
	}
}

?>