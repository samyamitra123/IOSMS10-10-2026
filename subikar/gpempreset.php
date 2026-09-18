<?php 
require 'master.php';
$post = $_REQUEST;
$id = $post['id'];
$Query = "SELECT block_id_pk FROM prd_location_master_block WHERE block_code = '".$id."'";
$blockData = $db->fetch_table($Query);
$blockIds = array();

foreach($blockData as $block)
{
	  $blockIds[] = $block['block_id_pk'];
	  //echo '<p>'.$ps['ps_name'].' PS-CODE: '.$ps['ps_code'].' | PS IOSMS ID:'.$ps['ps_id_pk'].'</p>';
}

$Query = "SELECT gp_id_pk,gp_name FROM prd_location_master_gp WHERE block_id_fk IN (".implode(',',$blockIds).")";
//print($Query); exit;
$gpData = $db->fetch_table($Query);
//print_r($gpData); exit;
$gpIds = array();

foreach($gpData as $gp)
{
	  $gpIds[] = $gp['gp_id_pk'];
	  //echo '<p>'.$ps['ps_name'].' PS-CODE: '.$ps['ps_code'].' | PS IOSMS ID:'.$ps['ps_id_pk'].'</p>';
}
//print_r($gpIds); exit;

$Query = "SELECT emp_id_pk,emp_id_const from prd_employee_master
        WHERE gp_id_fk IN (".implode(',',$gpIds).") AND emp_status = 1 AND emp_cosolidated_pay=0 AND ropa_status =1";
$gpEmployeeData = $db->fetch_table($Query);
//print_r($Query); exit;
$EmployeeIds = array();
foreach($gpEmployeeData as $emp)
   $EmployeeIds[] = "'".$emp['emp_id_const']."'";
$Query = "SELECT * from prd_gpf_request_master
          WHERE emp_id_const IN (".implode(',',$EmployeeIds).")";
$ngipfData = $db->fetch_table($Query);

//print_r($ngipfData);  exit;
if(count($ngipfData) > 0)
   {
   	   if($post['reset'] == 0)
   	   	  {
	 		$Query = "DELETE from prd_gpf_request_master
			          WHERE emp_id_const IN (".implode(',',$EmployeeIds).")";
			$db->update($Query);
			$Query = "DELETE from prd_ngipf_request_cron
			          WHERE emp_id_const IN (".implode(',',$EmployeeIds).")";  
			$db->update($Query);  
			echo "Total Data Deleted ".count($ngipfData);  	   	  	
   	   	  }
   	   	elseif($post['reset'] == 1)
   	   	  {
		$Query = "DELETE from prd_gpf_request_master
		          WHERE emp_id_const IN (".implode(',',$EmployeeIds).")";
		$db->update($Query);
		$Query = "UPDATE prd_ngipf_request_cron SET send_status=0
		          WHERE emp_id_const IN (".implode(',',$EmployeeIds).")";  
		//print($Query); exit;
		$db->update($Query);  
		echo "Total Reset Employee ".count($ngipfData);   	   	  	
   	   	  }


	} 
 else
    {
        echo "No Data Found";
    }   
//$getNgipfData = $db->fetch_table($Query);

?>