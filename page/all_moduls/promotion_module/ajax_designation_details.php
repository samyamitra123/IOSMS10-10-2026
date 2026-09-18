<?
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}





//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location: '. $config['base_url'] . "page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location: '. $config['base_url'] . "page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$cryptoGraph=new cryptography();
$db=new database();

$arr = $db->fetch_table("select designation_id,designation_name from mad_master_designation where grade_code_fk ='".$_GET['grade']."'");

?>
<select class="form-control vice_designation" name="vice_desig" id="emp_vice_desig">
<option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['designation_name']. '<br />'; ?>
<option value="<?= $key['designation_id']; ?>" <? if($emp_pay_scale==$key['designation_id'] || $pay_scale==$key['designation_id']){ echo "selected";}?>><?= $key['designation_name']; ?></option>
<? } 
@pg_close($con);
?>
</select>
