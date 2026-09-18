<?
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$cryptoGraph=new cryptography();

if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success'){
		$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Edited Successfully...</strong></div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div class="alert alert-danger" style="text-align:center"><strong>Employee Profile Edited Fails...</strong></div>';
	}
}

?>
<style>
.school table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
.school table, .school td, .school th
	{
		/*border:1px solid #fff;*/
		padding: 4px;
		text-align:center;
	}
	
.school table th{
		background-color: #3E9B96;
		border:1px solid #fff;
		color: #fff;
		padding: 6px;
		text-align:center;
	}
.school table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
		font-size: 14px;
	}
.school{
	background-color: #FFFFFF;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	padding: 10px;
	
}
.school .title h2{
	color: #FFF;
	text-align: center;
	padding: 0px;
	margin: 0px;
	background-color: #0D8BBD;
	border-radius: 8px;
	-moz-border-radius: 8px;
}
.school .action .ui-widget{
	font-size: 11px;
}
.school .action{
	text-align: center;
}
.school .action .ui-button .ui-button-text{
	padding: 5px 10px;
}

</style>
<script>
$(document).ready(function(e) {
    $("#sent a").click( function(){
	var link = $(this).attr('id');
	alert('<?= $config['base_url']?>page/intra_zp/dae/zp_emp/ajax_send_transfer.php?id='+link);
	$.post('<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/ajax_send_transfer.php?id='+link, function(data){
				  //alert(data);
				  $('.result').html(data);
			  	});
	});
});
</script>

<?php




$db=new database();
 $id=strtoupper($_REQUEST['id']);
$lpc_no=$_GET['lpc_no'];


$transfer_emp_chk = $db->fetch_table("SELECT 
										  transfer_emp_status,
										  transfer_alert_date,
										  transfer_level,
										  emp_id_const,
										  transfer_district_id_fk ,
										  transfer_block_id_fk ,
										  transfer_gp_id_fk ,
										  transfer_date ,
										  emp_id_fk ,
										  transfer_remarks ,
										  transfer_alert_date,
										  transfer_ps_id_fk,
										  transfer_level,
										  lpc_status,
										  lpc_number,zp_id_fk 
									FROM prd_employee_transfer 
									WHERE transfer_district_id_fk='".$_SESSION['location']['district_id']."' and transfer_level='557' and emp_id_const='".$id."' and transfer_emp_status='0'");

 

/*if(strtotime($transfer_emp_chk[0]['transfer_alert_date'])<strtotime(date("Y-m-d")))
{ */
   

	$exists=$db->fetch_table("select emp_first_name from prd_employee_master where emp_id_const='".$id."' AND zp_id_fk='".$_SESSION['location']['district_id']."'");
	if(count($exists)=='1')
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Employee Already Exists in '. $_SESSION['location']['district_name'].' ZP </strong></div>';
	}
	else if($lpc_no=="")
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter LPC Number</strong></div>';
	}
	else if($lpc_no!="" && (($transfer_emp_chk[0]['lpc_status']=='1' && $lpc_no!=$transfer_emp_chk[0]['lpc_number']) || $transfer_emp_chk[0]['lpc_status']=='0'))
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Invalid LPC Number</strong></div>';
	}
	else
	{
		
		$count=count($transfer_emp_chk);
		if($count=='1')
		{
			pg_query('BEGIN');
			$update=$db->update("UPDATE prd_employee_master SET zp_id_fk='".$transfer_emp_chk[0]['transfer_district_id_fk']."',emp_status='10',emp_form_status='1' WHERE emp_id_const='".$id."' AND emp_status='2'");
			$update_emp_transfer=$db->update("update prd_employee_transfer set transfer_emp_status='1' where emp_id_const='".$id."'");
		
			if($update && $update_emp_transfer)
			{
				pg_query('COMMIT');
			
				$arr=$db->fetch_table("select emp_id_const,emp_id_pk,emp_first_name,emp_second_name,emp_last_name,zp_id_fk,emp_form_status from prd_employee_master where emp_id_const='".$id."'");
				
				$name=$arr[0]['emp_first_name'].' '.$arr[0]['emp_second_name'].' '.$arr[0]['emp_last_name'];
				//$ps_name=$db->fetch_table("select ps_name,ps_code from prd_location_master_panchayat_samiti where ps_id_pk='".$arr[0]['ps_id_fk']."'");
				$zp_name=$db->fetch_table("select district_name,district_code from prd_location_master_district where district_id_pk='".$arr[0]['zp_id_fk']."'");
				?>
	
				<script>
                        $(document).ready(function(){
                          $( "tr:odd" ).css( "background-color", "#CCE6FF" );
                          $( "tr:even" ).css( "background-color", "#DDF7FF" );
                        });
                </script>
                
                <style>
                h1 {
                display: block;
                font-size: 2em;
                -webkit-margin-before: 0.67em;
                -webkit-margin-after: 0.67em;
                -webkit-margin-start: 0px;
                -webkit-margin-end: 0px;
                font-weight: bold;
                }
                </style>
                <? echo '<div class="alert alert-success" style="text-align:center"><strong>Employee is Successfully Transferred To '.$_SESSION['location']['district_name'] .' ZP</strong></div>'; ?>
                <div class="emplist">
                <div class="school">
                <div class="table-responsive">
                <table width="100%">
                <tr>
                <th colspan="8" style="font-size: 25px;"><?= $zp_name[0]['district_name'].' ('.$zp_name[0]['district_code'].')'?></th>
                </tr>
                <tr>
                <th>EMPLOYEE ID</th>
                <th>EMPLOYEE NAME</th>
                <th>PRIMARY DETAILS</th>
                <th>PROFESSIONAL DETAILS</th>
                <th>SALARY DETAILS</th>
                <th>PERSONAL DETAILS</th>
                <th>CONTACT DETAILS</th>
                <th>SEND TO  SECRETARY</th>
                </tr>
                <tr>
                <td><?= $arr[0]['emp_id_const'] ;?></td>
                <td><?= $name;?></td>
                <td>
                <? if($arr[0]['emp_form_status']=='1' || $arr[0]['emp_form_status']=='2' || $arr[0]['emp_form_status']=='3' || $arr[0]['emp_form_status']=='4' || $arr[0]['emp_form_status']=='5'){ ?>
                <a href="<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/profile_entry_basic_edit.php?emp_id_pk=<?=$cryptoGraph->encode($arr[0]['emp_id_pk'],4)?>&zp_id=<?=$cryptoGraph->encode($_SESSION['location']['district_id'],4) ?>"><img width="26" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit" /></a>
                <? } ?>
                </td>
                <td>
                <? if($arr[0]['emp_form_status']=='2' || $arr[0]['emp_form_status']=='3' || $arr[0]['emp_form_status']=='4' || $arr[0]['emp_form_status']=='5'){ ?>
                <a href="<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/profile_entry_prof.php?emp_id_pk=<?=$cryptoGraph->encode($arr[0]['emp_id_pk'],4)?>&zp_id=<?=$cryptoGraph->encode($_SESSION['location']['district_id'],4) ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a>
                 <? } ?>
                </td>
                <td>
                <? if($arr[0]['emp_form_status']=='3' || $arr[0]['emp_form_status']=='4' || $arr[0]['emp_form_status']=='5'){ ?>
                <a href="<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/profile_entry_sal.php?emp_id_pk=<?=$cryptoGraph->encode($arr[0]['emp_id_pk'],4)?>&zp_id=<?=$cryptoGraph->encode($_SESSION['location']['district_id'],4) ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a>
                <? } ?>
                </td>
                <td>
                <? if($arr[0]['emp_form_status']=='4' || $arr[0]['emp_form_status']=='5'){ ?>
                <a href="<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/profile_entry_per.php?emp_id_pk=<?=$cryptoGraph->encode($arr[0]['emp_id_pk'],4)?>&zp_id=<?=$cryptoGraph->encode($_SESSION['location']['district_id'],4) ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a>
                <? } ?>
                </td>
                <td>
                <? if($arr[0]['emp_form_status']=='5'){ ?>
                <a href="<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/profile_entry_contact.php?emp_id_pk=<?=$cryptoGraph->encode($arr[0]['emp_id_pk'],4)?>&zp_id=<?=$cryptoGraph->encode($_SESSION['location']['district_id'],4) ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a>
                <? } ?>
                </td>
                <td id="">
                <? if($arr[0]['emp_form_status']=='5'){ ?>
                <a href="<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/ajax_send_transfer.php?id=<?php echo $cryptoGraph->encode($arr[0]['emp_id_pk'],4) ?>"><img width="25" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="sent"/></a>
                <? } ?>
                </td>
                </tr>
                </table>
                </div>
                </div>
                </div>
                <div class="result"></div>
                </div>
                </div>
                </div>
                </div>
                </div>
                <? 
				}
				else
				{
					pg_query('ROLLBACK');
					echo '<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Employee Transfer Fails.</strong></div>'; exit(0);
				}

			} 
			else 
			{ 
				echo '<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Employee Transfer Fails.</strong></div>';
			} 
	}
/*}
else
{
	echo '<div class="alert alert-danger" style="text-align:center"><strong>Employee should not be added in '. $_SESSION['location']['ps_name'].' PS </strong></div>';
}*/

?>
<div class="clear"></div>
