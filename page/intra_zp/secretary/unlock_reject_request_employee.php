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

){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$cryptoGraph=new cryptography();

$emp_unlock_id=$cryptoGraph->decode($_POST['emp_unlock_id'],4);

if($cryptoGraph->decode($_POST['flag'],4)=='unlock')
{
	pg_query('BEGIN');
	$db=new database();
	
	$query_unlock=$db->update("UPDATE prd_employee_master SET emp_unlock_status='2'
								WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
								AND emp_unlock_status='1' AND emp_id_pk='".$emp_unlock_id."'"); 
								
	$insert_status=$db->insert("
								INSERT INTO psemp_employee_profile_update_status
								(
								ip_address,
								date,
								reason,
								ps_id_fk,
								emp_id_fk,
								prev_status,
								new_status,
								zp_id_fk,
								new_unlock_status
								)
								VALUES
								(
								'".$_SERVER['REMOTE_ADDR']."',
								'now()',
								'',
								'0',
								'".$emp_unlock_id."',
								'1',
								'1',
								'".$_SESSION['location']['district_id']."',
								'2'
								)
								");	
	
	
	if($query_unlock && $insert_status )
	{
		pg_query('COMMIT');
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Unlock Request Forwarded To AEO Successfully...</strong></div>';
		header('Location:employee_list.php');
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Unlock Request Forward  Failed. Please try again...</strong></div>';
		header ('Location:employee_list.php');
	}
}

if($cryptoGraph->decode($_POST['flag'],4)=='reject')
{
	$db=new database();										
	$query_reject=$db->update("UPDATE prd_employee_master SET emp_unlock_status='0'
								WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
								AND emp_unlock_status in ('1','3') AND emp_id_pk='".$emp_unlock_id."'"); 
	pg_query('BEGIN');				 
	
	$insert_status=$db->insert("
								INSERT INTO psemp_employee_profile_update_status
								(
								ip_address,
								date,
								reason,
								ps_id_fk,
								emp_id_fk,
								prev_status,
								new_status,
								zp_id_fk,
								new_unlock_status
								)
								VALUES
								(
								'".$_SERVER['REMOTE_ADDR']."',
								'now()',
								'',
								'0',
								'".$emp_unlock_id."',
								'1',
								'1',
								'".$_SESSION['location']['district_id']."',
								'0'
								)
								");	
	
	
	
	
	if($query_reject && $insert_status )
	{
		pg_query('COMMIT');
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> Employee Profile Unlock Request Rejected Successfully...</strong></div>';
		header('Location:employee_list.php');
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> Unlock Request Rejection Failed. Please try again...</strong></div>';
		header ('Location:employee_list.php');
	}
}


?>
