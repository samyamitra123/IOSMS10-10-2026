<?
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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

//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$crypto = new cryptography();
$bonus_id=$crypto->decode($_POST['del_id'],4);

if($_POST['del_id']!="" || $_POST['del_id']!="0")
{
	$db=new database();
	
	pg_query('BEGIN');
	
	$update=$db->update("UPDATE prd_bonus_type_details SET active_status='0' WHERE bonus_type_id_pk='".$bonus_id."'");
	
	$update_emp_bonus=$db->update("UPDATE prd_employee_bonus_details SET delete_status=0
									WHERE bonus_type_id_fk='".$bonus_id."' AND bonus_status in (1,2) AND delete_status=1");
	
	
	if($update && $update_emp_bonus)
	{
		pg_query('COMMIT');
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Bonus has been deleted successfully...</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit(0);
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION['gp_msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry !!! Bonus deletion fails...</strong></div>';
		header('location:ll_emp_bonus_module_sal_form.php');
		exit(0);

	}
}
?>