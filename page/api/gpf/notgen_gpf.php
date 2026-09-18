<?php
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');

$crypto = new cryptography();

$db = new database();
require_once('ngipfFunction.php');
// $Query = "SELECT lmg.*, lmb.block_name, lmd.district_name,pda.*,pbp.*,  (SELECT COUNT(*) as emp_count from prd_employee_master WHERE gp_id_fk = lmg.gp_id_pk AND emp_status=1 ) as emp_count from prd_location_master_gp as lmg 
//           LEFT JOIN prd_location_master_block as lmb
//           ON lmg.block_id_fk = lmb.block_id_pk
//           LEFT JOIN prd_dise_admin as pda
//           ON lmb.block_code::integer = pda.block_code::integer 
//           LEFT JOIN prd_block_profile as pbp
//           ON lmb.block_code::integer = pbp.block_code::integer                     
//           LEFT JOIN prd_location_master_district lmd
//           ON lmb.district_id_fk = lmd.district_id_pk
//           WHERE lmg.section_code='0' AND lmg.flag!=4;";
// $SectionCodeMissing = $db->fetch_table($Query);
// print_r(json_encode($SectionCodeMissing)); exit;

// For ZP Cron Insert Start
 
 $Query = "select  
                 pem.emp_first_name,
                 pem.emp_second_name,
                 pem.emp_last_name,
                 pem.emp_desig,
                 pem.emp_status,
                 pem.emp_id_pk,
                 pem.emp_system_code,
                 pem.emp_id_const,
                 pem.gp_id_fk,
                 pem.ropa_status
          from prd_employee_master as pem
          where pem.ropa_status=2 AND emp_status_deputation=0 AND emp_cosolidated_pay=0 AND pem.zp_id_fk > 0 
          order by pem.emp_first_name";
          // LEFT JOIN prd_gpf_request_master as pgrm on pem.emp_id_const= pgrm.emp_id_const
                 // pgrm.status,
                 // pgrm.pfaccno,
                 // pgrm.full_response,
                 // pgrm.request,  pem.emp_status in('6','1','9','2') AND         
$arr=$db->fetch_table($Query);
print_r($arr); exit;
foreach($arr as $item)
{
	  $Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status,pritype) VALUES (".$item['emp_id_pk'].",'".$item['emp_id_const']."',0,'zp')";
	 // print($Query); exit;
  	$db->insert($Query);
}
//print_r($arr); exit;
// For ZP Cron Insert End
echo "DONE";
exit;

$Query = "SELECT pgrm.emp_id_const, plmg.gp_name,plmg.gp_code, plmg.section_code, plmb.block_name, plmb.block_code, plmd.district_code, plmd.district_name   from prd_gpf_request_master as pgrm 
          LEFT JOIN prd_employee_master as pem ON pgrm.emp_id_const = pem.emp_id_const 
          LEFT JOIN prd_location_master_gp as plmg ON pem.gp_id_fk = plmg.gp_id_pk 
          LEFT JOIN prd_location_master_block as plmb ON plmg.block_id_fk = plmb.block_id_pk 
          LEFT JOIN prd_location_master_district as plmd ON plmb.district_id_fk = plmd.district_id_pk 
          WHERE  pgrm.status='3' AND pem.gp_id_fk > 0 AND plmg.section_code='0'";
$EmployeePending = $db->fetch_table($Query);
foreach($EmployeePending as $Employee)
{
	//unset($Employee['emp_id_const']);
	//$gpNoSectionCode[$Employee['gp_code']] = $Employee;
	$Query = "DELETE from prd_ngipf_request_cron WHERE emp_id_const='".$Employee['emp_id_const']."'";
	//print($Query);
  $db->update($Query);
  $deleteQuery = "DELETE from prd_gpf_request_master WHERE emp_id_const='".$Employee['emp_id_const']."'";
  //print($Query);
  $db->update($deleteQuery);
}
echo "Done";
exit;
$gpNoSectionCode = array();
foreach($EmployeePending as $Employee)
{
	unset($Employee['emp_id_const']);
	$gpNoSectionCode[$Employee['gp_code']] = $Employee;
}
$newGp = array();
foreach($gpNoSectionCode as $gp)
	 $newGp[] = $gp;
print_r(json_encode($newGp)); exit; 


$Query = "SELECT emp_id_const from prd_gpf_request_master WHERE full_response LIKE '%\"errCode\":\" 108\",%' AND status=3";
$EmployeePending = $db->fetch_table($Query);
//print_r(json_encode($EmployeePending)); exit;
$pendinggpf = array();
foreach($EmployeePending as $gpf)
  {
  	  $Query = "UPDATE prd_gpf_request_master SET status = 2 WHERE emp_id_const='".$gpf['emp_id_const']."'";
  		$db->update($Query);

 /* 	$Query = "SELECT req_id_pk from prd_ngipf_request_cron WHERE emp_id_const='".$gpf['emp_id_const']."'";
  	$reqData = $db->fetch_table($Query);
  	if(isset($reqData[0]['req_id_pk']))
  	{
  		$Query = "UPDATE prd_ngipf_request_cron SET send_status = 0 WHERE emp_id_const='".$gpf['emp_id_const']."'";
  		$db->update($Query);
  	}
  	else
  	{
		  $Query = "SELECT emp_id_pk from prd_employee_master 
		            WHERE emp_id_const = '".$gpf['emp_id_const']."'";
		  $EmployeeData = $db->fetch_table($Query);
		  $emp_id = $EmployeeData[0]['emp_id_pk'];  		
  		  $Query = "INSERT INTO prd_ngipf_request_cron (`emp_id_fk`,`emp_id_const`,`status`) VALUES (".$emp_id.",'".$gpf['emp_id_const']."',0)";
  		  $db->insert($Query);
  	}

  	$deleteQuery = "DELETE from prd_gpf_request_master WHERE emp_id_const='".$gpf['emp_id_const']."'";
  	$db->update($deleteQuery);
  	*/
  	
  } 
  echo "Reset All Data"; 
?>