<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
} else {
    $referer = '';
}

if ($referer == '') {
    // No referrer, redirect
    header("Location: ../../../../dashboard.php");
    exit;
}

if(
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
if($_GET['confirm'] == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Primary Details of the Employee submitted Successfully...</strong></div>';
}
else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}
$obj_crpto = new cryptography();

$true_stat=$obj_crpto->encode('true',4);
$false_stat=$obj_crpto->encode('false',4);

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
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}


function get_emp($id,$gp_id)
{
	$db  = new database();
	
	$obj_crpto = new cryptography();
	
	
	
	$data = $db->fetch_table("
							SELECT 
									  emp_id_const,
									  emp_id_pk,
									  gp_id_fk,
									  emp_first_name ,
									  emp_second_name ,
									  emp_last_name ,
									  emp_dob ,
									  emp_sex ,
									  emp_caste ,
									  emp_voter_id ,
									  emp_aadhar_no ,
									  emp_edu_quali,
									  emp_pension_status 
							FROM prd_employee_master
							WHERE emp_id_pk = '".$obj_crpto->decode($id,4)."'
							and gp_id_fk = '".$obj_crpto->decode($gp_id,4)."'
	
	");
	return $data;
}
if(!empty($_GET['emp_id_pk']))
{
	$emp_data = get_emp($_GET['emp_id_pk'],$_GET['gp_id']);
}
else
{
	$emp_data = get_emp($emp_id,$gpid);
}


$emp_id_const=$emp_data[0]['emp_id_const'];
$emp_first_name=$emp_data[0]['emp_first_name'];
$emp_second_name=$emp_data[0]['emp_second_name'];
$emp_last_name=$emp_data[0]['emp_last_name'];
$emp_dob=$emp_data[0]['emp_dob'];
$emp_sex=$emp_data[0]['emp_sex'];
$emp_caste=$emp_data[0]['emp_caste'];
$emp_voter_id=$emp_data[0]['emp_voter_id'];
$emp_aadhar_no=$emp_data[0]['emp_aadhar_no'];
$emp_edu_quali=$emp_data[0]['emp_edu_quali'];
$emp_id_pk=$emp_data[0]['emp_id_pk'];
$gp_id=$emp_data[0]['gp_id_fk'];
$emp_pension_status=$emp_data[0]['emp_pension_status'];
?>
<script>
	$(document).ready(function(){
		if($('#aadhar_status').val()=='1')
		{
			$('#aadhar_div').show();
		}
		else
		{
			$('#aadhar_div').hide();
		}
	});
		
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
		//child1 = new ajaxLoader('.child1');
		$.post('<?php echo $config['base_url'] ?>page/intra_prd/common/retirement_date.php',{employee_dob:employee_dob},function(data){
		if(data)
		{
			//if (child1) child1.remove();
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
			$('#aadhar_div').hide();
		}
	}
		
</script> 
<style>
	.form-horizontal .control-label 
	{
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
        <? echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
        ?></h3>
    </div>
    <!-- Latest compiled and minified JavaScript -->
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12" style="width:98%;">
            <h1 class="heading">Primary Details</h1>
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
            ?>
            <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
            <div id="form_show" class="dashcontenr invisible"> 
                <form class="form-horizontal" id="first_form" method="post" action="profile_entry_basic_edit_insert.php" onsubmit="return valid_code();">
                    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <input type="hidden" name="emp_id_pk" id="emp_id_pk" value="<?=$emp_id_pk?>" />
                    <input type="hidden" name="emp_date_retirement" id="emp_date_retirement" />
                    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                    <input type="hidden" name="gp_id" id="gp_id" value="<?= $gp_id ?>"  />
                    <input type="hidden" name="pension_stat" id="pension_stat" value="<?= $emp_pension_status ?>"  />
                    <input type="hidden" name="update_id" id="update_id" />
                    <input type="hidden" name="pension_id" id="pension_id" />
                   
                    <!--<div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">District:</label>
                    <div class="col-sm-2">
                    </div>
                    <label for="inputPassword3" class="col-sm-2 control-label">Block:</label>
                    <div class="col-sm-2">  
                    </div>
                    <label for="inputPassword3" class="col-sm-2 control-label">Gram Panchayet:</label>
                    <div class="col-sm-2">
                    </div>
                    </div>
                    <br/><br />-->
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label">Employee ID </label>
                        <div class="col-sm-3">
                        	<p class="text-muted" style="font-size:18px;font-weight:700;font-family: "MS Serif", "New York", serif;"><?= $emp_id_const; ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-3 control-label">Name<span class="star_color">*</span></label>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case" id="tch_fname"  name="tch_fname" placeholder="First Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $emp_first_name?>">
                        </div>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case" id="tch_mname"  name="tch_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $emp_second_name ?>">
                        </div>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case" id="tch_lname"  name="tch_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $emp_last_name ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label">Date Of Birth<span class="star_color">*</span></label>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control" id="tch_dob" name="tch_dob" onChange="calculate_retireyear(this.value)" value="<?=$emp_dob=='0001-01-01' ? '' : dateshow($emp_dob);?>" placeholder="DD-MM-YYYY"/>
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
                                <option value="<?= $key['code']; ?>" <? if($emp_sex==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
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
                                <option value="<?= $key['code']; ?>" <? if($emp_caste==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                <? } ?>
							</select>
                        </div>
                        <label for="inputPassword3" class="col-sm-3 control-label">Voter ID<span class="star_color">*</span></label>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case"  name="voter_id" id="voter_id" placeholder="Voter ID" value="<?= $emp_voter_id?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 control-label">Whether Aadhaar card exist<span class="star_color">*</span></label>
                        <div class="col-sm-3">
                        <!-- <input type="text" class="form-control upper_case" name="aadhaar_no" id="aadhaar_no" placeholder="Aadhaar ID" maxlength="12" onKeyPress="return keyRestrict(event,'0123456789 ');" value="<?=$aadhaar_no ?>">-->
                        
                            <select class="form-control" name="aadhar_status" id="aadhar_status" onChange="aadhar_view(this.value);">
                            <option value="">Please Select</option>
                            <option value="0" <? if($emp_aadhar_no=='') { echo "selected"; } ?>>NO</option>
                            <option value="1" <? if($emp_aadhar_no!='') { echo "selected"; } ?>>YES</option>
                            </select>
                        </div>
                        <label for="inputPassword3" class="col-sm-3 control-label">Educational Qualification<span class="star_color">*</span></label>
                        <div class="col-sm-3">
							<? $db = new database();
                            $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '12%' and code in('1210','1211','1202','1203','1204','1206','1209','1212') order by code");
                            ?>
                            <select class="form-control" name="emp_quali" id="emp_quali">
                                <option value="">-Please Select-</option>
                                <? foreach($arr as $key){ $key['code']. '<br />'; ?>
                                <option value="<?= $key['code']; ?>" <? if($emp_edu_quali==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                <? } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3" style="display:none;" id="aadhar_div">
                        <label for="inputPassword3" class="col-sm-3 control-label">Aadhaar ID<span class="star_color">*</span></label>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control upper_case" name="aadhaar_no" id="aadhaar_no" placeholder="Aadhaar ID" maxlength="12" onKeyPress="return keyRestrict(event,'0123456789 ');" value="<?=$emp_aadhar_no ?>">
                        </div>    
                    </div>
                    
                    <p style="border-top:1px dashed #27769F; text-align:center;"></p>
                    
                    <div class="row mb-3" style="    padding-left: 43%;">
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
	});
	
	function valid_code()
	{
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
		if($('#emp_quali').val()=='')
		{
			alert('Please select educational qualification.');
			$('#emp_quali').focus();
			return false;
		}
		var a=valid_updation();
		if(a!=true)
		{
			return false;
		}
	}
		
	function valid_updation()
	{
		var true_stat='<?php echo $true_stat; ?>';
		var false_stat='<?php echo $false_stat; ?>';
		
		var emp_first_name= '<?php echo $emp_first_name; ?>';
		var emp_second_name= '<?php echo $emp_second_name; ?>';
		var emp_last_name= '<?php echo $emp_last_name; ?>';
		var emp_dob= '<?php echo dateshow($emp_dob); ?>';
		var emp_sex= '<?php echo $emp_sex; ?>';
		var emp_caste= '<?php echo $emp_caste; ?>';
		var emp_voter_id= '<?php echo $emp_voter_id; ?>';
		var emp_aadhar_no= '<?php echo $emp_aadhar_no; ?>';
		var emp_edu_quali= '<?php echo $emp_edu_quali; ?>';
		
		var val_emp_first_name= $('#tch_fname').val().trim();
		var val_emp_second_name= $('#tch_mname').val().trim();
		var val_emp_last_name= $('#tch_lname').val().trim();
		var val_emp_dob= $('#tch_dob').val();
		var val_emp_sex= $('#drpSex').val();
		var val_emp_caste= $('#drpCast').val();
		var val_emp_voter_id= $('#voter_id').val().trim();
		var val_emp_aadhar_no= $('#aadhaar_no').val().trim();
		var val_emp_edu_quali= $('#emp_quali').val();
		
		if(emp_first_name!=val_emp_first_name || emp_second_name!=val_emp_second_name || emp_last_name!=val_emp_last_name || emp_dob!=val_emp_dob || emp_sex!=val_emp_sex || emp_caste!=val_emp_caste || emp_voter_id!=val_emp_voter_id || emp_aadhar_no!=val_emp_aadhar_no || emp_edu_quali!=val_emp_edu_quali)
		{
			$('#update_id').val(true_stat);
		}
		else
		{
			$('#update_id').val(false_stat);
		}
		if(emp_first_name!=val_emp_first_name || emp_second_name!=val_emp_second_name || emp_last_name!=val_emp_last_name || emp_dob!=val_emp_dob || emp_sex!=val_emp_sex || emp_voter_id!=val_emp_voter_id || emp_aadhar_no!=val_emp_aadhar_no)
		{
			$('#pension_id').val(true_stat);
		}
		else
		{
			$('#pension_id').val(false_stat);
		}
		
		return true;
		
	}
		
</script>
<?
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>