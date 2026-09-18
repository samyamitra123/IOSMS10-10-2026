<?
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
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
/*if(isset($_GET['confirm'])){
	if($_GET['confirm'] == 'success'){
		$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
	}else if($_GET['confirm'] == 'false'){
		$msg='<div id="error">Employee Profile Submission Fails...</div>';
	}
}*/


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
		});
    </script>
<?
$db=new database();
/*
$arr=$db->fetch_table("
						SELECT gp_id_pk,gp_code,gp_name,count(distinct(emp.emp_id_pk)) as total_emp
						from prd_location_master_gp gp
						LEFT JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk AND emp_status='1'
						WHERE CAST(gp_code AS text) like '".$_SESSION['location']['block_code']."%'  GROUP BY gp_name,gp_id_pk,gp_code order by gp_name

");*/


$arr=$db->fetch_table("
						SELECT gp_id_pk,gp_name,gp_code,count(distinct(emp.emp_id_pk)) as total_emp,
						count(distinct(em1.emp_id_pk)) as emp_waiting,
						count(distinct(em2.emp_id_pk)) as emp_finz,
						count(distinct(em3.emp_id_pk)) as emp_rej,
						count(distinct(em4.emp_id_pk)) as emp_incomplete,
						count(distinct(em5.emp_id_pk)) as emp_req_not_sent from prd_location_master_gp gp
						inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
						LEFT JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk 
						LEFT JOIN prd_employee_master as em1 on em1.gp_id_fk=gp.gp_id_pk and em1.emp_status='6'
						LEFT JOIN prd_employee_master as em2 on em2.gp_id_fk=gp.gp_id_pk and em2.emp_status='1'
						LEFT JOIN prd_employee_master as em3 on em3.gp_id_fk=gp.gp_id_pk and em3.emp_status='7'
						LEFT JOIN prd_employee_master as em4 on em4.gp_id_fk=gp.gp_id_pk and em4.emp_form_status in ('1','2','3','4')
						LEFT JOIN prd_employee_master as em5 on em5.gp_id_fk=gp.gp_id_pk and em5.emp_form_status not in ('1','2','3','4') AND em5.emp_status='10'
						WHERE CAST(b.block_code AS text) like '".$_SESSION['location']['block_code']."%'  GROUP BY gp_name,gp_id_pk ,gp_code order by gp_name

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
<h1 class="heading">Gram Panchayet LIST</h1>
<div class="border"></div>
</br>
</br>
<?php 
if(!empty($msg)){
echo $msg;
echo "<br/>";
echo "<br/>";
}
?>
<div class="emplist">
<div class="school">
<div class="table-responsive">
<table width="100%">
<tr>
<th>Serial No.</th>
<th>Gram Panchayet Name</th>
<th>Gram Panchayet Code</th>
<th>Total Finalized Employee</th>
<th>View</th>
</tr>
<? $cnt=1;$total_emp=0;$total_finz=0;$total_waiting=0;$total_incomplete=0;$total_rej=0;$total_req_not_sent=0; 
if(count($arr)){ foreach($arr as $item){ 
?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['gp_name']?></td>
<td><?= $item['gp_code']?></td>
<td><?= $item['total_emp']?></td>
<td class="view"><a href="<?= $config['base_url'] ?>page/all_moduls/employee_pay_commission/pay_commission_user_profile_view.php?id=<?= $cryptoGraph->encode($item['gp_id_pk'],4);?>"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>
</tr>
<? $cnt+=1; } ?>
<? } else { ?>
<tr>
<td colspan="4" style="color:red;font-weight:bold">No Data Found</td>
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
