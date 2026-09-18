<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
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
$payband_id=$db->fetch_table("select payband_id_pk from prd_payband_master where payband_code='".$_REQUEST['payband']."'");
$id=$payband_id[0]['payband_id_pk'];
$arr=$db->fetch_table("select payscale_code,payscale_range from prd_dise_payscale_master where payband_id_fk='".$id."'");
?>
<? foreach($arr as $key){ $key['payscale_code']. '<br />'; ?>
<option value="<?= $key['payscale_code']; ?>" <? if($emp_pay_scale==$key['payscale_code']){ echo "selected";}?>><?= $key['payscale_range']; ?></option>
<? } ?>
