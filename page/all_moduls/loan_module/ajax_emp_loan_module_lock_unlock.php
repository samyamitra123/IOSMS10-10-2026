<?php
ob_start();
session_start(); 

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

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

$str=$_SESSION['location']['gpcode'];
$state10=substr($str,0,4);
//   Kalyan Ghosh   16/3/2017    Finish

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

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "P&RD | Govt. of West Bengal ";

//------------------------------------------------------- HEADER --------------------------------------------------------------
//require '../../../../page/municipality_admin/common.php';

?>
<script>
function payband(val){
 $.post('<?= $config['base_url'] ?>page/intra_prd/ajax_payscale_details.php?payband='+val, function(data){
	 $(".payscale").html(data);	 
	 $.post('<?= $config['base_url'] ?>page/intra_prd/ajax_gradepay_details.php?payband='+val, function(data){
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
<div><center><h1 class="heading">UNLOCK LOAN</h1></center></div>
<br />
	<script>
	function installment_calculation(loan_type_attr)
	{
		//alert(loan_type_attr);
		var tot_amt = $('#'+loan_type_attr+'_total_amt').val();
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
    
<form class="form-horizontal" id="loginForm" method="post" action="" onsubmit="return valid_code();">
<input type="hidden" name="emp_id_pk" value="<?=$cryptoGraph->encode($emp_id_pk,4) ?>" />
<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
<input type="hidden" value="<?php if(isset($deductions['loan_div_id'])){echo $deductions['loan_div_id'];}else{echo '1';}?>" name="loan_div_ids" id="loan_div_ids_l" />
 <?php 
 
 $db = new database();
 
?>
<center><div id="abc" style="margin-right: 50px;"></div></center>
<?php
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
										emp_id_fk, 
										due_amount,
										total_amount,
										no_of_installment,
										installment_amount,
										reminder_amount,
										dise_code_fk,
										status,
										approval_status,
										lock_status,
										deduction_loan_type_variable,
										loan_name

									FROM
										prd_loan_deduction
									WHERE
									    emp_id_fk = '".$emp_id_pk."' AND status not in(0) AND status in(2) 
										AND approval_status in (3) AND edit_status in (1)");

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
          <select style='background-color:#d3d3d3;' class="form-control" id="<?=$identity1.$identity2?>_types" name="<?=$identity1.$identity2?>_types">
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
          </select>
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label"><span class="star_color"></span></label>
        <div class="col-sm-4">
          
        </div>
    </div>
	<div class="form-group" >
        <label for="inputPassword3" class="col-sm-2 control-label">Total Repayment Amount (Principal+Interest)<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3;'"; } ?>readonly style='background-color:#d3d3d3;' value="<?=$deductions['total_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_total_amt" id="<?=$identity1.$identity2?>_total_amt" placeholder="Total Repayment Amount" autocomplete="off" value="" maxlength="10" onkeyup="<?php echo 'return due_calculation(\''.$identity2.'\');'?>">
        </div>
         <label for="inputPassword3" class="col-sm-2 control-label">Due Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php echo "readonly style='background-color:#d3d3d3';"; ?> value="<?=$deductions['due_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_due_amt" id="<?=$identity1.$identity2?>_due_amt" placeholder="Due Amount" autocomplete="off" value="" maxlength="10" >
        </div>
     </div>
     <div class="form-group" >
     	<label for="inputPassword3" class="col-sm-2 control-label">Installment Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input readonly style='background-color:#d3d3d3;' value="<?=$deductions['installment_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_install_amt" id="<?=$identity1.$identity2?>_install_amt" placeholder="Installment Amount" autocomplete="off" value="" maxlength="10" onkeyup="<?php echo 'return installment_calculation(\''.$identity2.'\');'?>">
     	</div>
        <label for="inputPassword3" class="col-sm-2 control-label">No. of Installments<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3';"; } ?>readonly style='background-color:#d3d3d3;' value="<?=$deductions['no_of_installment']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_no_of_installment" id="<?=$identity1.$identity2?>_no_of_installment" placeholder="No of Installment" autocomplete="off" maxlength="10">
        </div>
     </div>
     
     <div class="form-group" >
        <label for="inputPassword3" class="col-sm-2 control-label">Remainder Amount<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input <?php //if(($deductions['status'] == 1 && $deductions['approval_status'] == 2) || ($deductions['status'] == 2 && $deductions['approval_status'] == 3)) { echo "readonly style='background-color:#d3d3d3';"; } ?>readonly style='background-color:#d3d3d3;' value="<?=$deductions['reminder_amount']?>" type="text" class="form-control" name="<?=$identity1.$identity2?>_reminder_amt" id="<?=$identity1.$identity2?>_reminder_amt" placeholder="Remainder Amount" autocomplete="off" maxlength="10">
        </div>
        
        <label for="inputPassword3" class="col-sm-2 control-label">Loan Name<span class="star_color">*</span></label>
        <div class="col-sm-4">
          <input value="<?=$deductions['loan_name']?>" readonly style='background-color:#d3d3d3;' type="text" class="form-control" name="<?=$identity1.$identity2?>_loan_name" id="<?=$identity1.$identity2?>_loan_name" placeholder="Loan Name" autocomplete="off" maxlength="50">
        </div>
      </div>
         <br/>
  
        <?php
		/*$count_sal_save = $db->fetch_table("SELECT count(*) AS sal_check FROM prd_employee_salary_save WHERE emp_id_fk='".$emp_id_pk."'
									AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."' AND salary_monthyear = '".date("Ym")."'");*/
		//if($deductions['lock_status']=='2' && $count_sal_save[0]['sal_check'] < 1)
		if($deductions['lock_status']=='2' )
		{
			
		?>
     <a class="btn btn-danger"  style="margin-left:48%;"   data-bs-target="#send"  id="unlock" data-bs-toggle="modal"   onclick="confirmation( '<?php echo $cryptoGraph->encode($deductions['loan_id_pk'],4) ?>','<?php echo $cryptoGraph->encode($deductions['emp_id_fk'],4) ?>');">REQUEST FOR UNLOCK</a>
         
		 <?php //echo '<a style="margin-left:40%;" data-bs-toggle="modal" id="'.$cryptoGraph->encode($deductions['loan_id_pk'],4).'" href="javascript:void(0);" class="btn btn-danger"  data-bs-target="#send" onclick="return confirmation(\''.$cryptoGraph->encode($deductions['loan_id_pk'],4).'\',\''.$cryptoGraph->encode($deductions['emp_id_fk'],4).'\');" >'; REQUEST FOR UNLOCK?><!--</a>-->
        <?php	
		}
		?>
        <br/><br/>
        
        
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
	$("#loan_div_ids_l").val(all_div_ids);
});
</script>  

 </div>

  <div class="form-group">
    <div class="col-sm-12" align="center">
    
    </div>
  </div>
  
    
    
</form>

        </div>
      </div>

    </div>
    <p style="border-top:1px dashed #27769F; text-align:center; width:820px;"></p>
        <div class="clear"></div>
    
<?
  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/municipality_admin/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>  



    <script>
		function confirmation(loan_id,emp_id)
		{
			//alert(loan_id);	
			//alert(emp_id);
			$("#loan_id_pk").val(loan_id);
			$("#emp_id_fk").val(emp_id);
			//$('#send').modal().hide();
			var height = $("#"+loan_id).position();
			//alert(height.top);
			$('#send').modal().css(
				{
					'margin-top': function () {
						return (height.top / 2);
					}
				});	
			$('#send').modal().show();
		}
		 $('#myModal').on('hidden.bs.modal', function() {
		$('#myModal2').modal("toggle");
	  });
		function confirm_send()
		{
			var loan_id_pk = $("#loan_id_pk").val();
			var emp_id_fk = $("#emp_id_fk").val();
			//alert(loan_id_pk);
			//alert(emp_id_fk);
			$('#send').modal().hide();
			
			
			//$('#lock_unlock_loan').modal('show');
			$.post('<?= $config['base_url'] ?>page/all_moduls/loan_module/loan_module_lock_unlock_insert_submit.php?loan_id_pk='+loan_id_pk+'&emp_id_fk='+emp_id_fk, function(data_request){
			/*alert(data_request);
			
			die;*/
			$("#abc").html(data_request);
			$("#"+loan_id_pk).hide();
			//$(".modal-backdrop").hide();
			//$('#lock_unlock_loan').modal().hide();
			//alert(data_request);
			});
		
		}
		
		function remove_conf()
		{
			$("#send").modal().hide();
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