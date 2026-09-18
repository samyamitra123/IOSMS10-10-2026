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
$emp_id_pk=isset($_GET['emp_id_pk'])?$_GET['emp_id_pk']:' '; 
$cryptoGraph=new cryptography();

if($_GET['confirm'] == 'success')
{
	$msg='<div class="alert alert-success" style="text-align:center">
	<strong>Primary Details of the Employee submitted Successfully...</strong> 
	</div>';
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
}
*/

$fun_store=new zp_ps_gp_class();

$obj_crpto = new cryptography();

function get_emp($id)
{
	$db  = new database();
	$obj_crpto = new cryptography();
	$data = $db->fetch_table("
								SELECT 
								emp_desig ,
								emp_first_join_date ,
								emp_conf_join_date ,
								emp_join_prsnt_post_date ,
								emp_join_prsnt_office_date ,
								emp_retirement_date ,
								emp_termination_date ,
								emp_status_deputation ,
								emp_group ,
								emp_desig_first_app ,
								emp_next_increment_date ,
								emp_next_increment_amount ,
								conf_dt_flag,
								emp_form_status,
								notification_no,
								emp_pension_status,
								zp_emp_type
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


$emp_desig=$emp_data[0]['emp_desig'];
$emp_first_join_date=$emp_data[0]['emp_first_join_date'];
$emp_conf_join_date=$emp_data[0]['emp_conf_join_date'];
$emp_join_prsnt_post_date=$emp_data[0]['emp_join_prsnt_post_date'];
$emp_join_prsnt_office_date=$emp_data[0]['emp_join_prsnt_office_date'];
$emp_retirement_date=$emp_data[0]['emp_retirement_date'];
$emp_status_deputation=$emp_data[0]['emp_status_deputation'];
$emp_group=$emp_data[0]['emp_group'];
$emp_desig_first_app=$emp_data[0]['emp_desig_first_app'];
//$emp_next_increment_date=strcmp($emp_data[0]['emp_next_increment_date'],'01-01-1970')?'':$emp_data[0]['emp_next_increment_date']; 
$emp_next_increment_date=$emp_data[0]['emp_next_increment_date'];
// $emp_next_increment_amount=$emp_data[0]['emp_next_increment_amount']=='0.00'?'':$emp_data[0]['emp_next_increment_amount'];
$emp_next_increment_amount=$emp_data[0]['emp_next_increment_amount'];
$emp_form_status=$emp_data[0]['emp_form_status'];
$conf_dt_flag=$emp_data[0]['conf_dt_flag'];
$notification_no=$emp_data[0]['notification_no'];
$emp_pension_status=$emp_data[0]['emp_pension_status'];
$zp_emp_type=$emp_data[0]['zp_emp_type'];

?>
<script>
$(function() {
				$( "#first_join_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy' 
				});
				$( "#confirm_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+10",
				dateFormat: 'dd-mm-yy'
				});
				$( "#present_post_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+0",
				dateFormat: 'dd-mm-yy'
				});
				$( "#present_office_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+60",
				dateFormat: 'dd-mm-yy'
				});
				$( "#increment_date" ).datepicker({
				changeMonth: true,
				changeYear: true,
				yearRange: "-100:+50",
				dateFormat: 'dd-mm-yy'
				});
});
</script> 
<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>

<!-- Latest compiled and minified JavaScript -->
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
    
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12">
                <h1 class="heading">Professional Details</h1>
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
                <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
                <div id="form_show" class="dashcontenr invisible"> 
                    <form class="form-horizontal" name="teacher_info" id="teacher_info" method="post" action="profile_entry_prof_insert.php" onsubmit="return validateCode();">
                        <input type="hidden" name="emp_id_pk" value="<?=$employee_id  ?>" />
                        <input type="hidden" name="form_status" value="<?=$emp_form_status ?>" />
                        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                        <input type="hidden" name="desg" id="desg" value="<?= $emp_desig ?>" />
                        <input type="hidden" name="emp_type" id="emp_type" value="<?= $zp_emp_type ?>" />
                        <input type="hidden" name="edit_status" id="edit_status" value="<?php echo $obj_crpto->encode('1',4); ?>" />
                        <input type="hidden" name="emp_pension_status" id="emp_pension_status" value="<?php echo $obj_crpto->encode($emp_pension_status,4);?>"/>
                        
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Notification number of the Appointment<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" id="notice_no" name="notice_no"  placeholder="NOTIFICATION NO" autocomplete="off" value="<?php echo $notification_no; ?>" >
                            </div>
                            <label for="inputEmail3" class="col-sm-3 control-label">Designation<span class="star_color">*</span></label>
                            <div class="col-sm-3">
								<?
                                $db = new database();
                                $arr = $db->fetch_table("SELECT designation_id, designation_name from zpemp_emp_desig_master where status='1'");
                                ?>
                                <select class="form-control" name="vice_desig" id="vice_desig" onchange="showAcadmic(this.value)">
                                    <option value="">-Please Select-</option>
                                    <? foreach($arr as $key){
                                    $key['designation_id']. '<br />';?>
                                    <option value="<?= $key['designation_id']; ?>" 
                                    <? if($emp_desig==$key['designation_id']){ echo "selected";}?>><?= $key['designation_name']; ?></option> <? } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of First Joining in Service<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" name="first_join_date" id="first_join_date"  placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$emp_first_join_date=='0001-01-01' ? '' : $fun_store->date_frmt($emp_first_join_date);?>" readonly style="background-color:#FFF;cursor:pointer;">
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Confirmation in Service Applicable<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control" name="conf_dt_applicable" id="conf_dt_applicable" onchange="showConfirmDate(this.value)">
                                    <option>-Please Select-</option>
                                    <option value="1" <? if($conf_dt_flag=='1'){ echo "selected";}?>>Yes</option>
                                    <option value="0" <? if($conf_dt_flag=='0'){ echo "selected";}?>>No</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Joining in the Present Post <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" name="present_post_date" id="present_post_date" placeholder="DD-MM-YYYY" autocomplete="off"  value="<?=$emp_join_prsnt_post_date=='0001-01-01' ? '' : $fun_store->date_frmt($emp_join_prsnt_post_date);?>" readonly style="background-color:#FFF;cursor:pointer;"/>
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label confirm_date_label" style="display:none">Date of Confirmation in Service <span class="star_color">*</span></label>
                            <div class="col-sm-3 confirm_date_div" style="display:none"> 
                            	<input type="text" class="form-control" name="confirm_date" id="confirm_date" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$emp_conf_join_date=='0001-01-01' ? '' : $fun_store->date_frmt($emp_conf_join_date);?>" readonly style="background-color:#FFF;cursor:pointer;">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Joining in the Present Office <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" name="present_office_date" id="present_office_date" placeholder="DD-MM-YYYY" autocomplete="off"  value="<?=$emp_join_prsnt_office_date=='0001-01-01' ? '' : $fun_store->date_frmt($emp_join_prsnt_office_date);?>" readonly style="background-color:#FFF;cursor:pointer;">
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Retirement / Termination <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" name="emp_date_retirement" id="emp_date_termination" readonly placeholder="DD-MM-YYYY" autocomplete="off"  value="<?  echo $fun_store->date_frmt($emp_retirement_date); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label" id="deput_label" style="display:none">Whether on Deputation <span class="star_color">*</span></label>
                            <div class="col-sm-3" id="deput_field" style="display:none">
                                <select class="form-control" name="deputation" id="deputation" <?php if($zp_emp_type=='366') { echo "disabled";} ?>>
                                    <option value="">Please Select</option>
                                    <option value="1" <?php if($emp_status_deputation=='1' || $zp_emp_type=='366') { echo "selected" ;} ?>>YES</option>
                                    <option value="0" <?php if($emp_status_deputation=='0' && $zp_emp_type!='366') { echo "selected" ;} ?>>NO</option>
                                </select>
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label" id="group_label" style="display:none">Employee Group <span class="star_color">*</span></label>
                            <div class="col-sm-3" id="group_field" style="display:none">
								<?
                                $db = new database();
                                $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=3 and substring(code,1,2)='63' order by code"); ?>
                                <select class="form-control" name="employee_group" id="employee_group">
                                    <option value="">-Please Select-</option>
                                    <? foreach($arr as $key){ $key['code']. '<br />'; ?>
                                    <option value="<?= $key['code']; ?>" <? if($emp_group==$key['code'] || $employee_group==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Designation at First Appointment</label>
                            <div class="col-sm-3">
								<?
                                $db = new database();
                                $arr = $db->fetch_table("SELECT designation_id, designation_name from zpemp_emp_desig_master where status='1'");
                                ?>
                                <select class="form-control" name="first_desig" id="first_desig" onchange="showAcadmic(this.value)">
                                    <option value="">-Please Select-</option>
                                    <? foreach($arr as $key){
                                    $key['designation_id']. '<br />';?>
                                    <option value="<?= $key['designation_id']; ?>" 
                                    <? if($emp_desig_first_app==$key['designation_id']){ echo "selected";}?>><?= $key['designation_name']; ?></option> <? } ?>
                                </select>
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Next Increment <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control"  name="increment_date" id="increment_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<?=$emp_next_increment_date=='0001-01-01' || $emp_next_increment_date=='01-01-1970'  ? '' : $fun_store->date_frmt($emp_next_increment_date);?>" readonly style="background-color:#FFF;cursor:pointer;">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Amount of Increment <span class="star_color">(On Basic Pay)</span> <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" id="increment_amount" name="increment_amount" maxlength="7" placeholder="INCREMENT AMOUNT" autocomplete="off" value="<? if(!empty($emp_next_increment_amount)){ echo $emp_next_increment_amount; } else { echo $emp_next_increment_amount;}?>" onKeyPress="return keyRestrict(event,'0123456789.');">
                            </div>
                        </div>
                        
                        <p style="border-top:1px dashed #27769F; text-align:center;"></p>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-info" style="float:right">SAVE & CONTINUE <i class="fa fa-chevron-right"></i></button>
                                <a href="profile_entry_basic_edit.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>
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
	
	function calculate_retireyear(employee_dob)
	{
		$.post('page/intra_ehrms/common/retirement_date.php',{employee_dob:employee_dob},function(data){
		if(data)
		{
			$('#emp_date_retirement').val(data);
		}
		});
	}
	
	function showAcadmic(type)
	{
		if(type=='1')
		{
			$('#deput_label').hide();
			$('#deput_field').hide();
			$('#group_label').hide();
			$('#group_field').hide();
		}
		else
		{
			$('#deput_label').show();
			$('#deput_field').show();
			$('#group_label').show();
			$('#group_field').show();
		}
	}
	
	function showConfirmDate(val)
	{
		if(val=='1')
		{
			$('.confirm_date_label').show();
			$('.confirm_date_div').show();
		}
		else
		{
			$('.confirm_date_label').hide();
			$('.confirm_date_div').hide();
		}
	}
	
	function validateCode()
	{
		var dateformat = /^(((((0[1-9])|(1\d)|(2[0-8]))-((0[1-9])|(1[0-2])))|((31-((0[13578])|(1[02])))|((29|30)-((0[1,3-9])|(1[0-2])))))-((20[0-9][0-9]))|(29-02-20(([02468][048])|([13579][26]))))$/;
		var a= document.forms["teacher_info"]["first_join_date"].value;
		var b= document.forms["teacher_info"]["confirm_date"].value;
		var c= document.forms["teacher_info"]["present_post_date"].value;
		var d= document.forms["teacher_info"]["present_office_date"].value;
		var f= document.forms["teacher_info"]["increment_date"].value;
		
		var first_dt=createdate(a);
		var confirm_dt=createdate(b);
		var post_dt=createdate(c);
		var office_dt=createdate(d);
		var increment_dt=createdate(f);
		
		var first_time=first_dt.getTime();
		var confirm_time=confirm_dt.getTime();
		var post_time=post_dt.getTime();
		var office_time=office_dt.getTime();
		var increment_time=increment_dt.getTime();
		
		if (document.getElementById("notice_no").value==0 || document.getElementById("notice_no").value=='')
		{
			alert ( "Please enter Notification number of confirming the Appointment." ) ; 
			document.teacher_info.notice_no.focus();
			return false; 
		}
		
		if (document.teacher_info.vice_desig.selectedIndex == 0)
		{ 
			alert ( "Please select Designation." ) ; 
			document.teacher_info.vice_desig.focus();
			return false;
		}
		if(a==null || a=="" || a =="01-01-1970" || a =="01-01-0001")
		{
			alert("Please select Date of First Joining in Service.");
			document.forms["teacher_info"]["first_join_date"].focus();
			return false;
		}
		/*else
		{
			if(!a.match(dateformat))
			{
				alert('Please Insert a Valid Date66.');
				a.focus();
				return false;
			}
		}*/	
		
		if(document.getElementById("conf_dt_applicable").value=='1'){
		if(b==null || b=="" || b =="01-01-1970" || b =="01-01-0001")
		{
			alert("Please select Date of Confirmation in service.");
			document.forms["teacher_info"]["confirm_date"].focus();
			return false;
		}
		/*else
		{
			if(!b.match(dateformat))
			{
				alert('Please Insert a Valid Date55.');
				b.focus();
				return false;
			}
		}*/
		/*if(confirm_time<first_time){
		alert('Please Enter Valid Date Of Confirmation in service.');
		document.forms["teacher_info"]["confirm_date"].focus();
		return false;
		}*/
		}
		if(c==null || c=="" || c =="01-01-1970" || c =="01-01-0001")
		{
			alert("Please select Date of joining in the present post.");
			document.forms["teacher_info"]["present_post_date"].focus();
			return false;
		}
		/*else
		{
			if(!c.match(dateformat))
			{
				alert('Please Insert a Valid Date44.');
				c.focus();
				return false;
			}
		}*/	
		/*if(post_time<first_time){
		alert('Please Enter Valid Date Of joining in the present post.');
		document.forms["teacher_info"]["present_post_date"].focus();
		return false;
		}*/
		if(d==null || d=="" || d =="01-01-1970" || d =="01-01-0001")
		{
			alert("Please select Date of joining in the present office.");
			document.forms["teacher_info"]["present_office_date"].focus();
			return false;
		}
		/*else
		{
			if(!d.match(dateformat))
			{
				alert('Please Insert a Valid Date33.');
				d.focus();
				return false;
			}
		}*/
		/*if(office_time<first_time || office_time<confirm_time || office_time<post_time){
		alert('Please Enter Valid Date Of joining in the present office.');
		document.forms["teacher_info"]["present_office_date"].focus();
		return false;
		}	*/
		var e= document.forms["teacher_info"]["emp_date_termination"].value;
		if(e==null || e=="" || e =="01-01-1970" || e =="01-01-0001")
		{
			alert("Please select Date of Retirement/Termination.");
			document.forms["teacher_info"]["emp_date_termination"].focus();
			return false;
		}
		/*else
		{
			if(!e.match(dateformat))
			{
				alert('Please Insert a Valid Date22.');
				e.focus();
				return false;
			}
		}*/
		
		if(f==null || f=="" || f =="01-01-1970" || f =="01-01-0001")
		{
			alert("Please select Next Increment Date in Service.");
			document.forms["teacher_info"]["increment_date"].focus();
			return false;
		}
		/*else
		{
			if(!f.match(dateformat))
			{
				alert('Please Insert a Valid Date11.');
				a.focus();
				return false;
			}
		}*/	
		
		if($('#vice_desig').val()!='1')
		{
			if($('#deputation').val()=='')
			{
				alert('Please Select Whether on Deputation.');
				$('#deputation').focus();
				return false;
			}	
			if($('#employee_group').val()=='')
			{
				alert('Please Select Employee Group.');
				$('#employee_group').focus();
				return false;
			}
		}
		if(document.teacher_info.conf_dt_applicable.selectedIndex == 0)
		{
			alert('Please Select Date of Confirmation in Service Applicable.');
			$('#conf_dt_applicable').focus();
			return false;
		}
		if($('#increment_amount').val()=='')
		{
			alert('Please Enter Increment Amount.');
			$('#increment_amount').focus();
			return false;
		}
		
		
		var db_emp_desig = '<?php echo $emp_desig; ?>';
		var db_emp_first_join_date = '<?php echo $fun_store->date_frmt($emp_first_join_date); ?>';
		var db_emp_conf_join_date = '<?php echo $fun_store->date_frmt($emp_conf_join_date); ?>';
		if(db_emp_conf_join_date == '01-01-0001')
		{
			db_emp_conf_join_date='';
		}
		var db_emp_join_prsnt_post_date = '<?php echo $fun_store->date_frmt($emp_join_prsnt_post_date); ?>';
		var db_emp_join_prsnt_office_date = '<?php echo $fun_store->date_frmt($emp_join_prsnt_office_date); ?>';
		var db_emp_retirement_date = '<?php echo $fun_store->date_frmt($emp_retirement_date); ?>';
		var db_emp_status_deputation = '<?php echo $emp_status_deputation; ?>';
		var db_emp_group = '<?php echo $emp_group; ?>';
		var db_emp_desig_first_app = '<?php echo $emp_desig_first_app; ?>';
		var db_emp_next_increment_date = '<?php echo $fun_store->date_frmt($emp_next_increment_date); ?>';
		var db_emp_next_increment_amount = '<?php echo $emp_next_increment_amount; ?>';
		var db_conf_dt_flag = '<?php echo $conf_dt_flag; ?>';
		var db_notification_no = '<?php echo $notification_no; ?>';

		if((db_emp_desig != $('#vice_desig').val()) ||
		(db_emp_first_join_date != $('#first_join_date').val()) ||
		(db_emp_conf_join_date != $('#confirm_date').val()) ||
		(db_emp_join_prsnt_post_date != $('#present_post_date').val()) ||
		(db_emp_join_prsnt_office_date != $('#present_office_date').val()) ||
		(db_emp_status_deputation != $('#deputation').val()) ||
		(db_emp_group != $('#employee_group').val()) ||
		(db_emp_desig_first_app != $('#first_desig').val()) ||	
		(db_emp_next_increment_date != $('#increment_date').val()) ||
		(db_emp_next_increment_amount != $('#increment_amount').val()) ||
		(db_conf_dt_flag != $('#conf_dt_applicable').val())	||
		(db_notification_no != $('#notice_no').val())
		)
		{
			$('#edit_status').val('<?php echo $obj_crpto->encode('2',4); ?>');	
		}
	}
	
	$(document).ready(function() {		
		/*******************Visibility of Form*********************/
		if($('#form_show').css("visibility")=="hidden"){
		$('#form_show').removeClass("invisible").css('height', 'auto');
		}
		/*******************Form Submit****************************/
		$("#btnSubmit").click(function(){
		$("form").submit();
		});
		if(document.getElementById('desg').value=='1')
		{
			$('#deput_label').hide();
			$('#deput_field').hide();
			$('#group_label').hide();
			$('#group_field').hide();
		}
		else
		{
			$('#deput_label').show();
			$('#deput_field').show();
			$('#group_label').show();
			$('#group_field').show();
		}
		if(document.getElementById('conf_dt_applicable').value=='1')
		{
			$('.confirm_date_label').show();
			$('.confirm_date_div').show();
		}
		else
		{
			$('.confirm_date_label').hide();
			$('.confirm_date_div').hide();
		}
	});
</script>