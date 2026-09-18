<?php
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

//   Kalyan Ghosh   16/3/2017    Start
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '.$config['base_url']."page/login.php");
	exit;
}



if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
//   Kalyan Ghosh   16/3/2017    Finish

//$str=$_SESSION['location']['gpcode'];
//$state10=substr($str,0,4); 

$cryp = new cryptography();

$emp_id_pk=$cryp->decode($_GET['id'],4);
//$dise=$cryp->decode($_GET['gp_id'],4);
//$time_token=time();
//$_SESSION['security_token']=$time_token;
//$enc_token=md5('371371371'.$time_token);

error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);

$db=new database();

?>
<style>
	.form-horizontal .control-label 
	{
		text-align:left;
	}
</style>

<?php

$cryptoGraph=new cryptography();
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Professional Details of the Employee submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "WBULBHRMS | Govt. of West Bengal ";

//------------------------------------------------------- HEADER --------------------------------------------------------------
//require '../../../../page/municipality_admin/common.php';


?>
  
<!-- Latest compiled and minified JavaScript -->
    <div class="row" id="cont">
    <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
    <div class="col-sm-12">
<!--<h1 class="heading">Salary Details AS ON 1st January 2016 </h1>-->

<?php 
if($msg){
echo $msg;
echo "<br/>";
}
if($error_msg){
echo $error_msg;
echo "<br/>";
}

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

?>



<div><center><h1 class="heading">INSERT ADDITIONAL DATA IN EMPLOYEE PROFILE</h1></center></div>
<br />

    <?php $arr=$db->fetch_table("select* from prd_stake_epension_employee_profile where emp_id_fk='".$emp_id_pk."'  ");
	$momo_no= $arr[0]['first_momo_no'];
	$wef_date=$arr[0]['first_momo_wef_date'];
	$stake=$arr[0]['stake_level_id_fk']; 
	 $present_memo_no=$arr[0]['present_memo_no']; 
	$present_momo_date=$arr[0]['presnt_memo_wef_date']; 
	
 ?>


<strong style="color:#E93437;"><center><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></center></strong>
<div id="form_show" class="dashcontenr invisible">

    <form class="form-horizontal" id="loginForm" method="post" action="employee_epension_data_insert.php" onsubmit="return valid_code();">
    <input type="hidden" name="emp_id_pk" value="<?=$cryptoGraph->encode($emp_id_pk,4) ?>" />
    
    <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
    
    
    
    <div id="<?=$identity1.$identity2?>_div" class="loan_id" style="/*border: solid 1px #000000;*/"> 
    
  <div class="row mb-3" >
        <label for="inputPassword3" class="col-sm-2 control-label"> Present Joining Memo no <span class="star_color">(Memo No. of joining in the present post in present office)</span>:<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input  type="text" class="form-control" name="p_momo_no" id="p_memo_no" placeholder="MEMO NUMBER" value="<?php echo $present_memo_no;?>" autocomplete="off"   >
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label"> Present Joining Memo Date<span class="star_color">*</span></label>
        <div class="col-sm-4">
        	<input type="text" class="form-control" id="p_tch_date" name="p_tch_date"   value="<?php if(count($arr)==0){echo  $present_momo_date="";}else { echo  dateshow($present_momo_date);}?>" placeholder="DD-MM-YY" readonly style="background-color:#FFF;cursor:pointer;" />
        </div>
         
     </div>
    
    
     
     
     <div class="row mb-3" >
        <label for="inputPassword3" class="col-sm-2 control-label"> First Joining Memo no:<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input  type="text" class="form-control" name="momo_no" id="memo_no" placeholder="MEMO NUMBER" value="<?php echo $momo_no;?>" autocomplete="off"   >
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label"> First Joining Memo Date<span class="star_color">*</span></label>
        <div class="col-sm-4">
        	<input type="text" class="form-control" id="tch_date" name="tch_date"   value="<?php if(count($arr)==0){echo  $wef_date="";}else { echo  dateshow($wef_date);}?>" placeholder="DD-MM-YY" readonly style="background-color:#FFF;cursor:pointer;" />
        </div>
     </div>
     
     
      <div class="row mb-3" >
        
         <label for="inputPassword3" class="col-sm-2 control-label">First Joining In<span class="star_color">*</span></label>
        <div class="col-sm-4">
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
	 $district_id=$arr[0]['district_id_fk']; 
	  $display1= "style='display:none;'";
 }
 else if($stake=='556')
 {
	 $display1= "style='display:yes;'";
	 $display2= "style='display:none;'";
	 $ps_code=$arr[0]['ps_code'];
	 $district_id=$arr[0]['district_id_fk'];
	 //$display= "style='display:none;'";
 }
 else if($stake=='555')
 {
	 $display2= "style='display:yes;'";
	 $display= "style='display:none;'";
	 $display1= "style='display:none;'";
	 $block_id_fk=$arr[0]['block_id_fk'];
	 $district_id=$arr[0]['district_id_fk'];
	 $gp_code= $arr[0]['gp_code'];
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
 
 <label for="inputPassword3"  id="district_show" class="col-sm-2 control-label  " <?php echo $display;?>>District<span class="star_color">*</span></label>
 
 <div class="col-sm-4">
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
  
  
  <label for="inputPassword3"  class="col-sm-2 control-label" id="ps_show" <?php echo $display1;?>>PS<span class="star_color">*</span></label>
  
  <div class="col-sm-4">
  
   <select class="form-control" id="ps_code" name="ps_code"  <?php echo $display1;?> >
   
  <?php $arr = $db->fetch_table("select ps_id_pk,ps_name,ps_code,district_id_fk from prd_location_master_panchayat_samiti where district_id_fk='". $district_id."' or ps_status='5'");?>
                            	<option value="">-Please Select-</option>
                                <? foreach($arr as $key){ $key['district_id_fk']. '<br />'; ?>
                               <option value="<?= $key['ps_code']; ?>" <? if($ps_code==$key['ps_code']){ echo "selected";}?> ><?= $key['ps_name']; ?></option>
             
                               <? } ?>
              </select>
                            </div>
                            </div>  
    
    
    <div class="row mb-3"  >
    
    <label for="inputPassword3"  id="district_show_gp" class="col-sm-2 control-label  " <?php echo $display2;?>>District<span class="star_color">*</span></label>
    
    <div class="col-sm-4">
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
    <label for="inputPassword3"  class="col-sm-2 control-label" id="block_show" <?php echo $display2;?>>BLOCK<span class="star_color">*</span></label>
    
    <div class="col-sm-4">
    
   <?php  $arr=$db->fetch_table("select block_id_pk,block_name,block_code from prd_location_master_block where district_id_fk='".$district_id."' or block_status='5'");
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
    
    <label for="inputPassword3"  id="gp_show" class="col-sm-2 control-label  " <?php echo $display2;?>>GP<span class="star_color">*</span></label>
    
    <div class="col-sm-4">
    
    
  <?php   $arr=$db->fetch_table("select gp_id_pk,gp_name,gp_code from prd_location_master_gp where block_id_fk='".$block_id_fk."' or flag='5'"); ?>

    
    <select class="form-control" id="gp_code" name="gp_code" <?php echo $display2;?>>
    <option value="">-Please Select-</option>
    <? foreach($arr as $key){ $key['gp_code']. '<br />'; ?>
<option value="<?=$key['gp_code']; ?>" <? if($gp_code==$key['gp_code']){ echo "selected";}?>><?= $key['gp_name']; ?></option>
<? } ?>
    </select>
    </div>    
    </div>
 </div>

<p style="border-top:1px dashed #27769F; text-align:center; width:800px;"></p>
<div class="row mb-3" style="margin-left: 11%;">

<div class="col-sm-12" style="margin-left:30%;">
<!--<button type="submit" class="btn btn-info" >SAVE & CONTINUE <i class=""></i></button>-->





<input <?php if(($check_status_for_save_button[0]['save_butz_status'] < 1) && ($check_status_for_save_button_2[0]['save_butz_status'] > 0)){echo "style='display:none';";} ?> type="submit" class="btn btn-info" name="loan_deductions_submit" id="loan_deductions_submit" value="SAVE & CONTINUE" />

</div>
</div>

</form>
</div>



</div>
<div class="clear"></div>
    
<?
  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/municipality_admin/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>  
    <!--   kalyan ghosh 10/3/2017   start  --> 
    <script>
		
	
  $(document).ready(function(){
	 	if($('#form_show').css("visibility")=="hidden"){
			$('#form_show').removeClass("invisible").css('height', 'auto');
		}
		
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



function valid_code(){
		
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
			
			
	}

	
  </script>
  <?php  @pg_close($con); ?>
  
    <style>
  .loan_id
  	{
	  width:800px;
	  border:1px solid #000000;
	  padding:9px;
	  margin-bottom:5px;
	 }
  .delete_one
  	{
	  float:right;
	  }
  </style>