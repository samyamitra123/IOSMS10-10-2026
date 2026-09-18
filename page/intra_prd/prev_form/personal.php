<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$id=isset($_GET['id'])?$_GET['id']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';*/
?>
<style>
	#sucess{
			background-color: green;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
			margin-top:-3%;
		}
		#error{
			background-color: red;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
			margin-top:-3%;
		}
</style>

<?php
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Primary Details of the Employee submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Data insertion failed. Please try again...</div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}
?>
<script>
function viewSpouse(type){
			/*if(type!='242'){
				$('#spouse_label').show();
				$('#spouse_field_fname').show();
				$('#spouse_field_maname').show();
				$('#spouse_field_last').show();
				$('#spouse_employee_label').show();
				$('#spouse_employee_field').show();
			}else{
				$('#employed_not').attr('checked', false);
				$('#spouse_details_label').hide();
				$('#spouse_details_field').hide();
				$('#spouse_pay_tr').hide();
				$('#employed_details').val('');
				/*$('#spouse_fname').val('FIRST');
				$('#spouse_mname').val('MIDDLE');
				$('#spouse_lname').val('LAST');*/
				/*$('#spouse_pay').val('');
				$('#spouse_hra').val('');
				$('#spouse_label').hide();
				$('#spouse_field_fname').hide();
				$('#spouse_field_maname').hide();
				$('#spouse_field_last').hide();
				$('#spouse_employee_label').hide();
				$('#spouse_employee_field').hide();
				$('#spouse_pay_field').hide();
				$('#spouse_hra_div').hide();
				$('#spouse_hra_tr').hide();
				$('#spouse_pay_tr').hide();
			}*/
			if(type=='242' || type==''){
				$('#employed_not').attr('checked', false);
				$('#spouse_details_label').hide();
				$('#spouse_details_field').hide();
				$('#spouse_pay_tr').hide();
				$('#employed_details').val('');
				/*$('#spouse_fname').val('FIRST');
				$('#spouse_mname').val('MIDDLE');
				$('#spouse_lname').val('LAST');*/
				$('#spouse_pay').val('');
				$('#spouse_hra').val('');
				$('#spouse_label').hide();
				$('#spouse_field_fname').hide();
				$('#spouse_field_maname').hide();
				$('#spouse_field_last').hide();
				$('#spouse_employee_label').hide();
				$('#spouse_employee_field').hide();
				$('#spouse_pay_field').hide();
				$('#spouse_hra_div').hide();
				$('#spouse_hra_tr').hide();
				$('#spouse_pay_tr').hide();
				
			}
			if(type=='241' || type=='243' || type=='244' || type=='245' || type=='246'){
				$('#spouse_label').show();
				$('#spouse_field_fname').show();
				$('#spouse_field_maname').show();
				$('#spouse_field_last').show();
				$('#spouse_employee_label').show();
				$('#spouse_employee_field').show();
			}
		}
		function sposeDetails(){
			if(document.getElementById('employed_not').checked==true){
				$('#spouse_details_label').show();
				$('#spouse_details_field').show();
				$('#spouse_pay_tr').show();
				$('#spouse_hra_tr').show();
			    $('#spouse_pay_field').show();
				$('#spouse_hra_div').show();
				$('#spouse_medical_tr').show();
				$('#spouse_medical_allowance_div').show();
			}else{
				$('#employed_details').val('');
				$('#spouse_pay').val('');
				$('#spouse_hra').val('');
				$('#spouse_details_label').hide();
				$('#spouse_details_field').hide();
				$('#spouse_pay_tr').hide();
				$('#spouse_hra_tr').hide();
				$('#spouse_pay_field').hide();
				$('#spouse_hra_div').hide();
				$('#spouse_medical_tr').hide();
				$('#spouse_medical_allowance_div').hide();
				$('#spouse_medical_allowance').removeAttr('selected').find('option:first').attr('selected','selected');
			}
		}
		function residentalView(type){
			if(type=='251'){
				$('#residental_label').show();
				$('#residental_field').show();
			}else{
				$('#residental_label').hide();
				$('#residental_field').hide();
			}
		}
		function stateDetailsView(type){
			if(type=='1'){
				$('#state_details_label').show();
				$('#state_details_field').show();
			}else{
				$('#state_details_label').hide();
				$('#state_details_field').hide();
				$('#state_details').val('');
			}
		}
        </script> 


<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
        <br /> <? require '../../../page/common_back_btns.php'; ?>
<h2 class="primary" align="center">Personal Details</h2>

<form class="form-horizontal" id="loginForm" method="post" action="profile_entry_personal_update.php">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Fathers Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="father_fname"  name="father_fname" placeholder="First Name">
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="father_mname"  name="father_mname" placeholder="Middle Name">
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="father_lname"  name="father_lname" placeholder="Last Name">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Mothers Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="mother_fname"  name="mother_fname" placeholder="First Name">
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="mother_mname"  name="mother_mname" placeholder="Middle Name">
    </div>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="mother_lname"  name="mother_lname" placeholder="Last Name">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Religion</label>
    <div class="col-sm-10">
              <?php
				$db = new database();
				$arr = $db->fetch_table("select code,description from prd_dise_code_master where substring(code,1,2)='16' and length(code)='3'");
              ?>
      <select class="form-control" name="religion" id="religion" >
      <option value="">-Please Select-</option>
       <?php foreach($arr as $key){ $key['code']. '<br />'; ?>
       <option value="<?php echo $key['code']; ?>"<? if($religion==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?></option>
       <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
     <label for="inputPassword3" class="col-sm-2 control-label">Mother Tongue </label>
    <div class="col-sm-10">
    <?php
	$db = new database();
	$arr_mother = $db->fetch_table( "select code,description from prd_dise_code_master where substring(code,1,2)='70' and length(code)='4'");
					
	?>
      <select class="form-control" name="mother_tounge" id="mother_tounge">
      <?php foreach($arr_mother as $key){ $key['code']. '<br />'; ?>
      <option value="<?php echo $key['code']; ?>" <? if($mother_tounge==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?></option>
      <?php } ?>
      </select>
  </div>
  </div>
   <div class="form-group">
     <label for="inputPassword3" class="col-sm-2 control-label">Marital status</label>
    <div class="col-sm-10">
    <?php
	$db = new database();
	$arr_m = $db->fetch_table( "select code,description from prd_dise_code_master where substring(code,1,2)='24' and length(code)='3'");
	?>
      <select class="form-control" name="mother_tounge" id="mother_tounge" onChange="return viewSpouse(this.value);">
      <option value="">-Please Select-</option>
	  <?php  foreach($arr_m as $key){ $key['code']. '<br />'; ?>
       <option value="<?php echo $key['code']; ?>" <? if($marital_status==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?></option>
        <?php } ?>
      </select>
  </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label" id="spouse_label" style="display:none">Spouse Name</label>
     <div class="col-sm-4" id="spouse_field_fname" style="display:none">
      <input type="text" class="form-control"  name="spouse_fname" id="spouse_fname" placeholder="First Name">
    </div>
    <div class="col-sm-3" id="spouse_field_maname" style="display:none">
      <input type="text" class="form-control"  name="spouse_mname" id="spouse_mname" placeholder="Middle Name">
    </div>
    <div class="col-sm-3" id="spouse_field_last" style="display:none">
      <input type="text" class="form-control"  name="spouse_lname" id="spouse_lname" placeholder="Last Name">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Whether spouse is employed</label>
    <div class="col-sm-4">
      <input type="checkbox" name="employed_not" id="employed_not" autocomplete="off" value="1" onClick="return sposeDetails();" >
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label" id="spouse_details_label" style="display:none">Employment Detail</label>
    <div class="col-sm-10" id="spouse_details_field" style="display:none">
      <input type="text" class="form-control" name="employed_details" id="employed_details" placeholder="Employment Details" autocomplete="off" value="">
    </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label" id="spouse_pay_tr" style="display:none">Spouse pay</label>
    <div class="col-sm-10" id="spouse_pay_field" style="display:none">
      <input type="text" class="form-control" name="spouse_pay" id="spouse_pay" placeholder="Spouse Pay" autocomplete="off" value="" >
    </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label" id="spouse_hra_tr" style="display:none">Spouse HRA</label>
    <div class="col-sm-10" id="spouse_hra_div" style="display:none">
      <input type="text" class="form-control" name="spouse_hra" id="spouse_hra" placeholder="Spouse HRA" autocomplete="off" value="" >
    </div>
  </div>
   <div class="form-group">
     <label for="inputPassword3" class="col-sm-2 control-label">Residential Status</label>
    <div class="col-sm-10">
    <?php
	$db = new database();
	$arr_r = $db->fetch_table("select code,description from prd_dise_code_master where substring(code,1,2)='25' and length(code)='3'");
	?>
      <select class="form-control" name="residental_status" id="residental_status" onChange="return residentalView(this.value);" >
      <option value="">-Please Select-</option>
       <?php foreach($arr_r as $key){ ?>
       <option value="<?php  echo $key['code']; ?>" <? if($key['code']==$residental_status){ echo "selected";}?>><?php echo $key['description']; ?></option>
        <?php } ?>
      </select>
  </div>
  </div>
<div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label" id="residental_label" style="display:none">Housing Scheme Name</label>
    <div class="col-sm-10" id="residental_field"  style="display:none">
      <input type="text" class="form-control" name="house_space_name" id="house_space_name" placeholder="Housing Scheme Name" autocomplete="off" value="">
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">PAN NO.</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="pan_no" id="pan_no" placeholder="PAN NUMBER" autocomplete="off" value="">
    </div>
  </div>
  <div class="form-group">
     <label for="inputPassword3" class="col-sm-2 control-label">Blood Group</label>
    <div class="col-sm-10">
      <select class="form-control" name="tch_blood_group" id="tch_blood_group">
      <option value="">-Please Select-</option>
        <?php
		   $db = new database();
	       $arr_blood = $db->fetch_table("select * from prd_dise_code_master where length(code)=5 and code like '20%' order by code ");
			foreach($arr_blood as $key){ $key['code']. '<br />'; ?>
        <option value="<?php echo $key['code']; ?>"  <? if($emp_blood_group==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?>
        </option>
        <?php } ?>
      </select>
  </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Height (In cm)</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="height" id="height" placeholder="Height" autocomplete="off" value="">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Whether Differently Able</label>
    <div class="col-sm-10">
      <select class="form-control" name="differently_able" id="differently_able" onChange="return stateDetailsView(this.value);">
      <option value="">Please Select</option>
      <option value="1">YES</option>
      <option value="0">NO</option>
      </select>
  </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label" id="state_details_label" style="display:none" >Status of Disability</label>
    <div class="col-sm-10" id="state_details_field" style="display:none" >
      <input type="text" class="form-control" name="state_details" id="state_details" placeholder="Status of Disability" autocomplete="off" value="">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Identification Mark</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="identification_mark" id="identification_mark" placeholder="Identification Mark" autocomplete="off" value="">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Save & Continue</button>
    </div>
  </div>
</form>

        </div>
      </div>

    </div>