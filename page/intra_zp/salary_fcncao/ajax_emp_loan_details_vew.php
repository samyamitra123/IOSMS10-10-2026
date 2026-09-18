<?php
ob_start();
session_start();

//$str=$_SESSION['location']['gpcode'];
//$state10=substr($str,0,4); 
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
//require '../../../page_visite.php';
require_once '../../../includes/library/cryptography.class.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '.$config['base_url']."page/login.php");
	exit;
}

error_reporting(0);

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");



$cryp = new cryptography();
 //$empcd=$cryp->decode($id[4],4);
//$empcd=$cryp->decode($_REQUEST['id'],4);
$emp_id_pk=$cryp->decode($_GET['id'],4); 
//die;
$dise=$cryp->decode($_GET['mu_id'],4);
//echo $dise.'<br>';
//echo $empcd.'<br>';
//$dise=$cryp->decode($id[5],4);
$time_token=time();
//$_SESSION['security_token']=$time_token;
//$enc_token=md5('371371371'.$time_token);

/*if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
if(!isset($_GET['id']) && !isset($_GET['dise'])){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}*/
//echo $_GET['dise'].'---------';
//$dise = $cryp->decode($_GET['dise'], 3);
	//echo $_GET['id'];
//require 'includes/library/session.class.php';
$db = new database();




                 /* $dedact = $db->fetch_table("select id,deduction_type from mad_salary_deduction_master where id=1"); 
                   $dedact[0]['id']; */
                 

						//	echo $tch[0]['emp_id_pk']; 
								


error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
$id=$tch[0]['emp_id_pk']; 
//$id=isset($_GET['id'])?$_GET['id']:' ';
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';
$db=new database();
//$bank_details=$db->fetch_table("SELECT id, bank_name, bank_code, bank_ifsc, digit_in_account_no, status FROM mad_dise_bank_master where status=1;");

$emp_data = $db->fetch_table("SELECT   
                                        tch.slno,
										tch.emp_pay_in_payband,
										tch.emp_grade_pay,
										tch.emp_pay_band,
										tch.emp_pay_scale,
										tch.interim_relief,
										tch.increment_status,
										tch.basic
										
									FROM
										mad_interim_relief_sal as tch
									WHERE
										tch.emp_id_fk = '".$emp_id_pk."'
											
											
								");
								

	$data= $db->fetch_table("
								SELECT  
										tch.emp_id_pk,
										tch.emp_first_join_date,
										tch.emp_id_const
										
									FROM
										mad_employee_master as tch
								WHERE
										emp_id_pk = '".$emp_id_pk."'
						
											
		
		");
	
	 $emp_first_join_date=$data[0]['emp_first_join_date'];
	
     $emp_first_join_match_date=date('Y-m-d', strtotime('+6 month',strtotime($emp_first_join_date)));
			
			 if(strtotime($emp_first_join_match_date)<=strtotime('2016-07-01'))
				    {
					
						 $emp_first_join_val=1;				
					}
					else
					{
						 $emp_first_join_val=0;
						}

?>
<style>
        .form-horizontal .control-label {
			text-align:left;
			}
        </style>

<?php

//$emp_id_pk=isset($_GET['emp_id_pk'])?$_GET['emp_id_pk']:' ';
$cryptoGraph=new cryptography();
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Professional Details of The Employee Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data Insertion Failed. Please Try Again...</strong></div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "WBPRD | Govt. of West Bengal";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
//require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
//require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------

?>
<script>
function payband(val){
 $.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/ajax_payscale_details.php?payband='+val, function(data){
	 $(".payscale").html(data);	 
	 $.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/ajax_gradepay_details.php?payband='+val, function(data){
	 $(".gradepay").html(data);	 
 });
 });
}
</script>
<?

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}
	
        $emp_count=count($emp_data); 
		$emp_pay_band=$emp_data[0]['emp_pay_band'];
	    $emp_pay_in_payband=$emp_data[0]['emp_pay_in_payband'];
	    $emp_grade_pay=$emp_data[0]['emp_grade_pay'];
	    $emp_pay_scale=$emp_data[0]['emp_pay_scale'];  
		$interim_relief=$emp_data[0]['interim_relief']; 
	    $increment_status=$emp_data[0]['increment_status'];
	    $new_pay_in_paband_val=$emp_data[0]['new_pay_in_payband'];
?>
 

<? //require '../../../../page/common_back_btns.php'; ?>
  
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
/*if(!empty($_GET['emp_id_pk'])){
$employee_id=$cryptoGraph->decode($_REQUEST['emp_id_pk'],4);
}
else{
$employee_id=$cryptoGraph->decode($emp_id,4);	
}*/

?>
    
<form class="form-horizontal" id="loginForm" method="post" action="" onsubmit="return valid_code();">
<!--<input type="hidden" name="desig" id="desig" value=<?= $cryptoGraph->decode($_GET['desig'],4); ?>  />-->
<input type="hidden" name="emp_id_pk" value="<?=$cryptoGraph->encode($emp_id_pk,4) ?>" />
 <!--<input type="hidden" name="emp_count" value="<?=$emp_count ?>" />
 <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
<input type="hidden" name="emp_first_join_val" id="emp_first_join_val" value="<?=$emp_first_join_val ?>" />
<input type="hidden" name="new_pay_in_payband_val" id="new_pay_in_payband_val"  value="<? if(!empty($new_pay_in_paband_val)){ echo $new_pay_in_paband_val; }else{ echo 0;} ?>"/>
-->
<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
 <?php 
 
 $db = new database();
 $loan_master_data = $db->fetch_table("SELECT   
										loan_master_id_pk,
										loan_type,
										dise_code,
										status,
										loan_type_variable

									FROM
										prd_master_loan_type
									WHERE
											 status='1' 
								");
								foreach ($loan_master_data as $key) {
									
			$get_loan_variables = $db->fetch_table("SELECT COUNT(*) AS count FROM prd_loan_deduction WHERE status not in (0) AND deduction_loan_type_variable = '".$key['loan_type_variable']."' AND emp_id_fk = '".$emp_id_pk."'");
			
			echo '&nbsp;&nbsp; &nbsp;&nbsp;';
			if($get_loan_variables[0]['count'] > 0)
			{
				$checked = "checked";
				$onclick = "onclick='return false;'";
			}
			else
			{
				$checked = "";
				$onclick = "";
			}
			/*echo	'<input '.$onclick.' '.$checked.' type="checkbox" id="'.$key['loan_type_variable'].'" ori_name="'.$key['loan_type'].'" name="loan_type[]" value="'.$key['loan_type_variable'].'" onclick="dynInput(\''.$key['loan_type_variable'].'\');" />';
	
			echo $key['loan_type'];*/
						}
 
 ?>
<p id="insertinputs"></p>   
 <div id="add_rows">
 <?php
 $loan_deduction_data = $db->fetch_table("SELECT   
										total_amount,
										due_amount,
										loan_id_pk,
										no_of_installment,
										installment_amount,
										reminder_amount,
										dise_code_fk,
										status,
										approval_status,
										deduction_loan_type_variable,
										loan_name

									FROM
										prd_loan_deduction
									WHERE
									    emp_id_fk = '".$emp_id_pk."' AND status not in(0) 
										AND status in ('2') AND approval_status in ('3')");
	
	$i=0;
	$pre_saved_div_ids = array();
	$identity2 = 1;
	
	foreach($loan_deduction_data as $deductions)
	{
	$loan_type = $db->fetch_table("SELECT loan_type FROM prd_master_loan_type WHERE dise_code = '".$deductions['dise_code_fk']."'");
	$identity1 ="loan_";
	//$identity2 = $deductions['loan_div_id'];
?>		
	<div id="<?=$identity1.$identity2?>_div" class="loan_id" style="/*border: solid 1px #000000;*/"> 
    	<input type="hidden" value="<?=$deductions['status']?>" name="<?=$identity1.$identity2?>_status"  />
        <input type="hidden" value="<?=$deductions['approval_status']?>" name="<?=$identity1.$identity2?>_approval_status"  />
        <input type="hidden" value="<?=$deductions['lock_status']?>" name="<?=$identity1.$identity2?>_lock_status"  />
        <input type="hidden" value="<?=$deductions['loan_id_pk']?>" name="<?=$identity1.$identity2?>_loan_id_pk"  />
        
    <div class="form-group" >
        <!--<center><h3 id="<?=$deductions['deduction_loan_type_variable']?>_heading"><?php echo strtoupper($loan_type[0]['loan_type']); ?></h3></center>-->
        <label for="inputPassword3" class="col-sm-2 control-label">Loan Type<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <label style='background-color:#d3d3d3;' class="form-control" id="<?=$identity1.$identity2?>_types" name="<?=$identity1.$identity2?>_types">
<?php
									
			$get_loan_variables = $db->fetch_table("SELECT loan_type_variable, loan_type FROM prd_master_loan_type WHERE status in ('1') AND loan_type_variable = '".$deductions['deduction_loan_type_variable']."'");
			//echo '&nbsp;&nbsp; &nbsp;&nbsp;';
			/*if($get_loan_variables[0]['count'] > 0)
			{
				$checked = "checked";
				$onclick = "onclick='return false;'";
			}
			else
			{
				$checked = "";
				$onclick = "";
			}*/
			/*echo	'<input class="loan_type_checkbox" '.$onclick.' '.$checked.' type="checkbox" id="'.$key['loan_type_variable'].'" ori_name="'.$key['loan_type'].'" name="loan_type[]" value="'.$key['loan_type_variable'].'" onclick="dynInput(\''.$key['loan_type_variable'].'\');" />';
	
			echo $key['loan_type'];*/
?>

			<option selected value="<?=$get_loan_variables[0]['loan_type_variable']?>"><?=$get_loan_variables[0]['loan_type']?></option>
          </label>
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label"><span class="star_color"></span></label>
        <div class="col-sm-4">
          
        </div>
    </div>
	<div class="form-group" >
        <label for="inputPassword3" class="col-sm-2 control-label">Total Repayment Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3;'"; } ?>readonly style='background-color:#d3d3d3;' value="<?=$deductions['total_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_total_amt" id="<?=$identity1.$identity2?>_total_amt" placeholder="Total Repayment Amount" autocomplete="off" value="" maxlength="10" onkeyup="<?php echo 'return due_calculation(\''.$identity2.'\');'?>">
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label">Due Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php echo "readonly style='background-color:#d3d3d3';"; ?> value="<?=$deductions['due_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_due_amt" id="<?=$identity1.$identity2?>_due_amt" placeholder="Due Amount" autocomplete="off" value="" maxlength="10" >
        </div>
     </div>
     <div class="form-group" >
     	<label for="inputPassword3" class="col-sm-2 control-label">Installation Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input readonly style='background-color:#d3d3d3;' value="<?=$deductions['installment_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_install_amt" id="<?=$identity1.$identity2?>_install_amt" placeholder="Installation Amount" autocomplete="off" value="" maxlength="10" onkeyup="<?php echo 'return installment_calculation(\''.$identity2.'\');'?>">
     	</div>
        <label for="inputPassword3" class="col-sm-2 control-label">No. of Installments<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3';"; } ?>readonly style='background-color:#d3d3d3;' value="<?=$deductions['no_of_installment']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_no_of_installment" id="<?=$identity1.$identity2?>_no_of_installment" placeholder="No of Installment" autocomplete="off" maxlength="10">
        </div>
     </div>
     
     <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Remainder Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3';"; } ?>readonly style='background-color:#d3d3d3;' value="<?=$deductions['reminder_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_reminder_amt" id="<?=$identity1.$identity2?>_reminder_amt" placeholder="Remainder Amount" autocomplete="off" maxlength="10">
        </div>
        
        <label for="inputPassword3" class="col-sm-2 control-label">Loan Name<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input value="<?=$deductions['loan_name']?>" readonly style='background-color:#d3d3d3;' type="text" class="form-control" name="<?=$identity1.$identity2?>_loan_name" id="<?=$identity1.$identity2?>_loan_name" placeholder="Loan Name" autocomplete="off" maxlength="50">
        </div>
      </div>
	</div>
<?php
	$pre_saved_div_ids[$i] = $identity2;
	$i=$i+1;
	$identity2=$identity2+1;	
	}
$all_div_ids = implode(',', $pre_saved_div_ids);
?>
   
 </div>
  <p style="border-top:1px dashed #27769F; text-align:center; width:800px;"></p>
  <?php /*?><div class="form-group">
    <div class="col-sm-12" align="center">
    <!--<button type="submit" class="btn btn-info" >SAVE & CONTINUE <i class=""></i></button>-->
    <input type="submit" class="btn btn-info" name="loan_deductions_submit" value="SAVE & CONTINUE" />
      <!-- <a href="profile_entry_prof.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>-->
    </div>
  </div><?php */?>
  
    
    
</form>

        </div>
      </div>

    </div>
        <div class="clear"></div>
    
<?
@pg_close($con);
  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>  
  <script>
	$(document).ready(function(value) {
		$('#conf_increment_applicable').click(function(value) {
			
		var conf_increment_applicable=$('#conf_increment_applicable').val();
		var grade_pay=$("#grade_pay option:selected").text(); 
		var pay_in_payband=$('#pay_in_payband').val();
		if(pay_in_payband=='')
		     {
				pay_in_payband=0;
				
			}
			
		
		var basic=parseInt(grade_pay)+parseInt(pay_in_payband);
		$('#new_pay_in_payband_val').val(basic);
		//var emp_first_join_value=$('#emp_first_join_val').val();
		if(grade_pay>0 &&  pay_in_payband>0)
		{
		if(conf_increment_applicable=='0')
		{
		/*	if(emp_first_join_value=='0')
		    {
		      var interim_relief=(parseInt(pay_in_payband)*10)/100;
		      $('#new_pay_in_payband_val').val(pay_in_payband);
		     }
		  else{*/
		  
		  
		  
		  
		  
			  var increment_val=(parseInt(basic)*3)/100;
			  if(Math.floor(increment_val)%10==0){
						//var promo_basic=parseInt(basic)+(Math.floor(promo_inc_pay/10)*10);
						var increment=(Math.floor(increment_val/10)*10);
					}else{
						//var promo_basic=parseInt(basic)+(Math.ceil(promo_inc_pay/10)*10);
						var increment=(Math.ceil(increment_val/10)*10);
					}
			  var new_pay_in_payband=parseInt(pay_in_payband)+parseInt(increment);
			  var interim_relief_val=((parseInt(new_pay_in_payband)*10)/100);
			  interim_relief= Math.round(interim_relief_val);
			//  $('#new_pay_in_payband_val').val(new_pay_in_payband);
			
		   //}
		   
		}
		else if(conf_increment_applicable=='1')
		{
			 var interim_relief=(parseInt(pay_in_payband)*10)/100;
			//$('#new_pay_in_payband_val').val(pay_in_payband);
			
		}
        
		$('#interim_relief').val(interim_relief);
			
			}
			});
		
		
		
		
		$('#grade_pay').change(function(value) {
			
		var conf_increment_applicable=$('#conf_increment_applicable').val();
		var grade_pay=$("#grade_pay option:selected").text(); 
		var pay_in_payband=$('#pay_in_payband').val();
		var basic=parseInt(grade_pay)+parseInt(pay_in_payband);
	//	var emp_first_join_value=$('#emp_first_join_val').val();
			$('#new_pay_in_payband_val').val(basic);
		if(conf_increment_applicable=='0')
		{
		/*   if(emp_first_join_value=='0')
		    {
		     var interim_relief=(parseInt(pay_in_payband)*10)/100;
		      $('#new_pay_in_payband_val').val(pay_in_payband);
		     }
		  else{*/
			   var increment_val=(parseInt(basic)*3)/100;
			  if(Math.floor(increment_val)%10==0){
						//var promo_basic=parseInt(basic)+(Math.floor(promo_inc_pay/10)*10);
						var increment=(Math.floor(increment_val/10)*10);
					}else{
						//var promo_basic=parseInt(basic)+(Math.ceil(promo_inc_pay/10)*10);
						var increment=(Math.ceil(increment_val/10)*10);
					}
			  var new_pay_in_payband=parseInt(pay_in_payband)+parseInt(increment);
			  var interim_relief_val=((parseInt(new_pay_in_payband)*10)/100);
			  interim_relief= Math.round(interim_relief_val);
			 // $('#new_pay_in_payband_val').val(new_pay_in_payband);
			
		 //  }
		   
		}
		else if(conf_increment_applicable=='1')
		{
			 var interim_relief=(parseInt(pay_in_payband)*10)/100;
			//$('#new_pay_in_payband_val').val(pay_in_payband);
			
		}
		$('#interim_relief').val(interim_relief);
	
				
				
			});	
				
			
	});
	/* function ircal(value)
     {
	
		var conf_increment_applicable=$('#conf_increment_applicable').val();
		var grade_pay=$("#grade_pay option:selected").text(); 
		var pay_in_payband=$('#pay_in_payband').val();
		var basic=parseInt(grade_pay)+parseInt(pay_in_payband);
		var emp_first_join_value=$('#emp_first_join_val').val();
			
		if(conf_increment_applicable=='0')
		{
		   if(emp_first_join_value=='0')
		    {
		     var interim_relief=(parseInt(pay_in_payband)*10)/100;
		      $('#new_pay_in_payband_val').val(pay_in_payband);
		     }
		  else{
			  var increment=(parseInt(basic)*3)/100;
			  var new_pay_in_payband=parseInt(pay_in_payband)+parseInt(increment);
			  var interim_relief_val=((parseInt(new_pay_in_payband)*10)/100);
			  interim_relief= Math.round(interim_relief_val);
			  $('#new_pay_in_payband_val').val(new_pay_in_payband);
			
		   }
		   
		}
		else if(conf_increment_applicable=='1')
		{
			 var interim_relief=(parseInt(pay_in_payband)*10)/100;
			$('#new_pay_in_payband_val').val(pay_in_payband);
			
		}
		$('#interim_relief').val(interim_relief);
		       
		}
*/
		
    function valid_code(){
		//alert($('#pay_scale').val());
		//var scale=cal_pay_in_band($('#pay_scale').val());
		if($('#desig').val()!='1120'){
		if($('#pay_band').val()==''){
					alert('Please Select Pay Band.');
					$('#pay_band').focus();
					return false;
		}	
		if($('#pay_in_payband').val()==''){
					alert('Please Enter Pay In Pay Band.');
					$('#pay_in_payband').focus();
					return false;
		}
		if($('#grade_pay').val()==''){
					alert('Please Enter Grade Pay.');
					$('#grade_pay').focus();
					return false;
		}
		if($('#pay_scale').val()==''){
					alert('Please Enter Pay Scale.');
					$('#pay_scale').focus();
					return false;
		}
		if($('#interim_relief').val()==''){
					alert('Please Enter Valid Data.');
					$('#interim_relief').focus();
					return false;
		}
			if($('#conf_increment_applicable').val()==''){
					alert('Please Select Valid Input.');
					$('#conf_increment_applicable').focus();
					return false;
		}
		if($('#new_pay_in_payband_val').val()==''){
					alert('Please Select Valid Data.');
					$('#new_pay_in_payband_val').focus();
					return false;
		}
	 }

/*	if($('#desig').val()=='1120'){
		if($('#cons_pay').val()==''){
					alert('Please Enter Consolidated Pay.');
					$('#cons_pay').focus();
					return false;
		} 
	 }
	 
	 if($('#txt_bank').val()==''){
					alert('Please Select Bank Name.');
					$('#txt_bank').focus();
					return false;
		} 
		if($('#txt_account').val()=='' || ($("#txt_account").val().length != parseInt($("#txt_account").attr('maxlength')) && parseInt($("#txt_account").attr('maxlength'))!=-1)){
					alert('Please enter valid  Bank Account No.');
					$('#txt_account').focus();
					return false;
				}
		if(($('#txt_ifsc').val()=='' || $('#txt_ifsc').val().length!=11)){
					alert('Please enter Valid Bank IFSC Code.');
					$('#txt_ifsc').focus();
					return false;
		}
		if(($('#txt_ifsc').val().substring(0,4)) != ($('#txt_bank').val().substring(0,4))){
					alert('Please enter Valid Bank IFSC Code.');
					$('#txt_ifsc').focus();
					return false;
		}*/
		
	}
	
	
	
    </script>
  
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