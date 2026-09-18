<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/errordoc.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
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

$arr=$db->fetch_table("
						SELECT gp.gp_id_pk,gp.gp_code,gp.gp_name,count(distinct(em5.emp_id_fk)) as total_emp
						from prd_location_master_gp gp
						LEFT JOIN prd_stake_epension_employee_profile as em5 on em5.curremt_gp_ps_zp_code=gp.gp_code  AND em5.status in('1','2','3','4')
						WHERE CAST(gp.gp_code AS text) like '".$_SESSION['location']['block_code']."%'  GROUP BY gp_name,gp_id_pk,gp.gp_code order by gp_name

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
					<? echo $_SESSION['location']['district_name']." ,".$_SESSION['location']['state_name'];
                      ?></h3>
       </div>
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">GRAM PANCHAYAT LIST</h1>
<div class="border"></div>
</br>
</br>
<?php 
if($msg){
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
<th>Gram Panchayat Name</th>
<th>Total Forwarded Employee</th>
<th>View</th>
</tr>
<? $cnt=1;$total_emp=0;$total_finz=0;$total_waiting=0;$total_incomplete=0;$total_rej=0;$total_req_not_sent=0; 
if(count($arr)){ foreach($arr as $item){ 
$total_emp+=$item['total_emp'];

?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['gp_name']?></td>
<td><?= $item['total_emp']?></td>
<td class="view"><a href="<?= $config['base_url'] ?>page/all_moduls/update_epension_emp_profile/up_employee_profile_approve_list.php?stake_level=<?php echo $cryptoGraph->encode(35,4);?>&id=<?php echo $cryptoGraph->encode(4,4); ?>&gp_id=<?= $cryptoGraph->encode($item['gp_id_pk'],4);?>"><img src="<?= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>
</tr>
<? $cnt+=1; } ?>
<tr>
<td></td>
<td style="font-weight:bold;">TOTAL:</td>
<td style="font-weight:bold;"><?= $total_emp ?></td>
<td></td>
</tr>
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
