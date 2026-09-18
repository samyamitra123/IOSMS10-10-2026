<?php
//echo 233; die;
session_start();
//print_r($_SESSION);
ob_start();
require_once '../../includes/config/config.php';
	require_once '../../includes/config/database.config.php';
	require_once '../../includes/library/database.class.php';
	require_once '../../includes/library/cryptography.class.php';
	
/*if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
*/

//echo 1222; die;
//echo $_SESSION['emp_id_const']; die;

$cryptoGraph=new cryptography();
$db=new database();

  $gp_id=$cryptoGraph->decode($_GET['gp'],4); 
 
  $stack =$cryptoGraph->decode($_GET['stack'],4);  
 
 if($stack=='GP')
 {

$db=new database();
$Query = "select pem.emp_first_name, pem.emp_second_name, pem.emp_last_name, pem.emp_id_const, pem.gp_id_fk, pdcm.description from prd_employee_master as pem 
    LEFT JOIN prd_dise_code_master as pdcm ON pem.emp_desig::varchar = pdcm.code 
    where pem.gp_id_fk='".$gp_id."' and pem.emp_status='1'";

$arr=$db->fetch_table($Query);
?>
 <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['emp_id_const']. '<br />'; ?>
<option value="<?=$cryptoGraph->encode($key['emp_id_const'],4); ?>" ><?= $key['emp_first_name'].' '. $key['emp_second_name'].' '.$key['emp_last_name'].' ('.$key['description'].')'; ?></option>

<? }

}
else if($stack=='PS')
{
$db=new database();
$Query = "select pem.emp_first_name, pem.emp_second_name, pem.emp_last_name, pem.emp_id_const, pem.ps_id_fk, pdcm.description from prd_employee_master as pem 
    LEFT JOIN prd_dise_code_master as pdcm ON pem.emp_desig::varchar = pdcm.code 
    where pem.ps_id_fk='".$gp_id."' and pem.emp_status='1'";
$arr=$db->fetch_table($Query);
?>
 <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['emp_id_const']. '<br />'; ?>
<option value="<?=$cryptoGraph->encode($key['emp_id_const'],4); ?>" ><?= $key['emp_first_name'].' '. $key['emp_second_name'].' '.$key['emp_last_name'].' ('.$key['description'].')'; ?></option>

<? }
}
else
{
$db=new database();	
$Query = "select pem.emp_first_name, pem.emp_second_name, pem.emp_last_name, pem.emp_id_const, pem.zp_id_fk, pdcm.description from prd_employee_master as pem 
    LEFT JOIN prd_dise_code_master as pdcm ON pem.emp_desig::varchar = pdcm.code 
    where pem.zp_id_fk='".$_SESSION['user_info']['district_id_fk']."' and pem.emp_status='1'";
$arr=$db->fetch_table($Query);
?>
 <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['emp_id_const']. '<br />'; ?>
<option value="<?=$cryptoGraph->encode($key['emp_id_const'],4); ?>" ><?= $key['emp_first_name'].' '. $key['emp_second_name'].' '.$key['emp_last_name'].' ('.$key['description'].')'; ?></option>

<? }
}


 ?>
