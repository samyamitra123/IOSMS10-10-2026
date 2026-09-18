<?
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
$arr=$db->fetch_table("select block_id_pk,block_name from prd_location_master_block where district_id_fk='".$_REQUEST['district']."'");
?>
 <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['block_id_pk']. '<br />'; ?>
<option value="<?= $key['block_id_pk']; ?>"><?= $key['block_name']; ?></option>
<? } ?>
