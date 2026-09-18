<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
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
$url = $_SERVER['PHP_SELF'] ;
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
		$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div id="error">Employee Profile Submission Fails...</div>';
	}
}

//$tchcd=isset($_GET['tchcd']) ? $_GET['tchcd']:'';


//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

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
		  $("#not_final").click(function(){
		  $.post('<?php echo $config['base_url'] ?>page/intra_prd/block/salary_finalize/not_finalize.php',function(data){
			  
			  $(".emplist").html(data);
		  });
		   });
		   $("#finalize_all").click(function(){
			  $.post('<?php echo $config['base_url'] ?>page/intra_prd/block/salary_finalize/lock_gp.php',function(data){
				  
				  if(data)
				  {
					$('#msg_unlock').html('');
					$('#msg_unlock').html('<div class="alert alert-success" style="text-align:center"><strong>GP locked successfully</strong></div>');
					$(".emplist").html(data);
				  }
				  else
				  {
					$('#msg_unlock').html('');
					$('#msg_unlock').html('<div class="alert alert-success" style="text-align:center"><strong>Failed to lock.</strong></div>');
				  }
			  });
		   });
		   
		   
});
    </script>
<?
$db=new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$arr=$db->fetch_table("
						SELECT gp_id_pk,gp.gp_code,gp_name,status_flag,
count(distinct(emp.emp_id_pk)) as total_emp,count(distinct(sal.emp_id_fk)) as total_finalized_emp 
from prd_location_master_gp gp 
inner JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk
inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
 inner JOIN prd_employee_salary_save sal on CAST(emp.gp_id_fk AS text)=sal.gp_id_fk AND status_flag in('2','3') and delete_status='1' and is_saved='1'
						WHERE CAST(sal.block_code AS text) like '".$_SESSION['location']['block_code']."%' 
						AND salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."' AND sal.ropa_status='2'
						GROUP BY gp_name,gp_id_pk,gp.gp_code,status_flag order by gp_name

");

$arr_check=$db->fetch_table("
						SELECT gp_id_pk,gp.gp_code,gp_name,status_flag 
						from prd_location_master_gp gp
						inner JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk 
						inner JOIN prd_employee_salary_save sal on CAST(emp.gp_id_fk AS text)=sal.gp_id_fk 
						inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
						AND status_flag ='2'
						WHERE CAST(sal.block_code AS text) like '".$_SESSION['location']['block_code']."%'   
						AND salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."' AND sal.ropa_status='2'
						GROUP BY gp_name,gp_id_pk,gp.gp_code,status_flag order by gp_name

");		

$emp_check=$db->fetch_table("
						SELECT gp_id_pk,gp.gp_code,gp_name from prd_location_master_gp gp 
inner join prd_location_master_block b on gp.block_id_fk=b.block_id_pk
inner JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk  
 WHERE CAST(b.block_code AS text) like '".$_SESSION['location']['block_code']."%' 
 AND emp.ropa_status='2' GROUP BY gp_name,gp_id_pk,gp.gp_code order by gp_name

");	



?>
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
                          echo $_SESSION['location']['gp_name'];
                      }elseif(isset($_SESSION['location']['block_name'])) {
                          echo $_SESSION['location']['block_name'];
                      } elseif(isset($_SESSION['location']['district_name'])) {
                          echo $_SESSION['location']['district_name'];
                      } elseif(isset($_SESSION['location']['state_name'])) {
                          echo $_SESSION['location']['state_name'];
                    } ?></h2><h3>
					<? echo $_SESSION['location']['district_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">Salary Locking</h1>
<div class="border"></div>
</br>
</br>
<div id="msg_unlock">
<?php 

/*if($_REQUEST['msg']){
echo $cryptoGraph->decode($_REQUEST['msg'],4);
//unset($_SESSION['msg']);
//echo "<br/>";
echo "<br/>";
}
*/
if(isset($_SESSION['msg'])){
echo $_SESSION['msg'];
unset($_SESSION['msg']);
}
?>
</div>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<button class="btn btn-info btn-sm" id="not_final" name="not_final" style="float: right;">Show Not finalized GP</button>

<?php  if(count($arr_check)==count($emp_check)) { ?>
<button class="btn btn-info btn-sm" id="finalize_all" name="finalize_all" style="float: right;margin-right: 1%;">Lock GP</button>
<?php } ?>
<div class="text-warning" style="font-family:'MS Serif', 'New York', serif; font-size:18px; font-weight:400; ">
<strong>Total <?php echo count($arr); ?> GP Finalized.</strong>
</div>
<table width="100%" cols="4">
<tr>
<th>Serial No.</th>
<th>Gram Panchayet Name</th>
<th>Gram Panchayet Code</th>
<th>Status</th>
<th>Action</th>
</tr>
<? $cnt=1;$total_emp=0;$total_finz=0;$total_waiting=0;$total_incomplete=0;$total_rej=0;$total_req_not_sent=0; 
if(count($arr)){ foreach($arr as $item){ 
if($item['status_flag'] == 2){
 $status= "<div style='color:#080;'>FINALIZED</div>";
 } elseif($item['status_flag'] == 3){
  $status= "<div style='color:#F00;'>LOCKED</div>";
 }
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['gp_name']?></td>
<td><?= $item['gp_code']?></td>
<td><?= $status ?></td>
<td>
<?php if($item['status_flag']=='2'){ ?>
  <a class="btn btn-sm btn-success" id="unlock_active" href="<?php echo $config['base_url'] ?>page/intra_prd/block/salary_finalize/unlock_gp.php?gp_code=<?php echo $cryptoGraph->encode($item['gp_code'],4); ?>">Unlock</a>
  <a class="btn btn-sm btn-success" id="unlock_active" href="<?php echo $config['base_url'] ?>page/intra_prd/block/salary_finalize/view_salary_requisition.php?gp_code=<?php echo $cryptoGraph->encode($item['gp_id_pk'],4); ?>">View Requisition</a> 
<?php } else if($item['status_flag']=='3') { ?>  
  <!--<button type="button" class="btn btn-sm btn-danger" id="unlock_active" disabled="disabled">Unlock</button>-->
<?php } ?> 
 </td>
</tr>
<? $cnt+=1; } ?>
<? } else { ?>
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
<div class="clear"></div>



<? require '../../../page/layout/footer.php'; ?>
