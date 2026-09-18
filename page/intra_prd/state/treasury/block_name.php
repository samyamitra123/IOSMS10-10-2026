<?php // echo $_POST['district_code'] ;
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
$db = new database();
$district_code =$_POST['district_code'] ;
//echo $district_code ;
if($district_code!=''){
$block = $db->fetch_table("select block_code,block_name from prd_location_master_block where CAST(block_code AS text) like '$district_code%' order by block_name") ; 
//print_r($sub_div);
$name="<option value=''>-Please Select-</option>";
		foreach($block as $item){
			//echo $item['district_code']." ".$item['district_name'] ;
			$name.="<option value='".$item['block_code']."'>".$item['block_name']."</option>";
			//echo $name ;
		}
print_r($name);
}
else
{
$name="<option value=''>-Please Select-</option>";
print_r($name);
	
}
?>