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
$circle_arr = $db->fetch_table("SELECT circle_id_pk FROM ehrms_dise_location_master_circle
							WHERE circle_code='".$_SESSION['user_info']['stake_user']."'");
$circle_id_pk = $circle_arr[0]['circle_id_pk'];
$arr = $db->fetch_table("select cm.circle_id_pk,cm.circle_name,cm.circle_code,sm1.status_flag,
count(distinct(sm1.schcd)) as finalized, count(distinct(sm2.schcd)) as not_finalized 
from (select circle_id_pk,circle_code,circle_name from ehrms_dise_location_master_circle where circle_code like '".$_SESSION['user_info']['stake_user']."%') as cm 
left join ehrms_teacher_salary_save_primary sm1 on cm.circle_code=sm1.circle_code and (sm1.status_flag=2 or sm1.status_flag=3)
left join ehrms_teacher_salary_save_primary sm2 on cm.circle_code=sm2.circle_code and (sm2.status_flag=1 or sm2.status_flag IS NULL) 
inner join ehrms_dise_teacher_primary as tch on tch.schcd = sm1.schcd
group by cm.circle_id_pk,cm.circle_name,sm1.status_flag,cm.circle_code having count(distinct(sm2.schcd)) = '0' order by cm.circle_name
		");
		
		
/*		
function totalSchool($circle_id){
	$db = new database();
	$total_school = $db->fetch_table("SELECT count(school_id_pk) as total FROM ehrms_dise_location_master_school
							WHERE circle_id_fk='$circle_id'");
	return $total_school[0]['total'];
}
function approvedSchool($circle_id){
	$db = new database();
	$total_school = $db->fetch_table("SELECT count(school_id_pk) as total FROM ehrms_dise_location_master_school
							WHERE circle_id_fk='$circle_id' AND flag=2");
	return $total_school[0]['total'];
}
function waitSchool($circle_id){
	$db = new database();
	$total_school = $db->fetch_table("SELECT count(school_id_pk) as total FROM ehrms_dise_location_master_school
							WHERE circle_id_fk='$circle_id' AND flag=1");
	return $total_school[0]['total'];
}*/
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
					icons: {
						primary: "ui-icon-folder-open"
					}
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
    <div class="shoload" style="display: none">Loading.........</div>
  
  
	
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
                    <!--<th width="100">SALARY NOT FINALIZED</th>-->
                    <th width="100">STATUS</th>
                    <th width="300">ACTION</th>
				</tr>
                
				<?php $count = 1; $cnt=0; foreach ($arr as $key) {  ?>
				<?php 
				
				$finalized_count=$db->fetch_table("
					select  
count(distinct(sm1.schcd)) as finalized 
from (select circle_id_pk,circle_code,circle_name from ehrms_dise_location_master_circle where circle_id_pk='".$key['circle_id_pk']."') as cm 
left join ehrms_teacher_salary_save_primary sm1 on cm.circle_code=sm1.circle_code and sm1.status_flag in (2,3) 
inner join ehrms_dise_teacher_primary as tch on tch.schcd = sm1.schcd
 group by cm.circle_id_pk,cm.circle_name,cm.circle_code order by cm.circle_name");

				$total_school = $db->fetch_table("SELECT count(distinct(school_dise_code)) as total FROM ehrms_dise_location_master_school as school inner join ehrms_dise_teacher_primary as tch on
tch.schcd = school.school_dise_code WHERE circle_id_fk='".$key['circle_id_pk']."' and flag='2'"); 
					if($total_school[0]['total']==$finalized_count[0]['finalized']){
					
					?>
                <tr>
                    <td style="width: 60px;" align="center"><?php echo $count; ?></td>
					<td><?php echo $key['circle_name']; ?></td>
					<td style="text-align:center"><?php echo $total_school[0]['total'] ; ?></td>
					<td style="width: 60px;" align="center"><?php echo $key['finalized'];  ?></td>
                    <!--<td style="width: 60px;" align="center"><?php echo $key['not_finalized']; ?></td>-->
                    <td style="width: 60px;" align="center">
                    <?php
                    if($key['status_flag']==2){ ?>
							<span style="color:#080;">FINALIZED</span>
					<?php }elseif($key['status_flag']==3){ ?>
                    		<span style="color:#F00;">LOCKED</span>
                    <?php } ?>
                    </td>
                    <td style="width: 60px;" align="center">
                    <?php 
					if($key['status_flag']==2){						
						if($total_school[0]['total']==$finalized_count[0]['finalized'] && $total_school[0]['total']!=0){ ?>
						<a class="unlock ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="click_me" style="text-decoration: none;" href="<?php echo $crypto->encode($key['circle_code'],3); ?>">Unlock</a> &nbsp;&nbsp;
					<?php } } ?>
                    <a class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="click_me" style="text-decoration: none;" href="page/intra_ehrms/dpsc/salary_school_list.php?circle_id<?php echo $crypto->encode($key['circle_id_pk'],3); ?>"><span class="ui-button-icon-primary ui-icon ui-icon-folder-open"></span><span class="ui-button-text">View School</span></a></td>
					
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
