<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform,  post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=ngipf_grampanchyayat'.date('jdY').'.xls');
header("Content-Transfer-Encoding: binary");
ob_start(); 

global $BlockReport,$newEmployeeReport; 
$empGpfMissing = array();
?>
<table width="100%" class="maintable" border="1">
	<thead>
		<tr>
		<th colspan="7" style="text-align: center;">NGIPF GP EMPLOYEE REPORT Block Wise</th>
	    </tr>
		<tr>
		<th>District Name</th>
		<th>Block Name</th>
		<th>Block Code</th>
		<th>Total Employee</th>
		<th>NGIPF Completed</th>
		<th>NGIPF Remining</th>
		<th>Employee Data</th>
	    </tr>	        
	</thead>
<?php foreach($BlockReport as $item):?>
	<tr>
		<td><?php echo $item->district_name ?></td>
		<td><?php echo $item->block_name ?></td>
		<td><?php echo $item->block_code ?></td>
		<td><?php echo $item->emp_count ?></td>
		<td><?php echo $item->gpf_count ?></td>
		<td><?php echo $item->non_gpf_count ?></td>

		<td>
			<?php 
			   if($item->non_gpf_count > 0)
			   {
			    $employees = explode(',',$item->emp_ids);
			   
			    //print_r($employees); exit;
			     $sl = 1; 
			     foreach($employees as $emp):
			     	 $empGpfMissing[] = trim($emp);
			?>
			<?php 
			    if(isset($newEmployeeReport[$emp])){
				      if($newEmployeeReport[$emp]['status'] == 'Pending TCS')
				            echo $sl.')'.$emp.' ('.$newEmployeeReport[$emp]['status'].')';
				      else
				      	    echo $sl.')'.$emp.' ('.$newEmployeeReport[$emp]['status'].') - Error ('.$newEmployeeReport[$emp]['error'].')';
				      
				    }
				 else
				 {
				 	echo $sl.')'.$emp.' (NEW)';
				 }
			?>
			<?php 
			    //exit;
			       echo "<br />";
			       $sl++;
		           endforeach;
			    } 
			?>	
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php 
  /* $Query = "SELECT emp_id_const from prd_ngipf_request_cron";
   $reqData = $db->fetch_table($Query);
   $curEmpCron = array();
   foreach($reqData as $item)
   {
   	  $curEmpCron[]=$item['emp_id_const'];
   }
   $diffArray = array_diff($empGpfMissing,$curEmpCron);
   print_r($curEmpCron); exit;
   $updateQuery = array();
   foreach($diffArray as $item)
   {
   	      $Query = "SELECT emp_id_pk, emp_id_const from prd_employee_master 
		            WHERE emp_id_const = '".$item."'";
		  $EmployeeData = $db->fetch_table($Query);
		  $emp_id = $EmployeeData[0]['emp_id_pk'];  		
		  $emp_id_const = $EmployeeData[0]['emp_id_const'];  		
  		  $updateQuery[] = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status,pritype) VALUES (".$emp_id.",TRIM(BOTH ' ' FROM LOWER('".$emp_id_const."')) ,0,'gp')";
  		  //print_r($updateQuery); exit;
  		  
   }
   $updateQuery = implode('; ',$updateQuery);
   print_r($updateQuery); exit; */
   //$db->insert($updateQuery);
   //echo "Done"; 
   //print_r($diffArray);
   //print_r($empGpfMissing); 
?>
<style type="text/css">
	.tabclass{border-left: 0px !important;
    border-bottom: 0px!important;
    border-top: 0px !important;}
   .maintable, .maintable td {border: 1px solid #c6c6c6; text-align: center;}
   .notabclass{border: 0px !important;}
   .maintable th{background-color: #916401;
    color: #fff;}
    .tabclassth{border-right: 1px solid #c6c6c6;}
    .pageHeading{text-align: center; color: #916401; border-bottom: 1px solid #000;text-transform: uppercase;width: 240px;margin: 0 auto;margin-bottom: 10px;padding-bottom: 5px; }
</style>