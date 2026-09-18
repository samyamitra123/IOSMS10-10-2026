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

/******************************Commented By ANJAN for error log *********************************/
//$str=$_SESSION['location']['gpcode'];
//$state10=substr($str,0,4); 
/***************************************** END **********************************************/
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

//$emp_id_pk=isset($_GET['emp_id_pk'])?$_GET['emp_id_pk']:' ';
$cryptoGraph=new cryptography();
if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success'){
		$msg='<div class="alert alert-success" style="text-align:center"><strong>Professional Details of the Employee submitted Successfully...</strong></div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
	}
}
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "WBULBHRMS | Govt. of West Bengal ";

//------------------------------------------------------- HEADER --------------------------------------------------------------
//require '../../../../page/municipality_admin/common.php';

function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}
	
        $emp_count=count($emp_data); 
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

?>
<div><center><h1 class="heading">SELECT LOAN TYPE</h1></center></div>
<br />
	<script>
	function installment_calculation(loan_type_attr)
	{
		loan_type_attr = 'loan_'+loan_type_attr;
		//alert(loan_type_attr);
		var tot_amt = $('#'+loan_type_attr+'_due_amt').val();
		//alert(tot_amt);
		var inst_amt = $('#'+loan_type_attr+'_install_amt').val();
		//alert(inst_amt);
		//var after_divi = (tot_amt / inst_amt);
		//alert(after_divi);
		var rem_amt = tot_amt % inst_amt;
		var after_devide = ((tot_amt - rem_amt)/inst_amt);
		
		$('#'+loan_type_attr+'_no_of_installment').val(after_devide);
		$('#'+loan_type_attr+'_reminder_amt').val(rem_amt);
	}
	</script>
    
    <script>
		function due_calculation(loan_type_attr)
		{
			//alert(loan_type_attr);
			loan_type_attr = 'loan_'+loan_type_attr;
			var tot_amt = $('#'+loan_type_attr+'_total_amt').val();
			$('#'+loan_type_attr+'_due_amt').val(tot_amt);
			var inst_amt = $('#'+loan_type_attr+'_install_amt').val();
			if(inst_amt > 0)
			{
				var rem_amt = tot_amt % inst_amt;
				var after_devide = ((tot_amt - rem_amt)/inst_amt);
				$('#'+loan_type_attr+'_no_of_installment').val(after_devide);
				$('#'+loan_type_attr+'_reminder_amt').val(rem_amt);
			}
		}
	</script>
    
    <script>
	function install_cla(loan_type_attr)
	{
		loan_type_attr = 'loan_'+loan_type_attr;	
		var tot_amt = $('#'+loan_type_attr+'_due_amt').val();
		var no_of_ins = $('#'+loan_type_attr+'_no_of_installment').val();
		var instl_amt = Math.round(tot_amt/no_of_ins);
		var remeindera_amt = (tot_amt - (instl_amt*no_of_ins));
		
		if(remeindera_amt > 0)
		{
			instl_amt = instl_amt + +1;
		}
		
		$('#'+loan_type_attr+'_install_amt').val(instl_amt);
		if(Number(remeindera_amt) <= 0)
		{
			$('#'+loan_type_attr+'_reminder_amt').val(0);
		}
		else
		{
			$('#'+loan_type_attr+'_reminder_amt').val(0);
		}
	}
	</script>
    
    


<strong style="color:#E93437;"><center><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></center></strong>
<div id="form_show" class="dashcontenr invisible">

<form class="form-horizontal" id="loginForm" method="post" action="entry_loan_deduction_salary_insert.php" onsubmit="return valid_code();">
<!--<input type="hidden" name="desig" id="desig" value=<?= $cryptoGraph->decode($_GET['desig'],4); ?>  />-->
<input type="hidden" name="emp_id_pk" value="<?=$cryptoGraph->encode($emp_id_pk,4) ?>" />
 <!--<input type="hidden" name="emp_count" value="<?=$emp_count ?>" />
 <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
<input type="hidden" name="emp_first_join_val" id="emp_first_join_val" value="<?=$emp_first_join_val ?>" />
<input type="hidden" name="new_pay_in_payband_val" id="new_pay_in_payband_val"  value="<? if(!empty($new_pay_in_paband_val)){ echo $new_pay_in_paband_val; }else{ echo 0;} ?>"/>
-->
<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
<input type="hidden" value="<?php if(isset($deductions['loan_div_id'])){echo $deductions['loan_div_id'];}else{echo '1';}?>" name="loan_div_ids" id="loan_div_ids" />

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
		$abc = $loan_master_data[0]['loan_type'];						
 
 ?>
<p id="insertinputs"></p>   
 <div id="add_rows">
 <?php
 $loan_deduction_data = $db->fetch_table("SELECT  
 										loan_id_pk,
										loan_name,
										due_amount, 
										total_amount,
										no_of_installment,
										installment_amount,
										reminder_amount,
										dise_code_fk,
										status,
										approval_status,
										deduction_loan_type_variable,
										lock_status,
										loan_div_id
									FROM
										prd_loan_deduction
									WHERE
									    emp_id_fk = '".$emp_id_pk."' AND status not in(0) AND edit_status in (1)");
$save_and_continue_butz = 0;
if(count($loan_deduction_data) > 0)
{
	$i=0;
	$pre_saved_div_ids = array();
	$identity2 = 1;
	foreach($loan_deduction_data as $deductions)
	{
		//Status for save button Start
		if(($deductions['status'] == '1'||$deductions['status'] == '2') && ($deductions['approval_status'] == '2'||$deductions['approval_status'] == '3') && ($deductions['lock_status'] == '2'||$deductions['lock_status'] == '3'))
		{
			$save_and_continue_butz = $save_and_continue_butz;
		}
		else
		{
			$save_and_continue_butz = $save_and_continue_butz +1;
		}
		//Status for save button End
		
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
        <?php
		if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) 
		{ 
		?>
        <select class="form-control" id="<?=$identity1.$identity2?>_types" name="<?=$identity1.$identity2?>_types" style='background-color:#d3d3d3;'>
<?php
			$get_loan_variables = $db->fetch_table("SELECT loan_type_variable, loan_type FROM prd_master_loan_type WHERE status in ('1') AND loan_type_variable = '".$deductions['deduction_loan_type_variable']."'");
?>
			<option selected value="<?=$get_loan_variables[0]['loan_type_variable']?>"><?=$get_loan_variables[0]['loan_type']?></option>
        </select>
       	<?php
		}	
		else
		{
		?>
          <select class="form-control" id="<?=$identity1.$identity2?>_types" name="<?=$identity1.$identity2?>_types">
          	<option value="">-Please Select-</option>
<?php
			foreach ($loan_master_data as $key) {
									
			$get_loan_variables = $db->fetch_table("SELECT COUNT(*) AS count FROM prd_loan_deduction WHERE status not in (0) AND deduction_loan_type_variable = '".$key['loan_type_variable']."' AND emp_id_fk = '".$emp_id_pk."' AND edit_status in (1)");
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
			<option <?php if($deductions['deduction_loan_type_variable']==$key['loan_type_variable']){ echo 'selected';} ?> value="<?=$key['loan_type_variable']?>"><?=$key['loan_type']?></option>
<?php
			}

?>
          </select>
        <?php
		}
		?>
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label"><span class="star_color"></span></label>
        <div class="col-sm-4">
          
        </div>
    </div>
	<div class="form-group" >
        <label for="inputPassword3" class="col-sm-2 control-label">Total Repayment Amount (Principal+Interest)<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3) || ($deductions['status'] == 2 && $deductions['lock_status'] == 4)) { echo "readonly style='background-color:#d3d3d3;'"; } ?> value="<?=$deductions['total_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_total_amt" id="<?=$identity1.$identity2?>_total_amt" placeholder="Total Repayment Amount" autocomplete="off" value="" maxlength="10" onkeyup="<?php echo 'return due_calculation(\''.$identity2.'\');'?>" onkeydown="<?php echo 'return due_calculation(\''.$identity2.'\');'?>">
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label">Due Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3 && ($deductions['lock_status'] == 2 || $deductions['lock_status'] == 3))) { echo "readonly style='background-color:#d3d3d3;'"; } ?> value="<?=$deductions['due_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_due_amt" id="<?=$identity1.$identity2?>_due_amt" placeholder="Due Amount" autocomplete="off" value="" maxlength="10" >
        </div>
     </div>
     <div class="form-group" >
     	<label for="inputPassword3" class="col-sm-2 control-label">Installment Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3 && ($deductions['lock_status'] == 2 || $deductions['lock_status'] == 3))) { echo "readonly style='background-color:#d3d3d3;'"; } ?> value="<?=$deductions['installment_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_install_amt" id="<?=$identity1.$identity2?>_install_amt" placeholder="Installment Amount" autocomplete="off" value="" maxlength="10" onkeyup="<?php echo 'return installment_calculation(\''.$identity2.'\');'?>" onkeydown="<?php echo 'return installment_calculation(\''.$identity2.'\');'?>">
     	</div>
        <label for="inputPassword3" class="col-sm-2 control-label">No. of Installments<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3 && ($deductions['lock_status'] == 2 || $deductions['lock_status'] == 3))) { echo "readonly style='background-color:#d3d3d3;'"; } ?> value="<?=$deductions['no_of_installment']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_no_of_installment" id="<?=$identity1.$identity2?>_no_of_installment" placeholder="No of Installment" autocomplete="off" maxlength="10" onkeyup="<?php echo 'return install_cla(\''.$identity2.'\');'?>" onkeydown="<?php echo 'return install_cla(\''.$identity2.'\');'?>">
        </div>
     </div>
     
     <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Remainder Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" readonly style='background-color:#d3d3d3;' value="<?=$deductions['reminder_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_reminder_amt" id="<?=$identity1.$identity2?>_reminder_amt" placeholder="Remainder Amount" autocomplete="off" maxlength="10">
        </div>
        
        <label for="inputPassword3" class="col-sm-2 control-label">Loan Name<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3) || ($deductions['status'] == 2 && $deductions['lock_status'] == 4)) { echo "readonly style='background-color:#d3d3d3;'"; } ?> value="<?=$deductions['loan_name']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_loan_name" id="<?=$identity1.$identity2?>_loan_name" placeholder="Loan Name" autocomplete="off" maxlength="50">
        </div>
      </div>
	</div>
<?php
	$pre_saved_div_ids[$i] = $identity2;
	$i=$i+1;
	$identity2=$identity2+1;	
	}
$all_div_ids = implode('.', $pre_saved_div_ids);
?>
<script>
$(document).ready(function(){
	var all_div_ids = '<?php echo $all_div_ids;?>';
	$("#loan_div_ids").val(all_div_ids);
});
</script>
<?php
}
else
{
$identity1 ="loan_";
$identity2 =1;

//Status for save button Start
$save_and_continue_butz = $save_and_continue_butz +1;
//Status for save button End
 ?>
 
<div id="<?=$identity1.$identity2?>_div" class="loan_id" style="/*border: solid 1px #000000;*/"> 
    	<input type="hidden" value="99" name="<?=$identity1.$identity2?>_status"  />
        <input type="hidden" value="99" name="<?=$identity1.$identity2?>_approval_status"  />
        <input type="hidden" value="99" name="<?=$identity1.$identity2?>_lock_status"  />
        <input type="hidden" value="<?=$deductions['loan_id_pk']?>" name="<?=$identity1.$identity2?>_loan_id_pk"  />
    <div class="form-group" >
        <!--<center><h3 id="<?=$deductions['deduction_loan_type_variable']?>_heading"><?php echo strtoupper($loan_type[0]['loan_type']); ?></h3></center>-->
        <label for="inputPassword3" class="col-sm-2 control-label">Loan Type<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <select class="form-control" id="<?=$identity1.$identity2?>_types" name="<?=$identity1.$identity2?>_types">
          	<option value="">-Please Select-</option>
<?php
			foreach ($loan_master_data as $key) {
									
			$get_loan_variables = $db->fetch_table("SELECT COUNT(*) AS count FROM prd_loan_deduction WHERE status not in (0) AND deduction_loan_type_variable = '".$key['loan_type_variable']."' AND emp_id_fk = '".$emp_id_pk."' AND edit_status in (1)");
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
			<option <?php if($get_loan_variables[0]['count'] > 0){ echo 'selected';} ?> value="<?=$key['loan_type_variable']?>"><?=$key['loan_type']?></option>
<?php
			}

?>
          </select>
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label"><span class="star_color"></span></label>
        <div class="col-sm-4">
          
        </div>
    </div>
	<div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Total Repayment Amount (Principal+Interest)<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3;'"; } ?> value="<?=$deductions['total_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_total_amt" id="<?=$identity1.$identity2?>_total_amt" placeholder="Total Repayment Amount" autocomplete="off" value="" maxlength="10" onkeyup="<?php echo 'return due_calculation(\''.$identity2.'\');'?>" onkeydown="<?php echo 'return due_calculation(\''.$identity2.'\');'?>">
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label">Due Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php //echo "readonly style='background-color:#d3d3d3';"; ?> value="<?=$deductions['due_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_due_amt" id="<?=$identity1.$identity2?>_due_amt" placeholder="Due Amount" autocomplete="off" value="" maxlength="10">
        </div>
     </div>
     <div class="form-group" >
     	<label for="inputPassword3" class="col-sm-2 control-label">Installment Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3 && ($deductions['lock_status'] == 2 || $deductions['lock_status'] == 3))) { echo "readonly style='background-color:#d3d3d3';"; } else if($deductions['status'] == 2 && $deductions['approval_status'] == 3 && $deductions['lock_status'] == 4){ echo ''; } ?> value="<?=$deductions['installment_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_install_amt" id="<?=$identity1.$identity2?>_install_amt" placeholder="Installment Amount" autocomplete="off" value="" maxlength="10" onkeyup="<?php echo 'return installment_calculation(\''.$identity2.'\');'?>" onkeydown="<?php echo 'return installment_calculation(\''.$identity2.'\');'?>">
     	</div>
        <label for="inputPassword3" class="col-sm-2 control-label">No. of Installments<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3';"; } ?> value="<?=$deductions['no_of_installment']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_no_of_installment" id="<?=$identity1.$identity2?>_no_of_installment" placeholder="No of Installment" autocomplete="off" maxlength="10" onkeyup="<?php echo 'return install_cla(\''.$identity2.'\');'?>" onkeydown="<?php echo 'return install_cla(\''.$identity2.'\');'?>">
        </div>
     </div>
     
     <div class="form-group" >
        <label for="inputPassword3" class="col-sm-2 control-label">Remainder Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input onkeypress="return keyRestrict(event,'1234567890');" <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3';"; } ?> value="<?=$deductions['reminder_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_reminder_amt" id="<?=$identity1.$identity2?>_reminder_amt" placeholder="Remainder Amount" autocomplete="off" maxlength="10" readonly style="background-color:#d3d3d3;">
        </div>
        
        <label for="inputPassword3" class="col-sm-2 control-label">Loan Name<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input value="<?=$deductions['loan_name']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_loan_name" id="<?=$identity1.$identity2?>_loan_name" placeholder="Loan Name" autocomplete="off" maxlength="50">
        </div>
      </div>
	</div>
<?php
$identity2 = $identity2+01;
}

$check_status_for_save_button = $db->fetch_table("SELECT COUNT(*) AS save_butz_status FROM prd_loan_deduction
													WHERE status in ('1','2') AND approval_status in ('1','3','4') 
													AND lock_status in ('1','4')
													AND emp_id_fk = '".$emp_id_pk."' 
													AND edit_status in (1)");
													
$check_status_for_save_button_2 = $db->fetch_table("SELECT COUNT(*) AS save_butz_status FROM prd_loan_deduction
													WHERE 
													emp_id_fk = '".$emp_id_pk."' 
													AND status not in (0)
													AND edit_status in (1)");
?>
  
 </div>
  <p style="border-top:1px dashed #27769F; text-align:center; width:800px;"></p>
  <div class="form-group">
    <div class="col-sm-12" style="margin-left:30%;">
    <!--<button type="submit" class="btn btn-info" >SAVE & CONTINUE <i class=""></i></button>-->
    <?php
	/*$count_sal_save = $db->fetch_table("SELECT count(*) AS sal_check FROM mad_employee_salary_save WHERE emp_id_fk='".$emp_id_pk."'
									AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."' AND salary_monthyear = '".date("Ym")."'");*/
	/*$count_sal_save = $db->fetch_table("SELECT count(*) AS sal_check FROM prd_employee_salary_save WHERE emp_id_fk='".$emp_id_pk."'
									AND zp_id_fk='".$_SESSION['location']['district_id']."' AND salary_monthyear = '".date("Ym")."'");								
	
	if($count_sal_save[0]['sal_check'] < 1)
	{*/
	?>
    <input <?php if(($check_status_for_save_button[0]['save_butz_status'] < 1) && ($check_status_for_save_button_2[0]['save_butz_status'] > 0)){echo "style='display:none';";} ?> type="submit" class="btn btn-info" name="loan_deductions_submit" id="loan_deductions_submit" value="SAVE & CONTINUE" />
    <?php
	//}
	?>
      <!-- <a href="profile_entry_prof.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>-->
    </div>
  </div>
  
</form>
<button title="Add more" class="btn btn-success add_more" onclick="return dynInput();">+</button>
</div>

        </div>
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
		
	function valid_code(){
		var return_val = 'true';
		$(".loan_id").each(function(){
			var div_id = $(this).attr('id');
			var type_var_arr = div_id.split("_");
			var type_var = type_var_arr[1];
			//alert(type_var);
			type_var = 'loan_'+type_var;
			//alert(type_var);
			var heading = $("#"+type_var+"_heading").html();
			
			if($("#"+type_var+"_types").val() == '')
			{
				alert('Please Select Loan Type.');
				$("#"+type_var+"_types").focus();
				return_val = 'flase';
				return false;
			}
			
			else if($("#"+type_var+"_total_amt").val() == 0 || $("#"+type_var+"_total_amt").val() == '' || $("#"+type_var+"_total_amt").val() < 0)
			{
				alert('Please Enter Valid Total Repayment Amount.');
				$("#"+type_var+"_total_amt").focus();
				return_val = 'flase';
				return false;
			}
			
			else if($("#"+type_var+"_due_amt").val() == 0 || $("#"+type_var+"_due_amt").val() == '' || $("#"+type_var+"_due_amt").val() < 0)
			{
				alert('Please Enter Valid Due Amount.');
				$("#"+type_var+"_due_amt").focus();
				return_val = 'flase';
				return false;
			}
			
			else if($("#"+type_var+"_install_amt").val() == 0 || $("#"+type_var+"_install_amt").val() == '' || $("#"+type_var+"_install_amt").val() < 0)
			{
				alert('Please Enter Valid Installment Amount.');
				$("#"+type_var+"_install_amt").focus();
				return_val = 'flase';
				return false;
			}
			
			else if($("#"+type_var+"_no_of_installment").val() == 0 || $("#"+type_var+"_no_of_installment").val() == '' || $("#"+type_var+"_no_of_installment").val() < 0)
			{
				alert('Please Enter Valid No. of Installments.');
				$("#"+type_var+"_no_of_installment").focus();
				return_val = 'flase';
				return false;
			}
			
			else if($("#"+type_var+"_reminder_amt").val() == '' || $("#"+type_var+"_reminder_amt").val() < 0)
			{
				alert('Please Enter Valid Remainder Amount.');
				$("#"+type_var+"_reminder_amt").focus();
				return_val = 'flase';
				return false;
			}
			
			else if($("#"+type_var+"_loan_name").val() == 0 || $("#"+type_var+"_loan_name").val() == '' || $("#"+type_var+"_loan_name").val() < 0)
			{
				alert('Please Enter Valid Loan Name.');
				$("#"+type_var+"_loan_name").focus();
				return_val = 'flase';
				return false;
			} 	
			
			else if(!(Number($("#"+type_var+"_total_amt").val()) >= Number($("#"+type_var+"_install_amt").val())))	
			{
				alert('Please Enter Valid Installment Amount.');
				$("#"+type_var+"_install_amt").focus();
				return_val = 'flase';
				return false;
			}
			else if(!(Number($("#"+type_var+"_total_amt").val()) > Number($("#"+type_var+"_reminder_amt").val())))	
			{
				alert('Please Enter Valid Remainder Amount.');
				$("#"+type_var+"_reminder_amt").focus();
				return_val = 'flase';
				return false;
			}
			else if(Number($("#"+type_var+"_status").val()) == 99 && Number($("#"+type_var+"_approval_status").val()) == 99 && Number($("#"+type_var+"_lock_status").val()) == 99)
			{
				if(Number($("#"+type_var+"_total_amt").val()) != Number($("#"+type_var+"_due_amt").val()))	
				{
					alert('Please Enter Valid Due Amount.');
					$("#"+type_var+"_due_amt").focus();
					return_val = 'flase';
					return false;
				}
			}
		})
		//alert(return_val);
		if(return_val == 'true')
		{
			return true;	
		}
		else 
		{
			return false;
		}	
	}
	
	</script>
    
     <!--   kalyan ghosh 10/3/2017  finish  --> 
  <script>
  $(document).ready(function(){
	 	if($('#form_show').css("visibility")=="hidden"){
			$('#form_show').removeClass("invisible").css('height', 'auto');
		}
		
	  	/*if($(".loan_type_checkbox:checked").length == 0)
		{
			$("#loan_deductions_submit").hide();
		}*/
		/*$(".loan_type_checkbox").click(function()
		{
			if($(".loan_type_checkbox:checked").length == 0)
			{
				$("#loan_deductions_submit").hide();
			}
			else
			{
				$("#loan_deductions_submit").show();
			}
		});*/
	});
  </script>
  <?php  @pg_close($con); ?>
  
  <script>
  	var save_and_continue = <?php echo $save_and_continue_butz; ?>;
  	var cbox =0;
	function dynInput() {
		if(cbox == 0)
		{
			cbox = '<?php echo $identity2?>';	
		}
		else
		{
			cbox = Number(cbox)+ +1;
			//alert(cbox);
		}
		//alert(cbox);
		var arrayFromPHP = <?php echo json_encode($loan_master_data) ?>;

			var wrapper = $("#add_rows");
			$(wrapper).append(
			'<div id="loan_'+cbox+'_div" class="loan_id" style="/*border: solid 1px #000000;*/">' +
			'<input type="hidden" value="99" name="loan_'+cbox+'_status"  />' +
			'<input type="hidden" value="99" name="loan_'+cbox+'_approval_status"  />' +
			'<input type="hidden" value="99" name="loan_'+cbox+'_lock_status"  />' +
	'<div class="form-group" >'+
        '<label for="inputPassword3" class="col-sm-2 control-label">Loan Type<span class="star_color">*</span></label>'+
        '<div class="col-sm-4">'+
          '<select class="form-control" id="loan_'+cbox+'_types" name="loan_'+cbox+'_types">'+
          	'<option value="">-Please Select-</option>'+
		  '</select>'+
		'</div>'+
         '<label for="inputPassword3" class="col-sm-2 control-label"><span class="star_color"></span></label>'+
        '<div class="col-sm-4">'+
          '<a href="javascript:void(0);" title="Remove" class="btn btn-warning delete_one" onclick="return remove_loan('+cbox+');">x</a>'+
        '</div>'+
    '</div>');
			$.each(arrayFromPHP, function (i, elem) {
    	$('#loan_'+cbox+'_types').append('<option value="'+elem.loan_type_variable+'">'+elem.loan_type+'</option>');
});
          $(cbox+'_types').append('</select>');
     
	$("#loan_"+cbox+"_div").append('<div class="form-group" >' +
    '<label for="inputPassword3" class="col-sm-2 control-label">Total Repayment Amount (Principal+Interest)<span class="star_color">*</span></label>' +
    '<div class="col-sm-4">' +
      '<input onkeypress="return keyRestrict(event,\''+1234567890+'\');" type="text" class="form-control" name="loan_'+cbox+'_total_amt" id="loan_'+cbox+'_total_amt" placeholder="Total Repayment Amount" autocomplete="off" value="" maxlength="10" onkeyup="return due_calculation(\''+cbox+'\');" onkeydown="return due_calculation(\''+cbox+'\');">' +
    '</div>' +
     '<label for="inputPassword3" class="col-sm-2 control-label">Due Amount<span class="star_color">*</span></label>' +
    '<div class="col-sm-4">' +
      '<input onkeypress="return keyRestrict(event,\''+1234567890+'\');" type="text" class="form-control" name="loan_'+cbox+'_due_amt" id="loan_'+cbox+'_due_amt" placeholder="Due Amount" autocomplete="off" value="" maxlength="10">' +
    '</div></div>' +
	'<div class="form-group" >' +
	'<label for="inputPassword3" class="col-sm-2 control-label">Installment Amount<span class="star_color">*</span></label>' +
    '<div class="col-sm-4">' +
      '<input onkeypress="return keyRestrict(event,\''+1234567890+'\');" type="text" class="form-control" name="loan_'+cbox+'_install_amt" id="loan_'+cbox+'_install_amt" placeholder="Installment Amount" autocomplete="off" value="" maxlength="10" onkeyup="return installment_calculation(\''+cbox+'\');" onkeydown="return installment_calculation(\''+cbox+'\');">' +
    '</div>' +
	'<label for="inputPassword3" class="col-sm-2 control-label">No. of Installments<span class="star_color">*</span></label>' +
    '<div class="col-sm-4">' +
      '<input onkeypress="return keyRestrict(event,\''+1234567890+'\');" type="text" class="form-control" name="loan_'+cbox+'_no_of_installment" id="loan_'+cbox+'_no_of_installment" placeholder="No of Installment" autocomplete="off" maxlength="10" onkeyup="return install_cla(\''+cbox+'\');" onkeydown="return install_cla(\''+cbox+'\');">' +
    '</div>' +
	'</div>'+
	'<div class="form-group" >' +
    '<label for="inputPassword3" class="col-sm-2 control-label">Remainder Amount<span class="star_color">*</span></label>'+
    '<div class="col-sm-4">' +
      '<input onkeypress="return keyRestrict(event,\''+1234567890+'\');" type="text" class="form-control" name="loan_'+cbox+'_reminder_amt" id="loan_'+cbox+'_reminder_amt" placeholder="Remainder Amount" autocomplete="off" maxlength="10" readonly style="background-color:#d3d3d3;">' +
	'</div>' +
	'<label for="inputPassword3" class="col-sm-2 control-label">Loan Name<span class="star_color">*</span></label>'+
    '<div class="col-sm-4">' +
      '<input type="text" class="form-control" name="loan_'+cbox+'_loan_name" id="loan_'+cbox+'_loan_name" placeholder="Loan Name" autocomplete="off" maxlength="50">' +
    '</div></div>' +
	'</div>');
	
	if($("#loan_div_ids").val() == "")
	{
		var ids = [1];
	}
	else
	{
		var ids = $("#loan_div_ids").val();
		ids = ids.split(".");
		//alert(ids[3]);
	}
	ids.push(cbox);
	ids = ids.join(".");
	/*alert(ids);
	ids = ids.join(".");
	alert(ids);*/
	$("#loan_div_ids").val(ids);
	
		//Save and continue button start
		save_and_continue = save_and_continue + 1;
		if(save_and_continue > 0)
		{
			$("#loan_deductions_submit").show();
		}
		//Save and continue button end
	
	}
	

	function remove_loan(div_id)
	{
		if($("#loan_div_ids").val() == "")
		{
			var ids = [1];
		}
		else
		{
			var ids = $("#loan_div_ids").val();
			ids = ids.split(".");
			//alert(ids[3]);
			//var ids = ['angel', 'clown', 'drum', 'mandarin', 'sturgeon'];
		}
		//ids.pull(div_id);
		var index = ids.indexOf(''+div_id+'');
		//alert(index);
		//removeByIndex(ids, 2);
		//ids = ids::remove(div_id);
		//var ids = _.without(ids, div_id);
		//alert(ids);
		//ids.delete("2");
		//alert(ids);
		ids.splice(index, 1);
		ids = ids.join(".");
		/*alert(ids);
		ids.split(",");
		ids.join('.');
		alert(ids);*/
		$("#loan_div_ids").val(ids);
		
		var identity = 'loan_';
		var ori_div_id = identity + div_id;
		$("#"+ori_div_id+"_div").remove();
		
		
		//Save and continue button start
		save_and_continue = save_and_continue - 1;
		//alert(save_and_continue);
		if(save_and_continue < 1)
		{
			$("#loan_deductions_submit").hide();
		}
		//Save and continue button end
		
	}
	</script>
    <script>
	/*function valid_code()
	{
		var ids = $("#loan_div_ids").val();
		ids.split(",");
		alert(ids[1]);
		//ids.join(".");
		return false;
	}*/
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