<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
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
require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>

<script>
    	$(document).ready(function(){
						
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  
		   $("#finalize_all").click(function(){
			  
			 
			$('#finalize').modal('show');
		
		   });
		
	   
		   
});


 function not_finalized()
{
	
	 $.post('not_finalize.php',function(data)
	 {
			
			  $(".emplist").html(data);
			  //td_load();
			  //datatable_show();
			  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  	  $( "tr:even" ).css( "background-color", "#DDF7FF" );
	
	  });
}

function list_finz()
{
	 $('#finalize').modal('hide');
	 $.post('<?php echo $config['base_url'] ?>page/intra_prd/block/advance_pay/lock_gp.php',function(data){
				 // alert(data);
				  if(data=='Failed')
				  {
					$('#msg_unlock').html('');
					$('#msg_unlock').html('<div class="alert alert-success" style="text-align:center"><strongFailed to lock.</strong></div>');
					$(".emplist").html(data);
				  }
				  else 
				  {
					  $('#finalize_all').attr("disabled", "disabled");
					$('#msg_unlock').html('');
					$('#msg_unlock').html('<div class="alert alert-success" style="text-align:center"><strong>GP locked successfully</strong></div>');
					window.setTimeout(function(){
							window.location.replace("<?php echo $config['base_url'] . 'page/intra_prd/block/advance_pay/advance_salary_finalize.php'; ?>");
					},3000);
				  }
			  });
	
	
	
}
	
    </script>
<?
$db=new database();

$arr=$db->fetch_table(  "
						SELECT DISTINCT(adv.gp_id_fk),gp_name,
						gp_code,status_flag
from prd_location_master_gp gp 
inner JOIN prd_adavance_pay adv on gp.gp_id_pk=adv.gp_id_fk 
						WHERE block_code='".$_SESSION['location']['block_code']."'  
						 and status_flag in('2','3')
						

");

/*$arr_check=$db->fetch_table("
						SELECT gp_id_pk,gp.gp_code,gp_name,status_flag 
						from prd_location_master_gp gp
						inner JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk 
						inner JOIN prd_employee_salary_save sal on CAST(emp.gp_id_fk AS text)=sal.gp_id_fk 
						AND status_flag ='2'
						WHERE CAST(gp.gp_code AS text) like '".$_SESSION['location']['block_code']."%'  
						AND salary_monthyear='".date('Ym')."'
						GROUP BY gp_name,gp_id_pk,gp.gp_code,status_flag order by gp_name

");	*/	







$emp_check=$db->fetch_table("
						select 
		count(distinct CASE WHEN (adv1.status_flag='3') then adv1.emp_id_fk else null end) as adv_count
                                from 
								prd_adavance_pay adv1 WHERE adv1.block_code='".$_SESSION['location']['block_code']."'  

");


$gp_count=$db->fetch_table("
				SELECT  
										count(DISTINCT(gp.gp_id_pk)) as gp_cot
										FROM
prd_location_master_block bol inner join 
prd_location_master_gp gp on bol.block_id_pk=gp.block_id_fk inner join 
prd_employee_master emp
on (emp.gp_id_fk=gp.gp_id_pk and emp.emp_status='1' 
										and ((emp.emp_group in ('633','634') 
        								and trim(emp.emp_grade_pay) in ('1001','1002','1003','1004','1005','1006','1007')) or emp.emp_desig='1120')
										and emp.emp_retirement_date>'2016-11-30')

										WHERE
										bol.block_code='".$_SESSION['location']['block_code']."' 

");

$adv_count=$db->fetch_table(  "
						SELECT count(DISTINCT(gp_id_fk)) as adv_cot from 
 prd_adavance_pay
						WHERE block_code='".$_SESSION['location']['block_code']."'  
						 and status_flag in('2')
						

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
if($_SESSION['msg']){
echo $_SESSION['msg'];
unset($_SESSION['msg']);
}
?>
</div>
<div class="emplist">
<div class="school">
<div class="table-responsive">


<button class="btn btn-info btn-sm" id="not_final" name="not_final" style="float: right;" onClick="not_finalized();">Show Not finalized GP</button>
<button class="btn btn-info btn-sm" id="finalize_all" dataname="finalize_all" onclick="openModal()" style="float: right;margin-right: 1%;" <? if(($gp_count[0]['gp_cot']!=$adv_count[0]['adv_cot']) || ($emp_check[0]['adv_count']>0)) { echo 'disabled'; } ?>>Lock GP</button>

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
if(count($arr)){ 



foreach($arr as $item){ 

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
<?php if($item['status_flag'] == 2){ ?>
<td class="view">
 <a class="btn btn-sm btn-success" id="unlock_active" href="<?php echo $config['base_url'] ?>page/intra_prd/block/advance_pay/unlock_gp.php?gp_code=<?php echo $cryptoGraph->encode($item['gp_code'],4); ?>">Unlock</a>
 
 <a href="<?= $config['base_url'] ?>page/intra_prd/block/advance_pay/advance_employee_list.php?id=<?= $cryptoGraph->encode($item['gp_id_fk'],4);?>">
 <button type="button" class="btn btn-info btn-sm" id="unlock_active" >View</button></a>

<a href="<?= $config['base_url'] ?>page/intra_prd/block/advance_pay/advance_pay_edit/emp_draw_advance_list.php?id=<?= $cryptoGraph->encode($item['gp_id_fk'],4);?>">
 <button type="button" class="btn btn-info btn-sm" id="unlock_active" >Edit</button></a>
 </td>
 
 <?php }else{
	 
 ?>
 <td>
 <button type="button" class="btn btn-sm btn-danger" disabled="disabled">Unlock</button>
 <button type="button" class="btn btn-sm btn-danger"  disabled="disabled">View</button>
 <!--<button type="button" class="btn btn-sm btn-danger" id="unlock_active" disabled="disabled">Edit</button>-->
 <a href="<?= $config['base_url'] ?>page/intra_prd/block/advance_pay/advance_lock_edit/emp_draw_advance_list.php?id=<?= $cryptoGraph->encode($item['gp_id_fk'],4);?>">
 <button type="button" class="btn btn-info btn-sm" id="lock_edit" >Edit</button></a>
 </td>
 
 
 <?php }$cnt+=1;} ?>
</tr>
<?  ?>
<? } else { ?>
<tr>
<td colspan="5" style="color:red;font-weight:bold">No Data Found </td>
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



<? require '../../../../page/layout/footer.php'; ?>


  
  <div class="modal fade bs-example-modal-sm" id="finalize" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content" >
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Advance Salary Lock</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> After Lock, the Block can not Unlock any GP.</p><p><strong> Are You Sure to lock the GP?</strong></p>
      <input type="hidden" id="school_id" name="school_id"/>
      <input type="hidden" name="emp_id10" id="emp_id55" />
      </div>
      <div class="modal-footer">
      <div class="btn-group">
        <input type="submit" name="submit" value="YES" onClick="list_finz();" class="btn btn-success finalize" />
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>

 