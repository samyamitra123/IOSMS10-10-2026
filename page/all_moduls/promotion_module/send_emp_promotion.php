<?

ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';




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



if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$emp_count=$_POST['emp_count'];

//$loan_id_pk = $_REQUEST['loan_id_pk'];
if($_SESSION['user_info']['stake_abbr'] == 'GP')
{
	$gp_or_ps_id_fk = 'gp_id_fk';
	$gp_or_ps_id_fk_val = substr($_SESSION['location']['gp_id'],0,7);
}
else if($_SESSION['user_info']['stake_abbr'] == 'DA')
{
	$gp_or_ps_id_fk = 'ps_id_fk';
	$gp_or_ps_id_fk_val = substr($_SESSION['location']['ps_id'],0,7);
}

/*********************************************** Added by ANJAN for ZP Start ******************************************************************/

else if($_SESSION['user_info']['stake_abbr'] == 'DEALING ASSISTANT (Establishment)')
{
	$gp_or_ps_id_fk = 'zp_id_fk';
	$gp_or_ps_id_fk_val = substr($_SESSION['location']['district_id'],0,7);
}

/************************************************* ZP end **********************************************************************************************/

$db = new database();
//die("1234");
if(isset($_POST['send_promotion']))
{
	$emp_id_fk = $cryptoGraph->decode($_POST['emp_id_fk'],4);	
	$Query = "UPDATE prd_employee_promotion_details SET approval_status = '2' WHERE emp_id_fk='".$emp_id_fk."'  AND approval_status in ('1') AND ".$gp_or_ps_id_fk." = '".$gp_or_ps_id_fk_val."' AND delete_status = '1'"; 
	//print($Query); exit;
	$query_update = $db->update($Query);
	
				if($query_update){
				//$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Unlocked Successfully Done.</strong></div>';
					header('location:emp_promotion_entry_view.php?lock='.$cryptoGraph->encode(sent,4));
				//	header('Location:emp_increment_edit_sal.php?&emp_id='.$cryptoGraph->encode($emp_id_pk,4));
					exit(0);
				}
				else{
					//$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Unlock Failed.</strong></div>';
				header('location:emp_promotion_entry_view.php?lock='.$cryptoGraph->encode(failed,4));
			   	exit(0);
					}
}
@pg_close($con);
?>