<?php
//echo 11;die;
session_start();
error_reporting(0);
  
ob_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
require_once '../all_function/fun_store/ifms_functions.php';
//echo 23;die;

$crypto=new cryptography();
$db=new database();
 if($_SERVER['HTTP_REFERER']==''){
 	header("Location:dashboard_intra_pri.php");
}
  $block_id = $_POST['block_id'];
  //print($block_id); exit;
  $gpLists = $db->fetch_table(" SELECT * FROM prd_location_master_gp WHERE block_id_fk = '".$block_id."' ");
?>
<option value="">-Please Select-</option>
<?php foreach($gpLists as $gp): ?>
	  <option value="<?php echo $gp['gp_id_pk']?>"><?php echo $gp['gp_name']?></option>
<?php endforeach; ?>