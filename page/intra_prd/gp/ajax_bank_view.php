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
$db=new database();
$arr=$db->fetch_table("select emp_id_const, emp_first_name,emp_second_name,emp_last_name,emp_bank_name, emp_id_pk, emp_acc_no,emp_ifsc_no,emp_status,bank_upd_status,emp_bank_branch,emp_branch_code,emp_micr_no,bank_msg_flag from prd_employee_master where emp_status='1' AND gp_id_fk='".$_SESSION['location']['gp_id']."' AND emp_id_pk='".$cryptoGraph->decode($_REQUEST['id'],4)."' order by emp_id_const");
//print_r($arr);
function fun_bank($val){
$db=new database();
$bnk=$db->fetch_table("select bank_name from prd_dise_bank_master where bank_code='".$val."'");
return $bnk[0]['bank_name'];
}
?>
<? if($arr[0]['bank_msg_flag']==1){ ?>
<div class="alert alert-success" style="text-align:center;font-weight:bold"><strong>Bank Details Updated By BDO</strong>
</div>
<? } ?>

<div class="school" style="background-color: #DFE2E2;">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Employee ID</th>
<th>Employee Name</th>
<th>Bank Name</th>
<th>Bank Branch</th>
<th>Bank Branch Code</th>
<th>Account No.</th>
<th>IFSC NO.</th>
<th>MICR NO.</th>
</tr>
<tr>
<td><?= $arr[0]['emp_id_const'] ?></td>
<td><?= $arr[0]['emp_first_name'].' '.$arr[0]['emp_second_name'].' '.$arr[0]['emp_last_name'] ?></td>
<td><?= fun_bank($arr[0]['emp_bank_name']) ?></td>
<td><?= $arr[0]['emp_bank_branch'] ?></td>
<td><?= $arr[0]['emp_branch_code'] ?></td>
<td><?= $arr[0]['emp_acc_no'] ?></td>
<td><?= $arr[0]['emp_ifsc_no'] ?></td>
<td><?= $arr[0]['emp_micr_no'] ?></td>
</tr>
</table>
</div>
</div>
<br /><br />
<div class="modal-footer">
<div class="btn-group">
        <? if($arr[0]['bank_upd_status']=='0'){ ?>
        <a class="btn btn-success" href="<?= $config['base_url']?>page/intra_prd/gp/ajax_bank_update.php?id=<?= $cryptoGraph->encode($arr[0]['emp_id_pk'],4);?>">Send Request</a> 
        <? } ?> 
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>      
      </div>
      </div>
<script>
    	$(document).ready(function(){
		 $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		});
</script>