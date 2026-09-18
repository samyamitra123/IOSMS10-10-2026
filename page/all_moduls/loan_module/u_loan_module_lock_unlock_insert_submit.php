<?
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

//  Kalyan Ghosh  16/3/2017   Start
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}
//  Kalyan Ghosh  16/3/2017   Finish

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

//   kalyan Ghosh   16/3/2017   Finish

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$emp_count=$_POST['emp_count'];

// Anupam dey 20/3/2017 start

$emp_id_fk = $cryptoGraph->decode($_GET['emp_id_fk'],4);
$loan_id_pk = $cryptoGraph->decode($_GET['loan_id_pk'],4);

// Anupam dey 20/3/2017 finish

$db = new database();
			
	$query_update = $db->update("UPDATE prd_loan_deduction SET lock_status = '4' WHERE emp_id_fk='".$emp_id_fk."' AND loan_id_pk='".$loan_id_pk."' AND status not in (0) AND lock_status in (3)");
	
				if($query_update){
				$msg  = '<div class="alert alert-success" style="text-align:center; width: 800px !important;""><strong>Employee Loan Details Has Been Unlocked Successfully.</strong></div>';
				echo $msg;
				}
				else{
					$msg= '<div class="alert alert-danger" style="text-align:center; width: 800px !important;""><strong>Employee Loan Details Has Not Been Unlocked. Please Try Again...</strong></div>';
					echo $msg;
					}
				
					
@pg_close($con);
?>