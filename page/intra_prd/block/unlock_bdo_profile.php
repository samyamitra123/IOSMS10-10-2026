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

	$db=new database();
	$update=$db->update("UPDATE prd_location_master_block SET block_status='5' WHERE block_code='".$_SESSION['location']['block_code']."'");
	if($update){
		$_SESSION['unlock_msg']='<div class="alert alert-success" style="text-align:center;"><strong>BDO Profile Unlock Request Sent successfully...</strong></div>';
		header('location:view_bdo_profile.php');
		exit(0);
	}else{
		$_SESSION['unlock_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry!!! Fails To Sent BDO Profile Unlock Request...</strong></div>';
		header('location:view_bdo_profile.php');
		exit(0);

	}

?>