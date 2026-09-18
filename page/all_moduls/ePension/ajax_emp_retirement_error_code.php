<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}


$cryptoGraph=new cryptography();
$emp_id=$cryptoGraph->decode($_REQUEST['id'],4);
$db=new database();
$emp_error_data=$db->fetch_table("select error_code from prd_pension_employee where emp_id_fk='".$emp_id."'");
 $emp_error_code=$emp_error_data[0]['error_code'];
 /*$exampleEncoded = json_encode($emp_error_data);
  $con = json_decode($exampleEncoded, true);*/
  //$code=explode(",",$emp_error_code);
//print_r($code);
 foreach (explode(",",$emp_error_code) as $val) {
	
		$code_data=$db->fetch_table("SELECT description_new FROM prd_pension_error_code_master where error_code='".$val."'");

   echo '<span class="contacts-title" style="color:#0E3670;"><li><span class="contacts-title" style=" color:#730A0A;"><strong>'.$code_data[0]['description_new'].'</strong></span></li></span>'.'</br>';

 }


?>