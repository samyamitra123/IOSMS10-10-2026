<?php
ob_start();
session_start();
 header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';


$cryp = new cryptography();
$db = new database();
error_reporting(0);

?>

<?php
//$municipality_id_fk = substr($_SESSION['user_info']['stake_user'],0,7);
$emp_id_pk=$cryp->decode($_GET['emp_id_pk'],4);
 $field_type=$cryp->decode($_GET['field_type'],4); 

if($field_type=='seg')
{
	$curr_yr=$_GET['curr_yr'];
	$ppb=$_GET['ppb'];
	
	
	$update_data=$db->update("UPDATE prd_emp_basic_pay_details_before_2019 SET
							pay_band='$ppb',
							edit_status='1'
							WHERE emp_id_fk='$emp_id_pk'
							AND year='$curr_yr'
							AND edit_status='99'");
							
	if($update_data)
	{
		echo 1;	
	}
}
else if($field_type=='lst')
{
	//$curr_yr=$_GET['curr_yr'];
	//$ppb=$_GET['ppb'];
	$bp_01_01_2020_manually=$_GET['bp_01_01_2020_manually'];
	$lvl_01_01_2020=$cryp->decode($_GET['lvl_01_01_2020'],4);
	
	if($bp_01_01_2020_manually=='')
	{
		echo 'Please Enter Valid Basic Pay as on 01-01-2020.';
		exit();
	}
	else if($lvl_01_01_2020=='')
	{
		echo 'Please Select Valid Level as on 01-01-2020.';
		exit();
	}
	
	
	$get_gradepay=$db->fetch_table("SELECT level FROM prd_dise_gradepay_master 
								WHERE CAST (grade_code as integer)=(SELECT CAST (emp_grade_pay as integer) FROM 
								prd_employee_master WHERE emp_id_pk='$emp_id_pk')");
								
								
								$emp_type=$db->fetch_table("SELECT zp_emp_type FROM prd_employee_master
								WHERE emp_id_pk='$emp_id_pk'");
								
								if($emp_type[0]['zp_emp_type']=='' || $emp_type[0]['zp_emp_type']=='0' || $emp_type[0]['zp_emp_type']=='367')
								{
									$ropa_table='ropa_2019';
								}
								else 
								{
									$ropa_table='ropa_2019_ll';
								}
	
	$get_ropa=$db->fetch_table("SELECT level".$get_gradepay[0]['level']." FROM ".$ropa_table." 
								WHERE level".$get_gradepay[0]['level']."='$bp_01_01_2020_manually'");
	
	if(($get_ropa[0]["level".$get_gradepay[0]['level']] != $bp_01_01_2020_manually) || ("level".$get_gradepay[0]['level'] != $lvl_01_01_2020))
	{
		echo 'Basic Pay and Level as on 01-01-2020 Does Not Match.';
		exit();
	}
				
							
	
	$update_data=$db->update("UPDATE ropa_2019_emp_pay_scale_master SET
							basic_pay='$bp_01_01_2020_manually',
							level='$lvl_01_01_2020',
							edit_status='1'
							WHERE emp_id_fk='$emp_id_pk'
							
							AND edit_status='99'");
							
	if($update_data)
	{
		echo 1;	
	}
}
?>
