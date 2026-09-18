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

$level=$_POST['level'];
$cryptoGraph=new cryptography();

$db=new database();

$arr_level=$db->fetch_table("SELECT $level from ropa_2019 ");

//var_dump($arr_level); die;
?>
	<option value="">Please Select</option>
    
	 <? foreach($arr_level as $key => $val){ 
	 //var_dump($val[$level]);
	 //$emp_full_name=$key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'];
	 ?>
		 <option value="<?=$val[$level]; ?>" ><?= $val[$level]; ?></option>

     <? }  

?>







