<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
if($_GET['action']=='approval'){
	$db=new database();
	$update=$db->update("UPDATE prd_location_master_block SET block_status='1' WHERE block_code='".$_SESSION['location']['block_code']."'");
	if($update){
		$_SESSION['gp_msg']='<div class="alert alert-success" style="text-align:center;"><strong>BDO Profile has been sent for approval successfully...</strong></div>';
		header('location:view_bdo_profile.php');
		exit(0);
	}else{
		$_SESSION['gp_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>BDO Profile has not been sent for approval...</strong></div>';
		header('location:view_bdo_profile.php');
		exit(0);

	}
}
?>