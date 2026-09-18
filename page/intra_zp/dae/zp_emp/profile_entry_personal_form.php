<?
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
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
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';

$emp_id_pk=isset($_GET['emp_id_pk'])?$_GET['emp_id_pk']:' ';
$cryptoGraph=new cryptography();

if($_GET['confirm'] == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Salary Details of the Employee submitted Successfully...</strong></div>';
}
else if($_GET['confirm'] == 'false')
{
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
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
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
/*function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}*/
$obj_crpto = new cryptography();

function get_emp($id)
{
	$db  = new database();
	
	$obj_crpto = new cryptography();

	$data = $db->fetch_table("
								SELECT
								emp_desig, 
								zp_emp_type,
								emp_father_name ,
								emp_mother_name ,
								emp_religion ,
								emp_mother_tongue ,
								emp_marital_status ,
								emp_spouse_name ,
								emp_spouse_job_status ,
								emp_spouse_details ,
								emp_spouse_pay ,
								emp_spouse_hra ,
								emp_spouse_res ,
								emp_spouse_house_schm ,
								emp_pan_no ,
								emp_blood_grp ,
								emp_height ,
								emp_diff_able ,
								emp_disable_status ,
								emp_idf_mark ,
								emp_form_status,
								spouse_medical_allowance,
								conv_allow_status,
								emp_accommodation,
								emp_pension_status,
								zp_emp_type,
								emp_gvt_hra_type,
								emp_gvt_hra_ammount,
								spouse_ss_enrolled
								FROM prd_employee_master
								WHERE emp_id_pk = '".$obj_crpto->decode($id,4)."'
								and zp_id_fk = '".$_SESSION['location']['district_id']."'
	");
	
	return $data;
}
if(!empty($_GET['emp_id_pk']))
{
	$emp_data = get_emp($_GET['emp_id_pk']);
}
else
{
	$emp_data = get_emp($emp_id);
}

if($cryptoGraph->decode($_GET['desig'],4)=='')
{
	$emp_desig=$emp_data[0]['emp_desig'];
}
else
{
	$emp_desig=$cryptoGraph->decode($_GET['desig'],4);
}

$emp_father_name=$emp_data[0]['emp_father_name'];
$fname=explode(' ',$emp_father_name);
$emp_mother_name=$emp_data[0]['emp_mother_name'];
$mname=explode(' ',$emp_mother_name);
$emp_religion=$emp_data[0]['emp_religion'];
$emp_mother_tongue=$emp_data[0]['emp_mother_tongue'];
$emp_marital_status=$emp_data[0]['emp_marital_status'];
$emp_spouse_name=$emp_data[0]['emp_spouse_name'];
$sname=explode(' ',$emp_spouse_name);
$emp_spouse_job_status=$emp_data[0]['emp_spouse_job_status'];
$emp_spouse_details=$emp_data[0]['emp_spouse_details'];

$emp_spouse_pay=$emp_data[0]['emp_spouse_pay']=='0'?'':$emp_data[0]['emp_spouse_pay'];
$emp_spouse_hra=$emp_data[0]['emp_spouse_hra']=='0'?'':$emp_data[0]['emp_spouse_hra'];
$spouse_medical_allowance= $emp_data[0]['spouse_medical_allowance'];
$emp_spouse_res=$emp_data[0]['emp_spouse_res'];
$emp_spouse_house_schm=$emp_data[0]['emp_spouse_house_schm'];
$emp_pan_no=$emp_data[0]['emp_pan_no'];
$emp_blood_grp=$emp_data[0]['emp_blood_grp'];
$emp_height=$emp_data[0]['emp_height']=='0'?'':$emp_data[0]['emp_height'];
$emp_diff_able=$emp_data[0]['emp_diff_able'];
$emp_disable_status=$emp_data[0]['emp_disable_status'];
$conv_status=$emp_data[0]['conv_allow_status'];
$emp_idf_mark=$emp_data[0]['emp_idf_mark'];
$emp_form_status=$emp_data[0]['emp_form_status'];
$emp_type=$emp_data[0]['zp_emp_type'];
$emp_pension_status=$emp_data[0]['emp_pension_status'];
$emp_accommodation=$emp_data[0]['emp_accommodation'];	
$zp_emp_type=$emp_data[0]['zp_emp_type'];
$emp_hra_type=$emp_data[0]['emp_gvt_hra_type'];
$emp_hra_ammount=$emp_data[0]['emp_gvt_hra_ammount'];
$spouse_ss_enrolled = $emp_data[0]['spouse_ss_enrolled'];
?>

<script>
	function viewSpouse(type)
	{
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
		if(type=='242' || type==''|| type=='243' || type=='244' || type=='245' || type=='246')
		{
			$('#employed_not').attr('checked', false);
			$('#spouse_details_label').hide();
			$('#spouse_details_field').hide();
			$('#spouse_pay_tr').hide();
			
			
			$('#employed_details').val('');
			/*$('#spouse_fname').val('FIRST');
			$('#spouse_mname').val('MIDDLE');
			$('#spouse_lname').val('LAST');*/
			$('#spouse_pay').val('');
			//$('#spouse_medical_allowance').val();
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
			//$('#spouse_medical_tr').hide();
			//$('#spouse_medical_div').hide();
			$('#emp_accommodation_level').hide();
			$('#emp_accommodation_div').hide();
		
		}
		if(type=='241')
		{
			$('#spouse_label').show();
			$('#spouse_field_fname').show();
			$('#spouse_field_maname').show();
			$('#spouse_field_last').show();
			$('#spouse_employee_label').show();
			$('#spouse_employee_field').show();
			$('#emp_accommodation_level').show();
			$('#emp_accommodation_div').show();
		}
	}
	
	function sposeDetails()
	{
		if(document.getElementById('employed_not').checked==true)
		{
			$('#spouse_details_label').show();
			$('#spouse_details_field').show();
			$('#spouse_pay_tr').show();
			$('#spouse_hra_tr').show();
			//$('#spouse_medical_tr').show();
			$('#spouse_pay_field').show();
			//$('#spouse_medical_allowance').show();
			$('#spouse_hra_div').show();
			//$('#spouse_medical_tr').show();
			//$('#spouse_medical_allowance_div').show();
			//$('#spouse_medical_div').show();
			//$('#emp_accommodation').show();
			//$('#emp_accommodation_div').show();
		}
		else
		{
			$('#employed_details').val('');
			$('#spouse_pay').val('');
			//$('#spouse_medical_allowance').val();
			$('#spouse_hra').val('');
			$('#spouse_details_label').hide();
			$('#spouse_details_field').hide();
			$('#spouse_pay_tr').hide();
			$('#spouse_hra_tr').hide();
			//$('#spouse_medical_tr').hide();
			$('#spouse_pay_field').hide();
			//$('#spouse_medical_allowance').hide();
			$('#spouse_hra_div').hide();
			//$('#spouse_medical_tr').hide();
			//$('#spouse_medical_allowance_div').hide();
			//$('#emp_accommodation').hide();
			//$('#emp_accommodation_div').hide();
			//$('#spouse_medical_allowance').removeAttr('selected').find('option:first').attr('selected','selected');
		}
	}
	
	function residentalView(type)
	{
		$('#hra_scheem_type').val('');
		$('#hra_ammount_value').val('');
		if(type=='251')
		{
			$('#residental_label').show();
			$('#residental_field').show();
			$('#hra_scheem').show();
			$('#hra_scheem_name').show();
		}
		else
		{
			$('#residental_label').hide();
			$('#residental_field').hide();
			$('#house_space_name').val('');
			$('#hra_scheem').hide();
			$('#hra_scheem_name').hide();
			$('#hra_ammount_field').hide();
	   		$('#hra_ammount_value').hide();
		}
	}
	
	function stateDetailsView(type)
	{
		if(type=='1')
		{
			$('#state_details_label').show();
			$('#state_details_field').show();
			$('#conv_eligible_label').show();
			$('#conv_eligible_div').show();
		}
		else
		{
			$('#state_details_label').hide();
			$('#state_details_field').hide();
			$('#state_details').val('');
			$('#conv_eligible_label').hide();
			$('#conv_eligible_div').hide();
		}
	}
	
	function hra_scheen_type(type)
	{ 
	   $('#hra_ammount_field').show();
	   $('#hra_ammount_value').show();
	   $('#hra_ammount_value').val('');
	   if(type=='200')
	   {
			$('#hra_ammount_value').val('0');
			$('#hra_ammount_value').attr('readonly','readonly');
			$('#scheem_info').html('NO HRA, BUT HRA DEDUCTION OR LICENSE FEE DEDUCTION AVAILAVLE');
		
	   }
	   else if(type=='201')
	   {
			//$('#hra_ammount_value').val('');
			$('#hra_ammount_value').removeAttr('readonly');
			$('#scheem_info').html('SAME AMOUNT OF HRA RECIEVED AND DEDUCTION AVAILABLE');
	   }
	   else if(type=='202')
	   {
			$('#hra_ammount_value').val('0');
			$('#hra_ammount_value').attr('readonly','readonly');
			$('#scheem_info').html('NO HRA AND NO HRA DEDUCTION OR LICENSE FEE DEDUCTION AVAILAVLE');
	   }
	   else
	   {
		   $('#hra_ammount_field').hide();
			$('#hra_ammount_value').hide();
			$('#scheem_info').html('');
	   }
	}
</script> 

<script>
	$(document).ready(function() {
		if($('#employed_not').is(':checked')==true)
		{
			$('#spouse_details_label').show();
			$('#spouse_details_field').show();
			$('#spouse_pay_tr').show();
			$('#spouse_hra_tr').show();
			//$('#spouse_medical_tr').show();
			//$('#spouse_medical_div').show();
			$('#spouse_pay_field').show();
			//$('#spouse_medical_allowance').show();
			$('#spouse_hra_div').show();
			//$('#spouse_medical_tr').show();
			//$('#spouse_medical_div').show(); 
			//$('#emp_accommodation').show();
			//$('#emp_accommodation_div').show();
			//$('#spouse_medical_allowance').find('option:first').attr('selected','selected');
		}
		if($("#differently_able").val()=='1')
		{
			$('#state_details_label').show();
			$('#state_details_field').show();
			$('#conv_eligible_label').show();
			$('#conv_eligible_div').show();	
		}
		if($("#residental_status").val()=='251')
		{
			$('#residental_label').show();
			$('#residental_field').show();
			$('#hra_scheem').show();
			$('#hra_scheem_name').show();
			$('#hra_ammount_field').show();
			$('#hra_ammount_value').show();
			if($('#hra_scheem_type').val()=='200')
			{
				$('#hra_ammount_value').val('0');
				$('#hra_ammount_value').attr('readonly','readonly');
			}
			else if($('#hra_scheem_type').val()=='201')
			{
				$('#hra_ammount_value').removeAttr('readonly');
				$('#hra_ammount_value').show();
			}
			else if($('#hra_scheem_type').val()=='202')
			{
				$('#hra_ammount_value').val('0');
				$('#hra_ammount_value').attr('readonly','readonly');
			}	
		}
		if($("#marital_status").val()=='241')
		{
			$('#spouse_label').show();
			$('#spouse_field_fname').show();
			$('#spouse_field_maname').show();
			$('#spouse_field_last').show();	
			$('#spouse_employee_label').show();
			$('#spouse_employee_field').show();
		}
	});
</script>
<style>
.form-horizontal .control-label {
text-align:left;
}
</style>

<div class="content">
	<? require '../../../../page/common_back_btns.php'; ?>
    <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        <?php
        if(isset($_SESSION['location']['gp_name'])) {
        echo $_SESSION['location']['gp_name'];
        }elseif(isset($_SESSION['location']['block_name'])) {
        echo $_SESSION['location']['block_name'];
        } elseif(isset($_SESSION['location']['district_name'])) {
        echo $_SESSION['location']['district_name'];
        } elseif(isset($_SESSION['location']['state_name'])) {
        echo $_SESSION['location']['state_name'];
        } ?></h2><h3>
        <? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name'];
        ?></h3>
    </div>
    <!-- Latest compiled and minified JavaScript -->
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12" style="width:98%">
                <h1 class="heading">Personal Details</h1>
                <div class="border"></div>
                </br>
                <?php 
					if($msg)
					{
						echo $msg;
						echo "<br/>";
					}
					if($error_msg)
					{
						echo $error_msg;
						echo "<br/>";
					}
					if(!empty($_GET['emp_id_pk']))
					{
						$employee_id=$cryptoGraph->decode($_GET['emp_id_pk'],4);
					}
					else
					{
						$employee_id=$cryptoGraph->decode($emp_id,4);
					}
                ?>
                <form class="form-horizontal" id="loginForm" method="post" action="profile_entry_personal_update.php" onsubmit="return valid_code();">
                    <input type="hidden" name="emp_id_pk" value="<?=$employee_id  ?>" />
                    <input type="hidden" name="desig" id="desig" value="<?= $emp_desig ?>" />
                    <input type="hidden" name="form_status" value="<?=$emp_form_status ?>" />
                    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <input type="hidden" name="edit_status" id="edit_status" value="<?php echo $obj_crpto->encode('1',4); ?>" />
                    <input type="hidden" name="emp_pension_status" id="emp_pension_status" value="<?php echo $obj_crpto->encode($emp_pension_status,4); ?>" />
                    
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-3 control-label">Father's Name</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control upper_case" id="father_fname"  name="father_fname" placeholder="First Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$fname[0]; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control upper_case" id="father_mname"  name="father_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$fname[1]; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control upper_case" id="father_lname"  name="father_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$fname[2]; ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-3 control-label">Mother's Name</label>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case" id="mother_fname"  name="mother_fname" placeholder="First Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');"value="<?=$mname[0]; ?>">
                        </div>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case" id="mother_mname"  name="mother_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');"value="<?=$mname[1]; ?>">
                        </div>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case" id="mother_lname"  name="mother_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');"value="<?=$mname[2]; ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label">Religion<span class="star_color">*</span></label>
                        <div class="col-sm-3">
							<?php
                            $db = new database();
                            $arr = $db->fetch_table("select code,description from prd_dise_code_master where substring(code,1,2)='16' and length(code)='3' ORDER BY description");
                            ?>
                            <select class="form-control" name="religion" id="religion" >
                                <option value="">-Please Select-</option>
                                <?php foreach($arr as $key){ $key['code']. '<br />'; ?>
                                <option value="<?php echo $key['code']; ?>"<? if($emp_religion==$key['code'] || $religion==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="inputPassword3" class="col-sm-3 control-label">Mother Tongue <span class="star_color">*</span></label>
                        <div class="col-sm-3">
							<?php
                            $db = new database();
                            $arr_mother = $db->fetch_table( "select code,description from prd_dise_code_master where substring(code,1,2)='70' and length(code)='4'");
                            ?>
                            <select class="form-control" name="mother_tounge" id="mother_tounge">
                                <option value="">-Please Select-</option>
                                <?php foreach($arr_mother as $key){ $key['code']. '<br />'; ?>
                                <option value="<?php echo $key['code']; ?>" <? if($emp_mother_tongue==$key['code'] || $mother_tounge==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label">Marital Status<span class="star_color">*</span></label>
                        <div class="col-sm-3">
							<?php
                            $db = new database();
                            $arr_m = $db->fetch_table( "select code,description from prd_dise_code_master where substring(code,1,2)='24' and length(code)='3'");
                            ?>
                            <select class="form-control" name="marital_status" id="marital_status" onChange="return viewSpouse(this.value);">
                                <option value="">-Please Select-</option>
                                <?php  foreach($arr_m as $key){ $key['code']. '<br />'; ?>
                                <option value="<?php echo $key['code']; ?>" <? if($emp_marital_status==$key['code'] || $marital_status==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_label" style="display:none">Spouse Name<span class="star_color">*</span></label>
                        <div class="col-sm-3" id="spouse_field_fname" style="display:none">
                        	<input type="text" class="form-control upper_case"  name="spouse_fname" id="spouse_fname" placeholder="First Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$sname[0]; ?>">
                        </div>
                        <div class="col-sm-3" id="spouse_field_maname" style="display:none">
                        	<input type="text" class="form-control upper_case"  name="spouse_mname" id="spouse_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$sname[1]; ?>">
                        </div>
                        <div class="col-sm-3" id="spouse_field_last" style="display:none">
                        	<input type="text" class="form-control upper_case"  name="spouse_lname" id="spouse_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$sname[2]; ?>">
                        </div>
                    </div>
                    
                    <?php if($cryptoGraph->decode($_GET['desig'],4)!='1' && $emp_desig!='1') 
                    { ?>
						<div class="row mb-3">
						<?php if ($sname[0] == "" && $emp_spouse_pay=="")
						{ ?>
                           
                                <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_employee_label" style="display:none">Whether Spouse is Employed<span class="star_color"></span></label>
                                <div class="col-sm-3" id="spouse_employee_field" style="display:none">
                                    <input type="checkbox" name="employed_not" id="employed_not" autocomplete="off" value="1"  onClick="return sposeDetails();" >
                                    <label for="employed_not"><span></span></label>
                                </div>
						<?php 
						} 
						else if ($sname[0] != "" && $emp_spouse_pay=="")
						{ ?>
                                <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_employee_label" style="display:none">Whether Spouse is Employed<span class="star_color"></span></label>
                                <div class="col-sm-3" id="spouse_employee_field" style="display:none">
                                    <input type="checkbox" name="employed_not" id="employed_not" autocomplete="off" value="1"  onClick="return sposeDetails();" >
                                    <label for="employed_not"><span></span></label>
                                </div>
						<?php  
						} 
						else if ($sname[0] != "" && $emp_spouse_pay!="")
						{?>
                                <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_employee_label" style="display:none">Whether Spouse is Employed<span class="star_color"></span></label>
                                <div class="col-sm-3" id="spouse_employee_field" style="display:none">
                                    <input type="checkbox" name="employed_not" id="employed_not" autocomplete="off" value="1" checked="checked" onClick="return sposeDetails();" >
                                    <label for="employed_not"><span></span></label>
                                </div>
						<?php 
						}
						if ($sname[0] == "") 
						{?>
                            <label for="inputPassword3" class="col-sm-3 control-label" id="emp_accommodation_level" style="display:none">Whether Spouse is being provided any accommodation by Employer:<span class="star_color">*</span></label>
                            <div class="col-sm-3" id="emp_accommodation_div" style="display:none">
                                <select name="emp_accommodation" id="emp_accommodation" class="form-control" >
                                    <option value="">Please Select</option>
                                    <option value="1" <? if($emp_accommodation=='1'){ echo 'selected';}?>>Yes</option>
                                    <option value="0" <? if($emp_accommodation=='0'){ echo 'selected';}?>>No</option>
                                </select>
                            </div>
						<?php }
						else if($sname[0] != "")
						{ ?>
                        	 <label for="inputPassword3" class="col-sm-3 control-label" id="emp_accommodation_level">Whether Spouse is being provided any accommodation by Employer?:<span class="star_color">*</span></label>
                            <div class="col-sm-3" id="emp_accommodation_div">
                                <select name="emp_accommodation" id="emp_accommodation" class="form-control" >
                                    <option value="">Please Select</option>
                                    <option value="1" <? if($emp_accommodation=='1'){ echo 'selected';}?>>Yes</option>
                                    <option value="0" <? if($emp_accommodation=='0'){ echo 'selected';}?>>No</option>
                                </select>
                            </div>
						<?php } ?>
						</div>
						<div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_details_label" style="display:none">Employment Detail<span class="star_color">*</span></label>
                            <div class="col-sm-3" id="spouse_details_field" style="display:none">
                            	<input type="text" class="form-control upper_case" name="employed_details" id="employed_details" placeholder="Employment Details" autocomplete="off" value="<?=$emp_spouse_details; ?>">
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_pay_tr" style="display:none">Spouse Pay<span class="star_color">*</span></label>
                            <div class="col-sm-3" id="spouse_pay_field" style="display:none">
                            	<input type="text" class="form-control" name="spouse_pay" id="spouse_pay" placeholder="SPOUSE PAY" autocomplete="off" value="<?=$emp_spouse_pay ?>"onKeyPress="return keyRestrict(event,'0123456789');" >
                            </div>
						</div>
                        
						<div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_hra_tr" style="display:none">Spouse HRA<span class="star_color">*</span></label>
                            <div class="col-sm-3" id="spouse_hra_div" style="display:none">
                            <input type="text" class="form-control" name="spouse_hra" id="spouse_hra" placeholder="SPOUSE HRA" autocomplete="off" value="<?=$emp_spouse_hra ?>" onKeyPress="return keyRestrict(event,'0123456789');">
                            </div>
                            
						</div>
                        
						<?php if($emp_type == 366 || $emp_type == 367)
						{ ?>
							<div class="row mb-3">
	                            <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_ss_tr">Spouse Opted for enrolment in Swasthya Sathi:<span class="star_color">*</span></label>
	                            <div class="col-sm-3" id="spouse_ss_div">
	                                <select name="spouse_ss_enrolled" id="spouse_ss_enrolled" class="form-control">
	                                    <option value="">Please Select</option>
	                                    <option value="1" <? if($spouse_ss_enrolled=='1'){ echo "selected";}?>>Yes</option>
	                                    <option value="0" <? if($spouse_ss_enrolled=='0'){ echo "selected";}?>>No</option>
	                                </select>
	                            </div>
							</div>						
							<div class="row mb-3">
	                            <label for="inputPassword3" class="col-sm-3 control-label" id="spouse_medical_tr">Employee Opted for enrolment in WB Health Scheme:<span class="star_color">*</span></label>
	                            <div class="col-sm-3" id="spouse_medical_div">
	                                <select name="spouse_medical_allowance" id="spouse_medical_allowance" class="form-control" >
	                                    <option value="">Please Select</option>
	                                    <option value="1" <? if($spouse_medical_allowance=='1'){ echo "selected";}?>>Yes</option>
	                                    <option value="0" <? if($spouse_medical_allowance=='0'){ echo "selected";}?>>No</option>
	                                </select>
	                            </div>
							</div>
						<?php } ?>
						
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label">Residential Status<span class="star_color">*</span></label>
                            <div class="col-sm-3">
								<?php
                                $db = new database();
                                $arr_r=$db->fetch_table("select code,description from prd_dise_code_master where substring(code,1,2)='25' and length(code)='3'");
                                ?>
                                <select class="form-control" name="residental_status" id="residental_status" onChange="return residentalView(this.value);" >
                                    <option value="">-Please Select-</option>
                                    <?php foreach($arr_r as $key){ ?>
                                    <option value="<?php  echo $key['code']; ?>" <? if($key['code']==$emp_spouse_res || $key['code']==$residental_status){ echo "selected";}?>><?php echo $key['description']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label" id="residental_label" style="display:none">Housing Scheme <span class="star_color">*</span></label>
                            <div class="col-sm-3" id="residental_field"  style="display:none">
                            	<input type="text" class="form-control upper_case" name="house_space_name" id="house_space_name" placeholder="Housing Scheme Name" autocomplete="off" value="<?=$emp_spouse_house_schm ?>">
                            </div>
						</div> 
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label" id="hra_scheem" style="display:none"> HRA Scheme Type <span class="star_color">*</span></label>
                            <div class="col-sm-3" id="hra_scheem_name"  style="display:none">
                            
                            <?php
                                $db = new database();
                                $arr_r=$db->fetch_table("select code,description from prd_dise_code_master where code like '20%' and length(code)='3'");
                                ?>
                            <select class="form-control" name="hra_scheem_type" id="hra_scheem_type" onChange="return hra_scheen_type(this.value);"  >
                                    <option value="">-Please Select-</option>
                                    <?php foreach($arr_r as $key){ ?>
                                    <option value="<?php  echo $key['code']; ?>" <? if($key['code']==$emp_hra_type){ echo "selected";}?>><?php echo $key['description']; ?></option>
                                    <?php } ?>
                                </select>
               					<p id="scheem_info" style="color: #ba2626;font-weight: bold; margin-top:8px;"></p>
                            </div>
                            
                            <label for="inputPassword3" class="col-sm-3 control-label" id="hra_ammount_field" style="display:none" >HRA Amount <span class="star_color">*</span></label>
                            <div class="col-sm-3" >
                            	<input type="text" class="form-control upper_case" name="hra_ammount_value" id="hra_ammount_value" placeholder="HRA AMOUNT" autocomplete="off"  value="<?=$emp_hra_ammount?>" style="display:none" onKeyPress="return keyRestrict(event,'0123456789');">
                            </div>
						</div> 
						<?php 
					} ?> 
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label">Blood Group</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="tch_blood_group" id="tch_blood_group">
                                <option value="">-Please Select-</option>
                                <?php
                                $db = new database();
                                $arr_blood = $db->fetch_table("select * from prd_dise_code_master where length(code)=5 and code like '20%' order by code ");
                                foreach($arr_blood as $key){ $key['code']. '<br />'; ?>
                                <option value="<?php echo $key['code']; ?>"  <? if($emp_blood_grp==$key['code'] || $tch_blood_group==$key['code']){ echo "selected";}?>><?php echo $key['description']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="inputPassword3" class="col-sm-3 control-label">Height (In cm)</label>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control" name="height" id="height" placeholder="HEIGHT" autocomplete="off" maxlength="4" value="<?=$emp_height ?>" onKeyPress="return keyRestrict(event,'0123456789.');">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label">Identification Mark</label>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case" name="identification_mark" id="identification_mark" placeholder="Identification Mark" autocomplete="off" value="<?=$emp_idf_mark ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label">Whether Differently Able <span class="star_color">*</span></label>
                        <div class="col-sm-3">
                            <select class="form-control" name="differently_able" id="differently_able" onChange="return stateDetailsView(this.value);">
                                <option value="">Please Select</option>
                                <option value="1" <? if($emp_diff_able=='1' || $differently_able=='1') { echo "selected"; } ?>>YES</option>
                                <option value="0" <? if($emp_diff_able=='0' || $differently_able=='0') { echo "selected"; } ?>>NO</option>
                            </select>
                        </div>
                        <label for="inputPassword3" class="col-sm-3 control-label" id="state_details_label" style="display:none" >Status of Disability <span class="star_color">*</span></label>
                        <div class="col-sm-3" id="state_details_field" style="display:none" >
                        	<input type="text" class="form-control upper_case" name="state_details" id="state_details" placeholder="Status of Disability" autocomplete="off" value="<?= $emp_disable_status ?>" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label" id="conv_eligible_label" style="display:none">Whether Eligible  For Conveyance Allowance <span class="star_color">*</span></label>
                        <div class="col-sm-3" id="conv_eligible_div" style="display:none">
                            <select class="form-control" name="conv_eligible" id="conv_eligible" >
                                <option value="">Please Select</option>
                                <option value="1" <? if($conv_status=='1' || $conv_eligible=='1') { echo "selected"; } ?>>YES</option>
                                <option value="0" <? if($conv_status=='0' || $conv_eligible=='0') { echo "selected"; } ?>>NO</option>
                            </select>
                        </div>
                    </div>
                    
                    <p style="border-top:1px dashed #27769F; text-align:center;"></p>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info" style="float:right">SAVE & CONTINUE <i class="fa fa-chevron-right"></i></button>
                            <a href="profile_entry_sal.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<?
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>  

<script>
function valid_code()
{
	var zp_emp_type='<?php echo $emp_type; ?>';
	var desig=$('#desig').val();
	if($('#religion').val()=='')
	{
		alert('Please Select Religion.');
		$('#religion').focus();
		return false;
	}
	if($('#mother_tounge').val()=='')
	{
		alert('Please Select Mother Tounge.');
		$('#mother_tounge').focus();
		return false;
	}
	if($('#marital_status').val()=='')
	{
		alert('Please Select Marital Status.');
		$('#marital_status').focus();
		return false;
	}
	if($('#marital_status').val()=='241')
	{
		if($('#spouse_fname').val()=='' || $('#spouse_fname').val()=='FIRST')
		{
			alert('Please Enter Spouse FIRST Name.');
			$('#spouse_fname').focus();
			return false;
		}
		if(desig!='1' && $('#emp_accommodation').val()=='')
		{
			alert('Please Select Whether Spouse is being provided any accommodation by Employer.');
			$('#emp_accommodation').focus();
			return false;
		}
	}
	if($('#employed_not').checked==true)
	{
		if($('#employed_details').val()=='')
		{
			alert('Please Enter Details of employment.');
			$('#employed_details').focus();
			return false;
		}
		if($('#spouse_pay').val()=='')
		{
			alert('Please Enter Spouse Pay.');
			$('#spouse_pay').focus();
			return false;
		}
		if($('#spouse_hra').val()==''){
			alert('Please Enter Spouse Hra.');
			$('#spouse_hra').focus();
			return false;
		}
		
		
	}
	if($('#spouse_medical_allowance').val()=='')
	{
		alert('Please Select Spouse opted for enrolment in West Bengal Health Scheme.');
		$('#spouse_medical_allowance').focus();
		return false;
	}
	if($('#residental_status').val()=='')
	{
		alert('Please Select Residental Status.');
		$('#residental_status').focus();
		return false;
	}
	if($('#residental_status').val()=='251')
	{
		if($('#house_space_name').val()==''){
			alert('Please enter housing details.');
			$('#house_space_name').focus();
			return false;
		}
		if($('#hra_scheem_type').val()==''){
			alert('Please select House Scheme Type.');
			$('#hra_scheem_type').focus();
			return false;
		}
		if($('#hra_scheem_type').val()==201)
		{
				if($('#hra_ammount_value').val()=='' || $('#hra_ammount_value').val()=='0')
				{
				alert('Please enter HRA Ammount.');
				$('#hra_ammount_value').focus();
				return false;
				}
		}
	}
	if($('#differently_able').val()=='')
	{
		alert('Please Select Whether Differently Able.');
		$('#differently_able').focus();
		return false;
	}
	if($('#differently_able').val()=='1')
	{
		if($('#state_details').val()=='')
		{
			alert('Please enter status of disability.');
			$('#state_details').focus();
			return false;
		}
		if($('#conv_eligible').val()=='')
		{
			alert('Please select whether eligible for conveyance allowance or not.');
			$('#conv_eligible').focus();
			return false;
		}
	}
	
	var db_emp_father_name = '<?php echo $emp_father_name; ?>';
	var db_emp_mother_name = '<?php echo $emp_mother_name; ?>';
	var db_emp_religion = '<?php echo $emp_religion; ?>';
	var db_emp_mother_tongue = '<?php echo $emp_mother_tongue; ?>';
	var db_emp_marital_status = '<?php echo $emp_marital_status; ?>';
	var db_emp_spouse_name = '<?php echo $emp_spouse_name; ?>';
	var db_emp_spouse_job_status = '<?php echo $emp_spouse_job_status; ?>';
	var db_emp_spouse_details = '<?php echo $emp_spouse_details; ?>';
	var db_emp_spouse_pay = '<?php echo $emp_spouse_pay; ?>';
	var db_emp_spouse_hra = '<?php echo $emp_spouse_hra; ?>';
	var db_spouse_medical_allowance = '<?php echo $spouse_medical_allowance; ?>';
	var db_emp_spouse_res = '<?php echo $emp_spouse_res; ?>';
	var db_emp_spouse_house_schm = '<?php echo $emp_spouse_house_schm; ?>';
	
	var db_emp_blood_grp = '<?php echo $emp_blood_grp; ?>';
	var db_emp_height = '<?php echo $emp_height; ?>';
	var db_emp_diff_able = '<?php echo $emp_diff_able; ?>';
	var db_emp_disable_status = '<?php echo $emp_disable_status; ?>';
	var db_conv_status = '<?php echo $conv_status; ?>';
	var db_emp_idf_mark = '<?php echo $emp_idf_mark; ?>';
	var db_emp_accommodation = '<?php echo $emp_accommodation; ?>';
	var db_zp_emp_type = '<?php echo $zp_emp_type; ?>';
	var father_fname=$('#father_fname').val();
	var father_mname=$('#father_mname').val();
	var father_lname=$('#father_lname').val();
	var db_father_name=father_fname+' '+father_mname+' '+father_lname;
	var mother_fname=$('#mother_fname').val();
	var mother_mname=$('#mother_mname').val();
	var mother_lname=$('#mother_lname').val();
	var db_mother_name=mother_fname+' '+mother_mname+' '+mother_lname;
	
	var spouse_fname=$('#spouse_fname').val();
	var spouse_mname=$('#spouse_mname').val();
	var spouse_lname=$('#spouse_lname').val();
	var db_spouse_name=spouse_fname+' '+spouse_mname+' '+spouse_lname;
	
	if((db_emp_father_name != db_father_name) ||
	(db_emp_mother_name != db_mother_name) ||
	(db_emp_religion != $('#religion').val()) ||
	(db_emp_mother_tongue != $('#mother_tounge').val()) ||
	(db_emp_marital_status != $('#marital_status').val()) ||
	(db_emp_spouse_name != db_spouse_name) ||
	(db_emp_spouse_job_status != $('#employed_not').val()) ||
	(db_emp_spouse_details != $('#employed_details').val()) ||	
	(db_emp_spouse_pay != $('#spouse_pay').val()) ||
	(db_emp_spouse_hra != $('#spouse_hra').val()) ||
	(db_emp_spouse_res != $('#residental_status').val()) ||
	(db_emp_spouse_house_schm != $('#house_space_name').val()) ||
	(db_emp_blood_grp != $('#tch_blood_group').val()) ||
	(db_emp_height != $('#height').val()) ||
	(db_emp_diff_able != $('#differently_able').val()) ||	
	(db_emp_disable_status != $('#state_details').val()) ||
	(db_conv_status != $('#conv_eligible').val()) ||
	(db_emp_idf_mark != $('#identification_mark').val()) ||
	(db_emp_accommodation != $('#emp_accommodation').val())	
	)
	{
		$('#edit_status').val('<?php echo $obj_crpto->encode('2',4); ?>');	
	}
	
	if(db_zp_emp_type == 366 || db_zp_emp_type == 367)
	{
		if(db_spouse_medical_allowance != $('#spouse_medical_allowance').val())
		{
			$('#edit_status').val('<?php echo $obj_crpto->encode('2',4); ?>');
		}
	}
}

function valid_pan()
{
	var pan=document.getElementById("pan_no").value;
	var regpan = /^([a-zA-Z]){3}([P]){1}([a-zA-Z]){1}([0-9]){4}([a-zA-Z]){1}?$/;
	if(pan!="")
	{
		if(regpan.test(pan) == false)
		{
			alert("Permanent Account Number (PAN No.) is not valid.");
			return false;
		}
		else
		{
			return true;
		}
	}
}
</script>