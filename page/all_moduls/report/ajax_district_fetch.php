<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

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
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page



$db=new database();
 $stake=$_GET['stake']; 
$district= $cryptoGraph->decode($_GET['district'],4); 
	

	
	$arr = $db->fetch_table("Select district_id_pk,district_code,district_name from prd_location_master_district ");


if(count($arr) > 0)
{


?>
<select class="form-control" name="emp_district"  id="emp_district"   /> 
 <option value="">--Please Select--</option>
<? foreach($arr as $key){ $key['district_id_pk']. '<br />'; ?>
<option value="<?= $key['district_id_pk']; ?>" ><?= $key['district_name']; ?></option>
<? }?>
</select>
<?php } 


else
{
	echo "No Data Found.";	
}

?>















      

