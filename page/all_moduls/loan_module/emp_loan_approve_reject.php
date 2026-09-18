<?
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

//   Kalyan Ghosh   16/3/2017   Start
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}
//   Kalyan Ghosh   16/3/2017   Finish

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
//   Kalyan Ghosh  16/3/2017   Finish


header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$emp_count=$_POST['emp_count'];
if($sec_time_token!=$enc_session)
{
	//die('1234');
	/*$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again...</strong></div>';
	$emp_id=$cryptoGraph->encode($_POST['emp_id_pk'],4);
	include 'emp_loan_deduction_entry_sal.php';*/
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again...</strong></div>';
	header('location:u_emp_loan_deduction_sal_form.php');
	exit(0);
	//exit;
}
else{

$emp_id_pk=$cryptoGraph->decode($_POST['emp_id_pk'],4);
$loan_type = $_POST['loan_div_ids'];
$loan_type = explode('.',$loan_type);

//$loan_type = $_POST['loan_type'];

$db = new database();
			
		   
		   $emp_data = 	$db->fetch_table("
											SELECT emp_id_const,
											zp_id_fk
											FROM prd_employee_master WHERE emp_id_pk = '".$emp_id_pk."';
	
										");									
	
			
if(isset($_POST['approve']))	
{
	
	foreach($loan_type as $loan_type_variable)
	{
		$loan_type_variable = 'loan_'.$loan_type_variable;
		$loan_type_identity = $_POST[$loan_type_variable.'_types'];
		$loan_amt = $_POST[$loan_type_variable.'_total_amt'];
		$install_amt = $_POST[$loan_type_variable.'_install_amt'];
		$no_of_install = $_POST[$loan_type_variable.'_no_of_installment'];
		$reminder_amt = $_POST[$loan_type_variable.'_reminder_amt'];
		$this_status = $_POST[$loan_type_variable.'_status'];
		$this_approval_status = $_POST[$loan_type_variable.'_approval_status'];
		$this_delete_status = $_POST[$loan_type_variable.'_delete_status'];
		$this_loan_id_pk = $_POST[$loan_type_variable.'_loan_id_pk'];
		
		 if(($this_status == 1) && ($this_approval_status == 2) && ($this_delete_status == 1))
		 {
			$send_ddo = $query_update=$db->update("UPDATE prd_loan_deduction SET
												status = '2',
												approval_status = '3',
												lock_status = '2'
												WHERE emp_id_fk = '".$emp_id_pk."'
												AND status in (1) AND approval_status in (2)
												AND deduction_loan_type_variable = '".$loan_type_identity."'
												AND loan_id_pk = '".$this_loan_id_pk."'");	 
		 }
		 
		 else if(($this_status == 1) && ($this_approval_status == 2) && ($this_delete_status == 2))
		 {
			 $pre_row_id = $db->fetch_table("SELECT * FROM prd_loan_deduction 
			 							WHERE emp_id_fk = '".$emp_id_pk."'
										AND status in (1) AND approval_status in (2)
										AND deduction_loan_type_variable = '".$loan_type_identity."'
										AND loan_id_pk = '".$this_loan_id_pk."'
										AND delete_status = '2'
			 						");
			 $new_installment_amount = $pre_row_id[0]['installment_amount'];
			 $loan_id_fk = $pre_row_id[0]['loan_id_fk'];
			 
			 $pre_row = $db->fetch_table("SELECT * FROM prd_loan_deduction
			 							WHERE loan_id_pk='".$loan_id_fk."' 
										AND deduction_loan_type_variable = '".$loan_type_identity."'
										AND delete_status = '1'
										AND edit_status = '2'");
			
			 //search salary save start
			 $salary_save_data = $db->fetch_table("SELECT COUNT(*) AS emp_sal_count FROM prd_employee_salary_save
			 									WHERE emp_id_fk = '".$emp_id_pk."'
												AND salary_monthyear = '".date("Ym")."'
												AND prd_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
			 //search salary save end
			 
			 if($salary_save_data[0]['emp_sal_count'] > 0)
			 {
										
				 $total_amount = $pre_row[0]['total_amount'];
				 $installment_amount = $pre_row[0]['installment_amount'];
				 $no_of_installment = $pre_row[0]['no_of_installment'];
				 $counter = $pre_row[0]['counter'];
				 $due_amount = $pre_row[0]['due_amount'];
				 $reminder_amount = $pre_row[0]['reminder_amount'];
				 
				 //Calculation For New Installment.
				 $new_reminder_amount = ($due_amount % $new_installment_amount);
				 
				 $new_no_of_installment = (($due_amount - $new_reminder_amount) / $new_installment_amount);
				 
				 $query_update=$db->update("UPDATE prd_loan_deduction SET
											status = '2',
											approval_status = '3',
											total_amount = '".$total_amount."',
											due_amount = '".$due_amount."',
											no_of_installment = '".$new_no_of_installment."',
											installment_amount = '".$new_installment_amount."',
											reminder_amount = '".$new_reminder_amount."',
											counter = '0',
											edit_status = '1',
											delete_status = '1',
											lock_status = '2'
											WHERE emp_id_fk = '".$emp_id_pk."'
											AND status in (1) AND approval_status in (2) 
											AND delete_status = '2'
											AND deduction_loan_type_variable = '".$loan_type_identity."'
											AND loan_id_pk = '".$this_loan_id_pk."'");
			 }
			 else
			 {
				 $query_update=$db->update("UPDATE prd_loan_deduction SET
											status = '2',
											approval_status = '3',
											edit_status = '1',
											delete_status = '1',
											lock_status = '2'
											WHERE emp_id_fk = '".$emp_id_pk."'
											AND status in (1) AND approval_status in (2) 
											AND delete_status = '2'
											AND deduction_loan_type_variable = '".$loan_type_identity."'
											AND loan_id_pk = '".$this_loan_id_pk."'");
				 
			 }
			 
			 //Update editable row with new no of installment and amount.
			 	 
			if($query_update)	
			{
				$copy_data = $db->insert("INSERT INTO  prd_loan_deduction_edit_archive (
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
																  edit_time,
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
														where emp_id_fk='".$emp_id_pk."' AND loan_id_pk ='".$loan_id_fk."'																															
														AND edit_status = '2'
														AND lock_status = '4'");
					if($copy_data)	
					{
						$delete=$db->update("delete from prd_loan_deduction where emp_id_fk='".$emp_id_pk."' AND loan_id_pk = '".$loan_id_fk."' AND edit_status = '2' AND lock_status = '4'");	
					}
			}
		 }
	}
	
			
				if($query_update){
				//header('Location:emp_increment_basic_form.php?&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4));
				
				//exit(0);
		

				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Employee Loan Details Has Been Approved Successfully.</strong></div>';
					header('location:u_emp_loan_deduction_sal_form.php');
				//	header('Location:emp_increment_edit_sal.php?&emp_id='.$cryptoGraph->encode($emp_id_pk,4));
					exit(0);
				}
				else{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Employee Loan Details Has Not Been Approved. Please Try Again...</strong></div>';
				header('location:u_emp_loan_deduction_sal_form.php');
			   	exit(0);
					
					}
				
}
if(isset($_POST['reject']))
{
	foreach($loan_type as $loan_type_variable)
	{
		$loan_type_variable = 'loan_'.$loan_type_variable;
		$loan_type_identity = $_POST[$loan_type_variable.'_types'];
		$loan_amt = $_POST[$loan_type_variable.'_total_amt'];
		$install_amt = $_POST[$loan_type_variable.'_install_amt'];
		$no_of_install = $_POST[$loan_type_variable.'_no_of_installment'];
		$reminder_amt = $_POST[$loan_type_variable.'_reminder_amt'];
		$this_status = $_POST[$loan_type_variable.'_status'];
		$this_approval_status = $_POST[$loan_type_variable.'_approval_status'];
		$this_loan_id_pk = $_POST[$loan_type_variable.'_loan_id_pk'];
		if(($this_status == 1) && ($this_approval_status == 2))
		 {
			$send_ddo = $query_update=$db->update("UPDATE prd_loan_deduction SET
												status = '1',
												approval_status = '4'
												WHERE emp_id_fk = '".$emp_id_pk."'
												AND status in (1) AND approval_status in (2)
												AND deduction_loan_type_variable = '".$loan_type_identity."'
												AND loan_id_pk = '".$this_loan_id_pk."'
												");	
		 }
	}
	if($query_update){
				//header('Location:emp_increment_basic_form.php?&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4));
				
				//exit(0);
		

				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Employee Loan Details Has Been Rejected Successfully.</strong></div>';
					header('location:u_emp_loan_deduction_sal_form.php');
				//	header('Location:emp_increment_edit_sal.php?&emp_id='.$cryptoGraph->encode($emp_id_pk,4));
					exit(0);
				}
				else{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Employee Loan Details Has Not Been Rejected. Please Try Again...</strong></div>';
				header('location:u_emp_loan_deduction_sal_form.php');
			   	exit(0);
					
					}
				
}
	
}
@pg_close($con);
?>