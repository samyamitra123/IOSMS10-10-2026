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
	// Anupam dey 17/3/2017 start
	
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again...</strong></div>';
	$emp_id=$cryptoGraph->encode($_POST['emp_id_pk'],4);
	//include 'emp_loan_deduction_edit_sal.php';
	header('location:emp_loan_deduction_sal_form.php');
	exit;
	
	// Anupam dey 17/3/2017 finish
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
		
		 if(($this_status == 1) && ($this_approval_status == 1))
		 {
			$send_ddo = $query_update=$db->update("UPDATE prd_loan_deduction SET
												status = '1',
												approval_status = '2'
												WHERE emp_id_fk = '".$emp_id_pk."'
												AND status in (1) AND approval_status in (1)
												AND deduction_loan_type_variable = '".$loan_type_identity."'
												AND loan_id_pk = '".$this_loan_id_pk."'
												");	 
		 }
	}
	
				
				if($query_update){
				//header('Location:emp_increment_basic_form.php?&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4));
				
				//exit(0);
		

				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Employee Loan Details Has Been Sent For Approval  Successfully...</strong></div>';
					header('location:emp_loan_deduction_sal_form.php');
				//	header('Location:emp_increment_edit_sal.php?&emp_id='.$cryptoGraph->encode($emp_id_pk,4));
					exit(0);
				}
				else{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Employee Loan Details Has Not Been Sent For Approval. Please Try Again...</strong></div>';
				header('location:emp_loan_deduction_sal_form.php');
			   	exit(0);
					
					}
				
					}
					
@pg_close($con);
?>