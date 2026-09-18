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


	


$logged_user=$_SESSION['user_info']['stake_abbr'];

$emp_id=$_GET['emp_id'];
$cryptoGraph=new cryptography();

$db=new database();

$desig_fetch=$db->fetch_table("SELECT emp_desig FROM prd_employee_master WHERE emp_id_pk='".$emp_id."'");
echo $desig_fetch[0]['emp_desig'];
?>







