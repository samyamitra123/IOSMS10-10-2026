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

			
		
		});
		
		
		
    </script>
<?

$db=new database();
$crypto = new cryptography();
$fun_store=new zp_ps_gp_class();
$stake_level= $_SESSION['user_info']['stake_abbr']; 
if($stake_level=="EO")
{
	 $id="ps_id_fk='".$_SESSION['location']['ps_id']."'";
	 $user='ps';
	 $gp_id=$_SESSION['location']['ps_id'];
}
else if($stake_level=="BDO")
{
	 $id="gp_id_fk='".$crypto->decode($_REQUEST['id'],4)."'";
	 $gp_id=$_REQUEST['id'];
	 $user='gp';
}
else if($stake_level=="DEALING ASSISTANT (Account)")
{
	 $id="zp_id_fk='".$_SESSION['location']['district_id']."'";
	 $user='zp';
}


	$arr=$db->fetch_table("select emp_first_name,emp_status,emp_desig,emp_second_name,gp_id_fk, emp_id_pk ,emp_last_name,emp_form_status,emp_id_pk,emp_desig,
							emp_status,emp_id_const,update_status from prd_employee_master where emp_status in('1','9')  AND ".$id."  AND emp_desig not in('9012','9013','9014','9015','9016','9017','1120','1124','1')
							order by emp_first_name");
							
	
							
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
<div class="col-sm-12" style="width:98%">
<h1 class="heading"> EMPLOYEE PAY FIXATION FOR ROPA 2019</h1>
<div class="border"></div>
</br>
<div id="msg_session">
<?php 

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
<th>Action</th>
<th>PDF</th>

</tr>
</thead>





<?php $cnt=1; if(count($arr)){ foreach($arr as $item){ 

$arr1=$db->fetch_table("select gp_id_fk,ps_id_fk,status,sent_status from ropa_2019_emp_pay_scale_master where emp_id_fk= '".$item['emp_id_pk']."'");
?>
<?


if($arr1[0]['status']=='' ||$arr1[0]['status']=='0')
{
	$status='<span style="color:#660066;font-weight:bold">PAY FIXATION SUBMISSION PENDING</span>';
}
else if($arr1[0]['status']=='1' )
{
	$status='<span style="color:#660066;font-weight:bold">PAY FIXATION SUBMISSION SUBMITED</span>';
}
else if($arr1[0]['status']=='2'  &&($stake_level=='EO' || $stake_level=='BDO'))
{
	$status='<span style="color:#ec971f;font-weight:bold">PAY FIXATION FINALIZED</span>';
}

else if($arr1[0]['status']=='2' &&($stake_level=="DEALING ASSISTANT (Account)" &&  $arr1[0]['sent_status']=='0'))
{
	$status='<span style="color:#660066;font-weight:bold">FORWARDED TO ACCOUNTANT</span>';
}
else if($arr1[0]['status']=='2' &&($stake_level=="DEALING ASSISTANT (Account)" &&  $arr1[0]['sent_status']=='1'))
{
	$status='<span style="color:#660066;font-weight:bold">FORWARDED TO FC&CAO</span>';
}
else if($arr1[0]['status']=='4' &&($stake_level=="DEALING ASSISTANT (Account)" &&  $arr1[0]['sent_status']=='2'))
{
	$status='<span style="color:#c9302c;font-weight:bold">PAY FIXATION REJECTED BY ACCOUNTANT</span>';
}
else if($arr1[0]['status']=='4' &&($stake_level=="DEALING ASSISTANT (Account)" &&  $arr1[0]['sent_status']=='3'))
{
	$status='<span style="color:#c9302c;font-weight:bold">PAY FIXATION REJECTED BY FC&CAO</span>';
}


else if($arr1[0]['status']=='3' )
{
	$status='<span style="color:#449d44;font-weight:bold">PAY FIXATION APPROVED</span>';
}
else if($arr1[0]['status']=='4')
{
	$status='<span style="color:#c9302c;font-weight:bold">PAY FIXATION REJECTED</span>';
}
		

?>

<tr>
<td><?= $cnt; ?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= $item['emp_id_const']?></td>
<td><?php if($stake_level=="DEALING ASSISTANT (Account)")
{
	
	echo $fun_store->fun_desig($item['emp_desig'],$desig_data);
}
else
{
	
	echo fun_common($item['emp_desig'],$code_data); 
}
?>
</td>
<td id="a_<?php echo $item['emp_id_pk'];?>"><?php echo $status; ?></td>


<td class="edit"><a data-bs-toggle="modal" data-bs-target="#edit_data" href="" id="<?php echo $crypto->encode($item['emp_id_pk'],4) ?>" statuss="<?php echo $item['emp_status'];?>">
<img width="25"  src="<?= $config['base_url'];?>themes/default/image/edit_icon.png" alt="Edit"  /></a>


</td>
<?php if($arr1[0]['status']=='3'  &&( $stake_level=="EO" || $stake_level=="BDO" ) )
{?>
<td class="downpdf" style="margin-left:89%"><a href="<?= $config['base_url']?>page/all_moduls/employee_pay_commission/pdf_employe_ropa_2019_details.php?id=<?php echo $crypto->encode($item['emp_id_pk'],4) ?>"><i class="fa fa-download" aria-hidden="true"></i></a></td>

<?php } 
else {?>
<td class="downpdf" style="margin-left:89% opacity:0.5"   ><i class="fa fa-download" aria-hidden="true"></i></a></td>

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


//function view_ps_modal(emp_id_pk)
//{
//	
//// alert(emp_id_pk);	
//$('#bdo_modal').modal('show');
//$('#emp_id_pk').val(emp_id_pk);
//$('#status').val(1);
//$("#reason").show();
//$("#reason_lbl").show();
//	  
//}

function show_confirmation(k)
{
	//alert(11);
	//return false;
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
	$("#mbody").html('<img style=" height:50px; width:50px; margin-left:50%;"  src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" />');
	
		$( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		$("#wait").css("display","none");
		$("#dialog-confirm").css("display", "none");
		$("#saving").css("display", "none");
		
		var link = $(this).attr('id');
		var statuss = $(this).attr('statuss');
		
		//alert(statuss);
		
		/*if(statuss=='9')
		{
			alert("EMPLOYEE HAS BEEN SUSPENDED");
			return false;
		}*/
		/*else{*/
		//return false;
			$.post('<?= $config['base_url'] ?>page/all_moduls/employee_pay_commission/ajax_pay_commission_employee_edit.php?id='+link , function(data){
			//$("#loader_id").html('<img style="margin-left: 39%;" src="<?php echo $config['base_url'] ?>themes/default/image/unlock_load.gif" />');
			
			$("#mbody").html(data);
			
		});
		/*}*/
	});
	


</script>

<script>

function send_action(k, emp_id_fk)
 { 

	// var emp_id_fk= '<?php echo $crypto->encode(emp_id_fk,4) ?>';
	 
	if(k=='approve')
	{
		//alert(emp_id_fk);
		var flag='<?php echo $crypto->encode('approve',4) ?>';
		var stack='<?php echo $gp_id ?>';
		$('#emp_id_k').val(emp_id_fk);
		$('#flag').val(flag);
		$('#stack').val(stack);
		$('#approve_show').show();
		$('#reject_show').hide();
	}
	else if(k=='reject')
	{
		var flag='<?php echo $crypto->encode('reject',4) ?>';
		//var stack='<?php echo $crypto->encode($user,4) ?>';
		$('#emp_id_k').val(emp_id_fk);
		$('#approve_show').hide();
		$('#reject_show').show();
		$('#flag').val(flag);
	}
	
	$('#approve_reject').modal('show');
 }
 
 
 
  function send_action1(k, emp_id_fk)
 { 

	
	 
	if(k=='unlock')
	{
		//alert(emp_id_fk);
		var status='<?php echo $crypto->encode('unlock',4) ?>';
		var stack_gp='<?php echo $gp_id ?>';
		$('#e_id').val(emp_id_fk);
		$('#status').val(status);
		$('#stack_gp').val(stack_gp);
		
	}
	
	
	$('#unlock1').modal('show');
 }
 
 
</script>


<form method="post" action="emp_pay_commission_profile_send.php" > 
<div class="modal bs-example-modal-sm" id="sent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Employee Send</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
		  <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Send This Profile to Accountant[Accountant] for Approval?</strong></p>
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






<div class="modal bs-example-modal-lg" id="edit_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 850px; margin-left: -1.5%;">
      <div class="modal-header">
       <div ><center><h1 style="color:#932203;" >ROPA 2019 PAY FIXATION</h1></center></div>
		<h4 class="modal-title" id="myModalLabel"></h4>
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

<form name="unlock_salary" id="unlock_salary" action="pay_commission_approve_reject.php" method="post">
    <div class="modal bs-example-modal-sm" id="approve_reject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
                    <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To <span id="approve_show" style="display:none;"> Approve </span> <span id="forward_show" style="display:none;"> Forward </span><span id="reject_show" style="display:none;">Reject </span></strong><strong> Pay Fixation Profile?</strong></p>
                    <input type="hidden" id="flag" name="flag"/>
                    <input type="hidden" id="emp_id_k" name="emp_id_k"/>
                     <input type="hidden" id="stack" name="stack"/>
                    
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>




<form name="unlock_salary1" id="unlock_salary1" action="pay_commission_unlock.php" method="post">
    <div class="modal bs-example-modal-sm" id="unlock1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-modal-parent="#schprfModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
                <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body"> 
                    		  <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Unlock this Pay Fixation Profile?</strong></p>

                    <input type="hidden" id="status" name="status"/>
                    <input type="hidden" id="e_id" name="e_id"/>
                     <input type="hidden" id="stack_gp" name="stack_gp"/>
                    
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <input type="submit" name="submit" value="YES" class="btn btn-success finalize" />
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal bs-example-modal-sm" id="sent_revoke" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
		 <h4 class="modal-title" id="myModalLabel">Employee Revoke</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
      </div>
      <div class="modal-body"> 
		  <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are You Sure To Revoke?</strong></p>
      </div>
      <div class="modal-footer">
		  <div class="btn-group">
			<input type="submit" name="sendtorevoke" id="sendtorevoke" value="YES" class="btn btn-success finalize" onClick="revoke();" >
			<button class="btn btn-warning" data-bs-dismiss="modal">NO</button>      
		  </div>
      </div>
    </div>
  </div>
</div>