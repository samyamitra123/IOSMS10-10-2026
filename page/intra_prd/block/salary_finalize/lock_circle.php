<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
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
	|| isset($_SESSION['blocked_privilege']['0505'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//----------------------------------------------------------------------------------------------------------------------------
//$schcd = $crypto->decode($_GET['id'],3);
//----------------------------------------------------------------------------------------------------------------------------
$db = new database();

//school finclized data
$update = $db->update("
		UPDATE ehrms_teacher_salary_save_primary
		SET status_flag =3
		WHERE status_flag =2 AND
		dpsc_code = '".$_SESSION['user_info']['stake_user']."' AND category_id=1
		
		
");

if($update){
			$query = $db->fetch_table("select now() now") ;
			$now = $query[0]['now'] ;
			
			
			//for insert into ehrms_monthly_salary_archive_final
			$fetch_table1=$db->fetch_table("select max(archive_final_pk) max from ehrms_monthly_salary_archive_final");
			$max=$fetch_table1[0]['max'];
			$archive_not_final=$db->fetch_table("INSERT INTO ehrms_monthly_salary_archive_final(
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
       overdrawn, salary_type, cause, circle_code, part_day from ehrms_teacher_salary_save_primary where dpsc_code='".$_SESSION['user_info']['stake_user']."' ");
			  
			  /*echo "INSERT INTO ehrms_monthly_salary_archive_final(
						school_code, teacher_code, salary_month_year, 
						bank_name, account_no, bank_ifsc, category_id, 
					   basic, da, hra, ma, cpf, pf_loan, ptax, itax, special_pay, pf_deduct, status) 
						select schcd,tchcd,salary_monthyear,bankname,accountno,bank_ifsc,category_id,basic,da,hra,ma,cpf,pf_loan,p_tax,i_tax
			  ,spl_pay,pf_deduct,status_flag from ehrms_teacher_salary_save_primary where dpsc_code='".$_SESSION['user_info']['stake_user']."'";*/
			  
			  $fetch_table=$db->fetch_table("select max(archive_final_pk) max from ehrms_monthly_salary_archive_final");
			  $max1=$fetch_table[0]['max'];
			  //echo $max." max1 ".$max1 ; exit ;
			  $query1 = $db->fetch_table("select now() now") ;
			  $now1 = $query1[0]['now'] ;
			  if(count($max)==0){
			  	$max=0;
			  }
			  $archive_not_final_upd=$db->update("UPDATE ehrms_monthly_salary_archive_final
			  SET entry_time='".$now1."', entry_ip='".$_SESSION['user_agent']['USER_IP']."' where archive_final_pk > $max and archive_final_pk <= $max1");
			  /*echo "UPDATE ehrms_monthly_salary_archive_final SET entry_time='".$now1."', entry_ip='".$_SESSION['user_agent']['USER_IP']."'
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
															created_by_stake
															
														)
										VALUES 			(
															'".$_SESSION['user_agent']['USER_IP']."',
															'".$now."',
															'".$_SESSION['user_agent']['BROWSER']."',
															'".$_SESSION['user_agent']['OS']."',
															'3',
															'1',
															'".$_SESSION['user_info']['stake_user']."',
															'".$_SESSION['user_info']['stake_level']."'
															
														)
							
									");
}
						
require_once '../../../block_list.php';
$arr = $db->fetch_table("select cm.circle_id_pk,cm.circle_name,cm.circle_code,sm1.status_flag, 
count(distinct(sm1.schcd)) as finalized
from (select circle_id_pk,circle_code,circle_name from ehrms_dise_location_master_circle where circle_code like '".$_SESSION['user_info']['stake_user']."%') as cm 
left join ehrms_teacher_salary_save_primary sm1 on cm.circle_code=sm1.circle_code and sm1.status_flag=3 
inner join ehrms_dise_teacher_primary as tch on tch.schcd = sm1.schcd
group by cm.circle_id_pk,cm.circle_name,cm.circle_code,sm1.status_flag order by cm.circle_name");
//school not finalized data


//----------------------------------------------------------------------------------------------------------------------------
    //echo "<pre>";
	//print_r($_REQUEST);
	
	
?>
   <script>
		  $(document).ready(function() {
		  	
		  	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
		  	$( "tr:even" ).css( "background-color", "#DDF7FF" );	
		    //Button
		      //finalize button
		       $( "a.finalize" ).button({
					icons: {
						primary: "ui-icon-folder-open"
					}
				}).click(function( event ) {
					var link = $(this).attr('href');
					$(document).ajaxStart(function() {
						$('.loading').html('<h3>Loading...<img height="20" src="themes/default/image/preloader.gif" /></h3>');
					});
					$(document).ajaxComplete(function() {
						
						//$("#saving").fadeOut();
						$('.loading').text('');
						//$("#saving").css("display", "none");

					});
					    	//alert(link);
					 	$('.tbl_border').load('page/intra_ehrms/dpsc/salary_finalize/salary_finalize.php?id=' + link, function(responseTxt, statusTxt, xhr) {
						if (statusTxt == "error") {
							//alert("Error: "+xhr.status+": "+xhr.statusText);
							$('.loading').text('Loading error');
							//$("#saving").css("display", "block");
						} else {
							$('.loading').text('');
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
			   $('.tbl_border').load('page/intra_ehrms/dpsc/salary_finalize/lock_schools.php', function(responseTxt, statusTxt, xhr) {
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
			
			//Redirect page
			var myVar=setInterval(function(){redirect_page()},2000);
		   function redirect_page(){
			   window.location.replace("<?php echo $config['base_url'] . 'page/login.php'; ?>");
			  }
		  });
	</script>
	<?php //echo $_GET['id']; ?>
<div class="mymeny">
				<a class="not_final" href="#">Show Not Finalized Circle</a>
			</div>
		<div class="countSch">Locked from circle ( <b>Total <?php $count = count($arr); echo $count; ?> circle<?php if($count > 1 ){ echo 's';} ?> Locked</b> )</div>

		<?php if(count($arr)){ ?>	
			<table width="100%">
				<tr>
					<th>SL NO.</th>
					<th>CIRCLE NAME</th>
					<th>STATUS</th>
					<!--<th>ACTION</th>-->
				</tr>
				<?php $count = 1; foreach ($arr as $key) { ?>
				<tr>
					<td style="width: 60px;"><?php echo $count; ?></td>
					<td><?php echo $key['circle_name']; ?></td>
					<td align="center">
					<?php
						if($key['status_flag'] == 2){
							echo "<div style='color:#080;'>FINALIZED</div>";
						} 
                        if($key['status_flag'] == 3){
							echo "<div style='color:#F00;'>LOCKED</div>";
						}
					?>
					</td>
					<!--<td style="width: 80px;" align="center">
						<?php if($key['status_flag'] == 2){ ?>
						<a class="finalize" href="<?php //echo $crypto->encode($key['schcd'],3) ?>">FINALIZE</a>
						<?php } ?>
					</td>-->
				</tr>
				<?php $count += 1; } ?>
			</table>
			<?php } else { echo "No data found" ;} ?>