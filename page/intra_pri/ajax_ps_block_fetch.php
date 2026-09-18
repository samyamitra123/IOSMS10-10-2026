<?php

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

$crypto=new cryptography();
$db=new database();?>


   <?php  
   $db=new database();
   
   $check=$db->fetch_table("select gp_id_fk,ps_id_fk from prd_employee_master 
   where emp_id_const='".$_SESSION['emp_id_const']."'");
   
   
   if($check[0]['gp_id_fk']!='0')
   {
   
   $arr1=$db->fetch_table("select b.block_id_pk,b.block_name,b.block_code,d.district_id_pk
   
from prd_employee_master as e

inner join prd_location_master_gp g on  e.gp_id_fk=g.gp_id_pk

inner join prd_location_master_block b on  g.block_id_fk=b.block_id_pk
			inner join prd_location_master_district d
			on b.district_id_fk=d.district_id_pk	
   
   where e.emp_id_const='".$_SESSION['emp_id_const']."'");
   
   $arr=$db->fetch_table("select block_id_pk,block_name,block_code from prd_location_master_block where district_id_fk='".$arr1[0]['district_id_pk']."'");?>
   
    <option value="">-Please Select-</option>
<?php foreach($arr as $key){ $key['block_id_pk']. '<br />'; ?>
<option value="<?= $crypto->encode($key['block_id_pk'],4); ?>" ><?= $key['block_name']; ?></option>

<!--<input type="text"  id="district_id_fk" name="district_id_fk" value="" />-->
<?php }?>
   
   
  <?php }
   else
   {
	   
	   $arr1=$db->fetch_table("select b.ps_id_pk,b.ps_name,b.ps_code,d.district_id_pk
   
from prd_employee_master as e
inner join prd_location_master_panchayat_samiti b on  e.ps_id_fk=b.ps_id_pk
			inner join prd_location_master_district d
			on b.district_id_fk=d.district_id_pk	
   
   where e.emp_id_const='".$_SESSION['emp_id_const']."'");
   
   $arr=$db->fetch_table("select ps_id_pk,ps_name,ps_code from prd_location_master_block where district_id_fk='".$arr1[0]['district_id_pk']."'");?>
   
 
   <option value="">-Please Select-</option>
<?php foreach($arr as $key){ $key['ps_id_pk']. '<br />'; ?>
<option value="<?= $crypto->encode($key['ps_id_pk'],4); ?>" ><?= $key['ps_name']; ?></option>

<!--<input type="text"  id="district_id_fk" name="district_id_fk" value="" />-->
<?php }

   }
   
   
  
   
   
   //$stake=$_GET['stake'];
  
   
   
   
?>

 
    
    
   

    