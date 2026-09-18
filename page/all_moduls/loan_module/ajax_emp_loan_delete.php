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

$str=$_SESSION['location']['gpcode'];
$state10=substr($str,0,4); 

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

$db=new database();
?>
<script>
$(document).ready(function(){
		$("#delete").hide();
	});
</script>
<script>
function delete_but()
{
	//alert("1234");
	if($('[name="delete[]"]:checked').length > 0)
	{
		//alert("12345");
		$("#delete").show();
	}
	else
	{
		$("#delete").hide();	
	}
}
</script>

<style>
        .form-horizontal .control-label {
			text-align:left;
			}
        </style>

<?php

//$emp_id_pk=isset($_GET['emp_id_pk'])?$_GET['emp_id_pk']:' ';
$cryptoGraph=new cryptography();
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Professional Details of the Employee submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "PRD | Govt. of West Bengal";
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

?>
<div><center><h1 class="heading">DELETE LOAN </h1></center></div>
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
    
<form class="form-horizontal" id="loginForm" method="post" action="emp_loan_module_delete_submit.php" onsubmit="return valid_code();">
<input type="hidden" name="emp_id_pk" value="<?=$cryptoGraph->encode($emp_id_pk,4) ?>" />
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
 $abc = $loan_master_data[0]['loan_type'];
 
 ?>
<p id="insertinputs"></p>   
 <div id="add_rows">
 <?php
 $loan_deduction_data = $db->fetch_table("SELECT 
 										loan_id_pk,  
										total_amount,
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
										AND delete_status in (1) AND edit_status in (1)");

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
        <!--<input type="text" name="eee" value="w" /> -->
          <?php if(($deductions['status'] == 1) && ($deductions['approval_status'] == 1 || $deductions['approval_status'] == 4)) { echo "<input type='checkbox' name='delete[]' class='chacked' value='".$deductions['loan_id_pk']."' onclick='return delete_but();'>"; }?>
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
	}
 ?>
   
 </div>
  <p style="border-top:1px dashed #27769F; text-align:center; width:800px;"></p>
  <div class="form-group">
    <div class="col-sm-12" style="margin-left:35%;">
      <input type="submit" id="delete" class="btn btn-danger" name="loan_deductions_submit" value="DELETE" />
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
	  input[type="checkbox"] {
    /* display: none; */
	display: inline;
}
</style>  
    
    
  
  