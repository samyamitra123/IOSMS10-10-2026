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
$update=$db->update("update prd_employee_master set bank_upd_status=1,bank_msg_flag=0
where emp_id_pk='".$id."' AND gp_id_fk='".$_SESSION['location']['gp_id']."'");
if($update){
	$_SESSION['bank_msg']='<div class="alert alert-success" style="text-align:center"><strong>Request For Bank Details Update Sent Successfully.</strong></div>';
	header('Location:'.$config['base_url'].'page/intra_prd/gp/update_bank_details.php');
	exit(0);
}
else{
	$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Request Not Sent.</strong></div>';
	header('Location:'.$config['base_url'].'page/intra_prd/gp/update_bank_details.php');
	exit(0);
}
?>