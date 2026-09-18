<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
$cryptoGraph=new cryptography();
$db=new database();

$emp_id=$cryptoGraph->decode($_GET['id'],4);
$monthyear=$_GET['monthyear']; 
 $benf_id_get=$cryptoGraph->decode($_GET['benf_id'],4);

$wrong_employee_data_fetch=$db->fetch_table("  SELECT 
													gp_id_fk,
													emp_first_name,
													emp_second_name,
													emp_last_name,
													emp_bank_name,
													emp_bank_branch,
													emp_branch_code,
													emp_micr_no,
													emp_acc_no,
													emp_ifsc_no,
													emp_id_const,
													emp_pension_status,
													sftp_benf_id_fk 
												FROM prd_employee_master emp
												INNER JOIN prd_sftp_benf_failure_details benf
												ON emp.emp_id_pk=benf.emp_id_fk
												WHERE benf.response_from='9' AND emp_id_pk='".$emp_id."'");

$bank_details=$db->fetch_table("SELECT id, bank_name, bank_code, bank_ifsc, digit_in_account_no, status FROM prd_dise_bank_master where status=1;");

$benf_id=$cryptoGraph->encode($wrong_employee_data_fetch[0]['sftp_benf_id_fk'],4);
?>

<script>

function valid_code()
{
	var benf_id='<?php echo $benf_id; ?>';
	if($('#tch_fname').val()=='' || $('#tch_fname').val()=='FIRST')
	{
		alert('Please Enter Your FIRST Name.');
		$('#tch_fname').focus();
		return false;
	}
	if($('#txt_bank').val()=='')
	{
		alert('Please Select Bank Name.');
		$('#txt_bank').focus();
		return false;
	} 
	if($('#txt_account').val()=='' || ($("#txt_account").val().length != parseInt($("#txt_account").attr('maxlength')) && parseInt($("#txt_account").attr('maxlength'))!=-1))
	{
		alert('Please enter valid  Bank Account No.');
		$('#txt_account').focus();
		return false;
	}
	if(($('#txt_ifsc').val()=='' || $('#txt_ifsc').val().length!=11))
	{
		alert('Please enter Valid Bank IFSC Code.');
		$('#txt_ifsc').focus();
		return false;
	}
	if(($('#txt_ifsc').val().substring(0,4)) != ($('#txt_bank').val().substring(0,4)))
	{
		alert('Please enter Valid Bank IFSC Code.');
		$('#txt_ifsc').focus();
		return false;
	}
					
	$.post('<?= $config['base_url'] ?>page/api/ifms/gp/employee_bank_details_insert.php',$("#form_edit").serialize(), function(data){
	   // alert(data);
	$('#indi_edit').modal('hide');
	var result = $.parseJSON(data);
	var error=result[0];
	var success=result[1];
	if(error.length!=0)
	{
		$('#msg').html('<div class="alert alert-danger" style="text-align:center"><strong>Invalid '+error[0]+'.</strong></div>');
	}
	else if(success.length!=0)
	{
		$.post('<?= $config['base_url'] ?>page/api/ifms/gp/sftp_wrong_data_edit.php?benf_id='+benf_id, function (data) {
            //alert(data);
            $("#mbody").html(data);
            $("#empshow").html(data);
			$('#msg').html('<div class="alert alert-success" style="text-align:center"><strong>Data of '+success[1]+' has been updated successfully.</strong></div>');
			$('#dis'+success[2]).hide();
			$('#en'+success[2]).show();
        });
		
	}
	/*if(data=='Success')
	{
	$('#view').modal('hide');
	$("#success").html('<strong>Success.</strong>').show();
	}
	else
	{
	$("#failed").html('<strong>FAILED.</strong>').show();
	}*/
	});
	

}

</script>

<form id="form_edit" method="post">
<div class="col-sm-12">
        <!--<div class="table-responsive">
        <table width="100%">
            <tr>
                <th>EMPLOYEE NAME</th>
                <th>BANK NAME</th>
                <th>BANK BRANCH</th>
                <th>BANK BRANCH CODE</th>
                <th>ACCOUNT NUMBER</th>
                <th>IFSC CODE.</th>
                <th>MICR NO.</th>
            </tr>
            <?php 
			if(count($wrong_employee_data_fetch)>0)
			{
				foreach($wrong_employee_data_fetch as $key)
				{ ?>
					<tr>
						<td>eee</td>
						<td><?
						$db = new database();
						$arr = $db->fetch_table("select bank_code,bank_name from prd_dise_bank_master order by bank_name");
						?>
						<select class="form-control" id="txt_bank" name="txt_bank" onChange="bank_value();">
						<option value="">-Please Select-</option>
						<? foreach($arr as $key){ $key['bank_code']. '<br />'; ?>
						<option value="<?= $key['bank_code']; ?>" <? if($emp_bank_name==$key['bank_code'] || $txt_bank==$key['bank_code']){ echo "selected";}?>><?= $key['bank_name']; ?></option>
						<? } ?>
						</select> 
						<input type="hidden" id="bank_code">
						</td>
						<td><input type="text" class="form-control upper_case" id="tch_bank_branch" name="tch_bank_branch" placeholder="Branch Name" autocomplete="off" value="<?=$emp_bank_branch ?>">
						</td>
						<td><input type="text" class="form-control upper_case" id="tch_branch_code" name="tch_branch_code" placeholder="Branch Code" autocomplete="off" value="<?=$emp_branch_code ?>">
						</td>
						<td><input type="text" class="form-control" id="new_account_no" name="new_account_no" placeholder="ACCOUNT NUMBER" autocomplete="off" value="<?=$emp_acc_no ?>" onKeyPress="return keyRestrict(event,'0123456789');">
						</td>
						<td><input type="text" class="form-control"  id="txt_ifsc" name="txt_ifsc"  placeholder="IFSC CODE" autocomplete="off"  value="<?=$emp_ifsc_no ?>" maxlength="11">
						</td>
						<td><input type="text" class="form-control upper_case" id="new_micr_no" name="new_micr_no" placeholder="MICR Code" autocomplete="off" value="<?=$emp_micr_no ?>" onKeyPress="return keyRestrict(event,'0123456789');">
						</td>
					</tr>
				<?php 
				} 
			}
			else
			{?>
            	<tr><td colspan=""></td></tr>
            <?php }?> 
        </table>
        </div>-->
        <div class="row mb-3" style="height:50px;">
	    <input type="hidden" value="<?php echo $monthyear; ?>" id="monthyear" name="monthyear">
	    <input type="hidden" value="<?php echo $benf_id_get; ?>" id="benf_id_get" name="benf_id_get">
	    
            <label for="inputEmail3" class="col-sm-3 col-form-label">Employee Name<span class="star_color">*</span></label>
            <div class="col-sm-3">
            	<input type="text" class="form-control upper_case" id="tch_fname"  name="tch_fname" placeholder="First Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $wrong_employee_data_fetch[0]['emp_first_name']?>">
            </div>
            <div class="col-sm-3">
            	<input type="text" class="form-control upper_case" id="tch_mname"  name="tch_mname" placeholder="Middle Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $wrong_employee_data_fetch[0]['emp_second_name'] ?>">
            </div>
            <div class="col-sm-3">
            	<input type="text" class="form-control upper_case" id="tch_lname"  name="tch_lname" placeholder="Last Name" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?= $wrong_employee_data_fetch[0]['emp_last_name'] ?>">
            </div>
        </div>
        <div class="row mb-3" style="height:50px;">
        <label for="inputPassword3" class="col-sm-3 col-form-label">Bank Name<span class="star_color">*</span></label>
        <div class="col-sm-3">
            <?
            $db = new database();
            $arr = $db->fetch_table("select bank_code,bank_name from prd_dise_bank_master order by bank_name");
            ?>
            <select class="form-control" id="txt_bank" name="txt_bank">
                <option value="">-Please Select-</option>
                <? foreach($arr as $key){ $key['bank_code']. '<br />'; ?>
                <option value="<?= $key['bank_code']; ?>" <? if($wrong_employee_data_fetch[0]['emp_bank_name']==$key['bank_code']){ echo "selected";}?>><?= $key['bank_name']; ?></option>
                <? } ?>
            </select>
        </div>
        <label for="inputPassword3" class="col-sm-3 control-label">Branch Name</label>
        <div class="col-sm-3">
            <input type="text" class="form-control upper_case" id="tch_bank_branch" name="tch_bank_branch" placeholder="Branch Name" autocomplete="off" value="<?=$wrong_employee_data_fetch[0]['emp_bank_branch'] ?>">
        </div>
    </div>
    
    <div class="row mb-3" style="height:50px;">
        <label for="inputPassword3" class="col-sm-3 col-form-label">Branch Code</label>
        <div class="col-sm-3">
            <input type="text" class="form-control upper_case" id="tch_branch_code" name="tch_branch_code" placeholder="Branch Code" autocomplete="off" value="<?=$wrong_employee_data_fetch[0]['emp_branch_code'] ?>">
        </div>
        <label for="inputPassword3" class="col-sm-3 control-label">MICR Code</label>
        <div class="col-sm-3">
            <input type="text" class="form-control upper_case" id="tch_micr_code" name="tch_micr_code" placeholder="MICR Code" autocomplete="off" value="<?=$wrong_employee_data_fetch[0]['emp_micr_no'] ?>" onKeyPress="return keyRestrict(event,'0123456789');">
        </div>
    </div>
    <div class="row mb-3" style="height:50px;">
        <label for="inputEmail3" class="col-sm-3 col-form-label">Account No <span class="star_color">*</span></label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="txt_account" name="txt_account" placeholder="ACCOUNT NUMBER" autocomplete="off" value="<?=$wrong_employee_data_fetch[0]['emp_acc_no'] ?>" onKeyPress="return keyRestrict(event,'0123456789');" >
        <p id="acc_display" style="color: green;font-weight: bold;"></p>
        </div>
        <label for="inputPassword3" class="col-sm-3 control-label">IFSC Code<span class="star_color">*</span></label>
        <div class="col-sm-3">
            <input type="text" class="form-control"  id="txt_ifsc" name="txt_ifsc"  placeholder="IFSC CODE" autocomplete="off"  value="<?=$wrong_employee_data_fetch[0]['emp_ifsc_no'] ?>" maxlength="11">
        </div>
    </div>
    <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $_GET['id'];?>" />
    <input type="hidden" name="pension_stat" id="pension_stat" value="<?php echo $wrong_employee_data_fetch[0]['emp_pension_status'];?>" />
    
     <div class="col-md-6 col-md-offset-5" style="padding-top:10px; margin-left: 45%;">
    	<button type="button" class="btn btn-success" id="submit" value="Submit"  onClick="valid_code();">SUBMIT</button>     
    </div>
</div>
  
</form>
        
<script>
		$(document).ready(function(){
		$( "tr:odd" ).css( "background-color", "#CCE6FF" );
		$( "tr:even" ).css( "background-color", "#DDF7FF" );
		if( $("#txt_bank").val()=='')
		  {
			$("#txt_ifsc").attr("disabled","disabled");
			$("#new_account_no").attr("disabled","disabled");
			$("#new_micr_no").attr("disabled","disabled");
			$("#tch_bank_branch").attr("disabled","disabled");
			$("#tch_branch_code").attr("disabled","disabled");
			var c= $("#txt_bank").val();
			$("#bank_code").val(c);
		  }
		});
		
		
		
		
		/*function bank_value()
		{
		if( $("#txt_bank").val()=='')
		  {
			$("#txt_ifsc").attr("disabled","disabled");
			$("#new_account_no").attr("disabled","disabled");
			$("#new_micr_no").attr("disabled","disabled");
			$("#tch_bank_branch").attr("disabled","disabled");
			$("#tch_branch_code").attr("disabled","disabled");
			$("#tch_bank_branch").val('');
			$("#tch_branch_code").val('');
			$("#txt_ifsc").val('');
			$("#new_account_no").val('');
			$("#new_micr_no").val('');
			$("#new_account_no").removeClass('your-class');
			$("#new_account_no").removeAttr('placeholder');
			$("#bank_code").val(c);
		  }
		  else
		  {
				var c= $("#txt_bank").val();
				$("#bank_code").val(c);
			  <?php foreach($bank_details as $item)
				{ ?>
					if(($("#txt_bank").val() == '<?php echo $item['bank_code'] ?>')) 
					{     
						$("#new_account_no").addClass('your-class').attr("placeholder","<?php echo $item['digit_in_account_no']." digits" ?>");                  	
						//$("#txt_account").removeAttr('disabled');
						$("#txt_ifsc").removeAttr('disabled');
						$("#new_account_no").removeAttr('disabled');
						$("#new_micr_no").removeAttr('disabled');
						$("#txt_ifsc").val('<?php echo $item['bank_ifsc'] ?>');
						//$("#acc_display").val('<?php echo $item['digit_in_account_no']." digits" ?>');
						$("#tch_bank_branch").removeAttr('disabled');
						$("#tch_branch_code").removeAttr('disabled');
						$('#new_account_no').addClass('your-class');
						$("#new_account_no").val('');
						$("#new_account_no").attr("maxlength","<?php echo $item['digit_in_account_no'] ?>");
					}
				<?php } ?>
		  }	
		}*/
</script>

<script>
$(document).ready(function()
	{
		var account_len = $("#txt_account").val().length;
		$("#txt_account").attr("maxlength",account_len);
		
		$("#txt_bank").click(function() 
		{ 
			if($(this).val() == '') 
			{
				$("#txt_account").val('');
				$("#txt_ifsc").val('');
				$("#txt_account").attr("disabled","disabled");
				$("#txt_ifsc").attr("disabled","disabled");
				$("#acc_display").html('');
			}
			else
			{
				<?php foreach($bank_details as $item)
				{ ?>
					if(($(this).val() == '<?php echo $item['bank_code'] ?>')) 
					{                       	
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
<style>
.your-class::-webkit-input-placeholder {
    color:#060;
	
}
</style>