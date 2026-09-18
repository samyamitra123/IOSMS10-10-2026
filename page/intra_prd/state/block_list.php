<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
error_reporting(0);
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
//require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$db=new database();
$block=$db->fetch_table("Select block_code,block_name From prd_location_master_block Where district_id_fk='".$_REQUEST['district_id']."' order by block_name");
$name="<option value=''>-Please Select-</option>";
		foreach($block as $item){
			//echo $item['district_code']." ".$item['district_name'] ;
			$name.="<option value='".$item['block_code']."'>".$item['block_name']."</option>";
			//echo $name ;
		}
print_r($name);
