<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require '../../../../includes/library/myvalidation.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '.$config['base_url']."page/login.php");
	exit;
}

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

//error_reporting(0);

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$emp_count=$_POST['emp_count'];

$db = new database();
if(isset($_POST['send_promotion']))
{
	$emp_id_fk = $cryptoGraph->decode($_POST['emp_id_fk'],4);	 
		
	$query_update = $db->update("UPDATE mad_employee_promotion_details SET approval_status_by_ddo = '3' WHERE emp_id_fk='".$emp_id_fk."' AND status in ('2','3') AND approval_status in ('9','11') AND approval_status_by_ddo in ('2')");
	
				if($query_update){
					header('location:emp_promotion_entry_view.php?lock='.$cryptoGraph->encode(sent,4));
					exit(0);
				}
				else{
				header('location:emp_promotion_entry_view.php?lock='.$cryptoGraph->encode(failed,4));
			   	exit(0);
				}
}
@pg_close($con);
?>