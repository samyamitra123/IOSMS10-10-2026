<?php
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');

$crypto = new cryptography();

$db = new database();
require_once('ngipfFunction.php');
// $gpIdfk = array(3355,3400,3402,3410,3411,3412,3413);
// $gpIdfk = implode(",",$gpIdfk);
// $psIdfk = array(342,343,344);
// $psIdfk = implode(",",$psIdfk);
// $zpIdfk = array(21,22);
// $zpIdfk = implode(",",$zpIdfk);
// $Query = "SELECT pem.emp_id_const, pem.emp_id_pk, pem.gp_id_fk, pem.ps_id_fk, pem.zp_id_fk, pem.emp_first_name from prd_employee_master pem where gpf_acc_no='0' AND emp_id_const != '' AND emp_cosolidated_pay = 0 AND pem.emp_status_deputation=0 AND gp_id_fk NOT IN (".$gpIdfk.") AND ps_id_fk NOT IN (".$psIdfk.") AND LOWER(emp_first_name) NOT LIKE '%test%'";
// $EmployeePending = $db->fetch_table($Query);
// //print_r($EmployeePending); exit;
// $empDataInArray = array();
// foreach($EmployeePending as $item)
// {
// 	 $empDataInArray[$item['emp_id_const']] = $item;
// }
// //print_r($empDataInArray); exit;
// $empIds = array();
// $empIdsAll = array();
// foreach($EmployeePending as $item)
// {
// 	 $empIds[] = "'".$item['emp_id_const']."'";
// 	 $empIdsAll[] = $item['emp_id_const'];
// }
// $empIds = implode(",",$empIds);
// $Query = "SELECT pem.emp_id_const, pem.pfaccno from prd_gpf_request_master pem where emp_id_const IN (".$empIds.") ";
// $EmployeeGpfDetails = $db->fetch_table($Query);
// $updateQuery = array();
// foreach($EmployeeGpfDetails as $item)
// {
// 	 if($item['pfaccno'] != '')
// 	  { 
// 	  	$updateQuery[] = $Query = "update prd_employee_master SET gpf_acc_no='".$item['pfaccno']."' where emp_id_const='".$item['emp_id_const']."'";
// 	    $db->update($Query);
// 	  }
// }
// $Query = "SELECT pem.emp_id_const, pem.send_status from prd_ngipf_request_cron pem where emp_id_const IN (".$empIds.") ";
// $EmployeeCronDetails = $db->fetch_table($Query);
// $excludeEmpIds = array();
// foreach($EmployeeCronDetails as $item)
// {
// 	 $excludeEmpIds[] = $item['emp_id_const'];
// }
// $InsertToCron = array_values(array_diff($empIdsAll, $excludeEmpIds));
// $InsertEmpIds = array();
// foreach($InsertToCron as $item)
// {
// 	$InsertEmpIds[] = $empDetails  = $empDataInArray[$item];
// 	$stake = ($empDetails['gp_id_fk'] > 0)?'gp':'';
// 	$stake = ($empDetails['ps_id_fk'] > 0)?'ps':$stake;
// 	$stake = ($empDetails['zp_id_fk']!= '' && $empDetails['zp_id_fk'] > 0)?'zp':$stake;

// 	$Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status,pritype) VALUES (".$empDetails['emp_id_pk'].",'".$empDetails['emp_id_const']."',0,'".$stake."')";
// 	//print($Query); exit;
// 	$db->insert($Query);
// }
// print_r(json_encode($InsertEmpIds)); exit; 

// $Query = "SELECT pem.emp_id_const, pem.emp_id_pk
// FROM prd_location_master_gp lmg
// LEFT JOIN prd_employee_master pem ON lmg.gp_id_pk = pem.gp_id_fk
// LEFT JOIN prd_gpf_request_master pgrm ON pem.emp_id_const = pgrm.emp_id_const
// WHERE lmg.section_name = '0'
// AND lmg.section_code != '0'
// AND pem.emp_status = 1
// AND pgrm.emp_id_const IS NULL;";
// //status = 3"; AND full_response LIKE '%NGIPF Acc No Already Generated for for IOSMS id%'
// $EmployeePending = $db->fetch_table($Query);

	$Query = "SELECT emp_id_const,pfaccno from prd_gpf_request_master where status != 1 ;";
	$EmployeePending = $db->fetch_table($Query);
  //print_r($EmployeePending); exit;
// //print_r($EmployeePending); exit;
$pendinggpf = array();
foreach($EmployeePending as $gpf)
  {
  	if($gpf['pfaccno'] == '')
  	{
		  	$Query = "SELECT req_id_pk from prd_ngipf_request_cron WHERE emp_id_const='".$gpf['emp_id_const']."'";
		  	$reqData = $db->fetch_table($Query);
		  	if(isset($reqData[0]['req_id_pk']))
		  	{
		  		$Query = "UPDATE prd_ngipf_request_cron SET send_status = 0 WHERE emp_id_const='".$gpf['emp_id_const']."'";
		  		//print($Query); exit;
		  		$db->update($Query);
		  	}
		  	else
		  	{
				   $Query = "SELECT emp_id_pk,gp_id_fk,ps_id_fk,zp_id_fk from prd_employee_master WHERE emp_id_const = '".$gpf['emp_id_const']."'";
				   $EmployeeData = $db->fetch_table($Query); 
				   $EmployeeData = $EmployeeData[0];
				   $empIdPk = $EmployeeData['emp_id_pk'];
				   $stake = ($EmployeeData['gp_id_fk'] > 0)?'gp':'NA';
				   $stake = ($EmployeeData['ps_id_fk'] > 0)?'ps':$stake;
				   $stake = ($EmployeeData['zp_id_fk'] > 0)?'zp':$stake;
				   //print_r($EmployeeData); exit;		
		  		 $Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status,pritype) VALUES (".$empIdPk.",'".$gpf['emp_id_const']."',0,'".$stake."')";
		  		 //print($Query); exit;
		  		 $db->insert($Query);
		  	}
	  	$deleteQuery = "DELETE from prd_gpf_request_master WHERE emp_id_const='".$gpf['emp_id_const']."'";
	  	$db->update($deleteQuery);		  	

  	}



  	
  } 
  echo "Reset All Data"; 
?>