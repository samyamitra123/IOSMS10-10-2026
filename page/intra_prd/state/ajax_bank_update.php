<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();
$id=$cryptoGraph->decode($_REQUEST['id'],4);
$db=new database();
$gp=$db->fetch_table("select gp_id_fk from prd_employee_master where emp_id_pk='".$id."'");
$gp_id=$gp[0]['gp_id_fk'];
if(trim($cryptoGraph->decode($_REQUEST['flag'],4))=='approve'){
$update_transfer=$db->update("update prd_employee_master set bank_upd_status=3
where emp_id_pk='".$id."'");
}
else if(trim($cryptoGraph->decode($_REQUEST['flag'],4))=='reject'){
$update_reject=$db->update("update prd_employee_master set bank_upd_status=4
where emp_id_pk='".$id."'");
}
if($update_transfer){
	$_SESSION['bank_msg']='<div class="alert alert-success" style="text-align:center"><strong>Request For Bank Details Update  Approved Successfully.</strong></div>';
	header('Location:'.$config['base_url'].'page/intra_prd/state/update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
	exit(0);
}
else if($update_reject){
	$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Request For Bank Details Update Rejected.</strong></div>';
	header('Location:'.$config['base_url'].'page/intra_prd/state/update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
	exit(0);
}
else{
	$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Request Not Sent.</strong></div>';
	header('Location:'.$config['base_url'].'page/intra_prd/block/update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
	exit(0);
}
?>