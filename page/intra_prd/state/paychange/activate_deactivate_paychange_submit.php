<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
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
$paychange_id_pk=$cryptography->decode($_REQUEST['id'],4);
if($validator->blank_select($paychange_id_pk)==FALSE || $validator->pattern_number($paychange_id_pk)==FALSE)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Updation Failed.</strong></div>';
		header('Location:'.$config['base_url'].'page/intra_prd/state/paychange/paychange_order_details.php');
		exit(0);
} 

if($_REQUEST['flag']=='activate')
{
	$update_paychange=$db->update("UPDATE prd_admin_paychange
   									SET flag='TRUE'
 									WHERE paychange_id_pk='".$paychange_id_pk."'");
									
	$update_paychange_de=$db->update("UPDATE prd_admin_paychange
   									SET flag='FALSE'
 									WHERE paychange_id_pk<>'".$paychange_id_pk."'");	
	if($update_paychange && $update_paychange_de){
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Paychange Succeessfully Activated.</strong></div>';
		header('Location:'.$config['base_url'].'page/intra_prd/state/paychange/paychange_order_details.php');
		exit(0);
		//echo '<div class="alert alert-success" style="text-align:center;"><strong>Paychange Succeessfully Activated.</strong></div>';exit;
	}
	
}else if($_REQUEST['flag']=='deactivate')
{
	$update_paychange=$db->update("UPDATE prd_admin_paychange
   									SET flag='FAlSE'
 									WHERE paychange_id_pk='".$paychange_id_pk."'");	
	if($update_paychange){
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Paychange Succeessfully Deactivated.</strong></div>';
		header('Location:'.$config['base_url'].'page/intra_prd/state/paychange/paychange_order_details.php');
		exit(0);
	}
	
}



?>