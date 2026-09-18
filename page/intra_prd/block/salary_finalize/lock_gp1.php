<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';





//--------------PHP MAIL-----------------------

require '../../../../includes/third-party/PHPMailer/class.phpmailer.php';
require '../../../../includes/third-party/PHPMailer/PHPMailerAutoload.php';
require_once '../../../all_function/mail/mail_fun.php';


//---------------------------------------------

echo 33; die;

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
if($_GET['confirm'] == 'success'){
	$msg='<div id="sucess">Employee Profile Submitted Successfully...</div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div id="error">Employee Profile Submission Fails...</div>';
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
//require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
//require '../../../page/layout/menu.php';
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
		   })
		});
    </script>
<?
$db=new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

			
			//for insert into ehrms_monthly_salary_archive_final
			$fetch_table1=$db->fetch_table("select max(archive_final_pk) max from prd_monthly_salary_archive_final");
			$max=$fetch_table1[0]['max'];
			/*$archive_not_final=$db->fetch_table("INSERT INTO prd_monthly_salary_archive_final(
						slno, latestupdate_time, latestupdate_ip_address, gp_id_fk, empcd, 
						bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
						i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
						emp_salary_id_fk, spl_alo, status_flag, salary_monthyear, 
						category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
						gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
						overdrawn, salary_type, cause, gp_code, part_day) 
											select slno, latestupdate_time, latestupdate_ip_address, gp_id_fk, empcd, 
										   bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
										   i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
										   emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
										   category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
										   gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
										   overdrawn, salary_type, cause, gp_code, part_day from prd_employee_salary_save where block_code='".$_SESSION['location']['block_code']."' AND salary_monthyear='".date('Ym')."'and is_saved='1' and delete_status='1' and status_flag='2' ");*/
			  
			 $update = $db->update("
		UPDATE prd_employee_salary_save
		SET status_flag =3
		WHERE status_flag =2 AND
		block_code = '".$_SESSION['location']['block_code']."' AND requisition_type='".$requisition_type."'
		AND salary_monthyear='".date('Ym')."' and delete_status='1' and is_saved='1'
		
		
");
			if(!empty($update)){
	
					//$_SESSION['msg']="GP successfully locked.";
					
					
	/*if($_SESSION['location']['block_code']=='3299001');
	{
	
	$gp_info = $db->fetch_table("select
									 gp.email_id, 
									 gp.mobile_no,
									 gp.gram_pradhan_name 
								 from 
	 								prd_gp_profile gp where CAST(gp.gp_code as character varying) like '".$_SESSION['location']['block_code']."%' "
									 );

foreach($gp_info as $item){ 

 $mail_body  = 'Dear '.$item['gram_pradhan_name'].' '.'<p>The Salary Requisition has been successfully Locked </p> <hr><b>Disclaimer:</b>This is a system generated mail.Please do not reply.';
$email=$gp_info['0']['email_id'];
$emp_sub='Employee Salary Status';
mail_function($mail_body,$email,$emp_sub);
}
	
	}*/
					
					
				
	
			$query = $db->fetch_table("select now() now") ;
			$now = $query[0]['now'] ;
			  
			  $fetch_table=$db->fetch_table("select max(archive_final_pk) max from prd_monthly_salary_archive_final");
			  $max1=$fetch_table[0]['max'];
			  //echo $max." max1 ".$max1 ; exit ;
			  $query1 = $db->fetch_table("select now() now") ;
			  $now1 = $query1[0]['now'] ;
			  if(count($max)==0){
			  	$max=0;
			  }
			  
			 /* $archive_not_final_upd=$db->update("UPDATE prd_monthly_salary_archive_final
			  SET entry_time='".$now1."', entry_ip='".$_SESSION['user_agent']['USER_IP']."' where archive_final_pk > $max and archive_final_pk <= $max1");*/
			  
			  
			  /*echo "UPDATE ehrms_monthly_salary_archive_final SET entry_time='".$now1."', entry_ip='".$_SESSION['user_agent']['USER_IP']."'
			   where monthly_salary_id_pk > $max and monthly_salary_id_pk <= $max1";exit;*/
			
			$security = $db->insert("
										INSERT INTO
										prd_salary_log (
															ip,
															date,
															browser,
															os,
															salary_status_id_fk,
															created_by,
															created_by_stake
															
														)
										VALUES 			(
															'".$_SESSION['user_agent']['USER_IP']."',
															'".$now."',
															'".$_SESSION['user_agent']['BROWSER']."',
															'".$_SESSION['user_agent']['OS']."',
															'3',
															'".$_SESSION['user_info']['stake_user']."',
															'".$_SESSION['user_info']['stake_level']."'
															
														)
							
									");
}

$arr=$db->fetch_table("
						SELECT gp_id_pk,gp.gp_code,gp_name,status_flag 
						from prd_location_master_gp gp
						inner JOIN prd_employee_master emp on emp.gp_id_fk=gp.gp_id_pk 
						inner JOIN prd_employee_salary_save sal on CAST(emp.gp_id_fk AS text)=sal.gp_id_fk AND status_flag='3' and salary_monthyear='".date('Ym')."'
						WHERE CAST(gp.gp_code AS text) like '".$_SESSION['location']['block_code']."%' AND sal.requisition_type='".$requisition_type."'  GROUP BY gp_name,gp_id_pk,gp.gp_code,status_flag order by gp_name

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
<div class="school">
<div class="table-responsive">
<button class="btn btn-info btn-sm" id="not_final" name="not_final" style="float: right;">Show Not finalized GP</button>
<!--<a class="btn btn-info btn-sm" style="float: right;margin-right: 1%;">Lock GP</a>-->
<table width="100%" cols="4">
<tr>
<th>Serial No.</th>
<th>Gram Panchayet Name</th>
<th>Gram Panchayet Code</th>
<th>Status</th>
<th>Unlock</th>
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
  <button type="button" class="btn btn-success" id="unlock_active" value="<?php echo $cryptoGraph->encode($item['gp_code'],4); ?>">Unlock</button>
<?php } else if($item['status_flag']=='3') { ?>  
  <button type="button" class="btn btn-danger" id="unlock_active" disabled="disabled">Unlock</button>
<?php } ?>  
  

</td>
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

