<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
error_reporting(0);
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require '../../../page_visite.php';
$crypto = new cryptography();

//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])


	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//Page variables
$common['title'] = "Salary Finalize | eHRMS | Govt. of West Bengal ";


//Meta tag variables
//$common['meta']['keyword'] = 'West Bengal School Education Department, SED department';
//$common['meta']['description'] = 'West Bengal School Education Department, SED department';

//Self variable
//-----------------------------------------------------------------QUERY-------------------------------------------------------

$db = new database();
$query = $db->fetch_table("select now() now") ;
$now = $query[0]['now'] ;
$circle_code = $crypto->decode($_GET['id'],3);
	
//===============================================================================================================	
	
if($circle_code !=''){
$update = $db->update("
		UPDATE ehrms_teacher_salary_save_primary
		SET status_flag ='1'
		WHERE status_flag ='2' AND circle_code = '".$circle_code."' AND category_id='1'");
}
//echo $update." ".$circle_code ;
if($update && $circle_code !='' ){
	$query = $db->fetch_table("select now() now") ;
	$now = $query[0]['now'] ;
	
	
	//for insert into ehrms_monthly_salary_archive_nonfinal
	$fetch_table1=$db->fetch_table("select max(archive_nonfinal_pk) max from ehrms_monthly_salary_archive_nonfinal");
	$max=$fetch_table1[0]['max'];
	$archive_not_final=$db->fetch_table("INSERT INTO ehrms_monthly_salary_archive_nonfinal(
				slno, latestupdate_time, latestupdate_ip_address, schcd, tchcd, 
       bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       teacher_salary_id_fk, spl_alo, status_flag, salary_monthyear, 
       category_id, dpsc_code, teacher_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
       overdrawn, salary_type, cause, circle_code, part_day) 
				select slno, latestupdate_time, latestupdate_ip_address, schcd, tchcd, 
       bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       teacher_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, dpsc_code, teacher_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
       overdrawn, salary_type, cause, circle_code, part_day from ehrms_teacher_salary_save_primary where circle_code='$circle_code' ");
	  
	  /*echo "INSERT INTO ehrms_monthly_salary_archive_nonfinal(
				school_code, teacher_code, salary_month_year, 
				bank_name, account_no, bank_ifsc, category_id, 
			   basic, da, hra, ma, cpf, pf_loan, ptax, itax, special_pay, pf_deduct, status) 
				select schcd,tchcd,salary_monthyear,bankname,accountno,bank_ifsc,category_id,basic,da,hra,ma,cpf,pf_loan,p_tax,i_tax
	  ,spl_pay,pf_deduct,status_flag from ehrms_teacher_salary_save_primary where circle_code='$circle_code' ";*/
	  
	  $fetch_table=$db->fetch_table("select max(archive_nonfinal_pk) max from ehrms_monthly_salary_archive_nonfinal");
	  $max1=$fetch_table[0]['max'];
	  //echo $max." max1 ".$max1 ; exit ;
	  if(count($max)==0){
			  	$max=0;
	  }
	  $query1 = $db->fetch_table("select now() now") ;
	  $now1 = $query1[0]['now'] ;
	  $archive_not_final_upd=$db->update("UPDATE ehrms_monthly_salary_archive_nonfinal
	  SET entry_time='".$now1."', entry_ip='".$_SESSION['user_agent']['USER_IP']."' where archive_nonfinal_pk > $max and archive_nonfinal_pk <= $max1");
	  /*echo "UPDATE ehrms_monthly_salary_archive_nonfinal SET entry_time='".$now1."', entry_ip='".$_SESSION['user_agent']['USER_IP']."'
	   where monthly_salary_id_pk > $max and monthly_salary_id_pk <= $max1";exit;*/
	
	
	
	
	
	
	
	$security = $db->insert("
							INSERT INTO
										ehrms_salary_log (
															ip,
															date,
															browser,
															os,
															salary_status_id_fk,
															category_id,
															created_by,
															created_by_stake,
															school_id_fk
															
														)
										VALUES 			(
															'".$_SESSION['user_agent']['USER_IP']."',
															'".$now."',
															'".$_SESSION['user_agent']['BROWSER']."',
															'".$_SESSION['user_agent']['OS']."',
															1,
															1,
															'".$_SESSION['user_info']['stake_user']."',
															'".$_SESSION['user_info']['stake_level']."',
															".$school_id_fk."
														)
							
									");
}
/*$circle_arr = $db->fetch_table("SELECT circle_id_pk FROM ehrms_dise_location_master_circle
							WHERE circle_code='".$_SESSION['user_info']['stake_user']."'");
$circle_id_pk = $circle_arr[0]['circle_id_pk'];*/

$arr = $db->fetch_table("select cm.circle_id_pk,cm.circle_name,cm.circle_code,
count(distinct(sm1.schcd)) as finalized, count(distinct(sm2.schcd)) as not_finalized 
from (select circle_id_pk,circle_code,circle_name from ehrms_dise_location_master_circle where circle_code like '".$_SESSION['user_info']['stake_user']."%') as cm 
left join ehrms_teacher_salary_save_primary sm1 on cm.circle_code=sm1.circle_code and sm1.status_flag=2 
left join ehrms_teacher_salary_save_primary sm2 on cm.circle_code=sm2.circle_code and sm2.status_flag=1 or sm2.status_flag IS NULL 
inner join ehrms_dise_teacher_primary as tch on tch.schcd = sm1.schcd
group by cm.circle_id_pk,cm.circle_name,cm.circle_code order by cm.circle_name
		");
$finalized_count=$db->fetch_table("
					select  
count(distinct(sm1.schcd)) as finalized 
from (select circle_id_pk,circle_code,circle_name from ehrms_dise_location_master_circle where circle_code like '".$_SESSION['user_info']['stake_user']."%') as cm 
left join ehrms_teacher_salary_save_primary sm1 on cm.circle_code=sm1.circle_code and sm1.status_flag=2 
inner join ehrms_dise_teacher_primary as tch on tch.schcd = sm1.schcd
 group by cm.circle_id_pk,cm.circle_name,cm.circle_code order by cm.circle_name");
	//print_r($arr);
		
//---------------------------------- HEADER -----------------------------------------------------------------------------------
//require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
//require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------
?>
<!--CONTENT START-->
<style>
	#sucess{
			background-color: green;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
		}
		#error{
			background-color: red;
			border: medium none salmon;
			border-radius: 8px;
			box-shadow: 2px 3px 3px #7d7c7d;
			color: #ffffff;
			font-family: Verdana,Geneva,sans-serif;
			font-size: 13px;
			padding: 8px;
			text-align:center;
			font-weight:bold;
		}
</style>

     <script>
		  $(document).ready(function() {
		  	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  	$( "tr:even" ).css( "background-color", "#DDF7FF" );	
		    //Button
		      //finalize button
		       $( "a.unlock" ).button({
					/*icons: {
						primary: "ui-icon-folder-open"
					}*/
				}).click(function( event ) {
					var link = $(this).attr('href');
					$(document).ajaxStart(function() {
						$(".shoload").css("display", "block");
						$('.shoload').html('<h3>Loading...<img height="20" src="themes/default/image/preloader.gif" /></h3>');
					});
					$(document).ajaxComplete(function() {
						
						//$("#saving").fadeOut();
						$(".shoload").css("display", "none");
						$('.shoload').text('');
						//$("#saving").css("display", "none");

					});
					    	//alert(link);
					 	$('.tbl_border').load('page/intra_ehrms/dpsc/salary_finalize/unlock.php?id=' + link, function(responseTxt, statusTxt, xhr) {
						//alert(responseTxt);
                        //alert(statusTxt);
						if (statusTxt == "error") {
							//alert("Error: "+xhr.status+": "+xhr.statusText);
							$('.shoload').text('Loading error');
							//$("#saving").css("display", "block");
						} else {
							$('.shoload').text('');
							//$("#saving").css("display", "none");
						}
					});
						event.preventDefault();
				});
					    
		   //School list
		   $( ".mymeny a" )
		      .button()
		      .click(function( event ) {
		        event.preventDefault();
		      });
		   //Show all
		   $('a.finalize_all').click(function(){
		   	
		   	$(document).ajaxStart(function() {
						$(".shoload").css("display", "block");
						$('.shoload').html('<h3>Loading...<img height="20" src="themes/default/image/preloader.gif" /></h3>');
					});
					$(document).ajaxComplete(function() {
						$(".shoload").css("display", "none");
						$('.shoload').text('');
					});
			   $('.tbl_border').load('page/intra_ehrms/dpsc/salary_finalize/lock_circle.php', function(responseTxt, statusTxt, xhr) {
					if (statusTxt == "error") {
						('.tbl_border').text('Loading error');

					} else {
						//$('.shoload').text('');
						//$(".shoload").css("display", "none");
						//$("#saving").css("display", "none");
					}
				});
			});
		   
		   $('a.not_final').click(function(){
		   	
		   	$(document).ajaxStart(function() {
						$(".shoload").css("display", "block");
						$('.shoload').html('<h3>Loading...<img height="20" src="themes/default/image/preloader.gif" /></h3>');
					});
					$(document).ajaxComplete(function() {
						$(".shoload").css("display", "none");
						$('.shoload').text('');
					});
			   $('.tbl_border').load('page/intra_ehrms/dpsc/salary_finalize/not_finalized.php', function(responseTxt, statusTxt, xhr) {
					if (statusTxt == "error") {
						('.tbl_border').text('Loading error');

					} else {
						//$('.shoload').text('');
						//$(".shoload").css("display", "none");
						//$("#saving").css("display", "none");
					}
				});
			});
		  });
	</script>
	
	<style>
	.dashboard-main table
	{
		border-collapse:collapse;
		background-color: #FFFFFF;
		font-family: "calibri";
	}
	.dashboard-main table, .dashboard-main td, .dashboard-main th
	{
		border:1px solid #fff;
		padding: 4px;
	}
	.dashboard-main table th{
		background-color: #0D8BBD;
		color: #fff;
		padding: 6px;
	}
	.dashboard-main table{
		border-radius: 5px;
		-moz-border-radius: 5px;
		overflow: hidden;
	}
	.tbl_border{
		background-color: #FFFFFF;
		padding: 8px;
		border-radius: 8px;
		-moz-border-radius: 8px;
	}
	#dialog_tch{
		display: none;
		max-height: 600px;
	}
	.ui-widget-overlay {
		background-color: black;
    	background-image: none;
		opacity: .4;
		filter: Alpha(Opacity=60);
	}
	.loading{
		text-align: center;
	}
	.loading img{
		vertical-align: middle
	}
	.page_title{
		text-align: center;
		text-transform: uppercase;
		color: #2779AA;
	}
	.mymeny{
		margin-bottom: 6px;
		text-align: right;
	}
	.shoload img{
		vertical-align: middle;
	}
	.shoload{
		text-align: center;
	}
	.tit{
		float: left;
		padding: 0px;
		margin: 0px;
		font-weight: bold;
		font-size: 18px;
		font-style: italic;
		color: #0066CC;
	}
	#click_me{
		text-decoration:none;
		color:#0D8BBD;
	}
	#click_me:hover{
		text-decoration:underline;
		color:#0D8BBD;
	}
	</style>

    
    <?php 
        if($_SESSION['msg']){
        	echo $_SESSION['msg'];
        	unset($_SESSION['msg']);
        	echo '<br>';
        }
		
     ?>
    
 
  
	
		<div class="tbl_border">
            <div class="mymeny">
                 <span>Finalized from Circle ( <b>Total <?php $count = count($arr); echo $count; ?> circle is <?php if($count > 1 ){ echo 's';} ?> finalized</b> )</span> 
				 <?php  $lock = $db->fetch_table("SELECT count(distinct(circle_code,status_flag)) FROM ehrms_teacher_salary_save_primary where status_flag in(1,3) and dpsc_code='".$_SESSION['user_info']['stake_user']."'"); 
				        if($lock[0]['count']==0){
				?>
				<a class="finalize_all" href="#">Lock Circle</a>
                <?php } ?>
				<a class="not_final" href="#">Show Not Finalized Circle</a>
			</div>
			<div class="mymeny">
				<!--<p class="tit">List of schools waiting for employee finalization</p>
				<a class="show_all" href="#">Show All Schools</a>-->
			</div>
			<?php if(count($arr)){ ?>
			<table width="100%">
				<tr>
					<th width="60">SL NO.</th>
					<th width="300">CIRCLE NAME</th>
					<th width="101">SCHOOL ENTERED</th>
					<th width="100">SALARY FINALIZED</th>
                    <th width="100">SALARY NOT FINALIZED</th>
                    <th width="300">ACTION</th>
				</tr>
                <?php $count = 1; $cnt=0; foreach ($arr as $key) {  ?>
				<?php $total_school = $db->fetch_table("SELECT count(distinct(school_id_pk)) as total FROM ehrms_dise_location_master_school as school inner join ehrms_dise_teacher_primary as tch on
tch.schcd = school.school_dise_code WHERE circle_id_fk='".$key['circle_id_pk']."' and flag='2'"); 
					if($total_school[0]['total']==$finalized_count[$cnt]['finalized']){
					
					?>
                <tr>
                    <td style="width: 60px;" align="center"><?php echo $count; ?></td>
					<td><?php echo $key['circle_name']; ?></td>
					<td style="text-align:center"><?php echo $total_school[0]['total'] ; ?></td>
					<td style="width: 60px;" align="center"><?php echo $key['finalized'];  ?></td>
                    <td style="width: 60px;" align="center"><?php echo $key['not_finalized']; ?></td>
                    <td style="width: 60px;" align="center">
                    <?php if($total_school[0]['total']==$finalized_count[$cnt]['finalized'] && $total_school[0]['total']!=0){ ?>
                    <a class="bk unlock ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="click_me" style="text-decoration: none;" href="<?php echo $crypto->encode($key['circle_code'],3); ?>">Unlock</a> &nbsp;&nbsp;
                    <?php } ?>
                    <a class="bk ui-button ui-widget ui-state-default  ui-corner-all ui-button-text-icon-primary" id="click_me" style="text-decoration: none;" href="page/intra_ehrms/dpsc/salary_school_list.php?circle_id<?php echo $crypto->encode($key['circle_id_pk'],3); ?>"><span class="ui-button-text">View School</span></a></td>
					
				</tr>
				<?php $count += 1; $cnt++;}
				else{ $cnt++;}
				} ?>
			</table>
			<?php } else { ?>
			<table width="100%">
				<tr><td align="center">No circle found finalized under this district.</td></tr>
            </table>
			<?php } ?>
		</div>
	
<?
//echo '<pre>';
//print_r($_SESSION);
//echo session_id();
?>
<?php
//---------------------------------- SLIDER -----------------------------------------------------------------------------------
//require '../../right_sidebar_dashboard.php';
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
//require '../../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
