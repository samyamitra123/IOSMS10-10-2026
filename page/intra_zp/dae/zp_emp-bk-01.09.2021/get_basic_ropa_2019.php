<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require '../../../../includes/library/myvalidation.class.php';
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
 $emp_pay_in_payband=$_POST['emp_pay_in_payband'];
$emp_type=$_POST['emp_type'];
$cryptoGraph=new cryptography();

if($emp_type=='367')
{
	
$db=new database();
//echo ("SELECT $level from ropa_2019 order by level ");die;
$arr_level=$db->fetch_table("SELECT $level from ropa_2019 order by level ");
}
else
{
	$db=new database();
	//echo ("SELECT $level from ropa_2019_ll order by level ");die;
	$arr_level=$db->fetch_table("SELECT $level from ropa_2019_ll order by level ");
}

//var_dump($emp_pay_in_payband); die;

?>

	<option value="">Please Select</option>
    
	 <? foreach($arr_level as $key => $val){ 
	 
	 
	 ?>
		
		 <option value="<?= $val[$level]; ?>" <? if($emp_pay_in_payband==$val[$level]){ echo "selected";}?>><?= $val[$level]; ?></option>

     <? }  

?>







