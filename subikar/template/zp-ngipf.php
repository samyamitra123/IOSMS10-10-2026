<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform,  post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Pragma: public"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);  
/*header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename=ngipf_zillaparishad'.date('jdY').'.xls');
header("Content-Transfer-Encoding: binary");
ob_start(); */
global $BlockReport,$newEmployeeReport; ?>
<table width="100%" class="maintable" border="1">
	<thead>
		<tr>
		<th colspan="6" style="text-align: center;">NGIPF ZP EMPLOYEE REPORT</th>
	    </tr>
		<tr>
		<th>District Name</th>
		<th>District Code</th>
		<th>Total Employee</th>
		<th>NGIPF Completed</th>
		<th>NGIPF Remining</th>
		<th>Employee Not Completed</th>
	    </tr>	    
	</thead>
<?php foreach($BlockReport as $item):?>
	<tr>
		<td><?php echo $item->district_name ?></td>
		<td><?php echo $item->district_code ?></td>
		<td><?php echo $item->emp_count ?></td>
		<td><?php echo $item->gpf_count ?></td>
		<td><?php echo $item->non_gpf_count ?></td>
		<td>			<?php 
			   if($item->non_gpf_count > 0)
			   {
			    $employees = explode(',',$item->emp_ids);
			    //print_r($employees); 
			     foreach($employees as $emp):
			     	$emp = ltrim($emp);
			?>
			<?php 
		
			    if(isset($newEmployeeReport[$emp])){
	

				      if($newEmployeeReport[$emp]['status'] == 'Pending TCS')
				            echo '<p>'.$emp.' ('.$newEmployeeReport[$emp]['status'].')</p>';
				      else
				      	    echo '<p>'.$emp.' ('.$newEmployeeReport[$emp]['status'].') - Error ('.$newEmployeeReport[$emp]['error'].')</p>';
				      
				    }
				 else
				 {
				 	echo '<p>'.$emp.' (NEW)</p>';
				 }
			?>
			<?php 
		           endforeach;
			    } 
			?></td>
	</tr>
<?php endforeach; ?>
</table>
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