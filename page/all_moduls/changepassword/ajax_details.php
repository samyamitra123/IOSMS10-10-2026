<?

session_start();
ob_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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
}*/
$cryptoGraph=new cryptography();
$db=new database();

$id=$_GET['id'];
//var_dump($_SESSION['user_info']['stake_abbr']);

if($_SESSION['user_info']['stake_abbr']=='EO')
{ 
	$arr=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name,emp_id_const from prd_employee_master where ps_id_fk='".$id."' and emp_status in('1','9')");
}
else if($_SESSION['user_info']['stake_abbr']=='BDO')
{ 
	$arr=$db->fetch_table("select emp_first_name,emp_second_name,emp_last_name,emp_id_const from prd_employee_master where gp_id_fk='".$id."' and emp_status in('1','9')");
}


?>
 <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['emp_id_const']. '<br />'; ?>
<option value="<?=$key['emp_id_const']; ?>" ><?= $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name']; ?></option>

<? } ?>
