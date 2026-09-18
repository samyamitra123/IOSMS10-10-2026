<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

    //var_dump($_SESSION);

error_reporting(0);
//ob_start();
session_start();
require '../../includes/config/config.php';
require '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require '../../includes/library/cryptography.class.php';

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

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";



//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

$crypto = new cryptography();
	function code_gp($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
		return $arr[0]['gp_name'];																															
	}
	
	function code_block($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT block_name FROM prd_location_master_block as b inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk
		 WHERE gp.gp_id_pk='".$val."'");
		return $arr[0]['block_name'];																															
	}
	
	function code_ps($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$val."'");
		return $arr[0]['ps_name'];																															
	}
	
	function code_district($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_id_pk ='".$val."'");
		return $arr[0]['district_name'];																															
	}
	
	function first_code_gp($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_code='".$val."'");
		return $arr[0]['gp_name'];																															
	}
	
	function first_code_block($val)
	{
		$db=new database();
		$arr =$db->fetch_table("SELECT block_name FROM prd_location_master_block as b inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk
		 WHERE gp.gp_code='".$val."'");
		return $arr[0]['block_name'];																															
	}
	
	function first_code_ps($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_code ='".$val."'");
		return $arr[0]['ps_name'];																															
	}
	
	function first_code_district($val)
	{ 
		$db=new database();
		$arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_code ='".$val."'");
		return $arr[0]['district_name'];																															
	}
	
	
	
?>
<style>
.form-horizontal .control-label {
  text-align:left;
	}
</style>
<script>

$(function() {
	$( "#date_of_notification_employee_notice" ).datepicker({
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0",
	dateFormat: 'dd-mm-yy',
	//minDate:dateToday	 
	});
});
 
		function Court_Case_Details(k)
		{
			if(k==1)
			{ //alert(55);
				$('#court_div').show();
				$('#court_case_attachment').show();
			}
			else
			{ //alert(666);
				$('#court_div').hide();
				$('#court_case_attachment').hide();
			}
		}



		function file_upload(k,j){
			
			var property = document.getElementById(k).files[0];
			var image_name = property.name;
			var image_extension = image_name.split('.').pop().toLowerCase();

			if(jQuery.inArray(image_extension,['pdf']) == -1){
			  alert("Invalid PDF file");
			}
			
			var form_data = new FormData();
			form_data.append("file",property);
			form_data.append('cg_id',$('#cg_id').val());
			form_data.append('flag_id',j);
			form_data.append('emp_id_const',$('#tch_emp_id').val());
			form_data.append('application_id',$('#application_id').val());
			
			  $.ajax({
				  url : 'ajax_intra_pri_overage_condonation_file_upload.php',
				  type : 'POST',
				  data:form_data,
				  contentType:false,
				  cache:false,
				  processData:false,
					success : function(data1) {
						  if(data1 == 1){
							alert('Document Uploaded Successfully....');
					  }
					},
					error:function(){
					  alert('Server Error');
					}
				  });
	  
		}
		
		
		
</script>

   <div class="content">
<?php require 'common_back_btns_intra_pri.php'; ?>
   <div class="welcome_msg">
	<?php
	//echo 33;die;
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT officer_name FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");

		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad">
<div class="col-sm-12" style="width:98%; padding-left:2%">
<h1 class="heading">Over Age Condonation</h1>
<div class="border"></div>
</br>
<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show">
	
	<?php 
	
	if(isset($_SESSION['user_info']['emp_id_const'])){
	$emp_id_detail_ocon = $db->fetch_table(" SELECT * FROM intra_pri_overage_condonation_master WHERE emp_id_const = '".$_SESSION['user_info']['emp_id_const']."'");
		//var_dump($emp_id_detail_ocon);
		unset($_SESSION['user_info']['emp_id_const']);	
	}
	
	if($_SESSION['msg']){
		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
	}
	?>  
	
	
<form name="myForm" id="myForm" method="post" class="w3_form_post" action="ajax_intra_pri_overage_condonation_submit.php" onsubmit="return validateForm()" enctype="multipart/form-data" >
<!--<form name="myForm" id="myForm" method="post" class="w3_form_post" enctype="multipart/form-data" >-->
<input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partA",4); ?>" />
	<div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Proposal Name</label>
		<div class="col-sm-8">
			<input type="text" class="form-control upper_case" id="proposal_id"  name="proposal_id" placeholder="Proposal for ovarage condonation in respect of i.r.o " value="<?php if(isset($emp_id_detail_ocon[0]['proposal'])){ echo $emp_id_detail_ocon[0]['proposal'];} ?>" readonly >
		</div>
	</div></br></br>
	
	<div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Application No.</label>
		<div class="col-sm-6">
			<input type="text" class="form-control upper_case" id="app_no"  name="app_no" placeholder="Application No." value="<?php if(isset($emp_id_detail_ocon[0]['application_id'])){ echo $emp_id_detail_ocon[0]['application_id'];} ?>" readonly >
		</div>
	</div>
  
    <div class="row mb-3">
    	<label for="inputEmail3" class="col-sm-3 col-form-label">Employee ID<span class="star_color">*</span></label>
    		<div class="col-sm-3">
				<input type="text" class="form-control upper_case" id="tch_emp_id"  name="tch_emp_id" placeholder="ID Search IOSMS" onKeyup="return emp_detail(this.value);" maxlength="12" value="<?php if(isset($emp_id_detail_ocon[0]['emp_id_const'])){ echo $emp_id_detail_ocon[0]['emp_id_const'];} ?>" >
    		</div>
	</div>
	<div id="number_msg" style="color: green;"></div>
	<div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Employee Name<span class="star_color">*</span></label>
		<div class="col-sm-3">
		  <input type="text" class="form-control upper_case" id="tch_fname"  name="tch_fname" placeholder="First Name" value="<?php if(isset($emp_id_detail_ocon[0]['emp_first_name'])){ echo $emp_id_detail_ocon[0]['emp_first_name'];} ?>" readonly>
		</div>
		<div class="col-sm-3">
		  <input type="text" class="form-control upper_case" id="tch_mname"  name="tch_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');"readonly value="<?php if(isset($emp_id_detail_ocon[0]['emp_second_name'])){ echo $emp_id_detail_ocon[0]['emp_second_name'];} ?>">
		</div>
		<div class="col-sm-3">
		  <input type="text" class="form-control upper_case" id="tch_lname"  name="tch_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" readonly value="<?php if(isset($emp_id_detail_ocon[0]['emp_last_name'])){ echo $emp_id_detail_ocon[0]['emp_last_name'];} ?>">
		</div>
	</div>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Date Of Birth<span class="star_color">*</span></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="tch_dob" name="tch_dob" placeholder="DD-MM-YYYY" readonly value="<?php if(isset($emp_id_detail_ocon[0]['emp_dob'])){ echo date("d-m-Y",strtotime($emp_id_detail_ocon[0]['emp_dob']));} ?>" />
    </div>
    <label for="inputPassword3" class="col-sm-3 control-label">Sex<span class="star_color">*</span></label>
    <div class="col-sm-3">

		<input type="hidden" name="drpSex" id="drpSex" value="<?php echo $emp_id_detail_ocon[0]['emp_sex']?>" />
		<input type="text" class="form-control upper_case" id="drpSex1"  name="drpSex1" placeholder="SEX" value="<?php 
		if(isset($emp_id_detail_ocon[0]['emp_sex']) == 91){ echo "Male"; }
			else if(isset($emp_id_detail_ocon[0]['emp_sex']) == 92){ echo "Female"; }
			else if(isset($emp_id_detail_ocon[0]['emp_sex']) == 93){ echo "Others"; } ?>" readonly>

    </div>
  </div>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Present Designation<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="vice_desig" id="vice_desig" value="<?php if(isset($emp_id_detail_ocon[0]['emp_desig'])){ echo $emp_id_detail_ocon[0]['emp_desig'];} ?>" />
		<input type="text" class="form-control upper_case" id="vice_desig1"  name="vice_desig1" placeholder="Present Designation" value="<?php if(isset($emp_id_detail_ocon[0]['emp_desig'])){ 
		
		$arr_des = $db->fetch_table("select code,description from prd_dise_code_master where code='".$emp_id_detail_ocon[0]['emp_desig']."' order by code");
		echo $arr_des[0]['description'];} ?>" readonly>
    </div>
	
	
<?php 

//var_dump(isset($emp_id_detail_ocon[0]['gp_id_fk']));
if($emp_id_detail_ocon[0]['gp_id_fk'] != 0 ){ ?>
	<label for="applylabellab" class="col-sm-3 col-form-label">Name of GP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="Name_of_GP_or_PS_posted_id" id="Name_of_GP_or_PS_posted_id" value="" />
		<input type="hidden" name="gp_ps_zp_identity" id="gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="Name_of_GP_or_PS_posted" id="Name_of_GP_or_PS_posted" readonly value="<?php echo code_gp($emp_id_detail_ocon[0]['gp_id_fk'] ); ?>">
    </div>
<?php } 
else if($emp_id_detail_ocon[0]['ps_id_fk'] != 0 ){ ?>

<label for="applylabellab" class="col-sm-3 col-form-label">Name of PS Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="Name_of_GP_or_PS_posted_id" id="Name_of_GP_or_PS_posted_id" value="" />
		<input type="hidden" name="gp_ps_zp_identity" id="gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="Name_of_GP_or_PS_posted" id="Name_of_GP_or_PS_posted" readonly value="<?php echo code_ps($emp_id_detail_ocon[0]['ps_id_fk'] ); ?>">
    </div>

<?php } 
else if($emp_id_detail_ocon[0]['zp_id_fk'] != 0 ){  ?>

<label for="applylabellab" class="col-sm-3 col-form-label">Name of ZP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="Name_of_GP_or_PS_posted_id" id="Name_of_GP_or_PS_posted_id" value="" />
		<input type="hidden" name="gp_ps_zp_identity" id="gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="Name_of_GP_or_PS_posted" id="Name_of_GP_or_PS_posted" readonly value="<?php echo code_district($emp_id_detail_ocon[0]['zp_id_fk'] ); ?>">
    </div>
	
<?php }
else{ ?>

    <label for="applylabellab" class="col-sm-3 col-form-label">Name of GP or PS or ZP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="Name_of_GP_or_PS_posted_id" id="Name_of_GP_or_PS_posted_id" value="" />
		<input type="hidden" name="gp_ps_zp_identity" id="gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="Name_of_GP_or_PS_posted" id="Name_of_GP_or_PS_posted" placeholder="Name of GP or PS or ZP posted" readonly value="">
    </div>
<?php } ?>
  </div>
  
  
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">First Designation<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_vice_desig" id="first_vice_desig" value="<?php if(isset($emp_id_detail_ocon[0]['emp_desig_first_app'])){ echo $emp_id_detail_ocon[0]['emp_desig_first_app'];} ?>" />
		<input type="text" class="form-control upper_case" id="first_vice_desig1"  name="first_vice_desig1" placeholder="First Designation" value="<?php if(isset($emp_id_detail_ocon[0]['emp_desig_first_app'])){ 
		
		$arr_des = $db->fetch_table("select code,description from prd_dise_code_master where code='".$emp_id_detail_ocon[0]['emp_desig_first_app']."' order by code");
		echo $arr_des[0]['description'];} ?>" readonly>
    </div>
	
	
<?php 

//var_dump(strlen($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']));
if(strlen($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']) == 10){  ?>
	<label for="first_applylabellab" class="col-sm-3 col-form-label">First GP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" readonly value="<?php echo first_code_gp($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code'] ); ?>">
    </div>
<?php } 
else if(strlen($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']) == 7 ){ ?>

<label for="first_applylabellab" class="col-sm-3 col-form-label">First PS Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" readonly value="<?php echo first_code_ps($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code'] ); ?>">
    </div>

<?php } 
else if(strlen($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']) == 4 ){  ?>

<label for="first_applylabellab" class="col-sm-3 col-form-label">First ZP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" readonly value="<?php echo first_code_district($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code'] ); ?>">
    </div>
	
<?php }
else{ ?>

    <label for="first_applylabellab" class="col-sm-3 col-form-label">First GP or PS or ZP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" placeholder="First GP or PS or ZP posted" readonly value="">
    </div>
<?php } ?>
  </div>
  
  
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">First Appointment Order No.<span class="star_color">*</span></label>
		<div class="col-sm-3">
			<input type="text" class="form-control upper_case" id="Appointment_MEMO"  name="Appointment_MEMO" placeholder="First Appointment Order No." readonly value="<?php if(isset($emp_id_detail_ocon[0]['emp_first_memo_no'])){ echo $emp_id_detail_ocon[0]['emp_first_memo_no'];} ?>">
		</div>
     <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Joining in the First Posting<span class="star_color">*</span></label>

    <div class="col-sm-3">
      <input type="text" class="form-control upper_case"  name="Date_of_first_Posting" id="Date_of_first_Posting" placeholder="Date of Joining in the First Posting" readonly value="<?php if(isset($emp_id_detail_ocon[0]['emp_first_join_date'])){ echo date("d-m-Y",strtotime($emp_id_detail_ocon[0]['emp_first_join_date']));} ?>">
    </div>
  </div>
  
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Catagory : <span class="star_color">*</span></label>
		<div class="col-sm-3">
			<input type="hidden" name="catagory_id" id="catagory_id" value="" />
			<input type="text" class="form-control upper_case" id="catagory"  name="catagory" placeholder="Catagory" readonly value="<?php if(isset($emp_id_detail_ocon[0]['emp_caste'])){ 
			$arr_catagory = $db->fetch_table("select code,description from prd_dise_code_master where code='".$emp_id_detail_ocon[0]['emp_caste']."' order by code");
				echo $arr_catagory[0]['description'];
		} ?>">
		</div>
  </div>
  
  
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Name of Appointing Authority <span class="star_color">*</span></label>
	<div class="col-sm-3">
		<input type="text" class="form-control" id="Appointment_Authorization"  name="Appointment_Authorization" placeholder="Name of Appointing Authority" value="<?php if(isset($emp_id_detail_ocon[0]['appoinment_authority'])){ echo $emp_id_detail_ocon[0]['appoinment_authority'];} ?>">
	</div>
     <label for="inputPassword3" class="col-sm-3 col-form-label">Date of Employement Notification/ Employement Exchange Call Letter Date<span class="star_color">*</span></label>

    <div class="col-sm-3">
      <input type="text" class="form-control upper_case" id="date_of_notification_employee_notice"  name="date_of_notification_employee_notice" placeholder="DD-MM-YYYY" value="<?php if(isset($emp_id_detail_ocon[0]['notice_date'])){ echo date("d-m-Y",strtotime($emp_id_detail_ocon[0]['notice_date']));} ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Wheather Appointment is based on Court Case Order <span class="star_color">*</span></label>
    <div class="col-sm-3">
    <select class="form-control" name="court_case" id="court_case" onChange="return Court_Case_Details(this.value);">
		<option value="">Please Select</option>
		<option value="1" <?php if($emp_id_detail_ocon[0]['court_case']=='1') { echo "selected"; } ?>>YES</option>
		<option value="0" <?php if($emp_id_detail_ocon[0]['court_case']=='0') { echo "selected"; } ?>>NO</option>

    </select>
    </div>
	
	<label for="inputPassword3" class="col-sm-3 col-form-label">Age to be Condon<span class="star_color">*</span></label>

    <div class="col-sm-3">
      <table name="age_condon" id="age_condon" width="100%">   
        <tr id="tr0">
          <td><input type="text" size="8" name="age_condon_year" id="age_condon_year" placeholder="Year" onKeyPress="return keyRestrict(event,'1234567890')" maxlength="2" value="<?php if(isset($emp_id_detail_ocon[0]['condon_year'])){ echo $emp_id_detail_ocon[0]['condon_year'].' Years';} ?>" /></td>
          <td><input type="text" size="8" name="age_condon_month" id="age_condon_month" placeholder="Month" onKeyPress="return keyRestrict(event,'1234567890')" maxlength="2" value="<?php if(isset($emp_id_detail_ocon[0]['condon_month'])){ echo $emp_id_detail_ocon[0]['condon_month'].' Months';} ?>" /></td>
          <td><input type="text" size="8" name="age_condon_days" id="age_condon_days" placeholder="Days" onKeyPress="return keyRestrict(event,'1234567890')" maxlength="2" value="<?php if(isset($emp_id_detail_ocon[0]['condon_days'])){ echo $emp_id_detail_ocon[0]['condon_days'].' Days';} ?>" /></td>
        </tr>    
     </table>
    </div>
  </div>
  <div class="row mb-3" <?php if($emp_id_detail_ocon[0]['court_case'] == 0 || $emp_id_detail_ocon[0]['court_case'] == '' ){ ?> style="display:none;" <?php } ?> id="court_div">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Court Case Details<span class="star_color">*</span></label>
    <div class="col-sm-3">
       <textarea type="text" class="form-control " name="case_details" id="case_details" placeholder="Court Case Details" rows="5" cols="40" ><?php if(isset($emp_id_detail_ocon[0]['court_case_details'])){ echo $emp_id_detail_ocon[0]['court_case_details'];} ?></textarea>
    </div>
  </div>
  
  <?php if($emp_id_detail_ocon =='' && $emp_id_detail_ocon == null){ ?>
  <div class="row mb-3" style="margin-left: 43%; margin-top: 4%;">
		<div class="col-sm-offset-5 col-sm-7">
			<button class="btn btn-info" type="submit" id="submit" name="submit">SAVE & CONTINUE</button>
			<!--<button class="btn btn-info" type="submit" id="submit" name="submit">SUBMIT & PREVIEW </button>-->
		</div>
	</div>
  <?php }
  if($emp_id_detail_ocon !='' && $emp_id_detail_ocon!= null){?>
<div class="border"></div>
	</br>
	<h5 class="heading"> Document to be Uploaded</h5>
	</br>
	
	<input type="hidden" name="form_flage" id="form_flage" value="<?= $crypto->encode("partB",4); ?>" /> 
	<input type="hidden" name="cg_id" id="cg_id" value="<?= $crypto->encode($emp_id_detail_ocon[0]['overage_condonation_pk'],4); ?>" /> 
	<input type="hidden" name="application_id" id="application_id" value="<?= $crypto->encode($emp_id_detail_ocon[0]['application_id'],4); ?>" /> 
	<div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label">First Appointment Letter <span class="star_color">*</span></label>
		<div class="col-sm-4">
		<?php 
		$db = new database();
		$arr_file_20 = $db->fetch_table("select * from intra_pri_file_upload where emp_id_const = '".$emp_id_detail_ocon[0]['emp_id_const']."' AND status = '1' AND flag= 20 AND application_id= '".$emp_id_detail_ocon[0]['application_id']."' "); 
		if($arr_file_20[0]['file_name']==''){?>
		  <input type="file" class="form-control " autocomplete="off" name="appointment_letter"  id="appointment_letter" value="" onchange="return file_upload(this.id, 20);">
		<?php } 
		else {?>
		  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_20[0]['file_name'],6) ;  ?></label>
		<?php } ?>
		</div>
		<div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("20",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
	</div>
	<div class="row mb-3">
	<div class="col-sm-5"></div>
		<div class="col-sm-offset-5 col-sm-7">
		  <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
		</div>
	</div>
  
	
	<div class="row mb-3">
		<div class="col-sm-2"></div>
		<label for="inputPassword3" class="col-sm-3 control-label">First Joining Letter Duly Accepted<span class="star_color">*</span></label>
		<div class="col-sm-4">
		<?php 
		$db = new database();
		$arr_file_21 = $db->fetch_table("select * from intra_pri_file_upload where emp_id_const = '".$emp_id_detail_ocon[0]['emp_id_const']."' AND status = '1' AND flag= 21 AND application_id= '".$emp_id_detail_ocon[0]['application_id']."' "); 
		 if($arr_file_21[0]['file_name']==''){?>
		  <input type="file" class="form-control " autocomplete="off" name="joining_letter"  id="joining_letter" value="" onchange="return file_upload(this.id, 21);" >
		<?php } 
		else{?>
		  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_21[0]['file_name'],6) ;  ?></label>
		<?php } ?>
		</div>
		<div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("21",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
	<div class="col-sm-5"></div>
		<div class="col-sm-offset-5 col-sm-7">
		  <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
		</div>
	</div>
    
  
  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Proof of Date of Birth<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_22 = $db->fetch_table("select * from intra_pri_file_upload where emp_id_const = '".$emp_id_detail_ocon[0]['emp_id_const']."' AND status = '1' AND flag= 22 AND application_id= '".$emp_id_detail_ocon[0]['application_id']."' "); 
		if($arr_file_22[0]['file_name']==''){ ?>
      <input type="file" class="form-control " autocomplete="off" name="date_of_birth"  id="date_of_birth" value="" onchange="return file_upload(this.id, 22);" >
		<?php }
			else{ ?>
	  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_22[0]['file_name'],6) ;  ?></label>
			<?php } ?>
    </div>
   <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("22",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
	<div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  

<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Employee Notification / Employment Exchange Call Letter<span class="star_color">*</span></label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_23 = $db->fetch_table("select * from intra_pri_file_upload where emp_id_const = '".$emp_id_detail_ocon[0]['emp_id_const']."' AND status = '1' AND flag= 23 AND application_id= '".$emp_id_detail_ocon[0]['application_id']."' "); 
		if($arr_file_23[0]['file_name']==''){?>
      <input type="file" class="form-control " autocomplete="off" name="call_letter"  id="call_letter" value="" onchange="return file_upload(this.id, 23);" >
		<?php } 
		else{ ?>
	  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_23[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("23",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
	<div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>

<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Others </label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_24 = $db->fetch_table("select * from intra_pri_file_upload where emp_id_const = '".$emp_id_detail_ocon[0]['emp_id_const']."' AND status = '1' AND flag= 24 AND application_id= '".$emp_id_detail_ocon[0]['application_id']."' "); 
		if($arr_file_24[0]['file_name']==''){?>
      <input type="file" class="form-control " autocomplete="off" name="others"  id="others" value="" onchange="return file_upload(this.id, 24);" >
		<?php } 
		else{ ?>
	  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_24[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("24",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
	<div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  
  
  <!--<div id="court_case_attachment" style="display:none;">-->
  <div id="court_case_attachment">
  <div class="row mb-3" >
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Court Case Details</label>
    <div class="col-sm-4">
	<?php 
		$db = new database();
		$arr_file_25 = $db->fetch_table("select * from intra_pri_file_upload where emp_id_const = '".$emp_id_detail_ocon[0]['emp_id_const']."' AND status = '1' AND flag= 25 AND application_id= '".$emp_id_detail_ocon[0]['application_id']."' "); 
		if($arr_file_25[0]['file_name']==''){ ?>
      <input type="file" class="form-control " autocomplete="off" name="Case_Details"  id="Case_Details" value="" onchange="return file_upload(this.id, 25);">
		<?php } 
		else{ ?>
	  <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_25[0]['file_name'],6) ;  ?></label>
		<?php } ?>
    </div>
    <div class="col-sm-1"><a id="del"  onclick="del('<?php echo $crypto->encode("25",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File</a></div>
    </div>
    <div class="row mb-3">
	<div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
  </div>
  </div>
  
  <div class="row mb-3">
  <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label"> Brief Note <span class="star_color">*</span></label>
    <div class="col-sm-3">
     <textarea type="text" class="form-control " name="case_history" id="case_history" placeholder="Brief Note" rows="5" cols="40"  ><?php if(isset($emp_id_detail_ocon[0]['case_history'])){ echo $emp_id_detail_ocon[0]['case_history'];} ?></textarea>
    </div>
  </div> 
  <div class="row mb-3">
  <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 col-form-label">Observation <span class="star_color">*</span></label>
    <div class="col-sm-3">
     <textarea type="text" class="form-control " name="observation" id="observation" placeholder="Observation" rows="5" cols="40" ><?php if(isset($emp_id_detail_ocon[0]['observation'])){ echo $emp_id_detail_ocon[0]['observation'];} ?></textarea>
    </div>
  </div> 

    
  			
	<div class="row mb-3" style="margin-left: 41%;">
		<div class="col-sm-offset-5 col-sm-7">
		<?php if($emp_id_detail_ocon[0]['case_history'] == '' || $emp_id_detail_ocon[0]['observation'] == '' ){ ?>	
			<button class="btn btn-info" type="submit" id="submit" name="submit" >SAVE & CONTINUE </button>
		<?php }
		else if($emp_id_detail_ocon[0]['case_history'] != '' && $emp_id_detail_ocon[0]['observation'] != '' && $emp_id_detail_ocon[0]['active_status'] == '' ){ ?>
			<a type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" onclick="return sub_prev(this.id)" > PREVIEW FOR FINAL SUBMIT </a>
		<?php }
		else if($emp_id_detail_ocon[0]['active_status'] == '1'){?>
			<a type="button" class="btn btn-info" style="width: 50%;" data-bs-toggle="modal" data-bs-target="#gpprofModal" onclick="return sub_prev(this.id)" > PREVIEW </a>
		<?php } ?>			
		</div>
	</div>
	<?php } ?>
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


<!--  -->

<script>

function validateForm(){
	if($('#tch_emp_id').val()==''){
					alert('Please Enter Employee ID.');
					$('#tch_emp_id').focus();
					return false;
				}
			else if($('#tch_fname').val()=='' ){
					alert('Please Enter Your FIRST Name.');
					$('#tch_fname').focus();
					return false;
				}
			
				else if($('#tch_dob').val()==''){
					alert('Please Enter Date of Birth.');
					$('#tch_dob').focus();
					return false;
				}
			

				else if($('#drpSex').val()==''){
					alert('Please Select Your sex.');
					$('#drpSex').focus();
					return false;
				}
				else if($('#Appointment_Authorization').val() == ''){
					alert('Please Select Your Appointment Authority .');
					$('#Appointment_Authorization').focus();
					return false;
				}
				else if($('#date_of_notification_employee_notice').val() == ''){
					alert('Please Select Date of Notification of Employement Notice/ Employement Exchange Call Letter Date .');
					$('#date_of_notification_employee_notice').focus();
					return false;
				}
				else if($('#court_case').val()=='')
				{
					alert('Please select Court Case.');
					$('#court_case').focus();
					return false;
				}
				else if($('#court_case').val()=='1')
				{
					if($('#case_details').val()=='')
					{
						alert('Please enter Court Case Details.');
						$('#case_details').focus();
						return false;
					}
					else{
						return true;
					}
				}
	
}


function emp_detail(k){ //alert(k);


		$.ajax({
				url : 'ajax_emp_master.php',
				type : 'POST',
				data : { "emp_id_const" : k },
					success : function(response) { 
					var result = $.parseJSON(response);
						//alert(result);
						if(result[14] != '' && result[14] != null ){ //alert(666); 
							window.location.reload();
						} 
						
						$("#tch_fname").val(result[0]);
						$("#tch_mname").val(result[1]);
						$("#tch_lname").val(result[2]);
						$("#tch_dob").val(result[3]);
						$("#drpSex").val(result[4]);
						$("#vice_desig1").val(result[5]);
						$("#vice_desig").val(result[6]);
						$("#first_vice_desig").val(result[16]);
						$("#first_vice_desig1").val(result[17]);
						$("#Date_of_first_Posting").val(result[10]);
						$("label[for='applylabellab']").text(result[12]);
						$("label[for='first_applylabellab']").text(result[20]);
						$("#Name_of_GP_or_PS_posted").val(result[7]);
						$("#Name_of_GP_or_PS_posted_id").val(result[8]);
						$("#Appointment_MEMO").val(result[9]);
						$("#drpSex1").val(result[11]);
						$("#gp_ps_zp_identity").val(result[13]);
						$("#first_GP_or_PS_posted").val(result[18]);
						$("#first_GP_or_PS_posted_id").val(result[15]);
						$("#first_gp_ps_zp_identity").val(result[21]);
						$("#catagory_id").val(result[22]);
						$("#catagory").val(result[23]);
						$("#proposal_id").val("Proposal for ovarage condonation in respect of i.r.o "+result[0]+" "+result[1]+" "+result[2]);
					}
				});

}


function sub_prev(v){
  //alert(v);
  
	var emp_id_const = $("#tch_emp_id").val();
	//var case_history = $("#case_history").val();
	//var observation = $("#observation").val();
	var cg_id = $("#cg_id").val();
	var app_no = $("#app_no").val();

		if($('#case_history').val()==''){
					alert('Please Enter Brif Note.');
					$('#case_history').focus();
					return false;
				}
		else if($('#observation').val()=='' ){
					alert('Please Enter Observation.');
					$('#observation').focus();
					return false;
				}
				
				
  $.ajax({
      url : 'ajax_intra_pri_overage_condonation_submit_last.php',
      type : 'POST',
      data : { "emp_id_const" : emp_id_const,
				"cg_id" : cg_id,
				"app_no" : app_no,
				"fi_sub" : v
				},
        success : function(response) { //alert(v);
			//var result1 = $.parseJSON(response);
			
				if(v =='fi_sub'){
					//alert(response);
				window.location.href = "intre_pri_overage_condonation_form.php";

			}
			else{
				$(".mbody").html(response);
			}
			
        },
        error:function(){
          alert('Server Error');
        }
      });
}

function del(k,l){
		var delete_f=$("#delete_f").val(k);
		var delete_id=$("#delete_id").val(l);
		
		$('#delet').modal('toggle');
	};
		
</script>


<div class="modal fade" id="gpprofModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<style>
.modal-backdrop fade in{
	height:auto !important;
}
</style>
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 150%; margin-left: -25%;">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">OVER AGE CONDONATION</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
	  <?php if($emp_id_detail_ocon[0]['active_status'] == ''){?>
        <button type="button" class="btn btn-success" id="fi_sub" onclick="return sub_prev(this.id)" > PROPOSAL SAVED </button>
	  <?php } ?>		
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade bs-example-modal-sm" id="delet" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
   		 <div class="modal-dialog modal-sm">
   			 <div class="modal-content" style="width: 200%; margin-left: -58%;">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">OVERAGE CONDONATION APPLICATION</h4>
    					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
   				 </div>
    <div class="modal-body"> 
		<form action="ajax_intra_pri_overage_condonation_submit.php" method="post">
		<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Delete Document ?</strong></p>
    </div>
    <div class="modal-footer">
    
		<div class="btn-group">
		<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
		
		<input type="hidden" id="delete_id" name="delete_id" />
		<input type="hidden" id="emp_id_const" name="emp_id_const" value="<?php echo $emp_id_detail_ocon[0]['emp_id_const']; ?>" />
		<input type="hidden" id="delete_f" name="delete_f" />
		<input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
		<button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
		</div>
    </div>
    </div>
    
    </form>
    </div>
    </div>
