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

// To select the pay scale of the employee depend on pay in pay band of employee
$db=new database();
$payband_id=$db->fetch_table("select payband_id_pk from prd_payband_master where payband_code='".$cryptoGraph->decode($_GET['payband'],4)."'");
$id=$payband_id[0]['payband_id_pk'];
$arr=$db->fetch_table("select payscale_code,payscale_range from prd_dise_payscale_master where payband_id_fk='".$id."'");
?>
<? foreach($arr as $key){ $key['payscale_code']. '<br />'; ?>
<option value="<?= $key['payscale_code']; ?>" <? if($emp_pay_scale==$key['payscale_code'] || $pay_scale==$key['payscale_code']){ echo "selected";}?>><?= $key['payscale_range']; ?></option>
<? } 
//@pg_close($con);
?>
