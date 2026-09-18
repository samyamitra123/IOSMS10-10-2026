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
	$update=$db->update("UPDATE prd_location_master_gp SET flag='1' WHERE gp_id_pk='".$_SESSION['location']['gp_id']."'");
	if($update){
		$_SESSION['gp_msg']='<div class="alert alert-success" style="text-align:center;"><strong>GP Profile has been sent for approval successfully...</strong></div>';
		header('location:view_gp_profile.php');
		exit(0);
	}else{
		$_SESSION['gp_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>GP Profile has not been sent for approval...</strong></div>';
		header('location:view_gp_profile.php');
		exit(0);

	}
}
?>