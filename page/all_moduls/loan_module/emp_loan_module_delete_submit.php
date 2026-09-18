<?
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

//   Kalyan Ghosh   16/3/2017    Start
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}
//   Kalyan Ghosh   16/3/2017    Finish

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}



if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}
//   Kalyan Ghosh   16/3/2017    Finish
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$emp_count=$_POST['emp_count'];
if($sec_time_token!=$enc_session)
{
	/*$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again...</strong></div>';
	$emp_id=$cryptoGraph->encode($_POST['emp_id_pk'],4);
	include 'emp_loan_deduction_edit_sal.php';
	exit;*/
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again...</strong></div>';
	header('location:emp_loan_deduction_sal_form.php');
	exit(0);
}
else{

$emp_id_pk=$cryptoGraph->decode($_POST['emp_id_pk'],4);
$delete_loans = $_POST['delete'];
//print_r($loan_type); die;

$db = new database();

		   $emp_data = 	$db->fetch_table("
											SELECT emp_id_const,
											zp_id_fk
											FROM prd_employee_master WHERE emp_id_pk = '".$emp_id_pk."';
	
										");									


	foreach($delete_loans as $delete)
	{
		
			 $mad_loan_archive_delete=$db->insert("INSERT INTO  prd_loan_deduction_delete_archive (
																						 loan_id_pk,
																						 total_amount,
																						  installment_amount,
																						  no_of_installment,
																						  reminder_amount,
																						  status ,
																						  approval_status ,
																						  counter,
																						  emp_id_fk,
																						  emp_unique_id ,
																						  zp_id_fk ,
																						  created_by,
																						  create_date ,
																						  ip ,
																						  loan_master_id_fk ,
																						  dise_code_fk ,
																						  deduction_loan_type_variable ,
																						  who,
																						  lock_status,
																						  delete_time,
																						  edit_status,
																						  delete_status,
																						  due_amount,
																						  loan_name  
																						  )
																						  
																						SELECT loan_id_pk ,
																						  total_amount,
																						  installment_amount,
																						  no_of_installment,
																						  reminder_amount ,
																						  status ,
																						  approval_status,
																						  counter,
																						  emp_id_fk ,
																						  emp_unique_id ,
																						  zp_id_fk,
																						  created_by ,
																						  create_date,
																						  '".$_SERVER['REMOTE_ADDR']."' ,
																						  loan_master_id_fk ,
																						  dise_code_fk ,
																						  deduction_loan_type_variable ,
																						  '".$_SESSION['user_info']['stake_user']."' ,
																						  lock_status,
																						  now(),
																						  edit_status,
																						  delete_status,
																						  due_amount,
																						  loan_name 
																						
																						FROM prd_loan_deduction
																						where emp_id_fk='".$emp_id_pk."' AND loan_id_pk ='".$delete."'");
		
																						
			
		if($mad_loan_archive_delete) 
		{
			$query_update=$db->update("delete from prd_loan_deduction where emp_id_fk='".$emp_id_pk."' AND loan_id_pk = '".$delete."'");
			//echo "delete from mad_loan_deduction where emp_id_pk='".$emp_id_pk."' AND loan_id_pk = '".$delete."'"; die;	
		}
	//}
	}
	
				if($query_update){
				//header('Location:emp_increment_basic_form.php?&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4));
				
				//exit(0);
		
				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center; "><strong>Loan Details Has Been Deleted Successfully.</strong></div>';
					header('location:emp_loan_deduction_sal_form.php');
				//	header('Location:emp_increment_edit_sal.php?&emp_id='.$cryptoGraph->encode($emp_id_pk,4));
					exit(0);
				}
				else{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center; width: 800px !important; margin-left: -15%;""><strong>Loan Details Has Not Been Deleted. Please Try Again...</strong></div>';
				header('location:emp_loan_deduction_sal_form.php');
			   	exit(0);
					
					}
				
					}
					
@pg_close($con);
?>