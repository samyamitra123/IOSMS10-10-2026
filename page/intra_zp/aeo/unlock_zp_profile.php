<?
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

	)
{
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();
$db=new database();

$action=$cryptoGraph->decode($_GET['action'],4);

if($action=='accept')
{
	pg_query('BEGIN');
	
	/*$insert_zp_status=$db->insert("
									INSERT INTO psemp_ps_profile_update_status 
									(	ip_address,
										prev_status,
										ps_id_fk,
										date,
										present_status)
									VALUES
									(	'".$_SERVER['REMOTE_ADDR']."',
										'7',
										'".$_SESSION['location']['ps_id']."',
										'now()',
										'8'	);	
	");*/
	
	$update_zp_status=$db->update("UPDATE prd_location_master_district SET zp_status='7', zp_unlock_status='3' WHERE district_id_pk='".$_SESSION['location']['district_id']."' AND zp_status='1' AND zp_unlock_status='2'");
	$insert_zp_status=1;
	if($insert_zp_status && $update_zp_status)
	{
		pg_query('COMMIT');
		$_SESSION['gp_msg1']='<div class="alert alert-success" style="text-align:center;"><strong>ZP Profile Unlock Request Accepted successfully...</strong></div>';
		header('location:zp_profile_approval.php');
		exit(0);
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION['gp_msg1']='<div class="alert alert-danger" style="text-align:center;"><strong>ZP Profile Unlock Request has not accepted...</strong></div>';
		header('location:zp_profile_approval.php');
		exit(0);
	}
	
}
elseif($action=='reject')
{
	pg_query('BEGIN');
	
	/*$insert_zp_status=$db->insert("
									INSERT INTO psemp_ps_profile_update_status 
									(	ip_address,
										prev_status,
										ps_id_fk,
										date,
										present_status)
									VALUES
									(	'".$_SERVER['REMOTE_ADDR']."',
										'7',
										'".$_SESSION['location']['ps_id']."',
										'now()',
										'2'	);	
	");*/
	$insert_zp_status=1;
	$update_zp_status=$db->update("UPDATE prd_location_master_district SET zp_unlock_status='0' WHERE district_id_pk='".$_SESSION['location']['district_id']."' AND zp_unlock_status='2'");
	
	if($insert_zp_status && $update_zp_status)
	{
		pg_query('COMMIT');
		$_SESSION['school_msg']='<div class="alert alert-success" style="text-align:center;"><strong>ZP Profile Unlock Request Rejected successfully....</strong></div>';
		header('location:zp_profile_approval.php');
		exit(0);
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION['school_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>ZP Profile Unlock Request has not rejected...</strong></div>';
		header('location:zp_profile_approval.php');
		exit(0);
	}
	
}
?>