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

 
	    $ir=$_POST['ir_submit']; 
		$emp_id_fk=$cryptoGraph->decode($_POST['unlock_emp_id'],4);

  /*if($validator->blank_select($ir) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter IR Ammount.</strong></div>';
		include 'emp_ir_cal.php';
		exit;
	}*/
	$db=new database();
	pg_query('BEGIN');

	$emp_id_pk_check=$db->fetch_table("select emp_id_fk,delete_status from psemp_interim_relief where emp_id_fk='".$emp_id_fk."' and ps_id_fk='".$_SESSION['location']['ps_id']."' and ir_status='4' and delete_status='1'");
	
	
								
	
 	if($emp_id_pk_check)
	{ 
		$ir_status_up=$db->update("UPDATE psemp_interim_relief SET delete_status='0',unlock_req='0' where emp_id_fk='".$emp_id_fk."'");
		
		$ir_submit=$db->insert("INSERT INTO psemp_interim_relief
	       (emp_id_fk,ir_status,entry_time,interim_relief,ps_id_fk,delete_status,unlock_req)
		VALUES('".$emp_id_fk."','4','now()','".$ir."','".$_SESSION['location']['ps_id']."','1','0')");
	if($ir_submit)
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
													AND ir.unlock_req=0
													AND emp.emp_id_pk='".$emp_id_fk."'");
		
		}//echo 99; die;
	} 
	else
	{
		
		$ir_submit=$db->insert("INSERT INTO psemp_interim_relief
	       (emp_id_fk,ir_status,entry_time,interim_relief,ps_id_fk,delete_status,unlock_req)
		VALUES('".$emp_id_fk."','1','now()','".$ir."','".$_SESSION['location']['ps_id']."','1','0')");
	
		 //echo 100; die;
								
	}
	
	if($ir_status_up && $ir_submit && $emp_master_ir_update)
	
		{
			pg_query('COMMIT');
			$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong> Interim Relief Submited successfully...</strong></div>';
			header('Location:employee_list_for_ir.php');
		}
		else
		{
			pg_query('ROLLBACK');
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Interim Relief insertion failed. Please try again...</strong></div>';
			header('Location:employee_list_for_ir.php');
		}
						
	
  
  ?>