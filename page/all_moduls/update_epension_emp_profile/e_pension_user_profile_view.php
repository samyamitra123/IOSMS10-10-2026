<?php

session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
  
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>User Profile Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}?>
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
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Submission Fails...</strong></div>';
}
/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/
//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
//require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
//require '../../../page/layout/menu.php';

//-----------------------------Business Logic----------------------------------------------------------------------------------
?>

<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".table:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  
		  $("#dialog").dialog({
				autoOpen : false,
				modal: true,
				width:900,
				opacity: 1,
				height:580,
				//resize: "auto",

				show : {
					effect : "fade",
					duration : 500
				},
				hide : {
					effect : "fade",
					duration : 500
				},
			});

			/*$("#sendtoeo").click(function() {
				var link = $('#emp_id_ps').val();
					$('#sent_eo').modal('hide');
				$(document).ajaxStart(function(){
				    $("#wait").css("display","block");
				  });
				$(document).ajaxComplete(function(){
				    $("#wait").css("display","none");
				    
				});
			    $(".emplist").load('<?= $config['base_url'] ?>page/intra_ps/da/ps_emp/ajax_employee_edit_details.php?id='+link, function(responseTxt,statusTxt,xhr){
					//alert(responseTxt); 
				  if(statusTxt=="error"){
			        $("#error_msg").css("display","block");
			      } else {
			      	$("#error_msg").css("display","none");
			      }
			  	});
				
				return false;
			});*/
		
		});
		
		
		
    </script>
<?

$db=new database();
$crypto = new cryptography();
//print_r($_SESSION); die;
//$emp_id_pk=$cryptoGraph->decode($_GET['emp_id'],4);
   $stake_level=$crypto->decode($_GET['stake_level'],4); 
  $check_stake=$_SESSION['user_info']['stake_level']; 
  $id=$crypto->decode($_GET['id'],4);
if($stake_level==37)
{
	 $id="ps_id_fk='".$_SESSION['location']['ps_id']."'";
	 $user='ps';
}
else if($stake_level==64)
{
	 $id="gp_id_fk='".$_SESSION['location']['gp_id']."'";
	 $user='gp';
}
else if($stake_level==52)
{
	 $id="zp_id_fk='".$_SESSION['location']['district_id']."'";
	 $user='zp';
}

/*$emp_status_check=$db->fetch_table("select emp_status,emp_id_pk,emp_id_const from prd_employee_master where emp_status in('6') and emp_id_pk='".$emp_id_pk."' AND ps_id_fk='".$_SESSION['location']['ps_id']."' order by emp_first_name");*/
	$arr=$db->fetch_table("select emp_first_name,emp_status,emp_second_name,emp_last_name,emp_form_status,emp_id_pk,emp_desig,emp_status,emp_id_const,update_status from prd_employee_master where emp_status in('1','9')  AND ".$id." order by emp_first_name");

?>

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
        <div class="col-sm-12" style="width:98%;">
<h1 class="heading">INSERT ADDITIONAL DATA IN EMPLOYEE PROFILE</h1>
<div class="border"></div>
</br>
<div id="msg_session">
<?php 
if($msg){
echo "<br/>";
echo $msg;
echo "<br/>";
}
if(isset($_SESSION['msg'])){
	echo "<br/>";
	echo $_SESSION['msg'];
	echo "<br/>";
	unset($_SESSION['msg']);
	             
}

?>
</div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%" id="example">
<thead>
<tr>
<th>Serial No.</th>
<th>Name</th>
<th>Employee ID</th>
<th>Status</th>
<th>Edit</th>
<?php if($user=='ps')
{?>
<th>Send To EO</th>
<? }else if($user=='gp')
{?>
	<th>Send To BLOCK</th>
<? }else if($user=='zp'){?>
<th>Send To Secretary</th>
<?php }?>
</tr>
</thead>





<?php $cnt=1; if(count($arr)){ foreach($arr as $item){ ?>

<?
if($item['update_status']=='0')
{
	$status='<span style="color:#660066;font-weight:bold">PROFILE SUBMISSION PENDING</span>';
}
if($item['update_status']=='1')
{
	$status='<span style="color:#660066;font-weight:bold">PROFILE SUBMITED</span>';
}

else if($item['update_status']=='2')
{
   $status='<span style="color:#127C89;font-weight:bold">PROFILE SENT</span>';
}
else if($item['update_status']=='3')
{
   $status='<span style="color:#127C89;font-weight:bold">PROFILE APPROVED</span>';
}
else if($item['update_status']=='4')
{
	$status='<span style="color:#660066;font-weight:bold">PROFILE REJECTED</span>';
}


?>

<tr>
<td><?= $cnt; ?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= $item['emp_id_const']?></td>


<td><?php echo $status; ?></td>
<?php /*?><?php if($item['update_status']=='0'){?>

<td class="edit"><a data-toggle="modal" data-target="#edit_data" href="" id="<?php echo $crypto->encode($item['emp_id_pk'],4) ?>">
<img width="25"  src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  /></a>
</td>
<?php }else{?>
<td class="edit"><img width="25" style="opacity:0.5;" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  /></a>
</td>
<?php }?>

<?php if($item['update_status']=='1'){?>
<td class="sendtoddo"><a id="<?php echo $crypto->encode($item['emp_id_pk'],4) ?>" onClick="show_confirmation(this.id);"><img width="25" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="Edit"/></a>
</td>
<?php }else{?>
<td class="sendtoddo"><img width="25" style="opacity:0.5;" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="Edit"/>
<?php }?><?php */?>


<?php if($item['update_status']=='0' || $item['update_status']=='4'){?>

<td class="edit"><a data-bs-toggle="modal" data-bs-target="#edit_data" href="" id="<?php echo $crypto->encode($item['emp_id_pk'],4) ?>">
<img width="25"  src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  /></a>
</td>
<?php }else{?>
<td class="edit"><img width="25" style="opacity:0.5;" src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  /></a>
</td>
<?php }?>

<?php if($item['update_status']=='1'){?>
<td class="sendtoddo"><a id="<?php echo $crypto->encode($item['emp_id_pk'],4) ?>" onClick="show_confirmation(this.id);"><img width="25" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="Edit"/></a>
</td>
<?php }else{?>
<td class="sendtoddo"><img width="25" style="opacity:0.5;" src="<?= $config['base_url'];?>themes/default/image/send.png" alt="Edit"/>
<?php }?>







</tr>
<?php $cnt++;} } else { ?>
<tr>
<td colspan="6" style="color:red;font-weight:bold">No Data Found</td>
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

<script type="text/javascript">
$(document).ready(function() 
{
	//alert(11);
	$("#example").DataTable({
		
		
		"aLengthMenu": [[2, 3, 5, -1], [2, 3,5, "All"]],
        "iDisplayLength": 3,
"bFilter": true,
	  	"bSort": true,
		"bPaginate": true,
		"bInfo": false,
		"bLengthChange": true
	});

	
	
  });
  

</script>
<script>
/*$(document).ready(function () {
	 $("#delete_id").click(function () {
		// alert(22);
		 $('#emp_id_pk').val("<?= ($item['emp_id_pk']);?>");
		 

						
	 });
});*/


function view_ps_modal(emp_id_pk)
{
	
// alert(emp_id_pk);	
$('#bdo_modal').modal('show');
$('#emp_id_pk').val(emp_id_pk);
$('#status').val(1);
$("#reason").show();
$("#reason_lbl").show();
	  
}

function show_confirmation(k)
{
	$('#sent').modal('show');
	$('#emp_id').val(k);
}

function show_reason(k)
{
	var shw_rsn_id=$('#rsn_view'+k).val();
	//alert(shw_rsn_id);
	$('#rsn_bdy').html(shw_rsn_id);
}

$(".edit a").click(function() 
	{	
		var tch=(<?=count($tch)?>);
		$( "tr:odd" ).css( "background-color", "#FFFFFF" );
		$( "tr:even" ).css( "background-color", "#DDF7FF" );
		$( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		$("#wait").css("display","none");
		$("#dialog-confirm").css("display", "none");
		$("#saving").css("display", "none");
		//var link = $(this).attr('href');
		var link = $(this).attr('id');
		
		$.post('<?= $config['base_url'] ?>page/all_moduls/update_epension_emp_profile/ajax_employee_edit.php?id='+link, function(data){
			$("#mbody").html(data);
		});
	});

/*function redirect_page()
{
var a=($('#emp_id_pk').val());


		window.location.href=("emp_delete_profile_submit.php?id="+a);
		  
}
*/


</script>


<form method="post" action="emp_epension_profile_send.php" > 
<div class="modal fade bs-example-modal-sm" id="sent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
     <h4 class="modal-title" id="myModalLabel">Employee Send</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Send This Employee Profile to Executive Officer for Approval?</strong></p>
      <input type="hidden" id="emp_id" name="emp_id"/>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="sendtoeo" id="sendtoeo" value="YES" class="btn btn-success finalize" />
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
</form>
<div class="modal fade bs-example-modal-lg" id="edit_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 850px; margin-left: -1.5%;">
      <div class="modal-header">
	  <h4 class="modal-title" id="myModalLabel">Employee Profile Update</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
          <div id="mbody"> 
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>


<!---------------------------------------------MODAl------------------------------------->
<!--<div class="modal fade bs-example-modal-sm" id="unlock" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send This BDO Profile For UNLOCK ?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <a class="btn btn-success" href="<?php echo $config['base_url'] ?>page/intra_prd/block/unlock_bdo_profile.php">YES</a>
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button> 
     
      </div>
      </div>
    </div>
  </div>
</div>-->
