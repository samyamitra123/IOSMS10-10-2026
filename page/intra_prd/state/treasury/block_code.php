<?php // echo $_POST['district_code'] ;
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
$db = new database();
$block_code = $_POST['block_code'] ;
/*echo "select distcd, block.block_code, treasury_block_code, treasury_name, lead_bank, lead_branch, entry_time, ip_add, desig_block, app_duration, address_line2,tan_no, admin_id_pk from prd_dise_admin as adm inner join prd_location_master_block as block on CAST(adm.block_code as text)=CAST(block.block_code as text) where block.block_code=3215001";exit;*/
//echo $sub_division_code ;
$block = $db->fetch_table("select distcd, block.block_code, treasury_block_code, treasury_name, lead_bank, lead_branch, entry_time, ip_add, desig_block, app_duration, address_line2,tan_no, admin_id_pk from prd_dise_admin as adm inner join prd_location_master_block as block on CAST(adm.block_code as text)=CAST(block.block_code as text) where block.block_code=$block_code") ; 
//print_r($sub_div);
if(count($block)>0){
	
	foreach($block[0] as $key=>$value){
		$item[$key] = $value ;
	}
	print_r(json_encode($item));
	//echo '0';
}
else
{
	echo '1';	
}

?>