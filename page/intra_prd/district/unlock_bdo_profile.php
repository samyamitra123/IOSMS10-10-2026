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
$cryptoGraph=new cryptography();

	$db=new database();
	$dist=$db->fetch_table("Select district_id_fk FROM prd_location_master_block where block_code='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
	$update=$db->update("UPDATE prd_location_master_block SET block_status='6' WHERE block_code='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
	if($update){
		$_SESSION['unlock_msg']='<div class="alert alert-success" style="text-align:center;"><strong>BDO Profile Unlocked Successfully...</strong></div>';
		header('location:block_list_for_profile_unlock.php?id='.$cryptoGraph->encode($dist[0]['district_id_fk'],4));
		exit(0);
	}else{
		$_SESSION['unlock_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry!!! Fails To Unlock BDO Profile...</strong></div>';
		header('location:block_list_for_profile_unlock.php?id='.$cryptoGraph->encode($dist[0]['district_id_fk'],4));
		exit(0);

	}

?>