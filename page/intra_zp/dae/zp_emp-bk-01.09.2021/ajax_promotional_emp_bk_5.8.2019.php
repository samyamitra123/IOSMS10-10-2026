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

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Edited Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Employee Profile Edited Fails...</strong></div>';
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
	//alert('<?= $config['base_url']?>page/intra_ps/da/ps_emp/ajax_send_transfer.php?id='+link);
	$.post('<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/ajax_send_promotional_emp.php?id='+link, function(data){
				  //alert(data);
				  $('.result').html(data);
			  	});
	});
});
</script>

<?php
$db=new database();
 $id=strtoupper($_REQUEST['id']); 

$pro_emp_chk = $db->fetch_table("select emp.emp_status, 
stop.reason_text, 
stop.reason_id_pk 
from prd_employee_master emp 
inner join prd_stop_sal_reason stop on stop.emp_id_fk=emp.emp_id_pk
 where stop.reason='1994' and emp.emp_id_const='".$id."' and emp.emp_status='2'
 ORDER BY 
stop.reason_id_pk DESC LIMIT 1");
if($pro_emp_chk!=0)
		{
 
$exists=$db->fetch_table("select emp_first_name,ps_id_fk from prd_employee_master where emp_id_const='".$id."' AND zp_id_fk='".$_SESSION['location']['district_id']."'");
	if(count($exists)=='1')
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Employee Already Exists in '. $_SESSION['location']['district_name'].' ZP </strong></div>';
	}
	else
	{
		
		$count=count($pro_emp_chk);
		if($count=='1')
		{
			pg_query('BEGIN');
			$pension=$db->fetch_table("select emp_pension_status from prd_employee_master  where emp_id_const='".$id."'");
	
			$pension_stat=$pension[0]['emp_pension_status'];
				if($pension_stat=='0')
				{
					 $update_pension='0';
				}
				elseif($pension_stat=='1' || $pension_stat=='2') 
				{
					 $update_pension='2'; 
				} 
			$update=$db->update("UPDATE prd_employee_master SET gp_id_fk='0',ps_id_fk='0',zp_id_fk='".$_SESSION['location']['district_id']."',emp_status='10',emp_form_status='1',emp_pension_status='".$update_pension."' WHERE emp_id_const='".$id."' AND emp_status='2'");
		
			if($update )
			{
				pg_query('COMMIT');
			
				$arr=$db->fetch_table("select emp_id_const,emp_id_pk,emp_first_name,emp_second_name,emp_last_name,zp_id_fk,emp_form_status from prd_employee_master where emp_id_const='".$id."'");
				
				$name=$arr[0]['emp_first_name'].' '.$arr[0]['emp_second_name'].' '.$arr[0]['emp_last_name'];
				$zp_name=$_SESSION['location']['district_name'];
				$zp_code=substr($_SESSION['user_info']['stake_user'],0,4);
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
                <? echo '<div class="alert alert-success" style="text-align:center"><strong>Employee Successfully Added To '.$zp_name .' ZP</strong></div>'; ?>
                <div class="emplist">
                <div class="school">
                <div class="table-responsive">
                <table width="100%">
                <!--<tr>
               <th colspan="8" style="font-size: 25px;"><?= $zp_name.' ('.$zp_code.')'?></th>
                </tr>-->
                <tr>
                <th>EMPLOYEE ID</th>
                <th>EMPLOYEE NAME</th>
                <th>PRIMARY DETAILS</th>
                <th>PROFESSIONAL DETAILS</th>
                <th>SALARY DETAILS</th>
                <th>PERSONAL DETAILS</th>
                <th>CONTACT DETAILS</th>
                <th>SEND TO EO</th>
                </tr>
                <tr>
                <td><?= $arr[0]['emp_id_const'] ;?></td>
                <td><?= $name;?></td>
                <td>
                <? if($arr[0]['emp_form_status']=='1' || $arr[0]['emp_form_status']=='2' || $arr[0]['emp_form_status']=='3' || $arr[0]['emp_form_status']=='4' || $arr[0]['emp_form_status']=='5'){ ?>
                <a href="<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/profile_entry_basic_edit.php?emp_id_pk=<?=$cryptoGraph->encode($arr[0]['emp_id_pk'],4)?>&zp_id=<?=$cryptoGraph->encode($_SESSION['location']['district_id'],4) ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a>
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
                <a href="<?= $config['base_url'] ?>page/intra_zp/dae/zp_emp/ajax_send_promotional_emp.php?id=<?php echo $cryptoGraph->encode($arr[0]['emp_id_pk'],4) ?>"><img width="25" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="sent"/></a>
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
				echo '<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Employee Added Fails.</strong></div>'; exit(0);
			}
	
		} 
			else 
			{ 
				echo '<div class="alert alert-danger" style="text-align:center"><strong>Sorry!!! Employee Added Fails.</strong></div>';
			} 
	}
}
else
{
	echo '<div class="alert alert-danger" style="text-align:center"><strong>Employee should not be added in '. $_SESSION['location']['ps_name'].' PS </strong></div>';
}	


?>
<div class="clear"></div>
