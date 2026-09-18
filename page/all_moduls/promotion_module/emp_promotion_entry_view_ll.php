<?php

ob_start();
session_start();

require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';

 
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

if (
    !(($_SESSION['privilege']['03'] == TRUE) 
  || ($_SESSION['privilege']['0301'] == TRUE)
  || ($_SESSION['privilege']['0302'] == TRUE)
  || ($_SESSION['privilege']['0303'] == TRUE)
  || ($_SESSION['privilege']['0304'] == TRUE)
  || ($_SESSION['privilege']['0305'] == TRUE)
  || ($_SESSION['privilege']['0306'] == TRUE)
  || ($_SESSION['privilege']['04'] == TRUE)
  || ($_SESSION['privilege']['0401'] == TRUE)
  )
  ){
  header('Location: '. $config['base_url'] . "page/dashboard.php");
  exit;
}

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
 /*   Kalyan Ghohs   20/3/2017   Finish   */	
		
		
$cryptoGraph=new cryptography();		
			
//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "WBULBHRMS | Govt. of West Bengal ";

//Self variable




//---------------------------------- HEADER -----------------------------------------------------------------------------------
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------




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

if($cryptoGraph->decode($_GET['lock'],4) == 'sent'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>Employee Promotion Details Has Been Sent To BDO Successfully...</strong></div>';
}else if($cryptoGraph->decode($_GET['lock'],4) == 'failed'){
	$msg='<div class="alert alert-danger" style="text-align:center">Employee Promotion Details Has Not Been Sent To BDO. Please Try Again... </strong></div>';
}
	
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  //$( ".modal fade in" ).css( "height", "1000px" );
		});
    </script>
    
<script>
function send_to_chairman(emp_id_pk){
	//alert(emp_id_pk);
	$("#emp_id_fk").val(emp_id_pk);
}
</script>
    
<?
$db=new database();

$arr=$db->fetch_table("select emp_first_name, emp_second_name, emp_last_name, emp_desig, emp_status, emp_id_pk, gp_id_fk, emp_id_const from prd_employee_master where emp_status in(1) AND gp_id_fk='".substr($_SESSION['location']['gp_id'],0,7)."' and emp_desig not in('1120') order by emp_id_pk DESC");

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
    

    <div class="content">
	<? require '../../../../page/common_back_btns.php'; ?>
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
    <center>
    <h1 class="heading">PROMOTION DETAILS </h1>
    <?  
    if(($_SESSION['msg']))
	{
		echo $_SESSION['msg'];
		unset($_SESSION['msg']);
	}
	?>
    <!--    Kalyan Ghosh    20/3/2017    Start-->
    <div id="sess_msg" style="padding-left:12px;padding-right:12px;">
    
	</div>
    <br />
  
</center>

     

<?php 
if(!empty($_GET['msg'])){
echo $cryptoGraph->decode($_GET['msg'],4);
echo "<br/>";
echo "<br/>";
}
echo $msg;
?>

<div class="row">
        <div class="content">
       <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
       <div class="emplist">
<div class="school">
<div class="table-responsive">
           
      <table class="table-responsive" style="width:100%;">
<thead>
<tr>
<th>Serial No.</th>
<th>Employee Name</th>
<th>Employee ID</th>
<th>Designation</th>
<th>Approval Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<? $cnt=1; if(count($arr) > 0){ foreach($arr as $item){
$get_promotional_data = $db->fetch_table("SELECT status,approval_status,approval_status_by_ddo FROM prd_employee_promotion_details WHERE emp_id_fk = '".$item['emp_id_pk']."' AND delete_status = '1'");
 
if($get_promotional_data[0]['status']=='1' && $get_promotional_data[0]['approval_status']=='1'){
	$status="<a data-toggle='modal' data-target='#send' style='cursor:pointer;' onclick='return send_to_chairman(\"".$cryptoGraph->encode($item['emp_id_pk'],4)."\");'><span class='text-primary' style='font-weight:bold; color:#FA8072;'>SEND TO BDO</span></a>";
}
else if($get_promotional_data[0]['status']=='1' && $get_promotional_data[0]['approval_status']=='2') {
	$status='<span style="color:#FA8072;font-weight:bold">Waiting for approval</span>';
}
else if(($get_promotional_data[0]['status']=='1' && $get_promotional_data[0]['approval_status']=='3') || ($get_promotional_data[0]['status']=='1' && $get_promotional_data[0]['approval_status']=='8') || ($get_promotional_data[0]['status']=='1' && $get_promotional_data[0]['approval_status']=='5')  || ($get_promotional_data[0]['status']=='1' && $get_promotional_data[0]['approval_status']=='6') || ($get_promotional_data[0]['status']=='1' && $get_promotional_data[0]['approval_status']=='7')) {
	$status='<span style="color:#FA8072;font-weight:bold">Waiting for approval</span>';
}
else if($get_promotional_data[0]['status']=='1' && $get_promotional_data[0]['approval_status']=='4') {
	$status='<span style="color:#FF0000;font-weight:bold">REJECTED BY BDO</span>';
}
else if($get_promotional_data[0]['status']=='2' && $get_promotional_data[0]['approval_status']=='9') {
	$status='<span style="color:#008000;font-weight:bold">APPROVED</span>';
}

else
{
 $status = '<span style="color:#FA8072;font-weight:bold">N/A</span>';
}
?>

<tr>
<td><?= $cnt;?></td>
<td><?= $item['emp_first_name'].' '.$item['emp_second_name'].' '.$item['emp_last_name']?></td>
<td><?= $item['emp_id_const']?></td>
<td><? $desig = $db->fetch_table("SELECT description,code FROM prd_dise_code_master WHERE code='".$item['emp_desig']."'");
echo $desig[0]['description']; ?></td>
<td><?= $status; ?></td>
<td class="view">
<?php if(($get_promotional_data[0]['status'] =='' && $get_promotional_data[0]['approval_status'] =='') || ($get_promotional_data[0]['status'] =='1' && $get_promotional_data[0]['approval_status'] =='1') || ($get_promotional_data[0]['status'] =='1' && $get_promotional_data[0]['approval_status'] =='4') || ($get_promotional_data[0]['status'] =='1' && $get_promotional_data[0]['approval_status'] =='10')){ ?>
 <div class="edit" style="display:inline;"><a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-toggle="modal" data-target="#myModal_edit"><img src="<?= $config['base_url'] ?>themes/default/image/Folder_Enter-512.png" width="25" alt="edit" title="Edit" /></a></div>
 <?php } else { ?> 
 <img style="opacity:0.5;" src="<?= $config['base_url'] ?>themes/default/image/Folder_Enter-512.png" width="25" alt="edit" />
 <?php } ?> 
 
 <?php if($get_promotional_data[0]['status']!='' && $get_promotional_data[0]['approval_status']!=''){ ?>
<a href="" id="<?= $cryptoGraph->encode($item['emp_id_pk'],4);?>" data-toggle="modal" data-target="#myModal"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" title="View" /></a> 
<?php } else { ?>   
<img style="opacity:0.5;" src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" />
<?php } ?>   
</td>
</tr>

<? $cnt+=1; }} else { ?>
<tr>
<td colspan="6" style="color:red;font-weight:bold">No Data Found</td>
</tr>
<? } ?>
</tbody>
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






<script>


$(document).ready(function(){
			
			
			var tch=(<?=count($tch)?>);
			
		
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  $("#wait").css("display","none");
		  $("#dialog-confirm").css("display", "none");
		  $("#saving").css("display", "none");
		
		  $(".edit a").click(function() {	
        //var link = $(this).attr('href');
		
			var link = $(this).attr('id');
			
		 
		
		
	//	alert(link1);
		//alert(link1);
		$.post('<?= $config['base_url'] ?>all_moduls/gp_promotion_module/promotion_module_for_gp/ajax_emp_promotion_edit.php?id='+link, function(data){
				//alert(data);
				 $("#mbody1").html(data);
		       });
		    });	
		});


		$(document).ready(function(){
		$(".view a").click(function() {	
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		
		$(".mbody").load('<?= $config['base_url'] ?>all_moduls/gp_promotion_module/promotion_module_for_gp/ajax_emp_promotion_view.php?id='+link, function(data){
				 // alert(data);
			});
		});
		});
		
</script>

   


<style>
.modal-backdrop fade in{
	height:auto 0;
}
</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View Employee Promotion/CAS Details </h4>
      </div>
      <div class="modal-body"> 
      <div class="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
      <div class="btn-group"> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
        </div>
      </div>
    </div>
  </div>
</div>

<!-----------------------------------------------------------------MODAL END---------------------------------------------------->

<div class="modal fade bs-example-modal-lg" id="myModal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content" style="width: 1080px; margin-left: -7.5%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Promotion Details</h4>
      </div>
      <div class="modal-body"> 
      <div id="mbody1"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>



<div class="modal fade bs-example-modal-sm" id="send" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Do You Want To Send This Employee Promotion Details For Approval?</strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
      <form action="send_emp_promotion.php" method="post">
      <input name="emp_id_fk" id="emp_id_fk" type="hidden"/>
        

		<input type="submit" id="send_promotion" name="send_promotion" value="YES" class="btn btn-success" />
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>
        </form>      
      </div>
      </div>
    </div>
  </div>
</div>
