<?php 
//echp 11;die;
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");



//ob_start();
session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';

$crypto = new cryptography();


?>

<style>
.modal-body{
font-size: 10px;
}

</style>

<meta charset="UTF-8">



<!--<body>-->
<?php

ob_start();

/*
if ( !isset($_SESSION['user_info']['stake_level']) || !isset($_SESSION['user_info']['stake_user_mob']) || !isset($_SESSION['user_info']['flag']) ){
		header('Location: '. $config['base_url'] . "index.php");
		exit;
}
*/

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";



//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------



?>

<script>

  $(function() {
	$( "#date_of_birth,#date_of_vacancy,#dt_join" ).datepicker({
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0",
	dateFormat: 'dd-mm-yy',
	//minDate:dateToday	 
	});
});

 $(function() {
	$( "#tch_doe" ).datepicker({
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0",
	dateFormat: 'dd-mm-yy',
	maxDate: "31-03-2010"
	//minDate:dateToday	 
	});
});
 
function file_upload(k,j,l){
     // alert(k);
      var property = document.getElementById(k).files[0];
      var image_name = property.name;
      var image_extension = image_name.split('.').pop().toLowerCase();

      if(jQuery.inArray(image_extension,['pdf']) == -1){
        alert("Invalid PDF file");
      }
      
      var form_data = new FormData();
      form_data.append("file",property);
	  form_data.append('flag_id',j);
	  
	  if(l == 'File'){
		  form_data.append('emp_id_const',$('#tch_fname').val());
		  form_data.append('application_id',$('#application_id').val());
	  }
	  else if(l == 'File_A'){
		  form_data.append('emp_id_const',$('#casual_daily_contractual').val());
		  form_data.append('application_id',$('#app_no_file_A').val());
	  }
	  else if(l == 'File_B'){
		  form_data.append('emp_id_const',$('#app_cas_day_con_worker').val());
		  form_data.append('application_id',$('#app_no_file_B').val());
	  }
	  else if(l == 'File_C'){
		  form_data.append('emp_id_const',$('#app_cas_day_con_worker_C').val());
		  form_data.append('application_id',$('#app_no_file_C').val());
	  }
	  
        $.ajax({
          url : 'ajax_intra_pri_9008_file_upload.php',
          type : 'POST',
          data:form_data,
          contentType:false,
          cache:false,
          processData:false,
          success : function(data1) {
            alert('Document Uploaded Successfully....');
          },
          error:function(){
            alert('Server Error');
          }
          });
    
    }
    
    
</script>
	
	

    <!-- validation -->
<script>
	function validateForm() {
		if($('#place_of_posting').val()==''){
					alert('Please Select Place of Posting.');
					$('#place_of_posting').focus();
					return false;
				}
		else if($('#tch_fname').val()=='' ){
					alert('Please Enter Your FIRST Name.');
					$('#tch_fname').focus();
					return false;
				}
		else if($('#date_of_birth').val()==''){
					alert('Please Enter Date of Birth.');
					$('#date_of_birth').focus();
					return false;
				}
		else if($('#tch_doe').val()==''){
					alert('Please Enter Date of Engagement.');
					$('#tch_doe').focus();
					return false;
				}
		else if($('#drp_engaged').val()==''){
					alert('Please Select Whether Engaged as Casual/Daily Rated Worker/Contractual Worker.');
					$('#drp_engaged').focus();
					return false;
				}
		else if($('#service_in_each_year').val()==''){
					alert('Please Enter No. of Days of Service in Each Year.');
					$('#service_in_each_year').focus();
					return false;
				}
		else if($('#service_in_each_days').val()==''){
					alert('Please Enter No. of Days of Service in Each Year.');
					$('#service_in_each_days').focus();
					return false;
				}
		else if($('#sanctioned_vacant_post').val()==''){
					alert('Please Select Whether engaged against sanctioned vacant post.');
					$('#sanctioned_vacant_post').focus();
					return false;
				}
		else if($('#amount_of_remuneration').val()==''){
					alert('Please Enter Amount of Remuneration P.M.');
					$('#amount_of_remuneration').focus();
					return false;
				}
		else if($('#still_working').val()==''){
					alert('Please Select Whether the incumbent is still working.');
					$('#still_working').focus();
					return false;
				}
		else if($('#source_of_fund').val()==''){
					alert('Please Select Source of Fund.');
					$('#source_of_fund').focus();
					return false;
				}
	}



function show_GP(val){ 
	if(val =='GP'){
		$('#comm_div').hide();
		$('#ps_div').hide();
		$('#gp_div').show();
		
		$('#desig_common').hide();
		$('#desig_ps').hide();
		$('#desig_gp').show();
	}
	else if(val =='PS'){
		$('#comm_div').hide();
		$('#ps_div').show();
		$('#gp_div').hide();
		
		$('#desig_common').hide();
		$('#desig_gp').hide();
		$('#desig_ps').show();
	}
	else if(val ==''){
		$('#comm_div').show();
		$('#ps_div').hide();
		$('#gp_div').hide();
		
		$('#desig_ps').hide();
		$('#desig_gp').hide();
		$('#desig_common').show();
	}
}


function show_GP_B(val){ 
	if(val =='GP'){
		
		$.ajax({ 
		url : 'present_remu_9008.php',
		type : 'POST',
		data : { "val": 'B_GP',
				"ps" : <?php echo substr($_SESSION['user_info']['stake_user_code'],0,7); ?>
				},
        success : function(response) {
			//alert(response);
				$("#gp_ps_zp_B").html(response);
		}
      });
	  
	  
	}
	else if(val =='PS'){
	
	  $.ajax({ 
		url : 'present_remu_9008.php',
		type : 'POST',
		data : { "val": 'B_PS',
				"ps" : <?php echo substr($_SESSION['user_info']['stake_user_code'],0,7); ?>
				},
        success : function(response) {
			//alert(response);
				$("#gp_ps_zp_B").html(response);
		}
      });
	  
	  
	}
	else if(val ==''){
		$('#comm_div_B').show();
	}
}





function show_GP_C(val){ 
	if(val =='GP'){
		
		$.ajax({ 
		url : 'present_remu_9008.php',
		type : 'POST',
		data : { "val": 'B_GP',
				"ps" : <?php echo substr($_SESSION['user_info']['stake_user_code'],0,7); ?>
				},
        success : function(response) {
			//alert(response);
				$("#gp_ps_zp_C").html(response);
		}
      });
	  
	  
	}
	else if(val =='PS'){
	
	  $.ajax({ 
		url : 'present_remu_9008.php',
		type : 'POST',
		data : { "val": 'B_PS',
				"ps" : <?php echo substr($_SESSION['user_info']['stake_user_code'],0,7); ?>
				},
        success : function(response) {
			//alert(response);
				$("#gp_ps_zp_C").html(response);
		}
      });
	  
	  
	}
	else if(val ==''){
		$('#comm_div_C').show();
	}
}




function present_remu(val){
	var period_eng = $('#period_eng').val();
	var cat_post = $('#cat_post').val();
	
	 $.ajax({
      url : 'present_remu_9008.php',
      type : 'POST',
      data : {
				"period_eng" : period_eng,
				"cat_post" : cat_post,
				"val" : val,
				},
        success : function(response) {
			
				$("#remuneration").html(response);
		}
      });

}


function present_remu_B(val){
	
	var app_cas_day_con_worker = $('#app_cas_day_con_worker').val();
	var gp_ps_zp_B = $('#gp_ps_zp_B').val();
	
	 $.ajax({
      url : 'present_remu_9008.php',
      type : 'POST',
      data : {
				"gp_ps_zp_B" : gp_ps_zp_B,
				"app_cas_day_con_worker" : app_cas_day_con_worker,
				"val" : val,
				},
        success : function(response) {
			
				//alert(response);
				$("#present_remuneration").val(response);
		}
      });
	  
	  

}


function get_emp(gp_ps){
	//alert(gp_ps);
	$.ajax({
      url : 'present_remu_9008.php',
      type : 'POST',
      data : { "val": 'B_worker',
				"gp_ps" : gp_ps
				},
        success : function(response) {
			//alert(response);
				$("#app_cas_day_con_worker").html(response);
				$("#app_cas_day_con_worker_C").html(response);
		}
      });
}


function remu_auto_calculate(val){
	
	var app_cas_day_con_worker_C = $('#app_cas_day_con_worker_C').val();
	var gp_ps_zp_C = $('#gp_ps_zp_C').val();
	
	 $.ajax({
      url : 'present_remu_9008.php',
      type : 'POST',
      data : {
				"gp_ps_zp_C" : gp_ps_zp_C,
				"app_cas_day_con_worker_C" : app_cas_day_con_worker_C,
				"fin_year": val,
				"val" : 'AUTO_CAL',
				},
        success : function(response) {
			
				//alert(response);
				$("#calculation").val(response);
		}
      });
}

</script>
<!-- validation -->


	
<style>
.form-horizontal .control-label {
  text-align:left;
	}
</style>
<div class="content">
<?php 
//var_dump($_SESSION ['user_info'] ['district_id_fk']);
require 'common_back_btns_intra_pri.php'; ?>
   <div class="welcome_msg">
	<!-- <?php 
	//echo 33;die;
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT * FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");
		?> -->
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12" style="width:98%; padding-left:2%">
<h1 class="heading">9008-F(P)</h1>
<div class="border"></div>
</br>
<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>

<div id="form_show" > 

  <?php 
	  if($_SESSION['msg']){
		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
	  }
	  
	  
  $submit_status = $_GET['val'];
//var_dump($_SESSION['user_info']);
  ?>  
<?php if($_SESSION['user_info']['stake_level_code'] == '33'){?>	
	<button class="btn btn-info" id="new_proposal" name="new_proposal" onclick="return prop_creation();" style="margin-left: 19%;" > NEW PROPOSAL CREATION</button>
	<button class="btn btn-info" id="addi_feature" name="addi_feature" onclick="return addition_feature();" style="margin-left: 6%;" > APPROVED EMPLOYEE UNDER 9008</button>
	<button class="btn btn-info" id="addi_feature" name="addi_feature" onclick="return inbox_9008();" style="margin-left: 6%;" >INBOX FOR 9008 </button>

<?php }
else{ ?>
	<button class="btn btn-info" id="addi_feature" name="addi_feature" onclick="return addition_feature();" style="margin-left: 30%;" > APPROVED EMPLOYEE UNDER 9008</button>
	<button class="btn btn-info" id="addi_feature" name="addi_feature" onclick="return inbox_9008();" style="margin-left: 6%;" >INBOX FOR 9008 </button>
<?php } ?>

<button class="btn btn-warning" id="entry_app_emp" name="entry_app_emp" onclick="return entry_format_app_emp();" style="margin-left: 30%; margin-top: 4%; display:none" > Entry Format for approved contractual Employees under FD GO-9008 before 31/05/2022</button>
<button class="btn btn-warning" id="enh_remu" name="enh_remu" onclick="return enh_remu_3_yr();" style="margin-left: 30%; margin-top: 1%; display:none" > Enhancement of Remuneration after every year</button>
<button class="btn btn-warning" id="req_fund" name="req_fund" onclick="return req_fund_payment();" style="margin-left: 30%; margin-top: 1%; display:none" > Requisition of Fund for Approved Casual/ Daily rated/ Contractual workers for payment of Remuneration</button>
	
</br>	
</br>	
</br>	
	
	
<form name="myForm_addi_A" id="myForm_addi_A" method="post" class="w3_form_post" action="ajax_intra_pri_9008_submit.php" onsubmit="return validateForm_addi()" enctype="multipart/form-data" <?php if($submit_status =='myForm_addi_A'){ ?> style="display:block;" <?php } else { ?>style="display:none;" <?php } ?> >
</br>

<?php 
  $data_9008_A = $db->fetch_table(" SELECT * FROM entry_format_9008 WHERE application_id = '".$_SESSION['user_info']['application_id']."' ");
 //var_dump($data_9008_A);
?>

<input type="hidden" name="myForm_type" id="myForm_type" value="<?= $crypto->encode("myForm_addi_A",4); ?>" /> 

<div class="border"></div>
	</br>
	<h4 class="heading"> ADDITIONAL FEATURES UNDER MODULE FD GO-9008: </h4>
	<h5 class="heading"> Entry Format for approved contractual Employees under FD GO-9008 before 31/05/2022: </h5>
	</br>

 <div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Application No.</label>
		<div class="col-sm-6">
			<input type="text" class="form-control upper_case" id="app_no_A"  name="app_no_A" placeholder="Application No." value="<?php if(isset($data_9008_A[0]['application_id'])){ echo $data_9008_A[0]['application_id'];} ?>" readonly >
		</div>
	</div>  
	
 <div class="row mb-3">
 <?php if($_SESSION['user_info']['stake_level'] == 'BLOCK'){ ?>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Block Name:<span class="star_color">*</span></label>
    <div class="col-sm-3">
	<?php 
	$db = new database();
	$arr_block = $db->fetch_table("select block_name, block_code from prd_location_master_block WHERE block_code='".substr($_SESSION['user_info']['stake_user_code'],0,7)."'");
	?>
		<input class="form-control upper_case" id="block_zp"  name="block_zp" value="<?php echo $arr_block[0]['block_name']; ?>" readonly>
    </div>
	
	<?php }
else if($_SESSION['user_info']['stake_level'] == 'DISTRICT'){	?>
	<label for="inputPassword3" class="col-sm-3 col-form-label">Zilla Parishad Name:<span class="star_color">*</span></label>
    <div class="col-sm-3">
	<?php 
	$db = new database();
	$arr_dist = $db->fetch_table("select district_name, district_code from prd_location_master_district WHERE district_code='".substr($_SESSION['user_info']['stake_user_code'],0,4)."'");
	?>
		<input class="form-control upper_case" id="block_zp"  name="block_zp" value="<?php echo $arr_dist[0]['district_name']; ?>" readonly>
    </div>

<?php } ?>

    <label for="inputPassword3" class="col-sm-3 col-form-label">Select GP/ PS/ ZP Stack: <span class="star_color">*</span></label>
	<?php if($_SESSION['user_info']['stake_level'] == 'BLOCK'){ ?> 
    <div class="col-sm-3">
		<select class="form-control" id="gp_ps_stack" name="gp_ps_stack" onchange="show_GP(this.value);" >
			<option value="">--Please Select--</option>
			<option value="GP" <?php if($data_9008_A[0]['gp_ps_zp_stake']=='GP'){ echo "selected"; } ?> >GP</option>
			<option value="PS" <?php if($data_9008_A[0]['gp_ps_zp_stake']=='PS'){ echo "selected"; } ?> >PS</option>
		</select>
    </div>
	<?php }
	else if($_SESSION['user_info']['stake_level'] == 'DISTRICT'){?>
	<div class="col-sm-3">
		<input type="text" class="form-control" id="gp_ps_stack" name="gp_ps_stack" value="ZP" readonly />
	</div>
	<?php } ?>
 </div>	
 
 <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Select GP/ PS/ ZP Name:<span class="star_color">*</span></label>
	<div class="col-sm-3" id="comm_div">
	<?php if($_SESSION['user_info']['stake_level'] == 'BLOCK'){
	if($data_9008_A[0]['gp_ps_zp_code'] ==''){?>
		<select class="form-control upper_case" id="gp_ps_zp"  name="gp_ps_zp">
			<option value="">--Please Select--</option>
		</select>
	<?php }
	else{	
			$db = new database();
			$arr_gp =$db->fetch_table("select gp_name , gp_code from prd_location_master_gp where gp_code = '".$data_9008_A[0]['gp_ps_zp_code']."'");
	?>	
			<input type="text" class="form-control" id="gp_ps_zp" name="gp_ps_zp" value="<?php echo $arr_gp[0]['gp_name']; ?>" readonly />
	<?php }
	}
	else if($_SESSION['user_info']['stake_level'] == 'DISTRICT'){	
	$db = new database();
	$arr_dist = $db->fetch_table("select district_name, district_code from prd_location_master_district WHERE district_code='".substr($_SESSION['user_info']['stake_user_code'],0,4)."'");
	?>
		<input type="text" class="form-control" id="gp_ps_zp" name="gp_ps_zp" value="<?php echo $arr_dist[0]['district_name']; ?>" readonly />
	<?php } ?>

    </div>
	
    <div class="col-sm-3" id="gp_div" style="display:none">
		<?php   
		$arr_gp =$db->fetch_table("select gp.gp_name as gp_name , gp.gp_code as gp_code from prd_location_master_gp as gp
		INNER JOIN prd_location_master_block as block ON gp.block_id_fk = block.block_id_pk where block.block_code = '".substr($_SESSION['user_info']['stake_user_code'],0,7)."'"); ?>
		<select class="form-control" id="gp_code" name="gp_code" >
		<option value="">-Please Select-</option>
		<? foreach($arr_gp as $key){ $key['gp_code']. '<br />'; ?>
			<option value="<?=$key['gp_code']; ?>" <? if($gp_code==$key['gp_code']){ echo "selected";}?>><?= $key['gp_name']; ?></option>
		<? } ?>
		</select>
		
		
    </div>
	
	<div class="col-sm-3" id="ps_div" style="display:none">
		<?php 
		$db = new database();
		$arr_block = $db->fetch_table("select block_name, block_code from prd_location_master_block WHERE block_code='".substr($_SESSION['user_info']['stake_user_code'],0,7)."'");
		?>
		<input type="hidden" class="form-control" id="ps_code" name="ps_code" value="<?php echo $arr_block[0]['block_code']; ?>" />
		<input type="text" class="form-control" value="<?php echo $arr_block[0]['block_name']; ?>" readonly />
    </div>
	
     <label for="inputPassword3" class="col-sm-3 col-form-label">Name of Casual/ Daily rated/ Contractual Workers: <span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="text" class="form-control" id="casual_daily_contractual" name="casual_daily_contractual" Placeholder="Name of Workers" value="<?php if(isset($data_9008_A[0]['worker_name'])){ echo $data_9008_A[0]['worker_name'];} ?>"/>
    </div>
 </div>	
 
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Name of Sanctioned Post:<span class="star_color">*</span></label>
	
	<?php if($_SESSION['user_info']['stake_level'] == 'DISTRICT'){ ?>
	
	<div class="col-sm-3">
		<?php
		$db = new database();
		$arr = $db->fetch_table("select * from zpemp_emp_desig_master order by designation_id");
		?>
		<select class="form-control" name="san_post2" id="san_post2" >
			<option value="">--Please Select--</option>
			<?php
			foreach($arr as $key){
			?>
			<option value="<?= $key['designation_id']; ?>"<? if($data_9008_A[0]['sanctioned_post_code']==$key['designation_id']){ echo "selected";}?>><?= $key['designation_name']; ?></option>
			<? } ?>
		</select>
	</div>
	<?php }
	else{ ?>
    <div class="col-sm-3" id="desig_gp" style="display:none;">
		<?php
		$db = new database();
		$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '11%' and code in('1114','1115','1116','1117','1118','1119','1120','1121','1122','1123','1124','1125') order by code");
		?>
		<select class="form-control" name="san_post" id="san_post">
			<option value="">--Please Select--</option>
			<?php 
			foreach($arr as $key){ ?>
			<option value="<?= $key['code']; ?>" <? if($data_9008_A[0]['sanctioned_post_code']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-sm-3" id="desig_ps" style="display:none;">
		<?php
		$db = new database();
		$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '90%' order by code");
		?>
		<select class="form-control" name="san_post1" id="san_post1" >
			<option value="">--Please Select--</option>
			<?php
			foreach($arr as $key){
			?>
			<option value="<?= $key['code']; ?>"<? if($data_9008_A[0]['sanctioned_post_code']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
			<? } ?>
		</select>
	</div>
	
	<div class="col-sm-3" id="desig_common" >
		<select class="form-control" >
			<option value="">--Please Select--</option>
		</select>
	</div>
	<?php } ?>
	
	
    <label for="inputPassword3" class="col-sm-3 col-form-label">Category of Post: <span class="star_color">*</span></label>
    <div class="col-sm-3">
		<select class="form-control" id="cat_post" name="cat_post" onchange="return present_remu('A');" >
			<option value="">--Please Select--</option>
			<option value="C" <?php if($data_9008_A[0]['post_category']=='C'){ echo "selected"; } ?> >C</option>
			<option value="D" <?php if($data_9008_A[0]['post_category']=='D'){ echo "selected"; } ?> >D</option>
		</select>
    </div>
 </div>	
 
 <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Period of Engagement:<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<select class="form-control upper_case" id="period_eng"  name="period_eng" onchange="return present_remu('A');" >
			<option value="">--Please Select--</option>
			<option value="5" <?php if($data_9008_A[0]['period_engagement']=='5'){ echo "selected"; } ?> >Less than 5 years</option>
			<option value="5-10" <?php if($data_9008_A[0]['period_engagement']=='5-10'){ echo "selected"; } ?> >5 to less than 10 years</option>
			<option value="10-15" <?php if($data_9008_A[0]['period_engagement']=='10-15'){ echo "selected"; } ?> >10 to less than 15 years</option>
			<option value="15-20" <?php if($data_9008_A[0]['period_engagement']=='15-20'){ echo "selected"; } ?> >15 to less than 20 years</option>
			<option value="20" <?php if($data_9008_A[0]['period_engagement']=='20'){ echo "selected"; } ?> >20 years and above</option>
		</select>
    </div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Present Remuneration: <span class="star_color">*</span></label>
    <div class="col-sm-3">
	<?php if($data_9008_A[0]['present_remuneration']== ''){?>
		<select class="form-control" id="remuneration" name="remuneration" >
			<option value="">--Please Select--</option>
		</select>
	<?php }
else{
	?>
	<input type="text" class="form-control" id="period_eng" name="period_eng" value="<?php if(isset($data_9008_A[0]['present_remuneration'])){ echo $data_9008_A[0]['present_remuneration'];} ?>" readonly >
<?php	
}	?>
    </div>
 </div>	
 
  <div class="row mb-3">
  <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Joining:<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input class="form-control upper_case" id="dt_join"  name="dt_join" Placeholder="Date of Joining" value="<?php if(isset($data_9008_A[0]['joining_date'])){ echo date("d-M-Y",strtotime($data_9008_A[0]['joining_date'])) ;} ?>">
    </div>
	
    <label for="inputPassword3" class="col-sm-3 col-form-label">Approval Order of Department (Memo No.):<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="text" class="form-control upper_case" id="dept_order"  name="dept_order" Placeholder="Approval Order of Department (Memo No.)" value="<?php if(isset($data_9008_A[0]['approval_order_memo_no'])){ echo $data_9008_A[0]['approval_order_memo_no'];} ?>">
    </div>
 </div>	

<input type="hidden" name="app_no_file_A" id="app_no_file_A" value="<?= $crypto->encode($data_9008_A[0]['application_id'],4); ?>" /> 

<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label"> Upload Approval Order of Department:<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_35 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 35 AND application_id= '".$data_9008_A[0]['application_id']."' "); 
		if($arr_file_35[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="approval_order"  id="approval_order" value="" onchange="return file_upload(this.id,35,'File_A');" <?php if($data_9008_A[0]['application_id'] ==''){?> disabled <?php } ?> >
	  <?php } 
		else {?>
		  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_35[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("35",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($data_9008_A[0]['application_id'],4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
  </div></br>	
  
  <?php if($data_9008_A[0]['application_id'] == ''){ ?>
		<button class="btn btn-info" type="submit" id="submit" name="submit" style='margin-left: 44%;' >SAVE & CONTINUE </button>
  <?php }
else if($data_9008_A[0]['application_id'] !=''){  ?>
		<a type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" style='margin-left: 44%;' id="myForm_PREV_A" onclick="return sub_prev_9008(this.id)" > PREVIEW FOR FINAL SUBMIT </a>
<?php } ?> 
  
</form>





















<form name="myForm_addi_B" id="myForm_addi_B" method="post" class="w3_form_post" action="ajax_intra_pri_9008_submit.php" onsubmit="return validateForm_addi()" enctype="multipart/form-data" <?php if($submit_status =='myForm_addi_B'){ ?> style="display:block;" <?php } else { ?>style="display:none;" <?php } ?> >
</br>
<?php 
  $data_9008_B = $db->fetch_table(" SELECT * FROM enhancement_remuneration_9008 WHERE application_id = '".$_SESSION['user_info']['application_id']."' ");
 //var_dump($data_9008_B);
?>
<input type="hidden" name="myForm_type" id="myForm_type" value="<?= $crypto->encode("myForm_addi_B",4); ?>" /> 
<div class="border"></div>
	</br>
	<h4 class="heading"> ADDITIONAL FEATURES UNDER MODULE FD GO-9008: </h4>
	<h5 class="heading"> Enhancement of Remuneration after every year: </h5>
	</br>

 <div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Application No.</label>
		<div class="col-sm-6">
			<input type="text" class="form-control upper_case" id="app_no_B"  name="app_no_B" placeholder="Application No." value="<?php if(isset($data_9008_B[0]['application_id'])){ echo $data_9008_B[0]['application_id'];} ?>" readonly >
		</div>
	</div> 
	
 <div class="row mb-3">
<label for="inputPassword3" class="col-sm-3 col-form-label">Select GP/ PS/ ZP Stack: <span class="star_color">*</span></label>
	<?php if($_SESSION['user_info']['stake_level'] == 'BLOCK'){ ?> 
    <div class="col-sm-3">
		<select class="form-control" id="gp_ps_stack" name="gp_ps_stack" onchange="show_GP_B(this.value);" />
			<option value="">--Please Select--</option>
			<option value="GP"  <?php if($data_9008_B[0]['gp_ps_zp_stake']=='GP'){ echo "selected"; } ?>>GP</option>
			<option value="PS"  <?php if($data_9008_B[0]['gp_ps_zp_stake']=='PS'){ echo "selected"; } ?>>PS</option>
		</select>
    </div>
	<?php }
	else if($_SESSION['user_info']['stake_level'] == 'DISTRICT'){?>
	<div class="col-sm-3">
		<input type="text" class="form-control" id="gp_ps_stack" name="gp_ps_stack" value="ZP" readonly />
	</div>
	<?php } ?>
	
 

    <label for="inputPassword3" class="col-sm-3 col-form-label">Select GP/ PS/ ZP Name:<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<?php if($data_9008_B[0]['gp_ps_zp_code'] == ''){ ?>
		<select class="form-control upper_case" id="gp_ps_zp_B" name="gp_ps_zp_B" onchange="get_emp(this.value)">
			<option value="">--Please Select--</option>
		</select>
	<?php } 
	else{
	$db = new database();
		$arr_gp =$db->fetch_table("select gp_name , gp_code from prd_location_master_gp where gp_code = '".$data_9008_B[0]['gp_ps_zp_code']."'");
?>	
		<input type="text" class="form-control" id="gp_ps_zp_B" name="gp_ps_zp_B" value="<?php echo $arr_gp[0]['gp_name']; ?>" readonly />
	
	<?php } ?>
    </div>
	
    
	
</div>
	
	
<div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Approved Casual/ Daily rated/ Contractual workers: <span class="star_color">*</span></label>
	
    <div class="col-sm-3">
	<?php if($data_9008_B[0]['worker_name'] == ''){?>
		<select class="form-control" id="app_cas_day_con_worker" name="app_cas_day_con_worker" onchange="return present_remu_B('B');">
			<option value="">-Please Select-</option>
		</select>
	<?php }
else{	?>
	<input type="text" class="form-control" id="app_cas_day_con_worker" name="app_cas_day_con_worker" value="<?php echo $data_9008_B[0]['worker_name']; ?>" readonly />
<?php } ?>	
    </div>
	
	
	
	<label for="inputPassword3" class="col-sm-3 col-form-label">Present Remuneration: <span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="text" class="form-control" id="present_remuneration" name="present_remuneration" placeholder="Present Remuneration" value="<?php echo $data_9008_B[0]['present_remuneration']; ?>" readonly />
    </div>
 </div>
 
<div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Enhanced Remuneration: <span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="text" class="form-control" id="enhanced_remuneration" name="enhanced_remuneration" placeholder="Enhanced Remuneration"  value="<?php echo $data_9008_B[0]['enhanced_remuneration']; ?>" />
    </div>
	
 </div>
	
	<input type="hidden" name="app_no_file_B" id="app_no_file_B" value="<?= $crypto->encode($data_9008_B[0]['application_id'],4); ?>" /> 
	
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label"> Upload signed copy:<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_36 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 36 AND application_id= '".$data_9008_B[0]['application_id']."' "); 
		if($arr_file_36[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="signed_copy"  id="signed_copy" value="" onchange="return file_upload(this.id,36,'File_B');" <?php if($data_9008_B[0]['application_id'] ==''){?> disabled <?php } ?>>
	  <?php } 
		else {?>
		  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_36[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("36",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($data_9008_B[0]['application_id'],4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
  </div>

 <?php if($data_9008_B[0]['application_id'] == ''){ ?>
		<button class="btn btn-info" type="submit" id="submit" name="submit" style='margin-left: 44%;' >SAVE & CONTINUE </button>
  <?php }
else if($data_9008_B[0]['application_id'] !=''){  ?>
		<a type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" style='margin-left: 44%;' id="myForm_PREV_B" onclick="return sub_prev_9008(this.id)" > PREVIEW FOR FINAL SUBMIT </a>
<?php } ?> 

</form>

















<form name="myForm_addi_C" id="myForm_addi_C" method="post" class="w3_form_post" action="ajax_intra_pri_9008_submit.php" onsubmit="return validateForm_addi()" enctype="multipart/form-data" <?php if($submit_status =='myForm_addi_C'){ ?> style="display:block;" <?php } else { ?>style="display:none;" <?php } ?> >
</br>
<?php 
  $data_9008_C = $db->fetch_table(" SELECT * FROM fund_requisition_9008 WHERE application_id = '".$_SESSION['user_info']['application_id']."' ");
 //var_dump($data_9008_C);
?>

<input type="hidden" name="myForm_type" id="myForm_type" value="<?= $crypto->encode("myForm_addi_C",4); ?>" /> 
<div class="border"></div>
	</br>
	<h4 class="heading"> ADDITIONAL FEATURES UNDER MODULE FD GO-9008: </h4>
	<h5 class="heading"> Requisition of Fund for Approved Casual/ Daily rated/ Contractual workers for payment of Remuneration: </h5>
	</br>

 <div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Application No.</label>
		<div class="col-sm-6">
			<input type="text" class="form-control upper_case" id="app_no_C"  name="app_no_C" placeholder="Application No." value="<?php if(isset($data_9008_C[0]['application_id'])){ echo $data_9008_C[0]['application_id'];} ?>" readonly >
		</div>
	</div> 
	
<div class="row mb-3">
<label for="inputPassword3" class="col-sm-3 col-form-label">Select GP/ PS/ ZP Stack: <span class="star_color">*</span></label>
	<?php if($_SESSION['user_info']['stake_level'] == 'BLOCK'){ ?> 
    <div class="col-sm-3">
		<select class="form-control" id="gp_ps_stack_C" name="gp_ps_stack_C" onchange="show_GP_C(this.value);" />
			<option value="">--Please Select--</option>
			<option value="GP" <?php if($data_9008_C[0]['gp_ps_zp_stake']=='GP'){ echo "selected"; } ?> >GP</option>
			<option value="PS" <?php if($data_9008_C[0]['gp_ps_zp_stake']=='PS'){ echo "selected"; } ?> >PS</option>
		</select>
    </div>
	<?php }
	else if($_SESSION['user_info']['stake_level'] == 'DISTRICT'){?>
	<div class="col-sm-3">
		<input type="text" class="form-control" id="gp_ps_stack_C" name="gp_ps_stack_C" value="ZP" readonly />
	</div>
	<?php } ?>
	
 

    <label for="inputPassword3" class="col-sm-3 col-form-label">Select GP/ PS/ ZP Name:<span class="star_color">*</span></label>
	<div class="col-sm-3">
	<?php if($data_9008_C[0]['gp_ps_zp_code'] == ''){?>
		<select class="form-control upper_case" id="gp_ps_zp_C" name="gp_ps_zp_C" onchange="get_emp(this.value)">
			<option value="">--Please Select--</option>
		</select>
	<?php }
else{
		$db = new database();
		$arr_gp =$db->fetch_table("select gp_name , gp_code from prd_location_master_gp where gp_code = '".$data_9008_C[0]['gp_ps_zp_code']."'");
?>	
		<input type="text" class="form-control" id="gp_ps_zp_C" name="gp_ps_zp_C" value="<?php echo $arr_gp[0]['gp_name']; ?>" readonly />

<?php } ?>
    </div>
	
    
	
</div>
	
<div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Approved Casual/ Daily rated/ Contractual workers: <span class="star_color">*</span></label>
    <div class="col-sm-3">
	<?php if($data_9008_C[0]['worker_name'] == ''){?>
		<select class="form-control" id="app_cas_day_con_worker_C" name="app_cas_day_con_worker_C" >
			<option value="">--Please Select--</option>
		</select>
	<?php } 
	else{ ?>	
		<input type="text" class="form-control" id="app_cas_day_con_worker_C" name="app_cas_day_con_worker_C" value="<?php echo $data_9008_C[0]['worker_name']; ?>" readonly />
	<?php } ?>
    </div>
	
	<label for="inputPassword3" class="col-sm-3 col-form-label" >Select Duration (Financial year-wise): <span class="star_color">*</span></label>
	<div class="col-sm-3">
		<select class="form-control" id="duration_C" name="duration_C" onchange="return remu_auto_calculate(this.value);" >
			<option value="">--Please Select--</option>
			<option value="2022-2023" <?php if($data_9008_C[0]['duration']=='2022-2023'){ echo "selected"; } ?> >2022-2023</option>
			<!--<option value="2023-2024">2023-2024</option>
			<option value="2024-2025">2024-2025</option>
			<option value="2025-2026">2025-2026</option>-->
		</select>
	</div>
 </div>
 
 <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Auto Calculation: <span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="text" class="form-control" id="calculation" name="calculation" placeholder="Calculation" value="<?php echo $data_9008_C[0]['auto_calculation']; ?>" readonly>
			
    </div>
	
 </div>
	
		<input type="hidden" name="app_no_file_C" id="app_no_file_C" value="<?= $crypto->encode($data_9008_C[0]['application_id'],4); ?>" /> 
		
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label"> Upload signed copy:<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_37 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 37 AND application_id= '".$data_9008_C[0]['application_id']."' "); 
		if($arr_file_37[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="signed_copy_C"  id="signed_copy_C" value="" onchange="return file_upload(this.id,37,'File_C');" <?php if($data_9008_C[0]['application_id'] ==''){?> disabled <?php } ?>>
	  <?php } 
		else {?>
		  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_37[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("37",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($data_9008_C[0]['application_id'],4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
  </div>	
  
  <?php if($data_9008_C[0]['application_id'] == ''){ ?>
		<button class="btn btn-info" type="submit" id="submit" name="submit" style='margin-left: 44%;' >SAVE & CONTINUE </button>
  <?php }
else if($data_9008_C[0]['application_id'] !=''){  ?>
		<a type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" style='margin-left: 44%;' id="myForm_PREV_C" onclick="return sub_prev_9008(this.id)" > PREVIEW FOR FINAL SUBMIT </a>
<?php } ?> 
</form>











<form name="myForm" id="myForm" method="post" class="w3_form_post" action="ajax_intra_pri_9008_submit.php" onsubmit="return validateForm()" enctype="multipart/form-data" <?php if($submit_status =='myForm'){ ?> style="display:block;" <?php } else { ?>style="display:none;" <?php } ?> >
</br>
<input type="hidden" name="myForm_type" id="myForm_type" value="<?= $crypto->encode("myForm",4); ?>" /> 
<input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partA",4); ?>" />

<?php 
  $data_9008 = $db->fetch_table(" SELECT * FROM intra_pri_9008 WHERE application_id = '".$_SESSION['user_info']['application_id']."' ");
  $year_day_9008 = $db->fetch_table(" SELECT * FROM intra_pri_service_each_year_9008 WHERE application_id = '".$_SESSION['user_info']['application_id']."' ");
  //unset($_SESSION['user_info']['application_id']);
  //var_dump($year_day_9008[0]);
?>
      <div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Application No.</label>
		<div class="col-sm-6">
			<input type="text" class="form-control upper_case" id="app_no"  name="app_no" placeholder="Application No." value="<?php if(isset($data_9008[0]['application_id'])){ echo $data_9008[0]['application_id'];} ?>" readonly >
		</div>
	</div>          
  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-3 col-form-label">Place of Posting:<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <select class="form-control" name="place_of_posting"  id="place_of_posting" onChange="place_of_posting(this.value)" >
            <option value="">-Please Select-</option>
            <option value="ZP" <?php if($data_9008[0]['posting']== 'ZP') { echo "selected"; } ?>>ZP</option>
            <option value="GP" <?php if($data_9008[0]['posting']== 'GP') { echo "selected"; } ?>>GP</option>
            <option value="PS" <?php if($data_9008[0]['posting']== 'PS') { echo "selected"; } ?>>PS</option>
      </select>
    </div>
    
  </div>  

  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-3 col-form-label">Employee Name: <span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" id="tch_fname"  name="tch_fname" placeholder="First Name" value="<?php if(isset($data_9008[0]['emp_first_name'])){ echo $data_9008[0]['emp_first_name'];} ?>"  />
      <!-- required pattern="[a-zA-Z]+" [a-zA-Z-' ]-->
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" id="tch_mname"  name="tch_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ')" value="<?php if(isset($data_9008[0]['emp_second_name'])){ echo $data_9008[0]['emp_second_name'];} ?>" />
      <!-- required pattern="[a-zA-Z]+" -->
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" id="tch_lname"  name="tch_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?php if(isset($data_9008[0]['emp_last_name'])){ echo $data_9008[0]['emp_last_name'];} ?>"/>
      <!-- required pattern="[a-zA-Z]+" -->
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Birth:<span class="star_color">*</span></label>
    <div class="col-sm-3">
	 <input type="text" class="form-control upper_case" id="date_of_birth"  name="date_of_birth" placeholder="DD-MM-YYYY" value="<?php if(isset($data_9008[0]['emp_dob'])){ echo date("d-m-Y",strtotime($data_9008[0]['emp_dob']));} ?>">
    </div>
     <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Engagement:<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="tch_doe" name="tch_doe" placeholder="DD-MM-YYYY" value="<?php if(isset($data_9008[0]['date_engagement'])){ echo date("d-m-Y",strtotime($data_9008[0]['date_engagement']));} ?>"/>
     <!-- required -->

    </div>
     </div>
   <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Whether Engaged as Casual/Daily Rated Worker/Contractual Worker:<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<select class="form-control" name="drp_engaged" readonly id="drp_engaged">
			<option value="" selected>-Please Select-</option>
			<option value="200" <?php if($data_9008[0]['whether_engagged']== '200') { echo "selected"; } ?> >Casual</option>
			<option value="201" <?php if($data_9008[0]['whether_engagged']== '201') { echo "selected"; } ?> >Daily Rated Worker</option>
			<option value="202" <?php if($data_9008[0]['whether_engagged']== '202') { echo "selected"; } ?> >Contractual Worker</option>
		</select>
    </div>
    <!-- add -->
     <label for="inputPassword3" class="col-sm-3 col-form-label">No. of Days of Service in Each Year:<span class="star_color">*</span></label>
    <div class="col-sm-3">
<!-- add fild -->
        
    <table name="each_year[]" id="each_year" width="100%">
		
        <?php if($year_day_9008[0]['service_each_year_9008_pk'] == 0 && $year_day_9008[0]['service_each_year_9008_pk'] == null){ ?>
		<tr id="tr0">
			<td><strong>Year:</strong></td>
            <td><input type="text" size="8" class="custom-input-field" name="service_in_each_year[]" id="service_in_each_year" placeholder="Year"  maxlength="4" /></td>
			<td><strong>Days:</strong></td>
			<td><input type="text" size="8" class="custom-input-field"name="service_in_each_days[]" id="service_in_each_days" placeholder="Days"  maxlength="3" /></td>
            
            <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row0" class="add_row0" title="Click To Add" src="../../themes/default/image/add_row_image.png" width="20"></td>
        </tr>
		<?php } else{ 
			foreach($year_day_9008 as $value){
		?>	
		<tr id="tr0">
			<td><strong>Year:</strong></td>
			<td><input type="text" size="8" value="<?php echo $value['year']; ?>" readonly /></td>
			<td><strong>Days:</strong></td>
			<td><input type="text" size="8" value="<?php echo $value['days']; ?>" readonly /></td>
		</tr>	
        <?php }
		}		?> 
		
			
    </table> 
   
        <!-- end add fild -->
  </div>
    </div>
 
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Whether engaged against sanctioned vacant post:<span class="star_color">*</span></label>
		<div class="col-sm-3">
            <select class="form-control" name="sanctioned_vacant_post" id="sanctioned_vacant_post" onChange="vacant_post(this.value);" > 
			  <option value="">Please Select</option>
			  <option value="0" <?php if($data_9008[0]['sanctioned_vacant_post']== '0') { echo "selected"; } ?> >NO</option>
			  <option value="1" <?php if($data_9008[0]['sanctioned_vacant_post']== '1') { echo "selected"; } ?> >YES </option>
        
      </select>  			
    	</div>
     
  </div>
  
<div class="row mb-3" id="new_post" <?php if($data_9008[0]['sanctioned_post_name']==''){ ?> style="display:none" <?php }?> >
    <label for="inputPassword3" class="col-sm-3 col-form-label">Name of the Sanctioned Post:<span class="star_color">*</span></label>
	
	<?php if($_SESSION['user_info']['stake_level'] == 'DISTRICT'){ ?>
	
	<div class="col-sm-3">
		<?php
		$db = new database();
		$arr = $db->fetch_table("select * from zpemp_emp_desig_master order by designation_id");
		?>
		<select class="form-control" name="name_of_post" id="name_of_post" >
			<option value="">--Please Select--</option>
			<?php
			foreach($arr as $key){
			?>
			<option value="<?= $key['designation_id']; ?>"<? if($data_9008_A[0]['sanctioned_post_code']==$key['designation_id']){ echo "selected";}?>><?= $key['designation_name']; ?></option>
			<? } ?>
		</select>
	</div>
	<?php }
else{	?>
	
	<div class="col-sm-3">
		<?php
		$db = new database();
		$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '11%' and code in('1114','1115','1116','1117','1118','1119','1120','1121','1122','1123','1124','1125') order by code");
		?>
		<select class="form-control" name="name_of_post1" id="name_of_post1">
			<option value="">--Please Select--</option>
			<?php 
			foreach($arr as $key){
			$key['code']. '<br />';
			?>
			<option value="<?= $key['code']; ?>" 
			<?php 
			if($data_9008[0]['sanctioned_post_name']==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
			<?php } ?>
		</select>
	</div>
							
	<?php } ?>						
          <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Occurence of Vacancy:<span class="star_color">*</span></label>
          <div class="col-sm-3">
            <input type="text" class="form-control upper_case" id="date_of_vacancy"  name="date_of_vacancy" placeholder="Date of vacancy" value="<?php if(isset($data_9008[0]['occurence_vacancy_date'])){ echo date("d-m-Y",strtotime($data_9008[0]['occurence_vacancy_date']));} ?>" >
             <!-- required -->
          </div>
</div>




  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Amount of Remuneration P.M: <span class="star_color">*</span></label>

    <div class="col-sm-3">
      <input type="text" class="form-control"  name="amount_of_remuneration" id="amount_of_remuneration" placeholder="Amount of remuneration P.M" onKeyPress="return keyRestrict(event,'1234567890')" value="<?php if(isset($data_9008[0]['remuneration'])){ echo $data_9008[0]['remuneration'];} ?>"/>
    </div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Whether the post is still vacant: <span class="star_color">*</span></label>
     
    <div class="col-sm-3">
     <select class="form-control" name="still_working"  id="still_working">
            <option value="">-Please Select-</option>
            <option value="500" <?php if($data_9008[0]['still_working']== '500') { echo "selected"; } ?> >YES</option>
            <option value="600" <?php if($data_9008[0]['still_working']== '600') { echo "selected"; } ?> >NO</option>
            </select> 
    </div>

  </div>
 <div class="row mb-3">
  
  <label for="inputPassword3" class="col-sm-3 col-form-label">Source of Fund:<span class="star_color">*</span></label>
    <div class="col-sm-3">     
        <select class="form-control" name="source_of_fund" id="source_of_fund" onChange="source_of_fund_note(this.value);">
			  <option value="">Please Select</option>
			  <option value="0" <?php if($data_9008[0]['source_of_fund']== '0') { echo "selected"; } ?> >Own Source(OSR)</option>
			  <option value="1" <?php if($data_9008[0]['source_of_fund']== '1') { echo "selected"; } ?> >Project</option>
		</select>
	</div>
 
 </div> 
 
 <div class="row mb-3" id="note" <?php if($data_9008[0]['source_of_fund']!='1'){ ?> style="display:none" <?php }?> >
    <label for="inputPassword3" class="col-sm-3 col-form-label">NOTE: <span class="star_color">*</span></label>
    <div class="col-sm-3"> 
      <textarea class="form-control"  name="noted" id="noted" placeholder="Note" ><?php if(isset($data_9008[0]['note'])){ echo $data_9008[0]['note'];} ?></textarea>
    </div>
 </div>

 <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">
    <div class="col-sm-offset-5 col-sm-7">

      <?php
      
    if($data_9008=='' && $data_9008 == null){
      
      ?>
	  <button class="btn btn-info" type="submit" id="submit" name="submit"> SAVE & CONTINUE</button>
	  <?php
	  }
	  else
	  {
		?>
               
    </div>
  </div>

<div class="border"></div>
	</br>
	<h5 class="heading"> Document to be Uploaded</h5>
	</br>
	<input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partB",4); ?>" /> 
	<input type="hidden" name="9008_id" id="9008_id" value="<?= $crypto->encode($data_9008[0]['9008_id_pk'],4); ?>" /> 
	<input type="hidden" name="application_id" id="application_id" value="<?= $crypto->encode($data_9008[0]['application_id'],4); ?>" /> 
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label"> Engagement Letter:<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_30 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 30 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_30[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="engasment_letter"  id="engasment_letter" value="" onchange="return file_upload(this.id,30,'File');">
	  <?php } 
		else {?>
		  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_30[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("30",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($data_9008[0]['application_id'],4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
  </div>
  <div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  
  
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Resolution (copy):<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_31 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 31 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_31[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="resolution"  id="resolution" value="" onchange="return file_upload(this.id,31,'File');" >
	   <?php } 
		else {?>
		<label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_31[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("31",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($data_9008[0]['application_id'],4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
    
  
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Year Wise Attandance in the From of Certificate:<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_32 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 32 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_32[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="attandance"  id="attandance" value="" onchange="return file_upload(this.id,32,'File');" >
	  <?php } 
		else {?>
		<label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_32[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
   <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("32",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($data_9008[0]['application_id'],4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Certificate of BDO/AEO Zilla Parishad: <span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_33 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 33 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_33[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="poof_of_engagement"  id="poof_of_engagement" value="" onchange="return file_upload(this.id,33,'File');" >
	  <?php } 
		else {?>
		<label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_33[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("33",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($data_9008[0]['application_id'],4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
<div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label"> Recommendation of DM/ADM(P)/AEO:  <span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_34 = $db->fetch_table("select * from intra_pri_file_upload where status = '1' AND flag= 34 AND application_id= '".$data_9008[0]['application_id']."' "); 
		if($arr_file_34[0]['file_name']==''){?>
      <input type="file" class="form-control" autocomplete="off" name="recommendation"  id="recommendation" value="" onchange="return file_upload(this.id,34,'File');" >
	   <?php } 
		else {?>
		<label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_34[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("34",4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($data_9008[0]['application_id'],4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
  <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  	
 <div class="row mb-3">
  <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Observation: <span class="star_color">*</span></label>
    <div class="col-sm-3">
     <textarea type="text" class="form-control " name="observation" id="observation" placeholder="Observation" rows="5" cols="40" ><?php if(isset($data_9008[0]['observation'])){ echo $data_9008[0]['observation'];} ?></textarea>
    </div>
  </div> 
  
	<!--<div class="row mb-3" style="margin-left: 41%;">
		<div class="col-sm-offset-5 col-sm-7">
		<a href="intre_pri_overage_condonation_pdf.php" id="submit" name="submit" class="btn btn-info" > GENERATE ORDER </a>
			<a type="button" id="submit" name="submit" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" > SUBMIT & PREVIEW </a>
		</div>
	</div>-->
	
	<div class="row mb-3" style="margin-left: 41%;">
		<div class="col-sm-offset-5 col-sm-7">
		<?php if($data_9008[0]['observation'] == '' ){ ?>	
			<button class="btn btn-info" type="submit" id="submit" name="submit" >SAVE & CONTINUE </button>
		<?php }
		else if($data_9008[0]['observation'] != '' ){ ?>
			<a type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" id="myForm_PREV" onclick="return sub_prev_9008(this.id)" > PREVIEW FOR FINAL SUBMIT </a>
		<?php }	?>	
		</div>
	</div>
	<?php
 }

?>
</form>

</div> 
</div> 
</div>
    </div>
    </div>
     

<?php
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>

<script>


function prop_creation(){
	$('#myForm').show();
	$('#myForm_addi_B').hide();
	$('#myForm_addi_A').hide();
	$('#myForm_addi_C').hide();
	$('#entry_app_emp').hide();
	$('#enh_remu').hide();
	$('#req_fund').hide();
}

function addition_feature(){
	$('#myForm').hide();
	$('#myForm_addi_A').hide();
	$('#entry_app_emp').show();
	$('#enh_remu').show();
	$('#req_fund').show();
}

function entry_format_app_emp(){
	$('#myForm_addi_A').show();
	$('#myForm_addi_B').hide();
	$('#myForm_addi_C').hide();
}

function enh_remu_3_yr(){
	$('#myForm_addi_B').show();
	$('#myForm_addi_A').hide();
	$('#myForm_addi_C').hide();
}

function req_fund_payment(){
	$('#myForm_addi_B').hide();
	$('#myForm_addi_A').hide();
	$('#myForm_addi_C').show();
}

function inbox_9008(){
	$('#myForm').hide();
	$('#myForm_addi_B').hide();
	$('#myForm_addi_A').hide();
	$('#myForm_addi_C').hide();
	$('#entry_app_emp').hide();
	$('#enh_remu').hide();
	$('#req_fund').hide();
}


</script>

<!-- vacant post -->
    <script>
    
    function vacant_post(k)
    {
      if(k==1)
      {
        $('#new_post').show();
      }
      else
      {
        $('#new_post').hide();
      }
    }
    
    
    function source_of_fund_note(k)
    {
      if(k==1)
      {
        $('#note').show();
      }
      else
      {
        $('#note').hide();
      }
    }
	
	function del(k,l,m){
		var delete_f=$("#delete_f").val(k);
		var delete_id=$("#delete_id").val(l);
		$('#application_id').val(m);
		$('#delet').modal('toggle');
	};
    
</script>  
		
		
<!-- add row -->
<script >
   function add_row(id)
    {  
    var id_length=id.length;
    var id=id.substr(7,id_length);
    $("#each_year").each(function(){ 
    var r = $('#each_year tr').length;
    if('tr'+id==$('#each_year tr:last').attr('id'))
    { 
		var tds = '<tr id="tr'+r+'">';
		tds+='<td><strong>Year:</strong></td>';
		tds+='<td><input type="text" size="8" class="custom-input-field" name="service_in_each_year[]" id="service_in_each_year"placeholder="Year" maxlength="4" /></td>';
		tds+='<td><strong>Days:</strong></td>';
		tds+='<td><input type="text" size="8" class="custom-input-field" name="service_in_each_days[]" id="service_in_each_days" placeholder="Days"   maxlength="3" /></td>';
		tds+=' <td><img onclick="return add_row(this.id)" style="cursor:pointer;" id="add_row'+r+'" class="add_row3" title="Click To Add" src="../../themes/default/image/add_row_image.png" width="20"><img onclick="return remove_row(this.id)" style="cursor:pointer;" id="remove_row'+r+'" class="remove_row3" title="Click To Remove" src="../../themes/default/image/remove_row_image.png" width="15"></td>';
		tds += '</tr>';
		
		if($('tbody', this).length > 0)
		{
			$('#each_year tr').last().after(tds);
			}  
		}
    })
    } 
	
    function remove_row(id)
    { 
		var rowCount = $('#each_year tr').length;
		var tr_id=id.substr(10);
		if(rowCount>1)
		{
			$('#tr'+tr_id).remove();
		}
    
    }
	
	
	
function sub_prev_9008(v){
	var id_9008 = $("#9008_id").val();
	
	if(v == 'myForm_PREV'){ var app_no = $("#app_no").val(); }
	else if(v == 'myForm_PREV_A'){ var app_no = $("#app_no_A").val(); }
	else if(v == 'myForm_PREV_B'){ var app_no = $("#app_no_B").val(); }
	else if(v == 'myForm_PREV_C'){ var app_no = $("#app_no_C").val(); }
	
	//alert(app_no);
 if($('#observation').val()=='' && v == 'myForm_PREV'){
		alert('Please Enter Observation.');
		$('#observation').focus();
		return false;
	}
				
				
  $.ajax({
      url : 'ajax_intra_pri_9008_submit_last.php',
      type : 'POST',
      data : {
				"id_9008" : id_9008,
				"app_no" : app_no,
				"fi_sub" : v
				},
        success : function(response) { //alert(v);
			//var result1 = $.parseJSON(response);
			
			if(v =='fi_sub_form'){
					//alert(response);
				//window.location.href = "intre_pri_9008_form.php";
				$('#forward_div').show();
				$('#fi_sub').hide();
				$('#service_type').val('5');
				$('#sub_menu').val('5');
			}
			else if(v =='fi_sub_formA'){
					//alert(response);
				//window.location.href = "intre_pri_9008_form.php";
				$('#forward_div').show();
				$('#fi_sub').hide();
				//$('#service_type').val('5A');
				$('#service_type').val('5');
				$('#sub_menu').val('5A');
			}
			else if(v =='fi_sub_formB'){ 
					//alert(response);
				//window.location.href = "intre_pri_9008_form.php";
				$('#forward_div').show();
				$('#fi_sub').hide();
				//$('#service_type').val('5B');
				$('#service_type').val('5');
				$('#sub_menu').val('5B');
			}
			else if(v =='fi_sub_formC'){
					//alert(response);
				//window.location.href = "intre_pri_9008_form.php";
				$('#forward_div').show();
				$('#fi_sub').hide();
				//$('#service_type').val('5C');
				$('#service_type').val('5');
				$('#sub_menu').val('5C');
			}
			else{
				$(".mbody").html(response);
				
				if(v == 'myForm_PREV'){ $("#fi_sub").val('fi_sub_form'); }
				else if(v == 'myForm_PREV_A'){ $("#fi_sub").val('fi_sub_formA'); }
				else if(v == 'myForm_PREV_B'){ $("#fi_sub").val('fi_sub_formB'); }
				else if(v == 'myForm_PREV_C'){ $("#fi_sub").val('fi_sub_formC'); }
			}
			
        },
        error:function(){
          alert('Server Error');
        }
      });
}
    
    </script>
    
 <!-- end add row -->


<div class="modal fade" id="gpprofModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 188%; margin-left: -44%;">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">9008-F(P)</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="fi_sub" value="" onclick="return sub_prev_9008(this.value)" > PROPOSAL SAVED </button>
		
		
		
	<div id="forward_div" style="display:none;">		
		<div class="row mb-3">
		<label for="inputPassword3" class="col-sm-6 col-form-label" style='margin-left: -155%; width: 85%;' >Forwarded To: </label>
		<div class="col-mb-3" >
		<input type="hidden" id="application_id_o" name="application_id_o" value="<?php if($data_9008[0]['application_id'] !=''){ echo $data_9008[0]['application_id']; } else if($data_9008_A[0]['application_id'] !=''){ echo $data_9008_A[0]['application_id']; } else if($data_9008_B[0]['application_id'] !=''){ echo $data_9008_B[0]['application_id']; } else if($data_9008_C[0]['application_id'] !=''){ echo $data_9008_C[0]['application_id']; } ?>" >
		<input type="hidden" id="service_type" name="service_type" value="" >
		<input type="hidden" id="emp_id_const" name="emp_id_const" value="<?php echo $data_9008[0]['emp_id_const']; ?>" />
		<input type="hidden" id="sub_menu" name="sub_menu" value="" />
		<input type="hidden" id="for_app_rej_status" name="for_app_rej_status" value="" >
		
		
		  <select class="form-control" id="forwarding" name="forwarding" style='margin-left: -60%;'>
			<option value="" >-- Please Select --</option>
			<?php	
			
			$forwarding_qury_ex = explode(',',$officer_name[0]['forwarding_user']);
			//var_dump($forwarding_qury_ex); die;
		foreach($forwarding_qury_ex as $val){
			$forward_user = $db->fetch_table(" SELECT desig.* , master.officer_id_const as officer_id_const, master.officer_name as officer_name 
			FROM intra_pri_designation_master as desig 
			INNER JOIN intra_pri_master as master ON master.stake_level_code= desig.designation_code
			WHERE master.stake_user_code = '".$val."' AND master.active_status='1' AND '5' = any( string_to_array( master.role_assign, ',' ) ) ");
					//var_dump($forward_user[0]['officer_id_const']);
			?>
				<option value="<?php echo $forward_user[0]['officer_id_const']; ?>" ><?php if($forward_user[0]['officer_id_const'] != NULL || $forward_user[0]['officer_id_const'] != ""){ echo $forward_user[0]['officer_name']."  (".$forward_user[0]['designation'].")"; }  ?></option>						
			<?php } ?>							
		  </select>
		</div>  
		</div> 


			<button type="button" class="btn btn-success" style="margin-top: -20%;" id="submit_app" onclick="return may_be_for(this.id);" > MAY BE APPROVED </button>
			<button type="button" class="btn btn-danger"  style="margin-top: -20%;" onclick="return may_be_rej(this.id);" > MAY BE REJECTED </button>
		  
		  
		<div class="row mb-3" style="display:none; margin-left: -118%;" id="remarks_div" > 
			<label for="inputPassword3" class="col-sm-2 col-form-label" >Reason: </label>
			<div class="col-sm-6">
				<textarea type="text" class="form-control" name="remarks_res" id="remarks_res" placeholder="Reason" rows="5" cols="40" style='margin-left: -9%;' ></textarea>
				<button type="button" class="btn btn-primary btn-sm" id="submit_rej" name="submit_rej" onclick="return may_be_rej_send(this.id);" > SEND </button>
			</div>
		</div>
	</div>
	
	
	
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade bs-example-modal-sm" id="delet" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 200%; margin-left: -58%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">9008-F(P)</h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
		<form action="ajax_intra_pri_9008_submit.php" method="post">
		<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Delete Document ?</strong></p>
    </div>
    <div class="modal-footer">
    
		<div class="btn-group">
		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
		<!--<input type="hidden" name="application_id" id="application_id" value="<?= $crypto->encode($data_9008[0]['application_id'],4); ?>" /> -->
		<input type="hidden" name="application_id" id="application_id" /> 
		<input type="hidden" id="delete_id" name="delete_id" />
		<input type="hidden" id="delete_f" name="delete_f" />
		<input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
		<button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
		</div>
    </div>
    </div>
    
    </form>
    </div>
</div>







<script>

function may_be_for(k){
	$("#remarks_div").hide();
	var officer_id_const = $("#forwarding").val();
	var application_id = $("#application_id_o").val();
	var service_type = $("#service_type").val();
	var remarks = $("#remarks_comm").val();
	var emp_id_const = $("#emp_id_const").val();
	var sub_menu = $("#sub_menu").val();
	var for_app_rej_status = 'MF';
	
	if($('#forwarding').val()==''){
		alert('Please Select Forwarding Officer.');
		$('#forwarding').focus();
		return false;
	}
	
		$.ajax({
				url : 'update_forward_intra_pri.php',
				type : 'POST',
				data : { "officer_id_const" : officer_id_const,
						"type": k,
						"service_type": service_type,
						"application_id": application_id,
						"for_app_rej_status": for_app_rej_status,
						"emp_id_const": emp_id_const,
						"sub_menu": sub_menu,
						"remarks": remarks },
							success : function(response) {
							alert(response);
							window.location.reload();
						}
				});		
}


function may_be_rej(k){
	$("#remarks_div").show();
	$("#comments_div").hide();
	$("#for_app_rej_status").val('MR');
}



function may_be_rej_send(k){
	//$("#remarks_div").show();
	var officer_id_const = $("#forwarding").val();
	var application_id = $("#application_id_o").val();
	var service_type = $("#service_type").val();
	var remarks = $("#remarks_res").val();
	var emp_id_const = $("#emp_id_const").val();
	var sub_menu = $("#sub_menu").val();
	var for_app_rej_status = $("#for_app_rej_status").val();

	if($('#forwarding').val()==''){
		alert('Please Select Forwarding Officer.');
		$('#forwarding').focus();
		return false;
	}	
		$.ajax({
				url : 'update_forward_intra_pri.php',
				type : 'POST',
				data : { "officer_id_const" : officer_id_const,
						"type": k,
						"service_type": service_type,
						"application_id": application_id,
						"for_app_rej_status": for_app_rej_status,
						"emp_id_const": emp_id_const,
						"sub_menu": sub_menu,
						"remarks": remarks },
							success : function(response) {
							alert(response);
							window.location.reload();
						}
				});		
}



</script>

 