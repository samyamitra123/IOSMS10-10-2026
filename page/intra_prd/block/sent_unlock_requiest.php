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


if($_GET['action']=='unlock'){
	$db=new database();
	$arr=$db->fetch_table("select DISTINCT(save.gp_id_fk) as gp_id_fk from prd_location_master_block block inner join
prd_location_master_gp gp on gp.block_id_fk=block.block_id_pk inner join 
prd_employee_salary_save save  on  save.gp_id_fk=CAST(gp.gp_id_pk as character varying)
WHERE block.block_code = '".$_SESSION['user_info']['stake_user']."'");

 $gp_id_fk=$arr[0]['gp_id_fk']; 

 $update=$db->update("UPDATE prd_employee_salary_save SET lock_status='1' WHERE block_code='".$_SESSION['user_info']['stake_user']."' AND salary_monthyear='".date('Ym')."' AND status_flag='3' and is_saved='1' and delete_status='1' ");
	
	if($update){
		$_SESSION['gp_msg']='<div class="alert alert-success" style="text-align:center;"><strong>Salary Unlock requiest has been sent  successfully...</strong></div>';
		header('location:unlock_bdo.php');
		exit(0);
	}else{
		$_SESSION['gp_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Salary Unlock requiest has not been sent successfully...</strong></div>';
		header('location:unlock_bdo.php');
		exit(0);

	}
}
?>