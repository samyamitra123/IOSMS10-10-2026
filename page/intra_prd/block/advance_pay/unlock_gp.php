<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
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
$gp_code=$cryptoGraph->decode($_REQUEST['gp_code'],4);
$db = new database();
//echo $config['base_url'].'page/intra_prd/block/salary_finalize.php';exit;
$arr=$db->fetch_table(" SELECT  gp_id_pk from prd_location_master_gp where gp_code='$gp_code'");

$gp_id=$arr[0]['gp_id_pk'];


	 
	if($gp_code !=''){
		


	
	
$update = $db->update("
		UPDATE prd_adavance_pay
		SET status_flag ='1'
		WHERE status_flag ='2' AND gp_id_fk = '".$gp_id."' AND advance_monthyear='".date('Ym')."'");
}



if($update)
{
	$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>GP successfully unlocked.</strong></div>';
	 //echo $cryptoGraph->encode('<div class="alert alert-success" style="text-align:center"><strong>GP successfully unlocked.</strong></div>',4);
	 header('Location:'.$config['base_url'].'page/intra_prd/block/advance_pay/advance_salary_finalize.php');
	 exit(0);
	//header('Location:'.$config['base_url'].'page/intra_prd/block/salary_finalize.php');
	//echo "GP successfully unlocked.";	
}else {
	 $_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Unlock failed. Please try again.</strong></div>';
	  header('Location:'.$config['base_url'].'page/intra_prd/block/advance_pay/advance_salary_finalize.php');
	 exit(0);
	//header('Location:'.$config['base_url'].'page/intra_prd/block/salary_finalize.php');
	//echo "Unlock failed. Please try again.";	
}

?>