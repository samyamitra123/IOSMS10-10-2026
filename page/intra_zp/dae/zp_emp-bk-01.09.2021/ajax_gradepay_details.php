<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']=='')
{
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

$db=new database();
$payband_id=$db->fetch_table("select payband_id_pk from prd_payband_master where payband_code='".$_REQUEST['payband']."'");
$id=$payband_id[0]['payband_id_pk'];
$arr=$db->fetch_table("select grade_amount,grade_code from prd_dise_gradepay_master where payband_id_fk='".$id."'");
?>
<option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['grade_code']. '<br />'; ?>
<option value="<?= $key['grade_code']; ?>" <? if($emp_pay_scale==$key['grade_code'] || $grade_pay==$key['grade_code']){ echo "selected";}?>><?= $key['grade_amount']; ?></option>
<? } ?>
