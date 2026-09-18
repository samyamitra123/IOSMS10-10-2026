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
$cryptoGraph=new cryptography();
$db=new database();

$old_gp=$cryptoGraph->decode($_GET['old_gp'],4);

$arr=$db->fetch_table("select gp_id_pk,gp_name from prd_location_master_gp where block_id_fk='".$_REQUEST['block']."'");
?>
 <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['gp_id_pk']. '<br />'; ?>
<option value="<?=$key['gp_id_pk']; ?>" <?php if($key['gp_id_pk']==$old_gp){ ?> disabled style="color:#DF5255" <?php } ?>><?= $key['gp_name']; ?></option>

<? } ?>
