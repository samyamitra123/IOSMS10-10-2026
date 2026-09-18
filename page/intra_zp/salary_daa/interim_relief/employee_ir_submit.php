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
      $cryptoGraph=new cryptography();


 
	   $ir=$_POST['ir_submit'];
	   $emp_id_fk=$_POST['unlock_emp_id'];
	   $emp_name= $_POST['ir_emp_name'];
  /*if($validator->blank_select($ir) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter IR Ammount.</strong></div>';
		include 'emp_ir_cal.php';
		exit;
	}*/
	$db=new database();

	$emp_id_pk_check=$db->fetch_table("SELECT 
											emp_id_fk,
											delete_status 
										FROM 
											psemp_interim_relief 
										WHERE 
											emp_id_fk='".$emp_id_fk."' AND zp_id_fk='".$_SESSION['location']['district_id']."' AND ir_status in('1','4') AND delete_status='1' AND unlock_req in('0','2')");
	
	
								
	pg_query('BEGIN');
	
 	if(($emp_id_pk_check))
	{ 
		
		$ir_status_up=$db->update("UPDATE psemp_interim_relief SET delete_status='0' where emp_id_fk='".$emp_id_fk."'");
		
		$ir_submit=$db->insert("INSERT INTO psemp_interim_relief
	       (emp_id_fk,ir_status,entry_time,interim_relief,zp_id_fk,delete_status)
		VALUES('".$emp_id_fk."','1','now()','".$ir."','".$_SESSION['location']['district_id']."','1')");
		

	} 
	else
	{
	 
		
		$ir_submit=$db->insert("INSERT INTO psemp_interim_relief
	       (emp_id_fk,ir_status,entry_time,interim_relief,zp_id_fk,delete_status)
		VALUES('".$emp_id_fk."','1','now()','".$ir."','".$_SESSION['location']['district_id']."','1')");
	
								
	}
	
	if($emp_id_pk_check)
	{
		if($ir_submit)
		{
			pg_query('COMMIT');
			$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong> Interim Relief Of '.$emp_name.' Submited successfully...</strong></div>';
			header('Location:emp_ir_cal.php');
		}
		else
		{
			pg_query('ROLLBACK');
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Interim Relief insertion failed. Please try again...</strong></div>';
			header('Location:emp_ir_cal.php');
		}
	}
	else
	{
		if($ir_submit)
		{
			pg_query('COMMIT');
			$_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong> Interim Relief Of '.$emp_name.' Submited successfully...</strong></div>';
			header('Location:emp_ir_cal.php');
		}
		else
		{
			pg_query('ROLLBACK');
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Interim Relief insertion failed. Please try again...</strong></div>';
			header('Location:emp_ir_cal.php');
		}
	}
						
	
  
  ?>