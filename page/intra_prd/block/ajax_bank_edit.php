<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
$db=new database();
$arr=$db->fetch_table("select emp_id_const, emp_first_name,emp_second_name,emp_last_name,emp_bank_name, emp_id_pk, gp_id_fk,emp_acc_no,emp_ifsc_no,emp_status,bank_upd_status,emp_bank_branch,emp_branch_code,emp_micr_no from prd_employee_master where emp_status='1' AND emp_id_pk='".$cryptoGraph->decode($_REQUEST['id'],4)."' order by emp_id_const");

$bank_details=$db->fetch_table("SELECT id, bank_name, bank_code, bank_ifsc, digit_in_account_no, status FROM prd_dise_bank_master where status=1;");

$bank_name=$arr[0]['emp_bank_name'];
$acc_no=$arr[0]['emp_acc_no'];
$branch=$arr[0]['emp_bank_branch'];
$branch_code=$arr[0]['emp_branch_code'];
$ifsc=$arr[0]['emp_ifsc_no'];
$micr=$arr[0]['emp_micr_no'];
$gp_id=$arr[0]['gp_id_fk'];
$emp_id_pk=$arr[0]['emp_id_pk'];

?>


<form class="form-horizontal" id="loginForm" method="post" action="ajax_bank_edit_submit.php" onsubmit="return valid_code();">
<input type="hidden" name="gp_id" id="gp_id" value="<? echo $gp_id; ?>" />
<input type="hidden" name="emp_id_pk" id="emp_id_pk" value="<? echo $emp_id_pk; ?>" />
<input type="hidden" name="sec_tok" id="sec_tok" value="<? echo $enc_token; ?>" />
<div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Employee ID</label>
    <div class="col-sm-4">
      <p class="text-warning" style="font-size:14px;margin-top: 2%;font-weight:700;font-family: "MS Serif", "New York", serif;"><?= $arr[0]['emp_id_const'] ?></p>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Employee Name</label>
    <div class="col-sm-4">
       <p class="text-warning" style="font-size:14px;margin-top: 2%;font-weight:700;font-family: "MS Serif", "New York", serif;"><?= $arr[0]['emp_first_name'].' '.$arr[0]['emp_second_name'].' '.$arr[0]['emp_last_name'] ?></p>
    </div>
  </div>

<div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Bank Name<span class="star_color">*</span></label>
     <div class="col-sm-4">
     <?
	 $db = new database();
	 $arr = $db->fetch_table("select bank_code,bank_name from prd_dise_bank_master order by bank_name");
	 ?>
      <select class="form-control" id="txt_bank" name="txt_bank">
      <option value="">-Please Select-</option>
       <? foreach($arr as $key){ $key['bank_code']. '<br />'; ?>
       <option value="<?= $key['bank_code']; ?>" <? if($bank_name==$key['bank_code']){ echo "selected";}?>><?= $key['bank_name']; ?></option>
        <? } ?>
      </select>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">Branch Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control upper_case" id="tch_bank_branch" name="tch_bank_branch" placeholder="Branch Name" autocomplete="off" value="<? echo $branch; ?>">
    </div>
  </div>
 
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Branch Code</label>
    <div class="col-sm-4">
      <input type="text" class="form-control upper_case" id="tch_branch_code" name="tch_branch_code" placeholder="Branch Code" autocomplete="off" value="<? echo $branch_code; ?>">
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">MICR Code</label>
    <div class="col-sm-4">
      <input type="text" class="form-control upper_case" id="tch_micr_code" name="tch_micr_code" placeholder="MICR Code" autocomplete="off" value="<? echo $micr; ?>" onKeyPress="return keyRestrict(event,'0123456789');">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Account No <span class="star_color">*</span></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="txt_account" name="txt_account" placeholder="Account Number" autocomplete="off" value="<? echo $acc_no; ?>" onKeyPress="return keyRestrict(event,'0123456789');">
       <p id="acc_display" style="color: green;font-weight: bold;"></p>
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">IFSC Code<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <input type="text" class="form-control"  id="txt_ifsc" name="txt_ifsc"  placeholder="IFSC Code" autocomplete="off"  value="<? echo $ifsc; ?>" maxlength="11">
    </div>
  </div>
<div class="modal-footer">
<div class="btn-group">
  <button type="submit" class="btn btn-success">Update</button>
  <a type="button" class="btn btn-warning" data-dismiss="modal">Close</a>
</div>
</div>
</form>
<script>
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
        <script>
    function valid_code(){	 
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
		}
	}
    </script>