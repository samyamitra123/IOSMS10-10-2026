<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
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
if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success'){
		$msg='<div class="alert alert-success" style="text-align:center"><strong>Bank Details Updated Successfully...</strong></div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div class="alert alert-danger" style="text-align:center"><strong>Bank Details Updation Fails...</strong></div>';
	}	
}

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
		  $(".show a").click(function() {	
		  var link = $(this).attr('id');
		  $.post('<?= $config['base_url'] ?>page/intra_prd/block/ajax_bank_view.php?id='+link, function(data){
		  $(".empshow").html(data);
		  });
		  });
		  
		  $(".edit a").click(function() {	
		  var link1 = $(this).attr('id');
		  $.post('<?= $config['base_url'] ?>page/intra_prd/block/ajax_bank_edit.php?id='+link1, function(data){
		  $(".empedit").html(data);
		  });
		  });
		  
		  
		});
    </script>
<?
$db=new database();
$arr=$db->fetch_table("select emp_id_const, emp_first_name,emp_second_name,emp_last_name,emp_bank_name, emp_id_pk, emp_acc_no,emp_ifsc_no,emp_status,bank_upd_status from prd_employee_master where emp_status='1' AND bank_upd_status in('1','2','3') AND gp_id_fk='".$cryptoGraph->decode($_REQUEST['gp_id'],4)."' order by emp_id_const");

function fun_bank($val){
$db=new database();
$bnk=$db->fetch_table("select bank_name from prd_dise_bank_master where bank_code='".$val."'");
return $bnk[0]['bank_name'];
}
?>

<div class="content">
<? require '../../../page/common_back_btns.php'; ?>
   <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?>
                      <?php
					  if(isset($_SESSION['location']['gp_name'])) {
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h2><h3>
					<? echo $_SESSION['location']['block_name']. " ,".$_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
<div class="content">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">Update Bank Details</h1>
<div class="border"></div>
</br>
<div id="msg_session">
<?php 
if(!empty($msg)){
echo "<br/>";
echo $msg;
echo "<br/>";
}
if(isset($_SESSION['bank_msg'])){
	echo "<br/>";
	echo $_SESSION['bank_msg'];
	echo "<br/>";
	//$_SESSION['bank_msg']=NULL;
	//unset($_SESSION['bank_msg']);
}
?>
</div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Employee ID</th>
<th>Name</th>
<th>Bank Name</th>
<th>Account No.</th>
<th>IFSC Code</th>
<th>Status</th>
<th>View</th>
<th>Edit</th>
</tr>
<?php $cnt=1; if(count($arr)){ foreach($arr as $item){ ?>

<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_id_const'] ?> </td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= fun_bank($item['emp_bank_name']) ?> </td>
<td><?= $item['emp_acc_no'] ?> </td>
<td><?= $item['emp_ifsc_no'] ?> </td>
<?php if($item['bank_upd_status']=='0'){ ?>
                    <td style="color:#996600" align="center">Request Not Send</td>
                    <?php } 
					 else if($item['bank_upd_status']=='1'){ ?>
                    <td style="color:#660066" align="center">Request From GP</td>
                    <?php } 
					 else if($item['bank_upd_status']=='2'){ ?>
                    <td style="color:#009999" align="center">Request Sent To DPRDO</td>
                    <?php }
					else if($item['bank_upd_status']=='3'){ ?>
                    <td style="color:green" align="center">Approved By DPRDO</td>
                    <?php } 
                    else if($item['bank_upd_status']=='4'){ ?>
                    <td style="color:red" align="center">Request Rejected</td>
                    <?php } ?>
<td class="show">
<a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-toggle="modal" data-target="#view"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>
<td class="edit">
<? if($item['bank_upd_status']!='3'){ ?>
<a style="opacity:0.3"><i class="fa fa-pencil-square-o fa-2x"></i></a>
<? } else{ ?>
<a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-toggle="modal" data-target="#edit"><i class="fa fa-pencil-square-o fa-2x"></i></a>
<? } ?>
</td>
</tr>
<?php $cnt++;} } else { ?>
<tr>
<td colspan="9" style="color:red;font-weight:bold">No Data Found</td>
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


<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Bank Details</h4>
      </div>
      <div class="modal-body"> 
      <div class="empshow"> 
      </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Bank Details</h4>
      </div>
      <div class="modal-body"> 
      <div class="empedit"> 
      </div>
      </div>
    </div>
  </div>
</div>
