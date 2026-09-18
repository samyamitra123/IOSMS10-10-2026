<?php
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');

$crypto = new cryptography();

$db = new database();
require_once('ngipfFunction.php');
/*$Query = "SELECT emp_id_const  from prd_gpf_request_master WHERE status=3";
$EmployeePending = $db->fetch_table($Query);
foreach($EmployeePending as $Employee)
{
	 $Query = "SELECT emp_id_pk from prd_employee_master WHERE emp_id_const='".$Employee['emp_id_const']."' AND ropa_status=1 AND emp_cosolidated_pay=0 ";
	 $EmployeePri = $db->fetch_table($Query);
	 if(count($EmployeePri) <=0)
	 	 {
	 	 	   //echo "I am not PRI".$Employee['emp_id_const'];
         $deleteQuery = "DELETE from prd_gpf_request_master WHERE emp_id_const='".$Employee['emp_id_const']."'";
         
  	     $db->update($deleteQuery);
	 	 }

}
echo "DONE";
*/
//print_r($deleteQuery);

$Query = "SELECT emp_id_const  from prd_gpf_request_master group by emp_id_const";
$EmployeePending = $db->fetch_table($Query);
foreach($EmployeePending as $Employee)
{
	 $Query = "SELECT *  from prd_gpf_request_master where emp_id_const='".$Employee['emp_id_const']."'";
	 
   $EmployeeDuplicate = $db->fetch_table($Query);
   if(count($EmployeeDuplicate) > 1 )
   	 {
   	 	  //print_r($EmployeeDuplicate);
         $deleteQuery = "DELETE from prd_gpf_request_master WHERE request_id_pk='".$EmployeeDuplicate[0]['request_id_pk']."'";
  	     $db->update($deleteQuery);
   	 }
}


/*$pendinggpf = array();
foreach($EmployeePending as $gpf)
  {
  	$Query = "SELECT req_id_pk from prd_ngipf_request_cron WHERE emp_id_const='".$gpf['emp_id_const']."'";
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
  	
  } 
  echo "Reset All Data"; */
?>