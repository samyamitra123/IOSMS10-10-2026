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


$emp_id_fk=$cryptoGraph->decode($_POST['request'],4); 
 $flag=$_POST['flag'];
if($emp_id_fk!="" && $flag!="")
{
	if($flag=='accept')
	{
	
		$db=new database();
		pg_query('BEGIN');
		
		$ir_status_update=$db->update("UPDATE psemp_interim_relief SET ir_status='4' where emp_id_fk='".$emp_id_fk."' AND ir_status='3'");
		
		if($ir_status_update)
		{
			$emp_master_ir_update=$db->update("UPDATE 
													prd_employee_master emp 
												SET 
													interim_relief=ir.interim_relief
												FROM 
													psemp_interim_relief ir 
												WHERE 
													ir.emp_id_fk=emp.emp_id_pk 
													AND ir.ps_id_fk=emp.ps_id_fk
													AND ir.ir_status=4 
													AND ir.delete_status=1 
													AND emp.emp_id_pk='".$emp_id_fk."'");
		}
		
		if($ir_status_update && $emp_master_ir_update)
		{
			pg_query('COMMIT');
			$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong> Interim Relief Accepted successfully...</strong></div>';
			header('Location:employee_list_for_ir.php');
		}
		else
		{
			pg_query('ROLLBACK');
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Interim Relief Accepted failed. Please try again...</strong></div>';
			header('Location:employee_list_for_ir');
		}
	}
	elseif($flag=='reject')
	{
		$db=new database();
		$ir_status=$db->update("UPDATE psemp_interim_relief SET ir_status='1',delete_status='1' WHERE emp_id_fk='".$emp_id_fk."' AND ir_status='3'");
		
		if($ir_status)
		{
			$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong> Interim Relief Reject successfully...</strong></div>';
			header('Location:employee_list_for_ir.php');
		}
		else
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Interim Relief Reject failed. Please try again...</strong></div>';
			header('Location:employee_list_for_ir');
		}
	}
	
	elseif($flag=='unlock')
	{            //echo 44; die;
		$db=new database();
		$ir_status=$db->update("UPDATE psemp_interim_relief SET ir_status='4',delete_status='1',unlock_req='2' WHERE emp_id_fk='".$emp_id_fk."' AND ir_status='4' AND unlock_req='1'AND delete_status='1'");
		
		if($ir_status)
		{
			$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong> Interim Relief Unlock Request Accepted successfully...</strong></div>';
			header('Location:employee_list_for_ir.php');
		}
		else
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Interim Relief unlock request failed. Please try again...</strong></div>';
			header('Location:employee_list_for_ir');
		}
	}
}

?>