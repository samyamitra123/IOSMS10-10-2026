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
require '../../all_function/fun_store/zp_ps_gp_function.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//error_reporting(0);
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
::before, ::after {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

</style>

<?php
/*echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/
//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

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

			
		
		});
		
		
		
    </script>
<?

$db=new database();
$crypto = new cryptography();
$fun_store=new zp_ps_gp_class();
$stake_level= $_SESSION['user_info']['stake_abbr'];  
if($stake_level=="EO")
{
	 $id="emp.ps_id_fk='".$_SESSION['location']['ps_id']."'";
	 $user='ps';
}
else if($stake_level=="BDO")
{
	 $id="emp.gp_id_fk='".$crypto->decode($_REQUEST['id'],4)."'";
	 $gp_id=$_REQUEST['id'];
	 $user='gp';
}
else if($stake_level=="ACCOUNTANT")
{
	
	 $id="emp.zp_id_fk='".$_SESSION['location']['district_id']."'"; 
	 $user='zp';
}
else if($stake_level=="FC&CAO")
{
	
	 $id="emp.zp_id_fk='".$_SESSION['location']['district_id']."'"; 
	 $user='zp';
}


	 if($stake_level=="FC&CAO")
{
	$Query = "select emp_first_name,emp_status,emp_desig,emp_second_name,emp.gp_id_fk, emp_id_pk ,emp_last_name,emp_form_status,
							emp_id_pk,emp_desig, emp_status,emp_id_const,update_status from prd_employee_master as emp 
							INNER JOIN ropa_2019_emp_pay_scale_master as scale 
							ON emp.emp_id_pk = scale.emp_id_fk WHERE  
							emp.emp_status in('1','9')  AND ".$id."  
							AND emp.emp_desig not in('9012','9013','9014','9015','9016','9017','1120','1124','1') 
							AND scale.status IN('2','3')
							
							AND scale.sent_status IN('1')
							
							order by emp.emp_first_name";	
	//print_r($Query); exit;					
 	$arr=$db->fetch_table($Query);
							
}

 if($stake_level=="ACCOUNTANT")
{
							
 	$arr=$db->fetch_table("select emp_first_name,emp_status,emp_desig,emp_second_name,emp.gp_id_fk, emp_id_pk ,emp_last_name,emp_form_status,
							emp_id_pk,emp_desig, emp_status,emp_id_const,update_status from prd_employee_master as emp 
							INNER JOIN ropa_2019_emp_pay_scale_master as scale 
							ON emp.emp_id_pk = scale.emp_id_fk WHERE  
							emp.emp_status in('1','9')  AND ".$id."  
							AND emp.emp_desig not in('9012','9013','9014','9015','9016','9017','1120','1124','1') 
							AND scale.status IN('2','3','4')
							AND scale.sent_status IN('0','1','3')
							order by emp.emp_first_name");
							
}
		
							
$code_data = $db->fetch_table("
SELECT code, description
FROM prd_dise_code_master;

");
$desig_data = $db->fetch_table("
SELECT designation_id, designation_name
FROM zpemp_emp_desig_master");

function fun_common($tcode, $code){
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}

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
        <div class="col-sm-12">
<h1 class="heading"> EMPLOYEE PAY FIXATION FOR ROPA 2019</h1>
<div class="border"></div>
</br>
<div id="msg_session">
<?php 
/*if($msg){
echo "<br/>";
echo $msg;
echo "<br/>";
}*/
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
<th> Employee Name</th>
<th>Employee ID</th>
<th>Designation</th>
<th>Status</th>
<th >Action</th>
<?php if($stake_level=="FC&CAO"){?>
<th >PDF</th>
<?php }?>
</tr>
</thead>





<?php $cnt=1; if(count($arr)){ foreach($arr as $item){ 

$arr1=$db->fetch_table("select status,sent_status,zp_id_fk from ropa_2019_emp_pay_scale_master where emp_id_fk= '".$item['emp_id_pk']."'");
?>
<?

if($arr1[0]['status']=='')
{
	$status='<span style="color:#660066;font-weight:bold">PAY FIXATION SUBMISSION PENDING</span>';
}
else if($arr1[0]['status']=='1' )
{
	$status='<span style="color:#660066;font-weight:bold">PAY FIXATION SUBMITED</span>';
}
else if($arr1[0]['status']=='2'  &&($stake_level=='EO' || $stake_level=='BDO'))
{
	$status='<span style="color:#ec971f;font-weight:bold">PAY FIXATION FINALIZED</span>';
}

else if($arr1[0]['status']=='2' && $arr1[0]['sent_status']=='0' && $stake_level=='ACCOUNTANT')
{
	$status='<span style="color:#660066;font-weight:bold"> FORWARDED BY DEALING ASSISTANT(ACCOUNTANTS)</span>';
}
else if($arr1[0]['status']=='2' && $arr1[0]['sent_status']=='1' && $stake_level=='ACCOUNTANT')
{
	$status='<span style="color:#660066;font-weight:bold"> FORWARDED TO FC&CAO </span>';
}
else if($arr1[0]['status']=='2' && $arr1[0]['sent_status']=='1' && $stake_level=='FC&CAO')
{
	$status='<span style="color:#660066;font-weight:bold"> FORWARDED BY ACCOUNTANT</span>';
}

else if($arr1[0]['status']=='3' )
{
	$status='<span style="color:#449d44;font-weight:bold">PAY FIXATION APPROVED</span>';
}
else if($arr1[0]['status']=='4' && $arr1[0]['sent_status']=='3')
{
	$status='<span style="color:#c9302c;font-weight:bold">PAY FIXATION REJECTED BY FC&CAO</span>';
}
else if($arr1[0]['status']=='4' && $arr1[0]['sent_status']=='2')
{
	$status='<span style="color:#c9302c;font-weight:bold">PAY FIXATION REJECTED BY ACCOUNTANT </span>';
}
		

?>
<input type="hidden" id="user" name="user" value="<?php echo $stake_level;?>" />
<tr>
<td><?= $cnt; ?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= $item['emp_id_const']?></td>
<td><?php if($stake_level=='ACCOUNTANT' || $stake_level=='FC&CAO')
{
	echo $fun_store->fun_desig($item['emp_desig'],$desig_data);
}
else
{
	echo fun_common($item['emp_desig'],$code_data); 
}
?>
</td>

<td><?php echo $status; ?></td>


<td class="edit">
	<a data-bs-toggle="modal" data-bs-target="#edit_data" href="" id="<?php echo $crypto->encode($item['emp_id_pk'],4) ?>" statuss="<?php echo $item['emp_status'];?>">
<img width="25"  src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  /></a>
</td>


<?php if($arr1[0]['status']=='3' && $stake_level=="FC&CAO" )
{?>
<td class="downpdf" style="margin-left:89%"><a href="<?= $config['base_url']?>page/all_moduls/employee_pay_commission/pdf_employe_ropa_2019_details.php?id=<?php echo $crypto->encode($item['emp_id_pk'],4) ?>"><i class="fa fa-download" aria-hidden="true"></i></a></td>

<?php } 


else if($stake_level!="ACCOUNTANT") {?>
<td class="downpdf" style="margin-left:89%" disabled ><a href="" ><i class="fa fa-download" aria-hidden="true"></i></a></td>

<?php } ?>

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
		"aLengthMenu": [[ 5, -1], [5, "All"]],
        "iDisplayLength": 5,
		"bFilter": true,
	  	"bSort": true,
		"bPaginate": true,
		"bInfo": false,
		"bLengthChange": true
	});

	
	
  });
  

</script>
<script>

	
	function send_action(k,emp_id_fk)
 { 
 
	 //var emp_id='<?php echo $crypto->encode(emp_id_fk,4) ?>';
	 var emp_id=emp_id_fk;
	 
	if(k=='approve')
	{
		//alert(11);
		var flag='<?php echo $crypto->encode('approve',4) ?>';
		$('#emp_id_k').val(emp_id);
		$('#flag').val(flag);
		$('#approve_show').show();
		$('#reject_show').hide();
	}
	else if(k=='reject')
	{
	var flag='<?php echo $crypto->encode('reject',4) ?>';
		$('#emp_id_k').val(emp_id);
		$('#approve_show').hide();
		$('#reject_show').show();
		$('#flag').val(flag);
	}
	else if(k=='forward')
	{
	var flag='<?php echo $crypto->encode('forward',4) ?>';
		$('#emp_id_k').val(emp_id);
		$('#forward_show').show();
		$('#approve_show').hide();
		$('#reject_show').hide();
		$('#flag').val(flag);
	}
	$('#approve_reject').modal('show');
 }

function view_ps_modal(emp_id_pk)
{	
$('#bdo_modal').modal('show');
$('#emp_id_pk').val(emp_id_pk);
$('#status').val(1);
$("#reason").show();
$("#reason_lbl").show();
	  
}

/*function show_confirmation(k)
{
	//alert(11);
	//return false;
	$('#sent').modal('show');
	$('#emp_id').val(k);
}*/

function show_reason(k)
{
	var shw_rsn_id=$('#rsn_view'+k).val();
	//alert(shw_rsn_id);
	$('#rsn_bdy').html(shw_rsn_id);
}

$(".edit a").click(function() 
	{	
	
	
	    var user1=$('#user').val();	
		$("#mbody").html('<img style=" height:50px; width:50px; margin-left:50%;"  src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" />');
		$( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		$("#wait").css("display","none");
		$("#dialog-confirm").css("display", "none");
		$("#saving").css("display", "none");
		//var link = $(this).attr('href');
		var link = $(this).attr('id');
		//console.log(link);
		if(user1=='FC&CAO')
		{
		$.post('<?= $config['base_url'] ?>page/all_moduls/employee_pay_commission/up_ajax_pay_commission_employee_edit.php?id='+link , function(data){
			//console.log(data);
			$("#mbody").html(data);
		});
		}
		else if(user1=='ACCOUNTANT')
		{
		$.post('<?= $config['base_url'] ?>page/all_moduls/employee_pay_commission/up_ajax_pay_commission_employee_edit_ll.php?id='+link , function(data){
			$("#mbody").html(data);
		});
		}
	});





</script>


<div class="modal bs-example-modal-lg" id="edit_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<!--<div class="modal-backdrop fade in" style="height:2100px;; opacity: 0.3;"></div>-->
  <div class="modal-dialog modal-lg" >
  
    <div class="modal-content" style="width: 850px; margin-left: -1.5%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
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

<form name="unlock_salary" id="unlock_salary" action="up_pay_commission_approve_reject.php" method="post">
    <div class="modal fade bs-example-modal-sm" id="approve_reject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To <span id="approve_show" style="display:none;"> Approve </span> <span id="forward_show" style="display:none;"> Forward </span><span id="reject_show" style="display:none;">Reject </span></strong><strong> Pay Fixation Profile?</strong></p>
                    <input type="hidden" id="flag" name="flag"/>
                    <input type="hidden" id="emp_id_k" name="emp_id_k"/>
                    
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
                        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>





