<?

session_start();
ob_start();
require_once '../../includes/config/config.php';
	require_once '../../includes/config/database.config.php';
	require_once '../../includes/library/database.class.php';
	require_once '../../includes/library/cryptography.class.php';
	
/*if($_SERVER['HTTP_REFERER']==''){
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
*/

//echo 1222; die;
//echo $_SESSION['emp_id_const']; die;

$cryptoGraph=new cryptography();
$db=new database();

 $old_gp=$cryptoGraph->decode($_GET['block'],4); 

$arr=$db->fetch_table("select gp_id_pk,gp_name,gp_code from prd_location_master_gp where block_id_fk='".$old_gp."'");
?>
 <option value="">-Please Select-</option>
<? foreach($arr as $key){ $key['gp_code']. '<br />'; ?>
<option value="<?=$cryptoGraph->encode($key['gp_code'],4); ?>" ><?= $key['gp_name']; ?></option>

<? } ?>
