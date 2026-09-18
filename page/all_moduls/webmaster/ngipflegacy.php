<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';

require '../../../includes/library/cryptography.class.php';




if (

	  !isset($_SESSION['user_info']['stake_user'])

	|| !isset($_SESSION['user_info']['stake_level'])

	|| !isset($_SESSION['user_info']['flag'])

	|| !isset($_SESSION['user_info']['stake_abbr'])

	



	){

	header('Location: '. $config['base_url'] . "page/login.php");

	exit;

}





if(!isset($_SERVER['HTTP_REFERER']))

{

    header('Location:'.$config['base_url']."page/errordoc.php?id=1");

    exit("Do not paste URL directly");

    

} 

elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 

{

    // substring is not found in string

    header('Location:'. $config['base_url']."page/errordoc.php?id=2");

    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");

}



	

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------



//Page variables

$common['title'] = " GPF Legacy  | PRD | Govt. of West Bengal ";

//---------------------------------- HEADER -----------------------------------------------------------------------------------

require '../../../page/layout/header.php';

//---------------------------------- MENU -------------------------------------------------------------------------------------

require '../../../page/layout/menu.php';

//-----------------------------Business Logic----------------------------------------------------------------------------------
global $db, $crypto;
$db=new database();

$crypto = new cryptography();


$Query = "Select * from prd_location_master_district WHERE zp_status=1 order by district_name asc";
$districtInArray = $db->fetch_table($Query);
//print_r($districtInArray); exit;

?>
<style type="text/css">
	.hide-status{display: none;}
</style>
<div class="container pb-4">
	<div class="row">
		<h2>PS PF Legacy Subscription Data</h2>
		<div class="col-md-4">
			<label for="district" class="form-label">Select District</label>
			<select name="district" id="district" class="form-control" required>
				<option>Select District</option>
				<?php foreach($districtInArray as $key=>$item):?>
					<option value="<?php echo $item['district_id_pk'];?>"><?php echo $item['district_name'];?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-4">
			<label for="block" class="form-label">Select Panchayat Samiti</label>
			<select name="block" id="block" class="form-control" required>
			</select>
		</div>	
		<div class="col-md-4">
			<label for="year" class="form-label">Select Year</label>
			<select name="year" id="year" class="form-control" required disabled>
				<option>Select Year</option>
				<?php for($year=2024;$year<=date('Y');$year++):?>
					<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
				<?php endfor; ?>
			</select>
		</div>						
	</div>
</div>
<div class="container pb-4">
	<div class="row">
		<div class="result table-responsive">
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#district').on('change', function(){
		var districtId = $(this).val();
		$.post('ajaxngipf.php', { districtId: districtId,action:'getblock'})
		  .done(function( data ) {
		  	//console.log(data);
		    $('#block').html(data);
		    $('#year').prop('disabled', true);
		  });
	    });
	$('#block').on('change', function(){
		$('#year').prop('selectedIndex',0);
		$('#year').prop('disabled', false);
	});	
	$('#year').on('change', function(){
		var psId = $('#block').val();
		var year = $('#year').val();
		$('#district').prop('disabled', true);
		$('#block').prop('disabled', true);
		$('#year').prop('disabled', true);
		data = 'Please wait fetching Data...';
		$('.result').html(data);
		$.post('ajaxngipf.php', { psId: psId,year:year,action:'getdrnDetails'})
		  .done(function( data ) {
            $('.result').html(data);
			$('#district').prop('disabled', false);
			$('#block').prop('disabled', false);
			$('#year').prop('disabled', false);            
		  });		
	});	
	function deleteDrn(drn_no,rawDrn,billNo)
	 {
        var decision = confirm("Are You Sure You want to Delete PF Subscription?");
        if(decision)
           {	
                $.post('ajaxngipf.php', { drn: drn_no,billno:billNo,action:'deletedrnDetails'})
				  .done(function( data ) {
				  	console.log(rawDrn);
				  	console.log(data);
		            $('.main_'+rawDrn).html(data);           
				  });	
		  }	
	 }
	function SendNgipf(drn_no,rawDrn)
	 {
	 	//console.log(drn_no); 
        var decision = confirm('Are You Sure You want to Send PF Subscription?');
        if(decision)
           {
			 	$.post('<?= $config['base_url'] ?>page/api/gpf/salary.php?drn='+drn_no+'&legacy=1', function (data) {
			 		var data = JSON.parse(data);
			 		if(data.status == 'Success')
			 		  {
					 	$('.hide-status_'+rawDrn).show('slow');
					 	$('.ngipfAction_'+rawDrn).hide('slow');
					 	$('.ngipfafterAction_'+rawDrn).show('slow'); 
					 	$('.ngipfmsg_'+rawDrn).html(data.msg);            
			 		  }
			 		 else
			 		  {
			 		  	 alert("Not Captured Please try again");
			 		  }	
			 	});
	       }
	 }	
	 function CheckNgipfStatus(drn_no)
	 {
	 	console.log(drn_no);
	 }
</script>




<?php

//---------------------------------- SLIDER -----------------------------------------------------------------------------------

//require 'right_sidebar_dashboard.php';

//----------------------------------- FOOTER ----------------------------------------------------------------------------------

require '../../../page/layout/footer.php';

//----------------------------------------------------------------------------------------------------------------------------

?>