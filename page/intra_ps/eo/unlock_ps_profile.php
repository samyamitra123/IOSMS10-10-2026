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
	
	$insert_ps_status=$db->insert("
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
	");
	
	$update_ps_status=$db->update("UPDATE prd_location_master_panchayat_samiti SET ps_status='8' WHERE ps_id_pk='".$_SESSION['location']['ps_id']."' AND ps_status='7'");
	
	if($insert_ps_status && $update_ps_status)
	{
		pg_query('COMMIT');
		$_SESSION['school_msg']='<div class="alert alert-success" style="text-align:center;"><strong>PS Profile Unlock Request Accepted successfully...</strong></div>';
		header('location:ps_profile_approval.php');
		exit(0);
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION['school_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>PS Profile Unlock Request has not accepted...</strong></div>';
		header('location:ps_profile_approval.php');
		exit(0);
	}
	
}
elseif($action=='reject')
{
	pg_query('BEGIN');
	
	$insert_ps_status=$db->insert("
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
	");
	
	$update_ps_status=$db->update("UPDATE prd_location_master_panchayat_samiti SET ps_status='2' WHERE ps_id_pk='".$_SESSION['location']['ps_id']."' AND ps_status='7'");
	
	if($insert_ps_status && $update_ps_status)
	{
		pg_query('COMMIT');
		$_SESSION['school_msg']='<div class="alert alert-success" style="text-align:center;"><strong>PS Profile Unlock Request Rejected successfully....</strong></div>';
		header('location:ps_profile_approval.php');
		exit(0);
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION['school_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>PS Profile Unlock Request has not rejected...</strong></div>';
		header('location:ps_profile_approval.php');
		exit(0);
	}
	
}
?>