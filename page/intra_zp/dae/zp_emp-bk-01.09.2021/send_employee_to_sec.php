<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
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

$cryptoGraph=new cryptography();
$emp_id_pk=$cryptoGraph->decode($_POST['emp_id_ps'],4);
if($emp_id_pk!="")
{
	
	pg_query('BEGIN');
	$update_query=$db->update("UPDATE prd_employee_master SET emp_status='6', emp_unlock_status='0'
								WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
								AND emp_status in ('5','10','7','4') AND emp_id_pk='".$emp_id_pk."'"); 
	
	
	$insert_status=$db->insert("
								INSERT INTO psemp_employee_profile_update_status
								(
									ip_address,
									date,
									ps_id_fk,
									emp_id_fk,
									prev_status,
									new_status,
									zp_id_fk
									)
									VALUES
									(
										'".$_SERVER['REMOTE_ADDR']."',
										'now()',
										'0',
										'".$emp_id_pk."',
										'10',
										'6',
										'".$_SESSION['location']['district_id']."'
									)
								");
	
	if($update_query && $insert_status)
	{
		pg_query('COMMIT');
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee profile send to Secretary Successfully...</strong></div>';
		header('Location:employee_edit_details.php');
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee profile send fails...</strong></div>';
		header('Location:employee_edit_details.php');
	}
	
}
else
{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee profile send fails...</strong></div>';
	header('Location:employee_edit_details.php');
}
?>