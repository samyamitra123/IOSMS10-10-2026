<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
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

$db=new database();
$arr=$db->fetch_table("select ps_id_pk,ps_name,ps_code from prd_location_master_panchayat_samiti where district_id_fk='".$_REQUEST['district']."' or ps_status='5'");
?>
 <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['ps_id_pk']. '<br />'; ?>
<option value="<?= $key['ps_code']; ?>"><?= $key['ps_name']; ?></option>
<? } ?>
