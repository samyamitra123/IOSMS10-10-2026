<?php
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');

$crypto = new cryptography();

$db = new database();
/* This Section is for GP
$Query = "SELECT gp_id_pk from prd_location_master_block as plmb 
          LEFT JOIN prd_location_master_gp as plmg 
          ON plmb.block_id_pk = plmg.block_id_fk WHERE plmb.ngipf_status=1";
$gpNgipfActive = $db->fetch_table($Query); */
/* This section for PS
$Query = "SELECT ps_id_pk from prd_location_master_panchayat_samiti WHERE ngipf_status=1";
$gpNgipfActive = $db->fetch_table($Query);

$gpLists = array();
foreach($gpNgipfActive as $item)
{
	if($item['ps_id_pk'] != '' && $item['ps_id_pk'] > 0)
	   $gpLists[] = "'".$item['ps_id_pk']."'";
}
$gpLists = implode(',',$gpLists); 
Insead of this -> pem.zp_id_fk > 0 replace pem.gp_id_fk IN (".$gpLists.") For GP AND PS
*/
$Query = "SELECT pem.emp_id_const,pem.emp_id_pk from prd_employee_master as pem 
          LEFT JOIN  prd_ngipf_request_cron pnrc 
          ON pem.emp_id_const = pnrc.emp_id_const 
          WHERE pem.zp_id_fk > 0 
          AND pem.ropa_status = 1 AND pem.emp_cosolidated_pay=0 AND pem.emp_retirement_date > '2023-04-01' AND pnrc.emp_id_const IS NULL;";

$gpNgipfEmpMissing = $db->fetch_table($Query);
//print_r($gpNgipfEmpMissing); exit;
foreach($gpNgipfEmpMissing as $item)
{
	   $priType = 'zp';
 		 $insertEmp[] = $Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status,pritype) VALUES (".$item['emp_id_pk'].",'".$item['emp_id_const']."',0,'".$priType."')";
 		  //print($Query); exit;
  	 $db->insert($Query);	  

}
print_r($insertEmp);
exit;

$EmployeeData = 'PE2018023983,PE2017020793,PE2016000771,PE2016000394,PE2016000612,PE2016001675,PE2016000310,PE2016000552,PE2016012598,PE2016000181';

$EmployeeData = explode(',',$EmployeeData);
$newEmp = array();
foreach($EmployeeData as $item)
{
	 $newEmp[] = "'".$item."'";
}
$newEmp = implode(',',$newEmp);
$Query = "SELECT * from prd_ngipf_request_cron WHERE emp_id_const IN (".$newEmp.")";
$EmployeePending = $db->fetch_table($Query);
//print_r($EmployeePending); exit;
$enewEmp = array();
$updateEmp = array();
foreach($EmployeePending as $item)
{
	 $enewEmp[] = $item['emp_id_const'];
	 $updateEmp[] = "'".$item['emp_id_const']."'";
}
$updateEmp = implode(',',$updateEmp);
$Query = "UPDATE prd_ngipf_request_cron SET send_status = 0 WHERE emp_id_const IN (".$updateEmp.")";
$db->update($Query);
$insertEmp = array();
foreach($EmployeeData as $item)
{
    if(!in_array($item, $enewEmp))
    {
    	$Query = "SELECT emp_id_pk,gp_id_fk,ps_id_fk,zp_id_fk from prd_employee_master WHERE emp_id_const='".$item."'";
    	$empdetails = $db->fetch_table($Query);
    	$empdetails = $empdetails[0];
    	$priType = '';
    	if($empdetails['gp_id_fk'] > 0)
    	{
          $priType = 'gp';
    	}
    	if($empdetails['ps_id_fk'] > 0)
    	{
          $priType = 'ps';
    	}
    	if($empdetails['zp_id_fk'] != '' && $empdetails['zp_id_fk'] > 0)
    	{
          $priType = 'zp';
    	}    	    	
    	//print_r($empdetails); exit();
 		 $insertEmp[] = $Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status,pritype) VALUES (".$empdetails['emp_id_pk'].",'".$item."',0,'".$priType."')";
 		  //print($Query); exit;
  		$db->insert($Query);
    }
}
//print_r($insertEmp); exit;
//$enewEmp = implode(',',$enewEmp);
//$Query = "UPDATE prd_ngipf_request_cron SET send_status = 0 WHERE emp_id_const IN (".$enewEmp.")";
//$db->update($Query);
print_r($insertEmp); exit;
?>