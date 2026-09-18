<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
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

$true_stat=$cryptoGraph->encode('true',4);
$false_stat=$cryptoGraph->encode('false',4);

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
									  emp_desig ,
									  gp_id_fk,
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
									  emp_pension_status,
									  recruitment_type
							FROM prd_employee_master
							WHERE emp_id_pk = '".$obj_crpto->decode($id,4)."'
							and gp_id_fk = '".$obj_crpto->decode($gp_id,4)."'
	
	");
	return $data;
}

function get_emp_addl($id)
{
	$db  = new database();
	
	$obj_crpto = new cryptography();

	
	$data=$db->fetch_table("select* from prd_stake_epension_employee_profile where emp_id_fk='".$obj_crpto->decode($id,4)."'  ");
	
	
	
	
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

if(!empty($_GET['emp_id_pk']))
{
	$emp_data_addl = get_emp_addl($_GET['emp_id_pk']);
}
else
{
   $emp_data_addl = get_emp_addl($emp_id);
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

//$emp_next_increment_amount=$emp_data[0]['emp_next_increment_amount']=='0.00'?'':$emp_data[0]['emp_next_increment_amount'];
$emp_next_increment_amount=$emp_data[0]['emp_next_increment_amount'];
$emp_form_status=$emp_data[0]['emp_form_status'];
$gp_id=$emp_data[0]['gp_id_fk'];
$conf_dt_flag=$emp_data[0]['conf_dt_flag'];
$emp_pension_status=$emp_data[0]['emp_pension_status'];
$recruitment_type = $emp_data[0]['recruitment_type'];
 $momo_no= $emp_data_addl[0]['first_momo_no']; 
$wef_date=$emp_data_addl[0]['first_momo_wef_date'];
$stake=$emp_data_addl[0]['stake_level_id_fk']; 
 $present_memo_no=$emp_data_addl[0]['present_memo_no']; 
$present_momo_date=$emp_data_addl[0]['presnt_memo_wef_date']; 

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
			
			$( "#tch_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			dateFormat: 'dd-mm-yy',
			//minDate:dateToday	 
			});
			
			$( "#p_tch_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			dateFormat: 'dd-mm-yy',
			//minDate:dateToday	 
			});
	});
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
        <? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name'];
        ?></h3>
    </div>
    <div class="row" id="cont">
        <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
            <div class="col-sm-12" style="width:98%;">
                <h1 class="heading">Professional Details</h1>
                <div class="border"></div>
                </br>
                <?php 
                if($msg){
                echo $msg;
                echo "<br/>";
                }
                if($error_msg){
                echo $error_msg;
                echo "<br/>";
                }
                ?>
                
                <strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
				<div id="form_show" class="dashcontenr invisible"> 
                    <form class="form-horizontal" name="teacher_info" id="teacher_info" method="post" action="profile_entry_prof_insert.php" onsubmit="return validateCode();">
                    
                        <input type="hidden" name="emp_id_pk" value="<?= $cryptoGraph->decode($_REQUEST['emp_id_pk'],4) ?>" />
                        <input type="hidden" name="form_status" value="<?=$emp_form_status ?>" />
                        <input type="hidden" name="gp_id" value="<?= $gp_id; ?>" />
                        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
                        <input type="hidden" name="desg" id="desg" value="<?= $emp_desig ?>" />
                        <input type="hidden" name="pension_stat" id="pension_stat" value="<?= $emp_pension_status ?>"  />
                        <input type="hidden" name="update_id" id="update_id" />
                        <input type="hidden" name="pension_id" id="pension_id" />
                        
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-3 control-label">Designation<span class="star_color">*</span></label>
                            <div class="col-sm-3">
								<?
                                $db = new database();
                                $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '11%' and code in('1114','1115','1116','1117','1118','1119','1120','1121','1122','1123','1124','1125') order by code");
                                ?>
                                <select class="form-control" name="vice_desig" id="vice_desig" onchange="showAcadmic(this.value)">
                                    <option value="">-Please Select-</option>
                                    <? 
                                    if(strlen($designation) == 1) 
                                    $designation = '110'.$designation;
                                    else if (strlen($designation) == 2)
                                    $designation = '11'.$designation;
                                    
                                    foreach($arr as $key){
                                    $key['code']. '<br />';
                                    ?>
                                    <option value="<?= $key['code']; ?>" 
                                    <? 
                                    if($emp_desig==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of First Joining in Service<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" name="first_join_date" id="first_join_date"  placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$emp_first_join_date=='0001-01-01' ? '' : dateshow($emp_first_join_date);?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Confirmation in Service Applicable<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control" name="conf_dt_applicable" id="conf_dt_applicable" onchange="showConfirmDate(this.value)">
                                <option>-Please Select-</option>
                                <option value="1" <? if($conf_dt_flag=='1'){ echo "selected";}?>>Yes</option>
                                <option value="0" <? if($conf_dt_flag=='0'){ echo "selected";}?>>No</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label confirm_date_label">Date of Confirmation in Service <span class="star_color">*</span></label>
                            <div class="col-sm-3 confirm_date_div" style="display:none">
                            	<input type="text" class="form-control" name="confirm_date" id="confirm_date" placeholder="DD-MM-YYYY" autocomplete="off" value="<?=$emp_conf_join_date=='0001-01-01' ? '' : dateshow($emp_conf_join_date);?>">
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Joining in the Present Post <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" name="present_post_date" id="present_post_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<?=$emp_join_prsnt_post_date=='0001-01-01' ? '' : dateshow($emp_join_prsnt_post_date);?>" />
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Joining in the Present Office <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" name="present_office_date" id="present_office_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<?=$emp_join_prsnt_office_date=='0001-01-01' ? '' : dateshow($emp_join_prsnt_office_date);?>">
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Retirement / Termination <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" name="emp_date_retirement" id="emp_date_termination"  readonly="readonly" placeholder="DD-MM-YYYY" autocomplete="off"  value="<? if(!empty($_REQUEST['emp_date_retirement'])) { echo $cryptoGraph->decode($_REQUEST['emp_date_retirement'],4); } else{ echo dateshow($emp_retirement_date);} ?>">
                            </div>
                        </div>
                        
                        
                        <div class="row mb-3" >
                            <label for="inputPassword3" class="col-sm-3 control-label"> Present Joining Memo no <span class="star_color">(Memo No. of joining in the present post in present office)</span>:<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            <input  type="text" class="form-control" name="p_momo_no" id="p_memo_no" placeholder="MEMO NUMBER" value="<?php echo $present_memo_no;?>" autocomplete="off"   >
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label"> Present Joining Memo Date<span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="p_tch_date" name="p_tch_date"   value="<?php if(count($arr)==0){echo  $present_momo_date="";}else { echo  dateshow($present_momo_date);}?>" placeholder="DD-MM-YY" readonly style="background-color:#FFF;cursor:pointer;" />
                            </div>
                    </div>
                        
                        
                <div class="row mb-3" >
                    <label for="inputPassword3" class="col-sm-3 control-label"> First Joining Memo no:<span class="star_color">*</span></label>
                    <div class="col-sm-3">
                    <input  type="text" class="form-control" name="momo_no" id="memo_no" placeholder="MEMO NUMBER" value="<?php echo $momo_no;?>" autocomplete="off"   >
                    </div>
                    <label for="inputPassword3" class="col-sm-3 control-label"> First Joining Memo Date<span class="star_color">*</span></label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control" id="tch_date" name="tch_date"   value="<?php if(count($arr)==0){echo  $wef_date="";}else { echo  dateshow($wef_date);}?>" placeholder="DD-MM-YY" readonly style="background-color:#FFF;cursor:pointer;" />
                    </div>
                </div>
                        
                <div class="row mb-3" >
                
					<label for="inputPassword3" class="col-sm-3 control-label">First Joining In<span class="star_color">*</span></label>
					<div class="col-sm-3">
					<?php 
					$transfer_level=$db->fetch_table("SELECT code, description, code_master_id_pk
					FROM prd_dise_code_master WHERE code IN ('555','556','557') AND length(code)=3
					");
					?>
					<select name="stake_lvl_select" style="width:100%;" id="stake_lvl_select" class="form-control">
					<option value="">---SELECT LEVEL---</option>
					<? foreach($transfer_level as $key)
					{
					
					?>
					<option value="<?=$key['code']?>" <? if($stake==$key['code']){ echo  "selected";}?>><?= $key['description']; ?></option>
					
					<? } ?>
					</select>
					</div>
                </div>
                
                
                
                
                
  <?php if($stake=='557')
 {
	 
	 $display= "style='display:yes;'";
	 $display1= "style='display:none;'";
	  $display2= "style='display:none;'";
	 $district_id=$emp_data_addl[0]['district_id_fk']; 
	  $display1= "style='display:none;'";
 }
 else if($stake=='556')
 {
	 $display1= "style='display:yes;'";
	 $display2= "style='display:none;'";
	 $ps_code=$emp_data_addl[0]['ps_code'];
	 $district_id=$emp_data_addl[0]['district_id_fk'];
	 //$display= "style='display:none;'";
 }
 else if($stake=='555')
 {
	 $display2= "style='display:yes;'";
	 $display= "style='display:none;'";
	 $display1= "style='display:none;'";
	 $block_id_fk=$emp_data_addl[0]['block_id_fk'];
	 $district_id=$emp_data_addl[0]['district_id_fk'];
	 $gp_code= $emp_data_addl[0]['gp_code'];
 }
 else
 {
	$display3= "style='display:none;'";
	$display2= "style='display:none;'";
	$display= "style='display:none;'";
	$display1= "style='display:none;'";

 }
 ?>
                
                
                
  <div class="row mb-3"  >
 
 <label for="inputPassword3"  id="district_show" class="col-sm-3 control-label  " <?php echo $display;?>>District<span class="star_color">*</span></label>
 
 <div class="col-sm-3">
  <?
							$db = new database();
							$arr = $db->fetch_table("select district_id_pk,district_name from prd_location_master_district");
							?>
							<select class="form-control" name="district_id" id="district_id" <?php echo $display;?> onchange="show_ps(this.value)">
                                <option value="">-Please Select-</option>
                                <? foreach($arr as $key){ $key['district_id_pk']. '<br />'; ?>
                                <option value="<?= $key['district_id_pk']; ?>" <? if($district_id==$key['district_id_pk']){ echo "selected";}?> ><?= $key['district_name']; ?></option>
                                <? } ?>
							</select>
  </div>
  
  
  <label for="inputPassword3"  class="col-sm-3 control-label" id="ps_show" <?php echo $display1;?>>PS<span class="star_color">*</span></label>
  
  <div class="col-sm-3">
  
   <select class="form-control" id="ps_code" name="ps_code"  <?php echo $display1;?> >
   
  <?php $arr = $db->fetch_table("select ps_id_pk,ps_name,ps_code,district_id_fk from prd_location_master_panchayat_samiti where district_id_fk='". $district_id."'");?>
                            	<option value="">-Please Select-</option>
                                <? foreach($arr as $key){ $key['district_id_fk']. '<br />'; ?>
                               <option value="<?= $key['ps_code']; ?>" <? if($ps_code==$key['ps_code']){ echo "selected";}?> ><?= $key['ps_name']; ?></option>
             
                               <? } ?>
              </select>
                            </div>
                            </div>  
    
    
    <div class="row mb-3">
    
		<label for="inputPassword3"  id="district_show_gp" class="col-sm-3 control-label  " <?php echo $display2;?>>District<span class="star_color">*</span></label>
		
		<div class="col-sm-3">
		<?
		$db = new database();
		$arr = $db->fetch_table("select district_id_pk,district_name from prd_location_master_district");
		?>
		<select class="form-control" name="district_gp" id="district_gp" <?php echo $display2;?> onchange="show_block(this.value)">
		<option value="">-Please Select-</option>
		<? foreach($arr as $key){ $key['district_id_pk']. '<br />'; ?>
		<option value="<?= $key['district_id_pk']; ?>" <? if($district_id==$key['district_id_pk']){ echo "selected";}?> ><?= $key['district_name']; ?></option>
		<? } ?>
		
		
		
		
		
		</select>
		</div>       
		<label for="inputPassword3"  class="col-sm-3 control-label" id="block_show" <?php echo $display2;?>>BLOCK<span class="star_color">*</span></label>
		
		<div class="col-sm-3">
		
	   <?php  $arr=$db->fetch_table("select block_id_pk,block_name,block_code from prd_location_master_block where district_id_fk='".$district_id."'");
	?>

	 
		
		<select class="form-control" id="block_id" name="block_id" <?php echo $display2;?> onchange="show_gp(this.value)">
		<option value="">-Please Select-</option>
	<? foreach($arr as $key){ $key['block_id_pk']. '<br />'; ?>
	<option value="<?= $key['block_id_pk']; ?>"<? if($block_id_fk==$key['block_id_pk']){ echo "selected";}?> ><?= $key['block_name']; ?></option>
	<? } ?>
		</select>
		</div>  
    </div>
                
                
    <div class="row mb-3"  >
    
		<label for="inputPassword3"  id="gp_show" class="col-sm-3 control-label  " <?php echo $display2;?>>GP<span class="star_color">*</span></label>
		
		<div class="col-sm-3">
		
		
	  <?php   $arr=$db->fetch_table("select gp_id_pk,gp_name,gp_code from prd_location_master_gp where block_id_fk='".$block_id_fk."'"); ?>

		
		<select class="form-control" id="gp_code" name="gp_code" <?php echo $display2;?>>
		<option value="">-Please Select-</option>
		<? foreach($arr as $key){ $key['gp_code']. '<br />'; ?>
	<option value="<?=$key['gp_code']; ?>" <? if($gp_code==$key['gp_code']){ echo "selected";}?>><?= $key['gp_name']; ?></option>
	<? } ?>
		</select>
		</div>    
    </div>
                        
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label" id="deput_label" style="display:none">Whether on Deputation <span class="star_color">*</span></label>
                            <div class="col-sm-3" id="deput_field" style="display:none">
                                <select class="form-control" name="deputation" id="deputation">
                                <option value="">Please Select</option>
                                <option value="1" <?php if($emp_status_deputation=='1') { echo "selected" ;} ?>>YES</option>
                                <option value="0" <?php if($emp_status_deputation=='0') { echo "selected" ;} ?>>NO</option>
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
                                    <option value="<?= $key['code']; ?>" <? if($emp_group==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-3 control-label">Designation at First Appointment</label>
                            <div class="col-sm-3">
								<?
                                $db = new database();
                                $arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '11%' and code in('1114','1115','1116','1117','1118','1119','1120','1121','1122','1123','1124','1125') order by code");
                                ?>
                                <select class="form-control" name="first_desig" id="first_desig">
                                <option value="">-Please Select-</option>
                                <? 
                                if(strlen($designation) == 1) 
                                $designation = '110'.$designation;
                                else if (strlen($designation) == 2)
                                $designation = '11'.$designation;
                                
                                foreach($arr as $key){
                                $key['code']. '<br />';
                                ?>
                                <option value="<?= $key['code']; ?>" 
                                <? 
                                if($emp_desig_first_app==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
                                <? } ?>
                                </select>
                            </div>
                            <label for="inputPassword3" class="col-sm-3 control-label">Date of Next Increment <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control"  name="increment_date" id="increment_date"  placeholder="DD-MM-YYYY" autocomplete="off"  value="<?=$emp_next_increment_date=='0001-01-01' || $emp_next_increment_date=='01-01-1970'  ? '' : dateshow($emp_next_increment_date);?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-3 control-label">Amount of Increment <span class="star_color">(On Basic Pay)</span>  <span class="star_color">*</span></label>
                            <div class="col-sm-3">
                            	<input type="text" class="form-control" id="increment_amount" name="increment_amount" maxlength="7"  placeholder="INCREMENT AMOUNT" autocomplete="off" value="<?=$emp_next_increment_amount;?>" onKeyPress="return keyRestrict(event,'0123456789.');">
                            </div>
                        </div>
					    <div class="row mb-3">
					    <label for="inputPassword3" class="col-sm-3 control-label">Employee Appointment Type <span class="star_color">(Promoted/Direct)</span> <span class="star_color">*</span></label>
					    <div class="col-sm-3">
					      <select class="form-control" id="recruitment_type" name="recruitment_type">
					      	<option value="0">Select Recruitment Type</option>
					      	<option value="D" <?php echo ($recruitment_type == 'D')?'selected':''; ?>>Direct</option>
					      	<option value="P" <?php echo ($recruitment_type == 'P')?'selected':''; ?> >Promoted</option>
					      </select>	
					    </div>
					  </div>                        
                        <p style="border-top:1px dashed #27769F; text-align:center;"></p>
                        
                        <div class="row mb-3" >
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-info" style="float:right">SAVE & CONTINUE <i class="fa fa-chevron-right"></i></button>
                                <a href="profile_entry_basic_edit.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>&gp_id=<? if(!empty($_GET['gp_id'])){ echo $_GET['gp_id']; } else { echo $gpid;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>
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
		//child1 = new ajaxLoader('.child1');
		$.post('page/intra_ehrms/common/retirement_date.php',{employee_dob:employee_dob},function(data){
		if(data)
		{
			//if (child1) child1.remove();
			$('#emp_date_retirement').val(data);
		}
		});
	}

	function showAcadmic(type)
	{
		if(type=='1120' || type=='1124' || type=='1125')
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
	else
	{
		if(a.match(dateformat)==false)
		{
			alert('Please Insert a Valid Date.');
			document.forms["teacher_info"]["first_join_date"].focus();
			return false;
		}
	}	
	if(document.getElementById("conf_dt_applicable").value=='1')
	{	
		if(b==null || b=="" || b =="01-01-1970" || b =="01-01-0001")
		{
			alert("Please select Date of Confirmation in service.");
			document.forms["teacher_info"]["confirm_date"].focus();
			return false;
		}
		else
		{
			if(b.match(dateformat)==false)
			{
				alert('Please Insert a Valid Date.');
				document.forms["teacher_info"]["confirm_date"].focus();
				return false;
			}
		}	
		if(confirm_time<first_time)
		{
			alert('Please Enter Valid Date Of Confirmation in service.');
			document.forms["teacher_info"]["confirm_date"].focus();
			return false;
		}
	}
	if(c==null || c=="" || c =="01-01-1970" || c =="01-01-0001")
	{
		alert("Please select Date of joining in the present post.");
		document.forms["teacher_info"]["present_post_date"].focus();
		return false;
	}
	else
	{
		if(c.match(dateformat)==false)
		{
			alert('Please Insert a Valid Date.');
			document.forms["teacher_info"]["present_post_date"].focus();
			return false;
		}
	}	
	if(post_time<first_time)
	{
		alert('Please Enter Valid Date Of joining in the present post.');
		document.forms["teacher_info"]["present_post_date"].focus();
		return false;
	}
	if(d==null || d=="" || d =="01-01-1970" || d =="01-01-0001")
	{
		alert("Please select Date of joining in the present office.");
		document.forms["teacher_info"]["present_office_date"].focus();
		return false;
	}
	else
	{
		if(d.match(dateformat)==false)
		{
			alert('Please Insert a Valid Date.');
			document.forms["teacher_info"]["present_office_date"].focus();
			return false;
		}
	}	
	/*if(office_time<first_time || office_time<confirm_time || office_time<post_time){
	alert('Please Enter Valid Date Of joining in the present office.');
	document.forms["teacher_info"]["present_office_date"].focus();
	return false;
	}*/	
	var e= document.forms["teacher_info"]["emp_date_termination"].value;
	if(e==null || e=="" || e =="01-01-1970" || e =="01-01-0001")
	{
		alert("Please select Date of Retirement/Termination.");
		document.forms["teacher_info"]["emp_date_termination"].focus();
		return false;
	}
	else
	{
		if(e.match(dateformat)==false)
		{
			alert('Please Insert a Valid Date.');
			document.forms["teacher_info"]["emp_date_termination"].focus();
			return false;
		}
	}
	if(f==null || f=="" || f =="01-01-1970" || f =="01-01-0001")
	{
		alert("Please select Next Increment Date in Service.");
		document.forms["teacher_info"]["increment_date"].focus();
		return false;
	}
	else
	{
		if(f.match(dateformat)==false)
		{
			alert('Please Insert a Valid Date.');
			document.forms["teacher_info"]["increment_date"].focus();
			return false;
		}
	}
	
	if($('#vice_desig').val()!='1120' && $('#vice_desig').val()!='1124' && $('#vice_desig').val()!='1125')
	{
		if($('#deputation').val()==''){
			alert('Please Select Whether on Deputation.');
			$('#deputation').focus();
			return false;
		}	
		if($('#employee_group').val()==''){
			alert('Please Select Employee Group.');
			$('#employee_group').focus();
			return false;
		}
	}
	if(document.teacher_info.conf_dt_applicable.selectedIndex == 0)
	{
		alert('Please Select Date of Confirmation in service Applicable.');
		$('#conf_dt_applicable').focus();
		return false;
	}
	if($('#increment_amount').val()=='')
	{
		alert('Please Enter Increment Amount.');
		$('#increment_amount').focus();
		return false;
	}
	if($('#memo_no').val() == '' || $('#memo_no').val() =='0')
			{
				alert('Please Enter First Joining Memo number.');
				$('#memo_no').focus();
				return false;
			}
			else if($('#tch_date').val() == '' || $('#tch_date').val() =='0')
			{
				alert('Please Enter First Joining Memo Date.');
				$('#tch_date').focus();
				return false;
			}
			
			if($('#p_memo_no').val() == '' || $('#p_memo_no').val() =='0')
			{
				alert('Please Enter Present Joining Memo number.');
				$('#p_memo_no').focus();
				return false;
			}
			else if($('#p_tch_date').val() == '' || $('#p_tch_date').val() =='0')
			{
				alert('Please Enter Present Joining Memo Date.');
				$('#p_tch_date').focus();
				return false;
			}
			else if($('#stake_lvl_select').val() == '' || $('#stake_lvl_select').val() =='0')
			{
				alert('Please Select Stake User.');
				$('#stake_lvl_select').focus();
				return false;
			}
			
			if($('#stake_lvl_select').val()=='557')
			{
			
			
				if($('#district_id').val() == '' || $('#district_id').val() =='0')
				{
					alert('Please Select District');
					$('#district_id').focus();
					return false;
				}
			
			}
			
			if($('#stake_lvl_select').val()=='556')
			{
			
			
				if($('#district_id').val() == '' || $('#district_id').val() =='0')
				{
					alert('Please Select District');
					$('#district_id').focus();
					return false;
				}
				else if($('#ps_code').val() == '' || $('#ps_code').val() =='0')
				{
					alert('Please Select PS ');
					$('#ps_code').focus();
					return false;
				}
				
			
			}
			
			if($('#stake_lvl_select').val()=='555')
			{
			
				if($('#district_gp').val() == '' || $('#district_gp').val() =='0')
				{
					alert('Please Select District');
					$('#district_gp').focus();
					return false;
				}
				else if($('#block_id').val() == '' || $('#block_id').val() =='0')
				{
					alert('Please Select Block ');
					$('#block_id').focus();
					return false;
				}
				else if($('#gp_code').val() == '' || $('#gp_code').val() =='0')
				{
					alert('Please Select GP ');
					$('#gp_code').focus();
					return false;
				}
				
			
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
		
		var emp_desig= '<?php echo $emp_desig; ?>';
		var emp_first_join_date= '<?php echo dateshow($emp_first_join_date); ?>';
		var emp_conf_join_date= '<?php if($emp_conf_join_date='0001-01-01'){ echo "";}else{ echo dateshow($emp_conf_join_date);} ?>';
		var emp_join_prsnt_post_date= '<?php echo dateshow($emp_join_prsnt_post_date); ?>';
		var emp_join_prsnt_office_date= '<?php echo dateshow($emp_join_prsnt_office_date); ?>';
		var emp_retirement_date= '<?php echo dateshow($emp_retirement_date); ?>';
		var emp_status_deputation= '<?php echo $emp_status_deputation; ?>';
		var emp_group= '<?php echo $emp_group; ?>';
		var emp_desig_first_app= '<?php if($emp_desig_first_app=="0"){echo "";}else{echo $emp_desig_first_app;} ?>';
		var emp_next_increment_date= '<?php echo dateshow($emp_next_increment_date); ?>';
		var emp_next_increment_amount= '<?php echo $emp_next_increment_amount; ?>';
		
		var val_emp_desig= $('#vice_desig').val();
		var val_emp_first_join_date= $('#first_join_date').val();
		var val_emp_conf_join_date= $('#confirm_date').val();
		var val_emp_join_prsnt_post_date= $('#present_post_date').val();
		var val_emp_join_prsnt_office_date= $('#present_office_date').val();
		var val_emp_retirement_date= $('#emp_date_termination').val();
		var val_emp_status_deputation= $('#deputation').val();
		var val_emp_group= $('#employee_group').val();
		var val_emp_desig_first_app= $('#first_desig').val();
		var val_emp_next_increment_date= $('#increment_date').val();
		var val_emp_next_increment_amount= $('#increment_amount').val().trim();
		
		if(emp_desig!=val_emp_desig || emp_first_join_date!=val_emp_first_join_date || emp_conf_join_date!=val_emp_conf_join_date || emp_join_prsnt_post_date!=val_emp_join_prsnt_post_date || emp_join_prsnt_office_date!=val_emp_join_prsnt_office_date || emp_retirement_date!=val_emp_retirement_date || emp_status_deputation!=val_emp_status_deputation || emp_group!=val_emp_group || emp_desig_first_app!=val_emp_desig_first_app || emp_next_increment_date!=val_emp_next_increment_date || emp_next_increment_amount!=val_emp_next_increment_amount)
		{
			//alert(12);
			$('#update_id').val(true_stat);
		}
		else
		{
			//alert(13);
			$('#update_id').val(false_stat);
		}
		if(emp_desig!=val_emp_desig || emp_first_join_date!=val_emp_first_join_date || emp_conf_join_date!=val_emp_conf_join_date || emp_join_prsnt_post_date!=val_emp_join_prsnt_post_date || emp_join_prsnt_office_date!=val_emp_join_prsnt_office_date || emp_retirement_date!=val_emp_retirement_date || emp_group!=val_emp_group || emp_desig_first_app!=val_emp_desig_first_app  ||  emp_next_increment_date!=val_emp_next_increment_date || emp_next_increment_amount!=val_emp_next_increment_amount)
		{
			//alert(15);
			$('#pension_id').val(true_stat);
		}
		else
		{
			//alert(16);
			$('#pension_id').val(false_stat);
		}
		
		return true;
	
	}


$(document).ready(function() {		
	/*******************Visibility of Form*********************/
	if($('#form_show').css("visibility")=="hidden")
	{
		$('#form_show').removeClass("invisible").css('height', 'auto');
	}
	/*******************Form Submit****************************/
	$("#btnSubmit").click(function()
	{
		//alert(123);
		$("form").submit();
	});
	if(document.getElementById('desg').value=='1120' || document.getElementById('desg').value=='1124' || document.getElementById('desg').value=='1125')
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


$('#stake_lvl_select').change(function(e) 
	{
		var id=$(this).val();
		var emp_desig=$('#desig').val();
		var user=$('#logged_user').val();
		
		if(id=='555')
		{
			$('#district_show').hide();
			$('#district_id').hide();
			$('#ps_show').hide();
			$('#ps_code').hide();
			$('#ps_show').val('');
			$('#ps_code').val('')
			$('#district_show').val('');
			$('#district_id').val('');
			$('#district_show_gp').show();
			$('#district_gp').show();
			$('#block_show').show();
			$('#block_id').show();
			$('#gp_show').show();
			$('#gp_code').show();
			
		}
		else if(id=='556')
		{
			$('#district_show').show();
			$('#district_id').show();
			$('#ps_show').show();
			$('#ps_code').show();
			$('#district_show_gp').hide();
			$('#district_gp').hide();
			$('#block_show').hide();
			$('#block_id').hide();
			$('#gp_show').hide();
			$('#gp_code').hide();
			
			$('#district_show_gp').val('');
			$('#district_gp').val('');
			$('#block_show').val('');
			$('#block_id').val('');
			$('#gp_show').val('');
			$('#gp_code').val('');
			
			
						
		}
		else if(id=='557')
		{
			$('#district_id').val('');
			$('#district_show').show();
			$('#district_id').show();
			$('#ps_show').hide();
			$('#ps_code').hide();
			$('#district_show_gp').hide();
			$('#district_gp').hide();
			$('#block_show').hide();
			$('#block_id').hide();
			$('#gp_show').hide();
			$('#gp_code').hide();
			
			
			$('#ps_show').val('');
			$('#ps_code').val('');
			$('#district_show_gp').val('');
			$('#district_gp').val('');
			$('#block_show').val('');
			$('#block_id').val('');
			$('#gp_show').val('');
			$('#gp_code').val('');
		}
		
	});
	
	
	function show_block(val)
	{
		$.post('<?= $config['base_url'] ?>page/all_moduls/update_epension_emp_profile/ajax_block_details.php?district='+val, function(data){
		$("#block_id").html(data);	
		});
	}
	
	function show_gp(vall)
	{
		var old_gp=''+$('#gp_id_fk').val()+'';
		$.post('<?= $config['base_url'] ?>page/all_moduls/update_epension_emp_profile/ajax_gp_details.php?block='+vall+'&old_gp='+old_gp, function(data){
		$("#gp_code").html(data);	
		});
	}
	
	function show_ps(val)
	{
		$.post('<?= $config['base_url'] ?>page/all_moduls/update_epension_emp_profile/ajax_ps_details.php?district='+val, function(data){
		$("#ps_code").html(data);	
		});
	}

</script>