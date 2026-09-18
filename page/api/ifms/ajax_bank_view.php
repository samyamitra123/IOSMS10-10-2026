<?
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
$cryptoGraph=new cryptography();
$db=new database();

$emp_id=$cryptoGraph->decode($_GET['id'],4);

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
													emp_id_const 
												FROM prd_employee_master emp
												INNER JOIN prd_sftp_benf_failure_details benf
												ON emp.emp_id_pk=benf.emp_id_fk
												WHERE benf.active status='1' AND benf.response_from='9'");

$bank_details=$db->fetch_table("SELECT id, bank_name, bank_code, bank_ifsc, digit_in_account_no, status FROM prd_dise_bank_master where status=1;");

?>
<form id="form">
    <div class="school" style="background-color: #DFE2E2;">
        <div class="table-responsive">
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
        </div>
    </div>
    
    <div class="col-md-6 col-md-6 col-xs-12 col-md-offset-5" style="padding-top:6px;">
    	<button type="button" class="btn btn-success" id="submit" value="Submit"  onClick="valid_code();">SUBMIT</button>     
    </div>
    <br /><br /><br />
    
    <div class="modal-footer">
        <div class="btn-group">
        	<button type="button" class="btn btn-warning"  data-dismiss="modal">Close</button> 
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
		
		function bank_value()
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
		}
</script>
<style>
.your-class::-webkit-input-placeholder {
    color:#060;
	
}
</style>