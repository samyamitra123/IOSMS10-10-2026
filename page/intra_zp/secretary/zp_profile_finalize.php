<?

session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';
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

if($cryptoGraph->decode($_GET['action'],4)=='approve')
{
	
	pg_query('BEGIN');
	
	$update=$db->update("UPDATE prd_location_master_district SET zp_status='4' WHERE district_id_pk='".$_SESSION['location']['district_id']."' AND zp_status='2'");
	
	$reason = $db->insert("INSERT INTO zpemp_zp_profile_update_status 
							(
								sec_ip_address,
								sec_date,
								district_id_fk,
								sec_present_status
							)
							VALUES 
							(
								'".$_SESSION['user_agent']['USER_IP']."',
								now(),
								'".$_SESSION['location']['district_id']."',
								'4'
							);");
	
	if($update && $reason)
	{
		pg_query('COMMIT');
		$_SESSION['gp_msg1']='<div class="alert alert-success" style="text-align:center;"><strong>ZP Profile forwarded successfully...</strong></div>';
		header('location:view_zp_profile.php');
		exit(0);
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION['gp_msg1']='<div class="alert alert-danger" style="text-align:center;"><strong>ZP Profile has not been forwarded...</strong></div>';
		header('location:view_zp_profile.php');
		exit(0);
	}
}

else if($cryptoGraph->decode($_GET['action'],4)=='reject')
{
	
	if($validator->blank_select($_GET['reason']) == FALSE)
	{ 
		$_SESSION['gp_msg1']='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Reason For Rejection..</strong></div>';
		header('location:view_zp_profile.php');
		exit(0);
	}
	else
	{
		pg_query('BEGIN');

		$reason = $db->insert("INSERT INTO zpemp_zp_profile_update_status 
								(
									sec_ip_address,
									sec_date,
									district_id_fk,
									sec_present_status,
									reason
								)
								VALUES 
								(
									'".$_SESSION['user_agent']['USER_IP']."',
									now(),
									'".$_SESSION['location']['district_id']."',
									'3',
									'".$_GET['reason']."'
								);");
		
		
		$update=$db->update("UPDATE prd_location_master_district SET zp_status='3' WHERE district_id_pk='".$_SESSION['location']['district_id']."' AND zp_status='2'");
		
		
		if($reason && $update)
		{
			pg_query('COMMIT');
			$_SESSION['gp_msg1']='<div class="alert alert-success" style="text-align:center;"><strong>ZP Profile Rejected successfully...</strong></div>';
			header('location:view_zp_profile.php');
			exit(0);
		}
		else
		{
			pg_query('ROLLBACK');
			$_SESSION['gp_msg1']='<div class="alert alert-danger" style="text-align:center;"><strong>ZP Profile has not been rejected...</strong></div>';
			header('location:view_zp_profile.php');
			exit(0);
		}
	}
}
?>