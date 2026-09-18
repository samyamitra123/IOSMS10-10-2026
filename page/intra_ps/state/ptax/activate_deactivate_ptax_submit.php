<?php
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//Page variables
$common['title'] = "Paychange Order Details| PRD | Govt. of West Bengal ";
//print_r($_REQUEST);exit;

$db = new database();
$cryptography=new cryptography();	
$ptax_orderfile_pk=$cryptography->decode($_REQUEST['id'],4);
if($validator->blank_select($ptax_orderfile_pk)==FALSE || $validator->pattern_number($ptax_orderfile_pk)==FALSE)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Updation Failed.</strong></div>';
		header('Location:'.$config['base_url'].'page/intra_ps/state/ptax/ptax_order_details.php');
		exit(0);
} 

if($_REQUEST['flag']=='activate')
{
	$update_ptax=$db->update("UPDATE psemp_ptax_order_file
   									SET active_status='1'
 									WHERE ptax_orderfile_pk='".$ptax_orderfile_pk."'");
									
	$update_ptax_de=$db->update("UPDATE psemp_ptax_order_file
   									SET active_status='0'
 									WHERE ptax_orderfile_pk<>'".$ptax_orderfile_pk."'");	
	if($update_ptax && $update_ptax_de){
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>PTAX Succeessfully Activated.</strong></div>';
		header('Location:'.$config['base_url'].'page/intra_ps/state/ptax/ptax_order_details.php');
		exit(0);
		//echo '<div class="alert alert-success" style="text-align:center;"><strong>Paychange Succeessfully Activated.</strong></div>';exit;
	}
	
}else if($_REQUEST['flag']=='deactivate')
{
	$update_paychange=$db->update("UPDATE psemp_ptax_order_file
   									SET active_status='0'
 									WHERE ptax_orderfile_pk='".$ptax_orderfile_pk."'");	
	if($update_paychange){
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>PTAX Succeessfully Deactivated.</strong></div>';
		header('Location:'.$config['base_url'].'page/intra_ps/state/ptax/ptax_order_details.php');
		exit(0);
	}
	
}



?>