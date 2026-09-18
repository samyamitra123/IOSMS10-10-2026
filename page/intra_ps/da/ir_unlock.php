
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
$emp_id=($cryptoGraph->decode($_POST['emp_id_pk'],4));
//$emp_full_name=$_GET['emp_full_name'];

 
 //echo $emp_id;

 $db=new database();
 
$arr = $db->fetch_table("select emp_id_fk from psemp_interim_relief  where emp_id_fk='".$emp_id."' 
AND delete_status='1' AND ps_id_fk='".$_SESSION['location']['ps_id']."' and ir_status='4' and unlock_req='0'");

if($arr)
{
	$save=$db->update("UPDATE psemp_interim_relief SET unlock_req='1' where emp_id_fk='".$emp_id."' 
AND delete_status='1' AND ps_id_fk='".$_SESSION['location']['ps_id']."' and ir_status='4' and unlock_req='0'");
if($save){
    $_SESSION['msg']='<div class="alert alert-success" style="text-align:center;"><strong>Unlock Request send to EO successfully...</strong></div>';
    header('Location:emp_ir_cal.php');
}else{
   $_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Unlock Request failed. Please try again...</strong></div>'; 
   header('Location:emp_ir_cal.php'); 
    
}

}
else
{
 $_SESSION['msg']='<div class="alert alert-danger" style="text-align:center;"><strong>Unlock Request failed. Please try again...</strong></div>'; 
   header('Location:emp_ir_cal.php'); 	
}	 



?>