<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']=='')
{
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
if($_POST['unlock_emp_id'])
{
	$emp_id_pk=$cryptoGraph->decode($_POST['unlock_emp_id'],4);
	$db=new database();
	$query_unlock=$db->update("UPDATE prd_employee_master SET emp_unlock_status='1'
								WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
								AND emp_status='1' AND emp_id_pk='".$emp_id_pk."'");  
}
if($query_unlock )
{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee Unlock Request Submitted Successfully...</strong></div>';
	header('Location:employee_view_list.php');
}
else
{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee Unlock Request failed. Please try again...</strong></div>';
	header ('Location:employee_view_list.php');
}

?>