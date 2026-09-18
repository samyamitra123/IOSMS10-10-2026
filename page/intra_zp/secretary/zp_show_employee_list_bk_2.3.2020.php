<?
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
header("Location:../../dashboard.php");
}

if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}
$cryptoGraph=new cryptography();
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

<?php


$common['title'] = "Start or Stop Or Suspend Salary| PRD | Govt. of West Bengal ";


//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
$(document).ready(function(){
$( "tr:odd" ).css( "background-color", "#CCE6FF" );
$( "tr:even" ).css( "background-color", "#DDF7FF" );
});
</script>
<?
$db=new database();

$arr=$db->fetch_table("
						SELECT 
						emp_first_name, 
						emp_second_name, 
						emp_last_name, 
						emp_desig, 
						emp_status, 
						emp_id_pk, 
						zp_id_fk,
						emp_id_const 
						FROM 
						prd_employee_master 
						WHERE 
						emp_status IN('1','2','9') AND zp_id_fk='".$_SESSION['location']['district_id']."'
						");


$code_data = $db->fetch_table("
								SELECT designation_id, designation_name
								FROM zpemp_emp_desig_master;
								
								");

function fun_common($tcode, $code)
{
	foreach ($code as $key) 
	{
		if($key['designation_id'] == $tcode)
		{
			return $key['designation_name'];
		}
	}
}




?>
<script>
$(document).ready(function(e) {

	$('.btn-group').show();
	
	$('.start_salary').click(function(e) 
	{
		var link1=$(this).val();
		var arr=link1.split('&');
		
		$('#confirm_start').modal('show');
		
		$('#start_emp_id').val(arr[0]);
		$('#start_gp_id').val(arr[1]);
		$('#decoded_start_emp_id').val(arr[2]);
		
		/*	$.post('<?= $config['base_url'] ?>page/intra_ps/eo/stop_start_suspend_submit.php?id='+arr[0]+'&flag=start'+'&ps_id_fk='+arr[1], function(data){
		
		if(data=='<div class="alert alert-success" style="text-align:center"><strong>Started Salary successfully!!.</strong></div>')
		{
		$('#msg_start').html(data);
		$('#suspend_sal'+arr[2]).removeAttr('disabled','disabled');
		$('#start_sal'+arr[2]).attr('disabled','disabled');
		$('#stop_sal'+arr[2]).removeAttr('disabled','disabled');
		
		}
		if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Start Salary error!!.</strong></div>')
		{
		$('#msg_start').html(data);
		$('#suspend_sal'+arr[2]).removeAttr('disabled','disabled');
		$('#start_sal'+arr[2]).attr('disabled','disabled');
		$('#stop_sal'+arr[2]).removeAttr('disabled','disabled');
		
		}
		});*/
	});
	
	$('.stop_salary').click(function(e) 
	{
		var emp_id=$(this).val();
		$.post('<?= $config['base_url'] ?>page/intra_zp/secretary/ajax_zp_reason_stop_sal.php?id='+emp_id, function(data){
		//alert(data);
		$('#myModal').modal('toggle');
		$('#stop').modal('toggle');
		$('#mbody_stop_sal').html(data);
		});	
	});
	
	$('.suspend_salary').click(function(e) 
	{
		var link1=$(this).val();
		$.post('<?= $config['base_url'] ?>page/intra_ps/eo/ajax_reason_suspend_sal.php?id='+link1, function(data){
		$('#myModal1').modal('toggle');
		$('#suspend').modal('toggle');
		$('#mbody_stop_sal1').html(data);
		
		$( "#suspention_effect_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-60:+100",
		dateFormat: 'dd-mm-yy' 
		});
		$( "#suspention_withdrawn_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "-60:+100",
		dateFormat: 'dd-mm-yy'
		});
		});
	});
	
	
	
	$('#confirm_delete').click(function(e) 
	{
		var link1=$('#delete_id').val();
		var arr=link1.split('&');
		$('#confirm_delete_modal').modal('toggle');
		$.post('<?= $config['base_url'] ?>page/intra_zp/secretary/zp_stop_start_suspend_submit.php?id='+arr[0]+'&flag=delete'+'&ps_id_fk='+arr[1], function(data)
		{
			if(data=='<div class="alert alert-success" style="text-align:center"><strong>Salary has been Deleted successfully.</strong></div>')
			{
				$('#msg_start').html(data);
				$('#delete_sal'+arr[2]).attr('disabled','disabled');
			}
			if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Salary Delete fails.</strong></div>')
			{
				$('#msg_start').html(data);
			}
		
		});
	});

});


function confirm_start_emp()
{
	var start_emp_id=$('#start_emp_id').val();
	var start_gp_id=$('#start_gp_id').val();
	var decode_start_emp=$('#decoded_start_emp_id').val();
	
	$.post('<?= $config['base_url'] ?>page/intra_zp/secretary/zp_stop_start_suspend_submit.php?id='+start_emp_id+'&flag=start'+'&zp_id_fk='+start_gp_id, function(data){
		if(data=='<div class="alert alert-success" style="text-align:center"><strong>Started Salary successfully!!.</strong></div>')
		{
			$('#confirm_start').modal('hide');
			$('#msg_start').html(data);
			$('#suspend_sal'+decode_start_emp).removeAttr('disabled','disabled');
			$('#start_sal'+decode_start_emp).attr('disabled','disabled');
			$('#stop_sal'+decode_start_emp).removeAttr('disabled','disabled');
		
		}
		if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Start Salary error!!.</strong></div>')
		{
			$('#confirm_start').modal('hide');
			$('#msg_start').html(data);
			$('#suspend_sal'+decode_start_emp).removeAttr('disabled','disabled');
			$('#start_sal'+decode_start_emp).attr('disabled','disabled');
			$('#stop_sal'+decode_start_emp).removeAttr('disabled','disabled');
		}
	});
}


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


<div class="content">
	<? require '../../../page/common_back_btns.php'; ?>
    
    <div class="welcome_msg">
    <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
    <?php
    if(isset($_SESSION['location']['gp_name'])) {
    echo $_SESSION['location']['gp_name'].", ";
    }elseif(isset($_SESSION['location']['block_name'])) {
    echo $_SESSION['location']['block_name'].", ";
    }elseif(isset($_SESSION['location']['ps_name'])) {
    echo $_SESSION['location']['ps_name'].", ";
    }elseif(isset($_SESSION['location']['district_name'])) {
    echo $_SESSION['location']['district_name'].", ";
    } elseif(isset($_SESSION['location']['state_name'])) {
    echo $_SESSION['location']['state_name'].", ";
    } ?></h2><h3>
    <?php   
    echo $_SESSION['location']['district_name'].", ".$_SESSION['location']['state_name'];
    
    ?></h3>
    </div>
    
    <div class="row" id="cont">
        <div class="content">
            <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
                <div class="col-sm-12">
                    <h1 class="heading"> ZP EMPLOYEE LIST</h1>
                    <div class="border"></div>
                    </br>
                    </br>
                    <div id="msg_start">
                    
                    </div>
                	<div class="msg"></div>
                    <div class="emplist">
                        <div class="school">
                            <div class="table-responsive">
                                <table width="100%">
                                    <tr>
                                        <th>Serial No.</th>
                                        <th>Employee Name</th>
                                        <th>Designation</th>
                                        <th>Employee Code</th>
                                        <th style=" width:24%">Action</th>
                                    </tr>
                                    <? $cnt=1; 
                                    if(count($arr))
                                    {
                                        foreach($arr as $item)
                                        {
											$arr_transfer=$db->fetch_table("select transfer_id_pk from prd_employee_transfer where transfer_emp_status='0' AND zp_id_fk='".$_SESSION['location']['district_id']."' and emp_id_fk='".$item['emp_id_pk']."'");
											?>
											<tr>
											<td><?= $cnt;?></td>
											<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
											<td><?= fun_common($item['emp_desig'],$code_data); ?></td>
											<td><?= $item['emp_id_const'] ?></td>
											<td>
											<div class="btn-group" role="group">
												<?php 
                                                if($item['emp_status']=='1' || count($arr_transfer)!='0') 
												{ ?>
                                                	<button type="button" class="btn btn-success start_salary" id="start_sal<?=$item['emp_id_pk']?>" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>" disabled="disabled">Start</button>
                                                <?php } 
												else 
												{ ?>
                                                	<button type="button" class="btn btn-success start_salary" id="start_sal<?=$item['emp_id_pk']?>" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>">Start</button>
                                                <?php } 
                                                if ($item['emp_status']=='2' || count($arr_transfer)!='0' ) 
												{ ?>
                                                	<button type="button" class="btn btn-danger stop_salary" id="stop_sal<?=$item['emp_id_pk']?>" data-toggle="modal"  value="<?=$cryptoGraph->encode($item['emp_id_pk'],4)?>" disabled="disabled" style="display: block;">Stop</button>
                                                <?php } 
												else 
												{?>
                                                	<button type="button" class="btn btn-danger stop_salary" id="stop_sal<?=$item['emp_id_pk']?>" data-toggle="modal"  value="<?=$cryptoGraph->encode($item['emp_id_pk'],4)?>" style="display: block;" >Stop</button>
                                                <?php } 
                                                if ($item['emp_status']=='9'  || count($arr_transfer)!='0') 
												{?>
                                                	<button type="button" class="btn btn-info suspend_salary" id="suspend_sal<?=$item['emp_id_pk']?>" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>" disabled="disabled">Suspend</button>
                                                <?php } 
												else 
												{ ?>
                                                	<button type="button" class="btn btn-info suspend_salary" id="suspend_sal<?=$item['emp_id_pk']?>" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['zp_id_fk'],4).'&'.$item['emp_id_pk']?>">Suspend</button>
                                                <?php } 
                                                ?>
											</div>
											</td>
											</tr>
											<? $cnt+=1; 
                                        }
                                    } 
                                    else 
                                    { ?>
                                        <tr>
                                            <td colspan="5" style="color:red;font-weight:bold">No Data Found</td>
                                        </tr>
                                    <? } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>

<? require '../../../page/layout/footer.php'; ?>
<!-----------------------------------------------------------------MODAL Start------------------------------------------------------>
<style>
.modal-backdrop fade in{
height:auto 0;
}
</style>

<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="min-height:1000px;">
<div class="modal-dialog modal-lg" style="height:">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Reason for STOP Salary</h4>
</div>
<div class="modal-body"> 
<div id="mbody_stop_sal"> 
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-warning" data-dismiss="modal" data-target="#close" data-toggle="modal">Close</button>   
</div>
</div>
</div>
</div>


<div class="modal fade bs-example-modal-sm" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="min-height:1000px;">
<div class="modal-dialog modal-lg" style="height:">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Reason for Suspend Salary</h4>
</div>
<div class="modal-body"> 
<div id="mbody_stop_sal1"> 
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-warning" data-dismiss="modal" data-target="#close" data-toggle="modal">Close</button>   
</div>
</div>
</div>
</div>



<div class="modal fade bs-example-modal-sm" id="confirm_delete_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
</div>
<div class="modal-body"> 
<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Delete This Employee's Salary For This Month ?</strong></p>
<input type="hidden" name="delete_id" id="delete_id" />
</div>
<div class="modal-footer">
<div class="btn-group">
<!--<a class="btn btn-success" href="<?php echo $config['base_url'] ?>page/intra_vtc/nodal_office/employee_sent_for_unlock.php?action=approval">YES</a>-->
<input type="submit" name="submit" value="YES" class="btn btn-success" id="confirm_delete"/>
<button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
</div>
</div>
</div>
</div>
</div>



<div class="modal fade bs-example-modal-sm" id="confirm_start" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
</div>
<div class="modal-body"> 
<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Start The Salary This Employee?</strong></p>
</div>
<div class="modal-footer">
<div class="btn-group">
<input type="hidden" name="start_emp_id" id="start_emp_id">
<input type="hidden" name="start_gp_id" id="start_gp_id">
<input type="hidden" name="decoded_start_emp_id" id="decoded_start_emp_id">
<button name="start_emp" id="start_emp" onClick="confirm_start_emp();" class="btn btn-success">YES </button>
<button type="button" class="btn btn-warning" data-dismiss="modal">NO</button> 
</form>     
</div>
</div>
</div>
</div>
</div>


<!-----------------------------------------------------------------MODAL END---------------------------------------------------->