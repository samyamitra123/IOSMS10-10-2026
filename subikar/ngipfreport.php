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
         $blockQuery = "SELECT emp_id_const,emp_first_name,emp_second_name,emp_last_name,lmg.gp_code, lmg.gp_name from prd_employee_master as pem 
                        LEFT JOIN prd_location_master_gp as lmg ON pem.gp_id_fk = lmg.gp_id_pk
                        WHERE lmg.block_id_fk='".$block->block_id_pk."' AND pem.emp_status=1 AND pem.emp_cosolidated_pay=0 AND pem.ropa_status = 1";
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
<link rel="stylesheet" type="text/css" href="https://priemp.wbprd.gov.in/themes/default/bootstrap_v5.1.3/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://priemp.wbprd.gov.in/themes/default/bootstrap_v5.1.3/css/bootstrap.min.css">

<script src="https://priemp.wbprd.gov.in/themes/default/bootstrap_v5.1.3/js/bootstrap.js" type="text/javascript"></script>
<script src="https://priemp.wbprd.gov.in/themes/default/bootstrap_v5.1.3/js/bootstrap.min.js" type="text/javascript"></script>


<link rel="stylesheet" href="https://priemp.wbprd.gov.in/themes/default/css/font-awesome.min.css">
<link rel="stylesheet" href="https://priemp.wbprd.gov.in/themes/default/css/style.css">


<link rel="stylesheet" type="text/css" href="https://priemp.wbprd.gov.in/themes/default/css/dataTables.bootstrap5.css">

<link rel="shortcut icon" href="https://priemp.wbprd.gov.in/themes/default/img/fav_icon.png">
<!--<link href="<//?php echo $config['base_url']; ?>themes/default/css/allinone_carousel.css" rel="stylesheet" type="text/css">-->
<link href="https://priemp.wbprd.gov.in/themes/default/css/owl.carousel.min.css" rel="stylesheet" type="text/css">
<link href="https://priemp.wbprd.gov.in/themes/default/css/owl.theme.default.min.css" rel="stylesheet" type="text/css">
<!--<link href="<//?php echo $config['base_url']; ?>themes/default/css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css">-->



<script src="https://priemp.wbprd.gov.in/themes/default/js/jquery-3.6.0.min.js"></script>


<script src="https://priemp.wbprd.gov.in/themes/default/js/jquery-migrate-3.3.2.min.js" type="text/javascript"></script>
<!--<script src="themes/default/js/jquery-migrate.js" type="text/javascript"></script>-->
<!--<script src="themes/default/js/allinone_carousel.js" type="text/javascript"></script>-->
<script src="https://priemp.wbprd.gov.in/themes/default/js/owl.carousel.min.js" type="text/javascript"></script>
<!--<script src="<//?php echo $config['base_url']; ?>themes/default/js/dhtmlgoodies_calendar.js" type="text/javascript"></script>-->
<script src="https://priemp.wbprd.gov.in/themes/default/js/commonfunc.js" type="text/javascript"></script>


<!--<script src="https://priemp.wbprd.gov.in/themes/default/js/jquery.dataTables5.min.js" type="text/javascript"></script>-->
<script src="https://priemp.wbprd.gov.in/themes/default/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://priemp.wbprd.gov.in/themes/default/js/dataTables.bootstrap5.js" type="text/javascript"></script>


<script src="https://priemp.wbprd.gov.in/themes/default/js/crypto-js.js" type="text/javascript"></script>

<!----- new development-------------------->


<script src="https://priemp.wbprd.gov.in/themes/default/js_ui/jquery-ui.min-1.13.0.js" type="text/javascript"></script>
<script src="https://priemp.wbprd.gov.in/themes/default/js_ui/jquery-ui-1.13.0.js" type="text/javascript"></script>
<script src="https://priemp.wbprd.gov.in/themes/default/js/jquery.ui.touch-punch.js" type="text/javascript"></script>
<script src="https://priemp.wbprd.gov.in/themes/default/js/jquery-ui-touch-punch.min.js" type="text/javascript"></script>
<link href="https://priemp.wbprd.gov.in/themes/default/js_ui/jquery-ui.min-1.13.0.css" rel="stylesheet" type="text/css">
<link href="https://priemp.wbprd.gov.in/themes/default/js_ui/jquery-ui-1.13.0.css" rel="stylesheet" type="text/css">
<link href="https://priemp.wbprd.gov.in/themes/default/js_ui/jquery-ui.structure.min-1.13.0.css" rel="stylesheet" type="text/css">
<link href="https://priemp.wbprd.gov.in/themes/default/js_ui/jquery-ui.structure-1.13.0.css" rel="stylesheet" type="text/css">
<link href="https://priemp.wbprd.gov.in/themes/default/js_ui/jquery-ui.theme.min-1.13.0.css" rel="stylesheet" type="text/css">
<link href="https://priemp.wbprd.gov.in/themes/default/js_ui/jquery-ui.theme-1.13.0.css" rel="stylesheet" type="text/css">
</head>
<body>
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
  				<?php echo (count($blockInfo->employeegpfMissing) > 0)?'<span class="show-hide" data-bs-toggle="modal" data-bs-target="#exampleModal" data-code="'.$blockInfo->block_code.'">+</span>':''; ?>

	  		   		
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
        <?php foreach($blockInArray as $blockInfo):?>
	  		    <?php if(count($blockInfo->employeegpfMissing) > 0):?>
	  		    	<div class="empgpf employeegpfmissing_<?php echo $blockInfo->block_code;?>" style="display: none;">
	  		    	<table class="table table-striped">
		  		    <tr>
		  		    	<td >Name</td>
		  		    	<td>Employee Id</td>
		  		    	<td>GP Name</td>
		  		    	<td>GP Code</td>
		  		    </tr>
		  		    <?php foreach($blockInfo->employeegpfMissing as $item):
	                      $middleName = ($item->emp_second_name != '')?' '.$item->emp_second_name:'';
		  		    ?>
		  		    <tr>
		  		    	<td><?php echo $item->emp_first_name.$middleName.' '.$item->emp_last_name; ?></td>
		  		    	<td><?php echo $item->emp_id_const; ?></td>
		  		    	<td><?php echo $item->gp_name; ?></td>
		  		    	<td><?php echo $item->gp_code; ?></td>
		  		    </tr> 
		  		    <?php endforeach;?>	
		  		    </table> 
		  		    </div>
	  		    <?php endif; ?>	         	
        <?php endforeach;?>
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
	//console.log(employeeMissingGpf); 
	$('.show-hide').click(function(){
		var blockCode = $(this).data('code');
		console.log(blockCode);
		$('.empgpf').hide();
		$('.employeegpfmissing_'+blockCode).show();
	})
</script>
</html>