<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
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
$tchcd=isset($_GET['tchcd'])?$_GET['tchcd']:' ';*/
$db=new database();
$bank_details=$db->fetch_table("SELECT id, bank_name, bank_code, bank_ifsc, digit_in_account_no, status FROM prd_dise_bank_master where status=1;");
?>
<style>
	#sucess{
			background-color: green;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
			margin-top:-3%;
		}
		#error{
			background-color: red;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
			margin-top:-3%;
		}
</style>

<?php
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Primary Details of the Employee submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Data insertion failed. Please try again...</div>';
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
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}
?>
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
		function calculate_retireyear(employee_dob){
			//alert(employee_dob);
			//child1 = new ajaxLoader('.child1');
			$.post('<?php echo $config['base_url'] ?>page/intra_prd/common/retirement_date.php',{employee_dob:employee_dob},function(data){
				if(data){
					//if (child1) child1.remove();
					//alert(data);
					$('#emp_date_retirement').val(data);
				}
			});
	  	}
		
		$(document).ready(function(){
			var account_len = $("#txt_account").val().length;
			$("#txt_account").attr("maxlength",account_len);
			$("#txt_bank").click(function() { 
                if($(this).val() == '') {
					$("#txt_account").val('');
					$("#txt_ifsc").val('');
					$("#txt_account").attr("disabled","disabled");
					$("#txt_ifsc").attr("disabled","disabled");
					$("#acc_display").html('');
					
				}
				else{
					<?php foreach($bank_details as $item){ ?>
					if(($(this).val() == '<?php echo $item['bank_code'] ?>')) {                       	
					$("#txt_account").removeAttr('disabled');
					$("#txt_ifsc").removeAttr('disabled');
					$("#txt_account").val('');	
					$("#txt_account").attr("maxlength","<?php echo $item['digit_in_account_no'] ?>");
					$("#txt_ifsc").val('<?php echo $item['bank_ifsc'] ?>');
					$("#acc_display").html('<?php echo $item['digit_in_account_no']." digits" ?>');
					}
					<?php } ?>
				}
			});
		});
        </script> 


<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
         <br /> <? require '../../../page/common_back_btns.php'; ?>
<h2 class="primary" align="center">Salary Details</h2>

<form class="form-horizontal" id="loginForm" method="post" action="profile_entry_salary_insert.php">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Pay Band</label>
    <div class="col-sm-7">
   				<?
				$db = new database();
				$arr = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=5 and code like '22%' order by code");
				?>
      <select class="form-control" name="pay_band" id="pay_band">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['code']. '<br />'; ?>
       <option value="<?= $key['code']; ?>" <? if($tch_pay_band_no==$key['code']){ echo "selected";}?>><?= $key['description']; ?></option>
       <? } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Pay in Pay Band</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="pay_in_payband" id="pay_in_payband" placeholder="Pay in Pay Band" autocomplete="off" value="">
    </div>
  </div>
  <div class="form-group">
     <label for="inputPassword3" class="col-sm-3 control-label">Grade Pay </label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="grade_pay" id="grade_pay" placeholder="Grade Pay" autocomplete="off" value="">
  </div>
  </div>
   <div class="form-group">
     <label for="inputPassword3" class="col-sm-3 control-label">Pay Scale</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="pay_scale" id="pay_scale" placeholder="Pay Scale" autocomplete="off" value="">
  </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Bank Name</label>
     <div class="col-sm-7">
     <?
	 $db = new database();
	 $arr = $db->fetch_table("select bank_code,bank_name from prd_dise_bank_master order by bank_name");
	 ?>
      <select class="form-control" id="txt_bank" name="txt_bank">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['bank_code']. '<br />'; ?>
       <option value="<?= $key['bank_code']; ?>" <? if($txt_bank_name==$key['bank_code']){ echo "selected";}?>><?= $key['bank_name']; ?></option>
        <? } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Branch Name</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="tch_bank_branch" name="tch_bank_branch" placeholder="Branch Name" autocomplete="off" value="">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Branch Code</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="tch_branch_code" name="tch_branch_code" placeholder="Branch Code" autocomplete="off" value="">
    </div>
  </div>
   <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">MICR Code</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="tch_micr_code" name="tch_micr_code" placeholder="MICR Code" autocomplete="off" value="">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Account No</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="txt_account" name="txt_account" placeholder="Account Number" autocomplete="off" value="">
    </div>
    <div class="col-sm-1"><p id="acc_display" style="color: green;font-weight: bold;"></p></div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">IFSC Code</label>
    <div class="col-sm-7">
      <input type="text" class="form-control"  id="txt_ifsc" name="txt_ifsc"  placeholder="IFSC Code" autocomplete="off"  value="">
    </div>
  </div>

  
  
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-7">
      <button type="submit" class="btn btn-primary">Save & Continue</button>
    </div>
  </div>
</form>

        </div>
      </div>

    </div>