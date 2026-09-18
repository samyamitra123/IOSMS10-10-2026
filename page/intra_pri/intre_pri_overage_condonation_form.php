<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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
//print_r($_SESSION['user_info']); exit;
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
		$Query = "SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_code='".$val."'";
		//return $Query;
		
		$arr =$db->fetch_table($Query);
		//print($arr); exit;
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

	function first_code_gp1($val)
	{
	$db=new database();
		$Query = "SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'";
		//return $Query;
		
		$arr =$db->fetch_table($Query);
		//print($arr); exit;
		return $arr[0]['gp_name'];	
	}
	
?>
<style>
.form-horizontal .control-label {
  text-align:left;
	}
	
	
.form_panal {
    background-color: #87bff6;
    text-align: center;
    padding: 8px 10px;
    border-radius: 4px;
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
			//form_data.append('cg_id',$('#cg_id').val());
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
						  if(data1 == 1)
							alert('Document Uploaded Successfully....');
						  else 
						  	alert(data);
					  
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


	//echo $_SESSION['user_info']['emp_id_const'];exit;
		$db = new database();
		$officer_name = $db->fetch_table(" SELECT * FROM intra_pri_master WHERE mobile_no = '".$_SESSION['user_info']['stake_user_mob']."' ");

		?>
		<h2><b>WELCOME TO <?php echo $_SESSION['user_info']['stake_level']; ?> LOGIN</b></h2>
		<h3> <?php echo $officer_name[0]['officer_name']; ?></h3>
    </div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
<div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad">

<h1 class="heading">Over Age Condonation</h1>
<div class="border"></div>

<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>

	
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

<!--<div class="container px-5 my-5" style="background-image: url('img/biswa_bangla_1.png'); height: 100%;" >-->	
<div class="container px-5 my-5 form_panal">

<form name="myForm" id="myForm" method="post" class="w3_form_post" action="ajax_intra_pri_overage_condonation_submit.php" onsubmit="return validateForm()" enctype="multipart/form-data" >
	<div class="row mb-3">
		<label for="inputEmail3" class="col-sm-3 col-form-label">Proposal Name</label>
		<div class="col-sm-8">
			<input type="text" class="form-control upper_case" id="proposal_id"  name="proposal_id" placeholder="Proposal for over age condonation in respect of i.r.o " value="<?php if(isset($emp_id_detail_ocon[0]['proposal'])){ echo $emp_id_detail_ocon[0]['proposal'];} ?>" readonly >
		</div>
	</div></br></br>
	
	
	 <!--<div class="form-floating mb-3">
            <input class="form-control" id="emailAddress" type="email" placeholder="Email Address" data-sb-validations="email,required" />
            <label for="emailAddress">Email Address</label>
        </div>-->
		
	
<!--<div class="form-floating mb-3">
			<input type="text" class="form-control upper_case" id="app_no"  name="app_no" placeholder="Application No." value="<?php if(isset($emp_id_detail_ocon[0]['application_id'])){ echo $emp_id_detail_ocon[0]['application_id'];} ?>" readonly >
		<label for="app_no">Application No.</label>
	</div>-->
  

  
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
		if(isset($emp_id_detail_ocon[0]['emp_sex']) && $emp_id_detail_ocon[0]['emp_sex'] == 91){ echo "Male"; }
			else if(isset($emp_id_detail_ocon[0]['emp_sex']) && $emp_id_detail_ocon[0]['emp_sex'] == 92){ echo "Female"; }
			else if(isset($emp_id_detail_ocon[0]['emp_sex']) && $emp_id_detail_ocon[0]['emp_sex'] == 93){ echo "Others"; } ?>" readonly>

    </div>
  </div>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Present Designation <span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="vice_desig" id="vice_desig" value="<?php if(isset($emp_id_detail_ocon[0]['emp_desig'])){ echo $emp_id_detail_ocon[0]['emp_desig'];} ?>" />
		<input type="text" class="form-control upper_case" id="vice_desig1"  name="vice_desig1" placeholder="Present Designation" value="<?php if(isset($emp_id_detail_ocon[0]['emp_desig'])){
			//////Code Change SAMYA//////

		 if($emp_id_detail_ocon[0]['gp_id_fk'] != 0 && $emp_id_detail_ocon[0]['zp_id_fk'] == 0 && $emp_id_detail_ocon[0]['ps_id_fk'] == 0)
		 {
		 	$arr_des = $db->fetch_table("select code,description from prd_dise_code_master where code='".$emp_id_detail_ocon[0]['emp_desig']."' order by code");
		 	//print_r($arr_des);
		 	 echo $arr_des[0]['description'];
		 }
		 elseif($emp_id_detail_ocon[0]['zp_id_fk'] != 0)
		 {
		 $arr_des = $db->fetch_table("select designation_id,designation_name from zpemp_emp_desig_master where designation_id='".$emp_id_detail_ocon[0]['emp_desig']."' order by designation_id");
		 echo $arr_des[0]['designation_name'];
		 }
		 else
		 {
		 	$arr_des = $db->fetch_table("select code,description from prd_dise_code_master where code='".$emp_id_detail_ocon[0]['emp_desig']."' order by code");
		 	//print_r($arr_des);
		 	 echo $arr_des[0]['description'];
		 }
		 //////Code Change SAMYA//////

		} ?>" readonly>
    </div>
	
	
<?php 
if ($emp_id_detail_ocon[0]['gp_id_fk'] != 0) { 
    if ($emp_id_detail_ocon[0]['ps_id_fk'] != 0) { ?>
    
        <label for="applylabellab" class="col-sm-3 col-form-label">Name of PS Posted<span class="star_color">*</span></label>
        <div class="col-sm-3">
            <input type="hidden" name="Name_of_GP_or_PS_posted_id" id="Name_of_GP_or_PS_posted_id" value="" />
            <input type="hidden" name="gp_ps_zp_identity" id="gp_ps_zp_identity" value="" />
            <input type="text" class="form-control upper_case" name="Name_of_GP_or_PS_posted" 
                   id="Name_of_GP_or_PS_posted" readonly 
                   value="<?php echo code_ps($emp_id_detail_ocon[0]['ps_id_fk']); ?>">

        </div>

    <?php } else { ?>

        <label for="applylabellab" class="col-sm-3 col-form-label">Name of GP Posted<span class="star_color">*</span></label>
        <div class="col-sm-3">
            <input type="hidden" name="Name_of_GP_or_PS_posted_id" id="Name_of_GP_or_PS_posted_id" value="" />
            <input type="hidden" name="gp_ps_zp_identity" id="gp_ps_zp_identity" value="" />
            <input type="text" class="form-control upper_case" name="Name_of_GP_or_PS_posted" 
                   id="Name_of_GP_or_PS_posted" readonly 
                   value="<?php echo code_gp($emp_id_detail_ocon[0]['gp_id_fk']); ?>">
        </div>

    <?php } 
}  

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
			//////Code Change SAMYA//////
		 $cnt = strlen($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']);
		 	if($cnt==10)
		 	{
		 		$arr_des = $db->fetch_table("select code,description from prd_dise_code_master where code='".$emp_id_detail_ocon[0]['emp_desig_first_app']."' order by code");
		 		echo $arr_des[0]['description'];

		 	}
		 	elseif($cnt==4)
		 	{
		 		$arr_des = $db->fetch_table("select designation_id,designation_name from zpemp_emp_desig_master where designation_id='".$emp_id_detail_ocon[0]['emp_desig_first_app']."' order by designation_id");
		 		echo $arr_des[0]['designation_name'];
		 	}
		 	elseif($cnt==7)
		 	{
		 		$arr_des = $db->fetch_table("select code,description from prd_dise_code_master where code='".$emp_id_detail_ocon[0]['emp_desig_first_app']."' order by code");
		 		echo $arr_des[0]['description'];
		 	}
		 		//////Code Change SAMYA//////
		 
		/*$arr_des = $db->fetch_table("select code,description from prd_dise_code_master where code='".$emp_id_detail_ocon[0]['emp_desig_first_app']."' order by code");*/
		
		//echo $arr_des[0]['designation_name'];

	} ?>" readonly>
    </div>
	
	
<?php 

if($emp_id_detail_ocon[0]['emp_first_tier'] == 1 ){  

//echo "samya";exit;

if($emp_id_detail_ocon[0]['gp_id_fk'] != 0 )
{
	?>
	<label for="first_applylabellab" class="col-sm-3 col-form-label">First GP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="<?php echo $emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']; ?>" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" readonly value="<?php echo first_code_gp($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code'] ); ?>">
    </div>
<?php }

else if($emp_id_detail_ocon[0]['zp_id_fk'] != 0 ){ 


	?>
<label for="first_applylabellab" class="col-sm-3 col-form-label">First ZP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="<?php echo $emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']; ?>" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" readonly value="<?php echo first_code_district($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code'] ); ?>">
    </div>

<?php }
}
else if($emp_id_detail_ocon[0]['emp_first_tier'] == 2 )
{
if($emp_id_detail_ocon[0]['gp_id_fk'] != 0 )

{ ?>
<label for="first_applylabellab" class="col-sm-3 col-form-label">First GP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="<?php echo $emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']; ?>" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" readonly value="<?php echo first_code_gp1($emp_id_detail_ocon[0]['gp_id_fk'] ); ?>">
    </div>
    <?php

} else { ?>
<label for="first_applylabellab" class="col-sm-3 col-form-label">First PS Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="<?php echo $emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']; ?>" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" readonly value="<?php echo first_code_ps($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code'] ); ?>">
    </div>

<?php } } 
else if($emp_id_detail_ocon[0]['emp_first_tier'] == 3 ){  ?>

<label for="first_applylabellab" class="col-sm-3 col-form-label">First ZP Posted<span class="star_color">*</span></label>
    <div class="col-sm-3">
		<input type="hidden" name="first_GP_or_PS_posted_id" id="first_GP_or_PS_posted_id" value="<?php echo $emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code']; ?>" />
		<input type="hidden" name="first_gp_ps_zp_identity" id="first_gp_ps_zp_identity" value="" />
		<input type="text" class="form-control upper_case"  name="first_GP_or_PS_posted" id="first_GP_or_PS_posted" readonly value="<?php echo code_district($emp_id_detail_ocon[0]['emp_first_gp_ps_zp_code'] ); ?>">
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
		<small>Note: Fill up without special character even not ,/.</small>
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
          <td>
              <select name="age_condon_year" id="age_condon_year" required>
              	<option value="35">Year</option>
              	<?php for($counter=35;$counter<=59;$counter++){ ?>
              		<option value="<?php echo $counter; ?>"   <?php echo (isset($emp_id_detail_ocon[0]['condon_year']) && $emp_id_detail_ocon[0]['condon_year'] == $counter)?"selected":""; ?>><?php echo $counter; ?> Years</option>
              	<?php } ?>
              </select>
          </td>
          <td>
  
          	 <select name="age_condon_month" id="age_condon_month" required>
              	<option value="0">Month</option>
              	<?php for($counter=0;$counter<=12;$counter++){ ?>
              		<option value="<?php echo $counter; ?>"   <?php echo (isset($emp_id_detail_ocon[0]['condon_month']) && $emp_id_detail_ocon[0]['condon_month'] == $counter)?"selected":""; ?>><?php echo $counter; ?> <?php echo ($counter <= 1)?'Month':'Months'; ?></option>
              	<?php } ?>
              </select>
          </td>
          <td>
          	 <select name="age_condon_days" id="age_condon_days" required>
              	<option value="0">Day</option>
              	
              	<?php for($counter=0;$counter<=31;$counter++){ ?>
              		<option value="<?php echo $counter; ?>"   <?php echo (isset($emp_id_detail_ocon[0]['condon_days']) && $emp_id_detail_ocon[0]['condon_days'] == $counter)?"selected":""; ?>><?php echo $counter; ?> <?php echo ($counter <= 1)?'Day':'Days'; ?></option>
              	<?php } ?>
              </select>
          </td>
        </tr>    
     </table>
    </div>
  </div>
  <div class="row mb-3" <?php if($emp_id_detail_ocon[0]['court_case'] == 0 || $emp_id_detail_ocon[0]['court_case'] == '' ){ ?> style="display:none;" <?php } ?> id="court_div">
    <label for="inputPassword3" class="col-sm-3 col-form-label">Court Case Details<span class="star_color">*</span></label>
    <div class="col-sm-3">
       <input type="text" class="form-control " name="case_details" id="case_details" placeholder="Court Case Details" value="<?php if(isset($emp_id_detail_ocon[0]['court_case_details'])){ echo $emp_id_detail_ocon[0]['court_case_details'];} ?>">
       <small>Note: Don't use special character "/=:" in text box</small>
    </div>
  </div>
  
  <?php 
  
  if($emp_id_detail_ocon =='' && $emp_id_detail_ocon == null){ ?>
  <div class="row mb-3" style="margin-left: 30%; margin-top: 4%;">
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
	
<input type="hidden" name="application_id" id="application_id" value="<?= $crypto->encode($emp_id_detail_ocon[0]['application_id'],4); ?>" />
<?php if($emp_id_detail_ocon[0]['application_status'] == 0): ?>
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
    <div class="col-sm-1"><a id="del" onclick="del('<?php echo $crypto->encode(" 20",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File
        </a></div>
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
    <div class="col-sm-1"><a id="del" onclick="del('<?php echo $crypto->encode(" 21",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File
        </a></div>
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
    <div class="col-sm-1"><a id="del" onclick="del('<?php echo $crypto->encode(" 22",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File
        </a></div>
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
    <div class="col-sm-1"><a id="del" onclick="del('<?php echo $crypto->encode(" 23",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File
        </a></div>
</div>
<div class="row mb-3">
    <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
        <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
</div>
<div id="court_case_attachment">
    <div class="row mb-3">
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
        <div class="col-sm-1"><a id="del" onclick="del('<?php echo $crypto->encode(" 25",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File
            </a></div>
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
    <label for="inputPassword3" class="col-sm-3 control-label">Caste Certificate</label>
    <div class="col-sm-4">
        <?php 
		$db = new database();
		$arr_file_26 = $db->fetch_table("select * from intra_pri_file_upload where emp_id_const = '".$emp_id_detail_ocon[0]['emp_id_const']."' AND status = '1' AND flag= 26 AND application_id= '".$emp_id_detail_ocon[0]['application_id']."' "); 
		if($arr_file_26[0]['file_name']==''){ ?>
        <input type="file" class="form-control " autocomplete="off" name="Caste_Certificate"  id="Caste_Certificate" value="" onchange="return file_upload(this.id, 26);">
        <?php } 
		else{ ?>
        <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_26[0]['file_name'],6) ;  ?></label>
        <?php } ?>
    </div>
    <div class="col-sm-1"><a id="del" onclick="del('<?php echo $crypto->encode(" 26",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File
        </a></div>
</div>
<div class="row mb-3">
    <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
        <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Service Book Record Copy</label>
    <div class="col-sm-4">
        <?php 
		$db = new database();
		$arr_file_38 = $db->fetch_table("select * from intra_pri_file_upload where emp_id_const = '".$emp_id_detail_ocon[0]['emp_id_const']."' AND status = '1' AND flag= 38 AND application_id= '".$emp_id_detail_ocon[0]['application_id']."' "); 
		if($arr_file_38[0]['file_name']==''){ ?>
        <input type="file" class="form-control " autocomplete="off" name="Service_Book_Copy"  id="Service_Book_Copy" value="" onchange="return file_upload(this.id, 38);">
        <?php } 
		else{ ?>
        <label for="show" class="alert alert-info" style="text-align:center; padding:5px"><?php echo substr($arr_file_38[0]['file_name'],6) ;  ?></label>
        <?php } ?>
    </div>
    <div class="col-sm-1"><a id="del" onclick="del('<?php echo $crypto->encode(" 38",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File
        </a></div>
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
    <div class="col-sm-1"><a id="del" onclick="del('<?php echo $crypto->encode(" 24",4); ?>','<?php echo $crypto->encode("delete",4); ?>');" name='del' class='del' style="color:red">Remove File
        </a></div>
</div>
<div class="row mb-3">
    <div class="col-sm-5"></div>
    <div class="col-sm-offset-5 col-sm-7">
        <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 1 MB.</span>
    </div>
</div>
<?php else: ?>
	<p>Note: Your application already visited STATE offices. From Now you can only upload document through comment Section. If file Returned to inbox.</p>
<?php endif; ?>
  
  			
		<div class="row mb-3" id="scroll_div" style="margin-left: 41%;">

			<!--<a type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#gpprofModal" onclick="return sub_prev(this.id)" > PREVIEW FOR FINAL SUBMIT </a>-->
			<?php if($emp_id_detail_ocon[0]['application_id'] !=''){
				if($emp_id_detail_ocon[0]['active_status'] == 1){
				?>
					<button type="submit" class="btn btn-warning" style='width: 25%; margin-left: -10%;' > <span class="spinner-grow spinner-grow-sm"></span> UPDATE </button>
				<?php } else { ?>
				<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#gpprofModal" style='width: auto; margin-left: 2%;' id="fi_sub" onclick="return sub_prev(this.id)"> <span class="spinner-grow spinner-grow-sm"></span> PREVIEW & FORWARD </button>
			<?php }
		        } else {			?>
				<button type="submit" id="submit" name="submit" class="btn btn-primary" style='width: auto;' onclick="return sub_prev(this.id)" >  <span class="spinner-grow spinner-grow-sm"></span> PREVIEW & SUBMIT </button>
			<?php } ?>
			
		</div>
	
	<?php } ?>
</form>
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
				else if($('#Appointment_MEMO').val()==''){
					alert('First Appointment Order No is Blank.');
					$('#Appointment_MEMO').focus();
					return false;
				}			
				else if($('#first_GP_or_PS_posted').val()==''){
					alert('First GP / PS posted is Blank.');
					$('#first_GP_or_PS_posted').focus();
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

        if(k.length <= 12)
          {	
				$.ajax({
						url : 'ajax_emp_master.php',
						type : 'POST',
						data : { "emp_id_const" : k },
							success : function(response) { 
								console.log(response);
							var result = $.parseJSON(response);
								
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

}


function sub_prev(v){
  //alert(v);
  
	var emp_id_const = $("#tch_emp_id").val();
	var app_no = $("#app_no").val();
						
  $.ajax({
      url : 'ajax_intra_pri_overage_condonation_submit_last.php',
      type : 'POST',
      data : { "emp_id_const" : emp_id_const,
				"app_no" : app_no,
				"fi_sub" : v
				},
        success : function(response) { //alert(v);
			//var result1 = $.parseJSON(response);
			
				if(v =='fi_sub'){
					//alert(response);
				window.location.href = "view_intra_pri_service.php";
				$('#forward_div').show();
				$('#fi_sub').hide();
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
	  <?php 
	  //$app_status = $db->fetch_table(" SELECT * FROM intra_pri_forwarding WHERE application_id='".$emp_id_detail_ocon[0]['application_id']."' AND from_officer_id_const ='".$_SESSION['user_info']['officer_id_const']."' ");
	  
	  //var_dump($app_status); die;
	  
	  if($emp_id_detail_ocon[0]['active_status'] == ''){?>
        <button type="button" class="btn btn-success" id="fi_sub" onclick="return sub_prev(this.id)" > PROPOSAL SAVED </button>
	  <?php }
		//else if($emp_id_detail_ocon[0]['active_status'] == '1' && $app_status[0]['to_officer_id_const']== ''){	  ?>	
			
	<div id="forward_div" style="display:none;">		
		<div class="row mb-3">
		<label for="inputPassword3" class="col-sm-6 col-form-label" style='margin-left: -110%;' >Forwarded To: </label>
		<div class="col-mb-3" >
		<input type="hidden" id="application_id_o" name="application_id_o" value="<?php echo $emp_id_detail_ocon[0]['application_id']; ?>" >
		<input type="hidden" id="service_type" name="service_type" value="<?php echo '4'; ?>" >
		<input type="hidden" id="emp_id_const" name="emp_id_const" value="<?php echo $emp_id_detail_ocon[0]['emp_id_const']; ?>" />
		<input type="hidden" id="sub_menu" name="sub_menu" value="<?php echo '4'; ?>" />
		<input type="hidden" id="for_app_rej_status" name="for_app_rej_status" value="" >
		
		
		  <select class="form-control" id="forwarding" name="forwarding" style='margin-left: -25%; width: auto;'>
			<option value="" >-- Please Select --</option>
			<?php	
			
			$forwarding_qury_ex = explode(',',$officer_name[0]['forwarding_user']);
			//var_dump($forwarding_qury_ex);
		foreach($forwarding_qury_ex as $val){
		$forward_user = $db->fetch_table(" SELECT desig.* , master.officer_id_const as officer_id_const, master.officer_name as officer_name FROM intra_pri_designation_master as desig 
			INNER JOIN intra_pri_master as master ON master.stake_level_code= desig.designation_code
			WHERE master.stake_user_code = '".$val."' AND master.active_status='1' AND '4' = any( string_to_array( master.role_assign, ',' ) ) ");
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
	
	
	
		<?php //} ?>	
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