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
	
	$update=$db->update("UPDATE prd_location_master_panchayat_samiti SET ps_status='2' WHERE ps_id_pk='".$_SESSION['location']['ps_id']."' and ps_status='1'");
	
	$reason = $db->insert("
							INSERT INTO psemp_ps_profile_update_status 
							(
								ip_address,
								prev_status,
								ps_id_fk,
								date,
								present_status
							)
							VALUES 
							(
								'".$_SESSION['user_agent']['USER_IP']."',
								'1',
								'".$_SESSION['location']['ps_id']."',
								now(),
								'2'
							);
							
							");
	
	if($update && $reason)
	{
		pg_query('COMMIT');
		$_SESSION['gp_msg1']='<div class="alert alert-success" style="text-align:center;"><strong>PS Profile Approved successfully...</strong></div>';
		header('location:ps_profile_approval.php');
		exit(0);
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION['gp_msg1']='<div class="alert alert-danger" style="text-align:center;"><strong>PS Profile has not been approved...</strong></div>';
		header('location:ps_profile_approval.php');
		exit(0);
	}
}

else if($cryptoGraph->decode($_GET['action'],4)=='reject')
{
	
	if($validator->blank_select($_GET['reason']) == FALSE)
	{ 
		$_SESSION['gp_msg1']='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Reason For Rejection..</strong></div>';
		header('location:ps_profile_approval.php');
		exit(0);
	}
	else
	{
		pg_query('BEGIN');

		$reason = $db->insert("
								INSERT INTO psemp_ps_profile_update_status 
								(
									ip_address,
									reason,
									prev_status,
									ps_id_fk,
									date,
									present_status
								)
								VALUES 
								(
									'".$_SESSION['user_agent']['USER_IP']."',
									'".$_GET['reason']."',
									1,
									'".$_SESSION['location']['ps_id']."',
									now(),
									'3'
								);
								
								");
		
		
		$update=$db->update("UPDATE prd_location_master_panchayat_samiti SET ps_status='3' WHERE ps_id_pk='".$_SESSION['location']['ps_id']."' and ps_status='1'");
		
		
		if($reason && $update)
		{
			pg_query('COMMIT');
			$_SESSION['gp_msg1']='<div class="alert alert-success" style="text-align:center;"><strong>PS Profile Rejected successfully...</strong></div>';
			header('location:ps_profile_approval.php');
			exit(0);
		}
		else
		{
			pg_query('ROLLBACK');
			$_SESSION['gp_msg1']='<div class="alert alert-danger" style="text-align:center;"><strong>PS Profile has not been rejected...</strong></div>';
			header('location:ps_profile_approval.php');
			exit(0);
		}
	}
}
?>