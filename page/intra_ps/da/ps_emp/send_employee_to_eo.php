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
	
	
	$update_query=$db->update("UPDATE prd_employee_master SET emp_status='6'
									 WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
									  AND emp_status in ('4','7','10') AND emp_id_pk='".$emp_id_pk."'"); 
	
	
	if($update_query)
	{
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee profile send to EO Successfully...</strong></div>';
		header('Location:employee_view_list.php');
	}
	else
	{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee profile send fails...</strong></div>';
		header('Location:employee_edit_details.php');
	}
	
	/*$emp_details=$db->fetch_table("SELECT emp_status,emp_id_const FROM prd_employee_master WHERE emp_id_pk='".$emp_id_pk."' AND ps_id_fk='".$_SESSION['location']['ps_id']."'");
	
	if($emp_details[0]['emp_status']=='4' && $emp_details[0]['emp_id_const']!='0')
	{

		$update_query=$db->update("UPDATE prd_employee_master SET emp_status='6'
									 WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
									  AND emp_status in ('4','7') AND emp_id_pk='".$emp_id_pk."'"); 
	
		if($update_query)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee profile send to EO Successfully...</strong></div>';
			header('Location:employee_view_list.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee profile send fails...</strong></div>';
			header('Location:employee_edit_details.php');
		}
	
	}
	else if($emp_details[0]['emp_status']=='10' && $emp_details[0]['emp_id_const']==0)
	{
		$update_query=$db->update("UPDATE prd_employee_master SET emp_status='6'
									 WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
									  AND emp_status='10' AND emp_id_pk='".$emp_id_pk."'"); 
	
		if($update_query)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee profile send to EO Successfully...</strong></div>';
			header('Location:employee_edit_details.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee profile send fails...</strong></div>';
			header('Location:employee_edit_details.php');
		}
	}*/
}
else
{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee profile send fails...</strong></div>';
	header('Location:employee_edit_details.php');
}
?>