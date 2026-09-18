<?php 
	require '../includes/config/config.php';
	require '../includes/config/database.config.php';
	require '../includes/library/database.class.php';
	global $db;
	$db = new database();
	$Query = "SELECT * from prd_location_master_block WHERE ngipf_status=1";
	$blockInArray = $db->fetch_obj($Query);

	foreach($blockInArray as $key=>$block)
	  {
	  	//print_r($block); exit;
         $blockQuery = "SELECT emp_id_const,emp_first_name,emp_second_name,emp_last_name from prd_employee_master as pem 
                        LEFT JOIN prd_location_master_gp as lmg ON pem.gp_id_fk = lmg.gp_id_pk
                        WHERE lmg.block_id_fk='".$block->block_id_pk."' AND pem.emp_status=1 AND pem.emp_cosolidated_pay=0 AND pem.ropa_status IN (1,2)";
         $blockEmployee = $db->fetch_obj($blockQuery);
         $blockInArray[$key]->employeeCount = count($blockEmployee);
         $empIds = array();
         foreach($blockEmployee as $employee)
         {
         	$empIds[] = "'".$employee->emp_id_const."'";
         }
         $empIds = implode(', ',$empIds);
         $gpfQuery = "SELECT emp_id_const,pfaccno FROM prd_gpf_request_master WHERE emp_id_const IN (".$empIds.") AND status=1";
         $blockgpfEmployee = $db->fetch_obj($gpfQuery);
         $blockInArray[$key]->employeegpfCount = count($blockgpfEmployee);
         $empGpfMissing = array();
         foreach($blockgpfEmployee as $employee)
         {
         	$empGpfMissing[] = $employee->emp_id_const;
         }	
         $empIds = array();
         foreach($blockEmployee as $employee)
         {
         	if(!in_array($employee->emp_id_const,$empGpfMissing))
         	  $empIds[] = $employee;
         }  
         //print_r($empIds); exit;
         $blockInArray[$key]->employeegpfMissing = $empIds;        
         
	  } 
     //print_r($blockInArray); exit;  
?>
<html>
<head>
 <title></title>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css">
 <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

 <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js" type="text/javascript"></script>
</head>
<body>
  <script type="text/javascript">
  	var employeeMissingGpf = {};
  </script>
  <div class="container">
  	<h2>Employee GPF Subscription Report</h2>
  <table id="example" class="table table-striped" width="100%">
  	<thead>
  		<th>Block Name</th>
  		<th>Block Code</th>
  		<th>Employee Count</th>
  		<th>GPF Active</th>
  		<th>Action</th>
  	</thead>
  	<tbody>
  		<?php foreach($blockInArray as $blockInfo):?>
  			<tr style="<?php echo ($blockInfo->employeeCount != $blockInfo->employeegpfCount)?'background-color: #c6c6c6;':'';?>">
  			<td><?php echo $blockInfo->block_name; ?></td>
  			<td><?php echo $blockInfo->block_code; ?></td>
  			<td><?php echo $blockInfo->employeeCount; ?></td>
  			<td><?php echo $blockInfo->employeegpfCount; ?></td>
  			<td>
  				<?php echo (count($blockInfo->employeegpfMissing) > 0)?'<span class="show-hide" data-bs-toggle="modal" data-bs-target="#exampleModal">+</span>':''; ?>
	  		    <?php if(count($blockInfo->employeegpfMissing) > 0):?>

		  		    <?php foreach($blockInfo->employeegpfMissing as $item):
	                      $middleName = ($item->emp_second_name != '')?' '.$item->emp_second_name:'';
	                      $employeeName = $item->emp_first_name.$middleName.' '.$item->emp_last_name;
		  		    ?>

		  		    <?php endforeach;?>	
	  		    <?php endif; ?>	  				
  			</td>
  		    </tr>
    
  		<?php endforeach;?>
    </tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Employee Missing GPF Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
</body>
<style type="text/css">
	.show-hide{font-size: 22px; font-weight: 600;}
</style>
<script type="text/javascript">
	new DataTable('#example');
	//$('.employeegpfmissing').hide();
	console.log(employeeMissingGpf); 
	/*$('.show-hide').click(function(){
		$(this).closest('.employeegpfmissing').toggle();
	})*/
</script>
</html>