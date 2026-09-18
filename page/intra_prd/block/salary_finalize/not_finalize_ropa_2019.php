<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../../dashboard.php");
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
//$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";

//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable

//------------------------------------------------------- HEADER --------------------------------------------------------------
//require '../../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
//require '../../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
?>
<script>
    	$(document).ready(function(){
		  $( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  $( "tr:even" ).css( "background-color", "#DDF7FF" );
		  $("#final").click(function(){
		  $.post('<?php echo $config['base_url'] ?>page/intra_prd/block/salary_finalize/salary_finalize_ropa_2019.php',function(data){
			  $(".emplist").html(data);
		  });
		   })
		});
    </script>
<?
$db=new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];


$arr=$db->fetch_table("
						SELECT gp_id_pk,gp.gp_code,gp_name
						from prd_location_master_gp gp
						inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
						LEFT JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk 
						WHERE CAST(b.block_code AS text) like '".$_SESSION['location']['block_code']."%'   
						GROUP BY gp_name,gp_id_pk,gp.gp_code 
						EXCEPT
						SELECT gp_id_pk,gp.gp_code,gp_name
						from prd_location_master_gp gp
						inner join prd_location_master_block b on b.block_id_pk=gp.block_id_fk
						inner JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk 
						inner JOIN prd_employee_salary_save sal on CAST(emp.gp_id_fk AS text)=sal.gp_id_fk 
						AND status_flag in('2','3')
						WHERE CAST(sal.block_code AS text) like '".$_SESSION['location']['block_code']."%'  
						AND salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."' AND sal.ropa_status='1'
						GROUP BY gp_name,gp_id_pk,gp.gp_code,status_flag order by gp_name
");
$count=count($arr);
?>

<div class="school">
<div class="table-responsive">
<a class="btn btn-info btn-sm" id="final" style="float: right;">Show Finalized GP</a>
<div class="text-warning" style="font-family:'MS Serif', 'New York', serif; font-size:18px; font-weight:400; ">
<strong>Total <?php echo $count; ?> GP Not Finalized.</strong>
</div>

<!--<a class="btn btn-info btn-sm" style="float: right;margin-right: 1%;">Lock GP</a>-->
<table width="100%" cols="4">

<tr>
<th>Serial No.</th>
<th>Gram Panchayet Name</th>
<th>Gram Panchayet Code</th>
<th>Status</th>
</tr>
<? $cnt=1;$total_emp=0;$total_finz=0;$total_waiting=0;$total_incomplete=0;$total_rej=0;$total_req_not_sent=0; 
if(count($arr)){ foreach($arr as $item){ 

?>
<tr>
<td><?= $cnt;?></td>
<td><?= $item['gp_name']?></td>
<td><?= $item['gp_code']?></td>
<td><span style="color:red; font-weight:bold">Not Finalized</span></td>
<!--<td class="view"><a href="<?//= $config['base_url'] ?>page/intra_prd/block/edit_emp/edit_employee_list.php?id=<?//= $cryptoGraph->encode($item['gp_id_pk'],4);?>"><img src="<?//= $config['base_url'] ?>themes/default/image/view_icon.png" width="25" alt="view" /></a></td>-->
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

