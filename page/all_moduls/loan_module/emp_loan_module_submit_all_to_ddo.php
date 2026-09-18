<?
ob_start();
session_start();

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require '../../../../includes/library/myvalidation.class.php';

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
if (
    !(($_SESSION['privilege']['05'] == TRUE) 
  || ($_SESSION['privilege']['0501'] == TRUE)
  || ($_SESSION['privilege']['06'] == TRUE)
  || ($_SESSION['privilege']['0601'] == TRUE)
  || ($_SESSION['privilege']['0502'] == TRUE)
  || ($_SESSION['privilege']['55'] == TRUE)
  || ($_SESSION['privilege']['5501'] == TRUE)
  || ($_SESSION['privilege']['5502'] == TRUE)
  || ($_SESSION['privilege']['58'] == TRUE)
  || ($_SESSION['privilege']['5801'] == TRUE)
  || ($_SESSION['privilege']['5802'] == TRUE)
  || ($_SESSION['privilege']['5803'] == TRUE)
  || ($_SESSION['privilege']['5804'] == TRUE)
  || ($_SESSION['privilege']['5805'] == TRUE)
  || ($_SESSION['privilege']['5806'] == TRUE)
  || ($_SESSION['privilege']['5807'] == TRUE)
  || ($_SESSION['privilege']['5808'] == TRUE)
)
){
  header('Location:'.$config['base_url']."page/dashboard.php");
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
//    Kalyan Ghosh    16/3/2017    Finish

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$db = new database();
			
		  
$saved_loan_data = $db->fetch_table("SELECT emp_id_fk FROM mad_loan_deduction
									WHERE status in (1) AND approval_status in (1)
									AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");	
if(count($saved_loan_data) > 0)
{
	foreach($saved_loan_data as $loan_data)
	{
		$send_ddo = $query_update=$db->update("UPDATE mad_loan_deduction SET
											status = '1',
											approval_status = '2'
											WHERE emp_id_fk = '".$loan_data['emp_id_fk']."'
											AND status in (1) AND approval_status in (1)");
	}
}
	
	
				 
		
	
				
	if($send_ddo)
	{
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>All Saved Loan Details Has Been Sent For Approval  Successfully...</strong></div>';
			header('location:emp_loan_deduction_entry_sal.php');
		//	header('Location:emp_increment_edit_sal.php?&emp_id='.$cryptoGraph->encode($emp_id_pk,4));
			exit(0);
	}
	else
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Loan Details Has Not Been Sent For Approval. Please Try Again...</strong></div>';
	header('location:emp_loan_deduction_entry_sal.php');
	exit(0);
		
	}
								
@pg_close($con);
?>