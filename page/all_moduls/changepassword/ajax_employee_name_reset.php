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



$db=new database();

  $id=$_GET['id']; 
  
  $check = $db->fetch_table("SELECT emp_id_const FROM prd_employee_login WHERE emp_id_const='" .$id . "' ");
  
  if(count($check)==1)
  {
	$arr=$db->fetch_table("select emp_id_const,emp_first_name,emp_mobile_no from prd_employee_master where emp_id_const='".$id."' and emp_status in('1','9')");
	
	$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$diff_password =  substr(str_shuffle($str_result), 0, 8);
				
	/*
	$pin=rand(1000, 9999);
 $pas1=substr($arr[0]['emp_first_name'],0,2); 
  $pas2=substr($arr[0]['emp_first_name'],-2); 
  $pas=$pas1.$pas2;
 $password=$pas.'@'.$pin;
 */
  }
  else
  {
$arr='';

  

//$pin=random_int(100000, 999999);

 }

echo json_encode(array($arr[0]['emp_id_const'],$diff_password,$arr[0]['emp_mobile_no']));
