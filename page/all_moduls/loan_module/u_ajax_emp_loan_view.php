<?php
ob_start();
session_start();

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

//   Kalyan Ghosh   16/3/2017   Start
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
//   Kalyan Ghosh   16/3/2017   Finish

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
$common['title'] = "Loan Deduction |P&RD  | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

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
		$emp_pay_band=$emp_data[0]['emp_pay_band'];
	    $emp_pay_in_payband=$emp_data[0]['emp_pay_in_payband'];
	    $emp_grade_pay=$emp_data[0]['emp_grade_pay'];
	    $emp_pay_scale=$emp_data[0]['emp_pay_scale'];  
		$interim_relief=$emp_data[0]['interim_relief']; 
	    $increment_status=$emp_data[0]['increment_status'];
	    $new_pay_in_paband_val=$emp_data[0]['new_pay_in_payband'];
?>

<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">


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
<div><center><h1 class="heading">VIEW LOAN DETAILS</h1></center></div>
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
    
<form class="form-horizontal" id="loginForm_yearly" method="post" action="emp_loan_approve_reject.php">
<!--<input type="hidden" name="desig" id="desig" value=<?= $cryptoGraph->decode($_GET['desig'],4); ?>  />-->
<input type="hidden" name="emp_id_pk" value="<?=$cryptoGraph->encode($emp_id_pk,4) ?>" />
 <!--<input type="hidden" name="emp_count" value="<?=$emp_count ?>" />
 <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
<input type="hidden" name="emp_first_join_val" id="emp_first_join_val" value="<?=$emp_first_join_val ?>" />
<input type="hidden" name="new_pay_in_payband_val" id="new_pay_in_payband_val"  value="<? if(!empty($new_pay_in_paband_val)){ echo $new_pay_in_paband_val; }else{ echo 0;} ?>"/>
-->
<input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
<input type="hidden" value="<?php if(isset($deductions['loan_div_id'])){echo $deductions['loan_div_id'];}else{echo '1';}?>" name="loan_div_ids" id="loan_div_ids_v" />
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
										total_amount,
										delete_status,
										no_of_installment,
										installment_amount,
										reminder_amount,
										dise_code_fk,
										due_amount,
										status,
										approval_status,
										deduction_loan_type_variable,
										loan_name

									FROM
										prd_loan_deduction
									WHERE
									    emp_id_fk = '".$emp_id_pk."' AND status not in(0) 
										AND edit_status in(1)");
	
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
        <input type="hidden" value="<?=$deductions['delete_status']?>" name="<?=$identity1.$identity2?>_delete_status"  />
        
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
	$("#loan_div_ids_v").val(all_div_ids);
});
</script>   
 </div>
  <p style="border-top:1px dashed #27769F; text-align:center; width:800px;"></p>
  <div class="form-group">
    <div class="col-sm-12" style="margin-left:32%;">
    <!--<button type="submit" class="btn btn-info" >SAVE & CONTINUE <i class=""></i></button>-->
    <?php
	$chk_status = $db->fetch_table("SELECT loan_id_pk FROM prd_loan_deduction WHERE emp_id_fk = '".$emp_id_pk."' AND status in(1) AND approval_status in(2)");
	
	/*$count_sal_save = $db->fetch_table("SELECT count(*) AS sal_check FROM prd_employee_salary_save WHERE emp_id_fk='".$emp_id_pk."'
									AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."' AND salary_monthyear = '".date("Ym")."'");*/
	
	//if((count($chk_status)>0) && ($count_sal_save[0]['sal_check'] < 1))
	if((count($chk_status)>0))
	{
	?>
    <input id="approve_butz" type="submit" class="btn btn-success" value="Approve" name="approve" onclick="return confirm_send_approve();" />
    <input id="reject_butz" type="submit" class="btn btn-danger" value="Reject" name="reject" onclick="return confirm_send_reject();" />
    <?php
	}
	?>
      <!-- <a href="profile_entry_prof.php?emp_id_pk=<? if(!empty($_GET['emp_id_pk'])){ echo $_GET['emp_id_pk']; } else { echo $emp_id;} ?>" class="btn btn-danger" style="float:left;"><i class="fa fa-chevron-left"></i> PREVIOUS</a>-->
    </div>
  </div>
  
    
    
</form>

        </div>
      </div>

    </div>
        <div class="clear"></div>
    
<?
  //----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../../page/municipality_admin/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
@pg_close($con);
?>  

<script>
var chk_status_approve = 0;
var chk_status_reject = 0;

//For Approval
function confirm_send_approve()
{
	if(chk_status_approve == 0)
	{
		$('#finalize').modal('show');
		return false;
	}
	else
	{
		return true;
	}
	//return false;
}

function final_confirm_send_approve()
{
	chk_status_approve = 1;
	//alert(chk_status_approve);
	$('#loginForm_yearly #approve_butz').click();
}


//For Rejection
function confirm_send_reject()
{
	if(chk_status_reject == 0)
	{
		$('#reject').modal('show');
		return false;
	}
	else
	{
		return true;
	}
	//return false;
}

function final_confirm_send_reject()
{
	chk_status_reject = 1;
	$('#loginForm_yearly #reject_butz').click();
}
</script>

<div class="modal fade bs-example-modal-sm" id="finalize" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Loan Details Approval</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Approve This Employee Loan Details?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success finalize" onclick="return final_confirm_send_approve();">YES</a> 
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade bs-example-modal-sm" id="reject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Loan Details Rejection</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Reject This Employee Loan Details?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success reject" onclick="return final_confirm_send_reject();">YES</a> 
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
 
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
<?php @pg_close($con); ?>  
  