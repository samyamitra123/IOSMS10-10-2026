<?
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';
error_reporting(0);

//   Kalyan Ghosh   16/3/2017    Start
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}
//   Kalyan Ghosh    16/3/2017    Finish

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//    Kalyan Ghosh    16/3/2017    Start

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}
//    Kalyan Ghosh    16/3/2017    Finish
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$emp_count=$_POST['emp_count'];
if($sec_time_token!=$enc_session)
{
	// Anupam Dey 17/3/2017 start
	
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again...</strong></div>';
	//$emp_id=$cryptoGraph->encode($_REQUEST['emp_id_pk'],4);
	//include 'emp_loan_deduction_edit_sal.php';
	header('Location:emp_loan_deduction_sal_form.php');
	exit;
	
	// Anupam dey 17/3/2017 finish
}
else{

//$pay_band=$_REQUEST['pay_band']==''?0:$_REQUEST['pay_band'];

$emp_id_pk=$cryptoGraph->decode($_POST['emp_id_pk'],4);
$loan_type = $_POST['loan_div_ids'];

$loan_type = explode('.',$loan_type);
//print_r($loan_type); die;

$db = new database();

		   $emp_data = 	$db->fetch_table("
											SELECT emp_id_const,
											zp_id_fk
											FROM prd_employee_master WHERE emp_id_pk = '".$emp_id_pk."';
	
										");									


	foreach($loan_type as $loan_type_variable)
	{
		
		//  < kalyan ghosh   10/3/2017   start >
		$loan_type_variable = 'loan_'.$loan_type_variable;
		
		if ($_POST[$loan_type_variable.'_types'] == '')
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Select Loan Type. </strong></div>';
		header('Location:emp_loan_deduction_sal_form.php');
		exit(0);
		}
		
		if ($_POST[$loan_type_variable.'_total_amt'] == 0  || $validator->blank_select($_POST[$loan_type_variable.'_total_amt']) == FALSE || $_POST[$loan_type_variable.'_total_amt'] < 0 || $validator->pattern_number($_POST[$loan_type_variable.'_total_amt']) == FALSE)
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong> Please Enter Valid Total Repayment Amount. </strong></div>';
		header('Location:emp_loan_deduction_sal_form.php');
		exit(0);
		}
		
		if ($_POST[$loan_type_variable.'_due_amt'] == 0  || $validator->blank_select($_POST[$loan_type_variable.'_due_amt']) == FALSE || $_POST[$loan_type_variable.'_due_amt'] < 0 || $validator->pattern_number($_POST[$loan_type_variable.'_due_amt']) == FALSE)
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Due Amount. </strong></div>';
		header('Location:emp_loan_deduction_sal_form.php');
		exit(0);
		}
		
		if ($_POST[$loan_type_variable.'_install_amt'] == 0  || $validator->blank_select($_POST[$loan_type_variable.'_install_amt']) == FALSE || $_POST[$loan_type_variable.'_install_amt'] < 0 || $validator->pattern_number($_POST[$loan_type_variable.'_install_amt']) == FALSE)
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Installation Amount. </strong></div>';
		header('Location:emp_loan_deduction_sal_form.php');
		exit(0);
		}
		
		if ($_POST[$loan_type_variable.'_no_of_installment'] == 0  || $validator->blank_select($_POST[$loan_type_variable.'_no_of_installment']) == FALSE || $_POST[$loan_type_variable.'_no_of_installment'] < 0 || $validator->pattern_number($_POST[$loan_type_variable.'_no_of_installment']) == FALSE)
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid No. of Installments. </strong></div>';
		header('Location:emp_loan_deduction_sal_form.php');
		exit(0);
		}
		
		if ($validator->blank_select($_POST[$loan_type_variable.'_reminder_amt']) == FALSE || $_POST[$loan_type_variable.'_reminder_amt'] < 0 || $validator->pattern_number($_POST[$loan_type_variable.'_reminder_amt']) == FALSE)
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Remainder Amount. </strong></div>';
		header('Location:emp_loan_deduction_sal_form.php');
		exit(0);
		}
		
		if ($validator->blank_select($_POST[$loan_type_variable.'_loan_name']) == FALSE)
		{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Loan Name. </strong></div>';
		header('Location:emp_loan_deduction_sal_form.php');
		exit(0);
		}
		
		
		//  < kalyan ghosh   10/3/2017   finish >
		$loan_type_identity = $_POST[$loan_type_variable.'_types'];
		$loan_amt = $_POST[$loan_type_variable.'_total_amt'];
		$install_amt = $_POST[$loan_type_variable.'_install_amt'];
		$no_of_install = $_POST[$loan_type_variable.'_no_of_installment'];
		$reminder_amt = $_POST[$loan_type_variable.'_reminder_amt'];
		$this_status = $_POST[$loan_type_variable.'_status'];
		$this_approval_status = $_POST[$loan_type_variable.'_approval_status'];
		$this_lock_status = $_POST[$loan_type_variable.'_lock_status'];
		$this_due_amount = $_POST[$loan_type_variable.'_due_amt'];
		$this_loan_name = $_POST[$loan_type_variable.'_loan_name'];
		
		/*if((($this_status == 99) && ($this_approval_status == 99) && ($this_lock_status == 99)) || (($this_status == 1) && ($this_approval_status == 1) && ($this_lock_status == 1)))*/
		/*if(($this_status == 99) && ($this_approval_status == 99) && ($this_lock_status == 99))
		{
			if($loan_amt != $this_due_amount)	
			{
				$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Amount. </strong></div>';
				header('Location:emp_loan_deduction_sal_form.php');
				exit(0);
			}
		}*/
		/*if(!($loan_amt >= $install_amt))	
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Installemnt Amount. </strong></div>';
			header('Location:emp_loan_deduction_entry_sal.php');
			exit(0);
		}
		if(!($loan_amt > $reminder_amt))	
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Remainder Amount. </strong></div>';
			header('Location:emp_loan_deduction_entry_sal.php');
			exit(0);
		}*/
		/*$chk_reminder_amt = $this_due_amount % $install_amt;
		$chk_no_of_install = (($this_due_amount - $chk_reminder_amt)/$install_amt);

		if(($chk_reminder_amt != $reminder_amt) || ($chk_no_of_install != $no_of_install))
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Data. </strong></div>';
			header('Location:emp_loan_deduction_entry_sal.php');
			exit(0);
		}*/
		
		
		
		//if($this_lock_status == 4)
		if(isset($_POST[$loan_type_variable.'_loan_id_pk']))
		{
			$this_loan_id_pk = $_POST[$loan_type_variable.'_loan_id_pk'];
		}
		
		$get_loan_type=$db->fetch_table("SELECT loan_master_id_pk,
										  dise_code
										  FROM prd_master_loan_type
										  WHERE 
										  loan_type_variable = '".$loan_type_identity."' AND status in(1)");
										  
	    if(($this_status == 99) && ($this_approval_status == 99) && ($this_lock_status == 99))
		{
		
		$query_insert=$db->insert("INSERT into prd_loan_deduction
											(
				                            total_amount,
											installment_amount,
											no_of_installment,
											reminder_amount,
											status,
											approval_status,
										    counter,
											emp_id_fk,
											emp_unique_id,
											zp_id_fk,
											created_by,
											create_date,
											ip,
											loan_master_id_fk,
											dise_code_fk,
											deduction_loan_type_variable,
											who,
											lock_status,
											edit_status,
											delete_status,
											due_amount,
											loan_name
											 )
											 VALUES(
											'".$loan_amt."',
											'".$install_amt."',
											'".$no_of_install."',
											'".$reminder_amt."',
											'1',
										    '1',
											'0',
											'".$emp_id_pk."',
											'".$emp_data[0]['emp_id_const']."',
											'".$emp_data[0]['zp_id_fk']."',
											'".$_SESSION['user_info']['stake_user']."',
											now(),
											'".$_SERVER['REMOTE_ADDR']."',
											'".$get_loan_type[0]['loan_master_id_pk']."',
											'".$get_loan_type[0]['dise_code']."',
											'".$loan_type_identity."',
											'".$_SESSION['user_info']['stake_user']."',
											'1',
											'1',
											'1',
											'".$this_due_amount."',
											'".$this_loan_name."'
										)");
										
										
			
		}
		else if(($this_status == 1) && ($this_approval_status == 1 || $this_approval_status == 4) && ($this_lock_status == 1))
		{
			$query_update=$db->update("UPDATE prd_loan_deduction SET
				                            total_amount = '".$loan_amt."',
											installment_amount = '".$install_amt."',
											no_of_installment = '".$no_of_install."',
											reminder_amount = '".$reminder_amt."',
											status = '1',
											approval_status = '1',
										    counter = '0',
											who = '".$_SESSION['user_info']['stake_user']."',
											ip = '".$_SERVER['REMOTE_ADDR']."',
											update_time = now(),
											due_amount = '".$this_due_amount."',
											loan_name = '".$this_loan_name."'
											WHERE 
											emp_id_fk = '".$emp_id_pk."' AND status in (1) AND approval_status in (1,4)
											AND loan_id_pk = '".$this_loan_id_pk."'");					
		 }
		 //Still not modified Start
		 else if(($this_status == 2) && ($this_approval_status == 3) && ($this_lock_status == 4))
		 {
			$query_insert=$db->insert("INSERT into prd_loan_deduction
											(
				                            total_amount,
											installment_amount,
											no_of_installment,
											reminder_amount,
											status,
											approval_status,
										    counter,
											emp_id_fk,
											emp_unique_id,
											zp_id_fk,
											created_by,
											create_date,
											ip,
											loan_master_id_fk,
											dise_code_fk,
											deduction_loan_type_variable,
											who,
											lock_status,
											edit_status,
											delete_status,
											loan_id_fk,
											due_amount,
											loan_name
											 )
											 VALUES(
											'".$loan_amt."',
											'".$install_amt."',
											'".$no_of_install."',
											'".$reminder_amt."',
											'1',
										    '1',
											'0',
											'".$emp_id_pk."',
											'".$emp_data[0]['emp_id_const']."',
											'".$emp_data[0]['municipality_id_fk']."',
											'".$_SESSION['user_info']['stake_user']."',
											now(),
											'".$_SERVER['REMOTE_ADDR']."',
											'".$get_loan_type[0]['loan_master_id_pk']."',
											'".$get_loan_type[0]['dise_code']."',
											'".$loan_type_identity."',
											'".$_SESSION['user_info']['stake_user']."',
											'1',
											'1',
											'2',
											'".$this_loan_id_pk."',
											'".$this_due_amount."',
											'".$this_loan_name."'
										)");
				
				if($query_insert)
				{
					$query_update=$db->update("UPDATE prd_loan_deduction SET
				                            edit_status = '2'
											WHERE 
											emp_id_fk = '".$emp_id_pk."' AND status in (2) AND approval_status in (3) 
											AND loan_id_pk = '".$this_loan_id_pk."'");
				}
		}
		//Still not modified End
		
		 /*else
		 {
			 exit(0);
		 }*/
	}
	//die;
			
					
				
				if($query_insert || $query_update){
				//header('Location:emp_increment_basic_form.php?&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4));
				
				//exit(0);
		

				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Employee Loan Details Has Been Saved Successfully.</strong></div>';
					header('location:emp_loan_deduction_sal_form.php');
				//	header('Location:emp_increment_edit_sal.php?&emp_id='.$cryptoGraph->encode($emp_id_pk,4));
					exit(0);
				}
				else{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Employee Loan Details Has Not Been Saved. Please Try Again...</strong></div>';
				header('location:emp_loan_deduction_sal_form.php');
			   	exit(0);
					
					}
				
				}
					
@pg_close($con);
?>