<?php
if($_SERVER['HTTP_REFERER']=='')
{
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

$time_token1=time();

$enc_token1=md5('369'.$time_token1);
$_SESSION['security_token1']=$time_token1;


$id=isset($_GET['id'])?$_GET['id']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';

require_once '../../../all_function/fun_store/zp_ps_gp_function.php';
$fun_store=new zp_ps_gp_class();

if($_GET['confirm'] == 'success'){
$msg='<div class="alert alert-success" style="text-align:center"><strong>Primary Details of the Employee submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

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
?>
<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>

<script>
	$(function() {
		$( "#tch_dob" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		dateFormat: 'dd-mm-yy' 
		});
		$( "#doj" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+10",
		dateFormat: 'dd-mm-yy'
		});
		$( "#tch_approval_appointment" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+0",
		dateFormat: 'dd-mm-yy'
		});
		$( "#date_of_next_increment" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+60",
		dateFormat: 'dd-mm-yy'
		});
		$( "#emp_date_termination" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+50",
		dateFormat: 'dd-mm-yy'
		});
	});
	
	function calculate_retireyear(employee_dob)
	{
		$.post('<?php echo $config['base_url'] ?>page/intra_prd/common/retirement_date.php',{employee_dob:employee_dob},function(data){
			if(data)
			{
				$('#emp_date_retirement').val(data);
			}
		});
	}
	
	function aadhar_view(k)
	{
		if(k==1)
		{
			$('#aadhar_div').show();
		}
		else
		{
			$('#aadhaar_no').val('');
			$('#aadhar_div').hide();
		}
	}
	
	function emp_type_change(k)
	{
		if(k=='366')
		{
			$('#govt_id_lvl').show();
			$('#govt_id').show();
			$('#emp_quali_lvl').hide();
			$('#emp_quali').hide();
		}
		else
		{
			$('#govt_id_lvl').hide();
			$('#govt_id').hide();
			$('#emp_quali_lvl').show();
			$('#emp_quali').show();
		}
	}
</script> 

<div class="content">
	<? require '../../../../page/common_back_btns.php'; ?>
    <div class="welcome_msg">
        <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
        <?php
        if(isset($_SESSION['location']['gp_name'])) {
        echo $_SESSION['location']['gp_name'].", ";
        }elseif(isset($_SESSION['location']['block_name'])) {
        echo $_SESSION['location']['block_name'].", ";
        }elseif(isset($_SESSION['location']['ps_name'])) {
        echo $_SESSION['location']['ps_name'].", ";
        }elseif(isset($_SESSION['location']['district_name'])) {
        echo $_SESSION['location']['district_name'].", ";
        } elseif(isset($_SESSION['location']['state_name'])) {
        echo $_SESSION['location']['state_name'].", ";
        } ?></h2><h3>
        <?php   
        echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        
        ?></h3>
    </div>
    <!-- Latest compiled and minified JavaScript -->
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12" style="width:98%">
                <h1 class="heading">Primary Details</h1>
                <div class="border"></div>
                </br>
                <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
                <div id="form_show" class="dashcontenr invisible"> 
                    <form class="form-horizontal" id="first_form" method="post" action="profile_entry_basic_insert.php" onsubmit="return valid_code();"> 
<!--                        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />-->
                        <input type="hidden" name="teacher_id_pk" id="teacher_id_pk" value="<?=$teacher_id_pk?>" />
                        <input type="hidden" name="tchcd" id="tchcd" value="<?=$tchcd?>" />
                        <input type="hidden" name="emp_date_retirement" id="emp_date_retirement" />
                        <input type="hidden" name="sec_tok1" id="sec_tok1" value="<?=$enc_token1?>" />
                        <?php if($msg)
						{
							echo $msg;
                        }
						if($error_msg)
						{?>
                            <br /><br />
                            <?php echo $error_msg;
						} ?>
                        <br />  
                        
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-3 control-label">Employee Type <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            <?php
								$db = new database();
								$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('366','367')  order by code");
                            ?>
                            <select class="form-control" name="emp_type" id="emp_type" onChange="emp_type_change(this.value);">
                                <option value="">-Please Select-</option>
                                <?php foreach($arr as $key){ $key['code']. '<br />'; ?>
                                <option value="<?= $key['code']; ?>" <?php  if($drpCast==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                <? } ?>
                            </select>
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label" style="display:none;" id="govt_id_lvl">Government ID</label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control upper_case" style="display:none;"  name="govt_id" id="govt_id" placeholder="Government ID" value="<?=$govt_id ?>">
                            </div> 
                        </div>        
                        
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-3 control-label">Name<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control upper_case" id="tch_fname"  name="tch_fname" placeholder="First Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$fname ?>">
                            </div>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control upper_case" id="tch_mname"  name="tch_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$mname ?>">
                            </div>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control upper_case" id="tch_lname"  name="tch_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?=$lname ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label">Date Of Birth<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" id="tch_dob" name="tch_dob" onChange="calculate_retireyear(this.value)" value="<?=$tch_dob=='0001-01-01' ? '' : $fun_store->date_frmt($dob);?>" placeholder="DD-MM-YYYY" readonly style="background-color:#FFF;cursor:pointer;" />
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Sex<span class="star_color">*</span></label>
                            <div class="col-sm-3">
								<?
                                $db = new database();
                                $arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('91','92','93') order by code");
                                ?>
                                <select class="form-control" name="drpSex" id="drpSex">
                                    <option value="">-Please Select-</option>
                                    <? foreach($arr as $key){ $key['code']. '<br />';?>
                                    <option value="<?= $key['code']; ?>" <? if(!empty($drpSex)){if($drpSex=$key['code']){ echo "selected";} }?>><?= $key['description']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label">Caste<span class="star_color">*</span></label>
                            <div class="col-sm-3">
								<?
                                $db = new database();
                                $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=3 and code like '10%' and code!='100' and code!='105' and code!='106' order by code");
                                ?>
                                <select class="form-control" name="drpCast" id="drpCast">
                                    <option value="">-Please Select-</option>
                                    <? foreach($arr as $key){ $key['code']. '<br />'; ?>
                                    <option value="<?= $key['code']; ?>" <? if($drpCast==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Voter ID<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control upper_case"  name="voter_id" id="voter_id" placeholder="Voter ID" value="<?=$voter_id ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-3 control-label">PAN NO <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control upper_case" maxlength="10" id="pan_no"  name="pan_no" placeholder="PAN NO" value="<?=$pan_no ?>">
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label" id="emp_quali_lvl">Educational Qualification<span class="star_color">*</span></label>
                            <div class="col-sm-3">
								<? $db = new database();
                                	$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('1211','1210','1202','1203','1204','1206','1209','1212')");
                                ?>
                                <select class="form-control" name="emp_quali" id="emp_quali">
                                    <option value="">-Please Select-</option>
                                    <? foreach($arr as $key){ $key['code']. '<br />'; ?>
                                    <option value="<?= $key['code']; ?>"<? if($emp_quali==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label">Whether Aadhaar card exist<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control" name="aadhar_status" id="aadhar_status" onChange="aadhar_view(this.value);">
                                    <option value="">Please Select</option>
                                    <option value="0" <? if($emp_aadhar_no=='') { echo "selected"; } ?>>NO</option>
                                    <option value="1" <? if($emp_aadhar_no!='') { echo "selected"; } ?>>YES</option>
                                </select>
                            </div>
                            <div style="display:none;" id="aadhar_div">
                                <label for="inputPassword3" class="col-sm-3 control-label">Aadhaar ID<span class="star_color">*</span></label>
                                <div class="col-sm-3">
                                	<input type="text" class="form-control upper_case" name="aadhaar_no" id="aadhaar_no" placeholder="Aadhaar ID" maxlength="12" onKeyPress="return keyRestrict(event,'0123456789 ');" value="<?=$emp_aadhar_no ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- <div class="form-group" style="display:none;" id="aadhar_div">
                            <label for="inputPassword3" class="col-sm-3 control-label">Aadhaar ID<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control upper_case" name="aadhaar_no" id="aadhaar_no" placeholder="Aadhaar ID" maxlength="12" onKeyPress="return keyRestrict(event,'0123456789 ');" value="<?=$emp_aadhar_no ?>">
                            </div>    
                        </div>-->
                        
                        <p style="border-top:1px dashed #27769F; text-align:center;"></p>
						<div class="row mb-3" style="margin-left: 41%;">
                            <div class="col-sm-offset-5 col-sm-7">
                            	<button type="submit" class="btn btn-info">SAVE & CONTINUE <i class="fa fa-chevron-right"></i></button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>
<?php 
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>


<script>
	function createdate(dateval)
	{
		// dateval must be in dd/mm/yyyy format	
		var x=dateval.split("-");
		var jvsdateval=new Date(x[2],parseInt(x[1]-1),parseInt(x[0]));
		return jvsdateval;
	}
	
	var DateDiff = {
						inDays: function(d1, d2) {
						var t2 = d2.getTime();
						var t1 = d1.getTime();
						return parseInt((t2-t1)/(24*3600*1000));
						},
						inWeeks: function(d1, d2) {
						var t2 = d2.getTime();
						var t1 = d1.getTime();
						return parseInt((t2-t1)/(24*3600*1000*7));
						},
						inMonths: function(d1, d2) {
						var d1Y = d1.getFullYear();
						var d2Y = d2.getFullYear();
						var d1M = d1.getMonth();
						var d2M = d2.getMonth();
						return (d2M+12*d2Y)-(d1M+12*d1Y);
						},
						inYears: function(d1, d2) {
						return d2.getFullYear()-d1.getFullYear();
						}
	}
	
	
	$(document).ready(function(){
		if($('#form_show').css("visibility")=="hidden")
		{
			$('#form_show').removeClass("invisible").css('height', 'auto');
		}
		
		if($('#aadhar_status').val()=='1')
		{
			$('#aadhar_div').show();
		}
		else
		{
			$('#aadhaar_no').val('');
			$('#aadhar_div').hide();
		}
		
		if($('#emp_type').val()=='366')
		{
			$('#govt_id_lvl').show();
			$('#govt_id').show();
			$('#emp_quali_lvl').hide();
			$('#emp_quali').hide();
		}
		else
		{
			$('#govt_id_lvl').hide();
			$('#govt_id').hide();
			$('#emp_quali_lvl').show();
			$('#emp_quali').show();
		}
	});
	
	function valid_code()
	{
		if($('#emp_type').val()=='')
		{
			alert('Please Select Your Employee Type.');
			$('#emp_type').focus();
			return false;
		}
		/*if($('#emp_type').val()=='366')
		{
			if($('#govt_id').val()=='')
			{
				alert('Please Enter Your Government ID.');
				$('#govt_id').focus();
				return false;
			}
		}*/
			
		if($('#tch_fname').val()=='' || $('#tch_fname').val()=='FIRST')
		{
			alert('Please Enter Your FIRST Name.');
			$('#tch_fname').focus();
			return false;
		}
		var dateformat = /^(((((0[1-9])|(1\d)|(2[0-8]))-((0[1-9])|(1[0-2])))|((31-((0[13578])|(1[02])))|((29|30)-((0[1,3-9])|(1[0-2])))))-((20[0-9][0-9]))|(29-02-20(([02468][048])|([13579][26]))))$/;
		if($('#tch_dob').val()=='')
		{
			alert('Please Enter Date of Birth.');
			$('#tch_dob').focus();
			return false;
		}	
		var today_obj = new Date();
		if($("#tch_dob").val()!='')
		{
			datediff = DateDiff.inDays(createdate($('#tch_dob').val()),today_obj);	
			if(datediff <= 6750)
			{
				alert('Date Of Birth must be greater than 18 year');
				$('#tch_dob').focus();
				return false;
			}
		}
		if($('#drpSex').val()=='')
		{
			alert('Please Select Your sex.');
			$('#drpSex').focus();
			return false;
		}
		if($('#drpCast').val()=='')
		{
			alert('Please select your cast.');
			$('#drpCast').focus();
			return false;
		}
		if($('#voter_id').val()=='')
		{
			alert('Please enter your voter id number.');
			$('#voter_id').focus();
			return false;
		}
		if($('#aadhar_status').val()=='')
		{
			alert('Please select Whether Aadhaar Card Present or not.');
			$('#aadhar_status').focus();
			return false;
		}
		if($('#aadhar_status').val()=='1')
		{
			if($('#aadhaar_no').val()=="")
			{
				alert('Please enter your aadhaar number.');
				$('#aadhar_status').focus();
				return false;
			}
		}
		if($('#pan_no').val()=='')
		{
			alert('Please enter your Permanent Account Number (PAN No.).');
			$('#pan_no').focus();
			return false;
		}
		else
		{
			var test=valid_pan();
			if(test==false)
			{
				$('#pan_no').focus();	
				return false;
			}	
		}
		if($('#emp_type').val()=='367')
		{
			if($('#emp_quali').val()=='')
			{
				alert('Please select educational qualification.');
				$('#emp_quali').focus();
				return false;
			}
		}
	}
	
	function valid_pan()
	{
		var pan=document.getElementById("pan_no").value;
		//var regpan = /^([a-zA-Z]){3}([P]){1}([a-zA-Z]){1}([0-9]){4}([a-zA-Z]){1}?$/;
		var regpan = /^([a-zA-Z]){3}([P]){1}([a-zA-Z]){1}([0-9]){4}([a-zA-Z]){1}/i;
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