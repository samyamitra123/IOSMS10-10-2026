<?
session_start();
ob_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
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
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
}

$common['title'] = "Start or Stop Or Suspend Salary| PRD | Govt. of West Bengal ";


//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
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


$arr=$db->fetch_table("SELECT 
							distinct(emp.emp_first_name),
							emp.emp_second_name,
							emp.emp_last_name,
							emp.emp_desig,
							emp.emp_status,
							emp.emp_id_pk,
							emp.emp_system_code,
							emp.ps_id_fk,
							emp.emp_id_const,
							tran.transfer_emp_status,
							tran.lpc_status
							 
						FROM 
							prd_employee_master emp
						
						LEFT JOIN
							(SELECT emp_id_fk,ps_id_fk,transfer_emp_status,lpc_status FROM prd_employee_transfer WHERE transfer_emp_status in (0,1)) as tran
						ON
							emp.emp_id_pk=tran.emp_id_fk
						WHERE 
							emp.emp_status=1 AND emp.ps_id_fk='".$_SESSION['location']['ps_id']."'
						ORDER BY emp_first_name");


$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}




?>
<script>
$(document).ready(function(e) {

	$('.btn-group').show();
    $('.start_salary').click(function(e) {

		var link1=$(this).val();
		var arr=link1.split('&');
		$.post('<?= $config['base_url'] ?>page/intra_ps/eo/transfer/stop_start_submit.php?id='+arr[0]+'&flag=start'+'&ps_id_fk='+arr[1], function(data){
			
		  if(data=='<div class="alert alert-success" style="text-align:center"><strong>Employee has been revoked successfully!!.</strong></div>')
		  {
			  $('#msg_start').html(data);
			  $('#suspend_sal'+arr[2]).removeAttr('disabled','disabled');
			  $('#start_sal'+arr[2]).attr('disabled','disabled');
			  $('#stop_sal'+arr[2]).removeAttr('disabled','disabled');
			 
		  }
		  if(data=='<div class="alert alert-danger" style="text-align:center"><strong>Employee revoke error!!.</strong></div>')
		  {
			  $('#msg_start').html(data);
			  $('#suspend_sal'+arr[2]).removeAttr('disabled','disabled');
			  $('#start_sal'+arr[2]).attr('disabled','disabled');
			  $('#stop_sal'+arr[2]).removeAttr('disabled','disabled');
			 
		  }
		});
    });
	
	
	
	
	
	$('.stop_salary').click(function(e) {
		
		var emp_id=$(this).val();
	
					$.post('<?= $config['base_url'] ?>page/intra_ps/eo/transfer/ajax_reason_stop_sal.php?id='+emp_id, function(data){
						//alert(data);
					  $('#myModal').modal('toggle');
					  $('#stop').modal('toggle');
					  $('#mbody_stop_sal').html(data);
					});

		
    });
	
	
	
	

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
<div class="content">
<? require '../../../../page/common_back_btns.php'; ?>
   <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      }elseif(isset($_SESSION['location']['ps_name'])) {
                          echo $_SESSION['location']['ps_name'].", ";
                      }elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h2><h3>
			<? echo $_SESSION['location']['district_name'];
                     ?></h3>
       </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">EMPLOYEE LIST</h1>
<div class="border"></div>
</br>
</br>
<div id="msg_start">
<?php 
if($_SESSION['msg'])
{
	echo $_SESSION['msg'];
	unset($_SESSION['msg']);
}
?>
</div>
<div class="msg"></div>
<div class="emplist">
<div class="school">
<div class="table-responsive">

<div class="col-sm-12" align="right" id="report" ><a class="btn btn-info btn-sm" href="lpc_generation.php?ps_id=<?= $cryptoGraph->encode($_SESSION['location']['ps_id'],4)?>"><i class="fa fa-file-text"></i>&nbsp;  LPC GENERATE</a> </div>

<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee Name</th>
<th>Designation</th>
<th>Employee Code</th>
<th style=" width:24%">Action</th>
</tr>
<? $cnt=1; if(count($arr)){ foreach($arr as $item){
    
 $arr_transfer=$db->fetch_table("select transfer_emp_status,emp_first_name,emp_second_name,emp_last_name,emp_desig,emp_status,emp_id_fk,emp_system_code,gp_id_fk,emp_id_const,ps_id_fk from prd_employee_transfer where transfer_emp_status in('0') AND ps_id_fk='".$_SESSION['location']['ps_id']."' and emp_id_fk='".$item['emp_id_pk']."'");
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= fun_common($item['emp_desig'],$code_data); ?></td>
<td><?= $item['emp_id_const'] ?></td>
<td>
<div class="btn-group" role="group">
    <?php 
    if($item['emp_status']=='1' and $arr_transfer[0]['transfer_emp_status']=='0' ) { ?>
 <button type="button" class="btn btn-success start_salary" id="start_sal<?=$item['emp_id_pk']?>" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>">Revoke</button>
    <?php }else if($item['transfer_emp_status']=='1' ) { ?>
  <button type="button" class="btn btn-success start_salary" id="start_sal<?=$item['emp_id_pk']?>" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>" disabled="disabled">Revoke</button>
<?php } else { ?>
  <button type="button" class="btn btn-success start_salary" id="start_sal<?=$item['emp_id_pk']?>" value="<?=$cryptoGraph->encode($item['emp_id_pk'],4).'&'.$cryptoGraph->encode($item['ps_id_fk'],4).'&'.$item['emp_id_pk']?>" disabled="disabled">Revoke</button>
  <?php } 
  if ($item['emp_status']=='2' || $arr_transfer[0]['transfer_emp_status']=='0' ) { ?>
  <button type="button" class="btn btn-danger stop_salary" id="stop_sal<?=$item['emp_id_pk']?>" data-toggle="modal"  value="<?=$cryptoGraph->encode($item['emp_id_pk'],4)?>" disabled="disabled" style="display: block;">Transfer</button>
  <?php } else {?>
  <button type="button" class="btn btn-danger stop_salary" id="stop_sal<?=$item['emp_id_pk']?>" data-toggle="modal"  value="<?=$cryptoGraph->encode($item['emp_id_pk'],4)?>" style="display: block;" >Transfer</button>
  <?php } 
  
  ?>
</div>
</td>
</tr>
<? $cnt+=1; }} else { ?>
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
<? require '../../../../page/layout/footer.php'; ?>
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


<!-----------------------------------------------------------------MODAL END---------------------------------------------------->