<?php

//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
error_reporting(0);
//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';     
require_once '../../../all_function/fun_store/zp_ps_gp_function.php';

 $k=strtotime("first day of last month");
  $arr = date("Y-m-d",$k); 
  
  $month_arr=explode('-',$arr);
$salary_monthyear=$month_arr[0].$month_arr[1];
$db = new database();
$fun_store=new zp_ps_gp_class();
$party_code='007';
function ifms_error_description_generate($code)
{
	$db=new database();
	$err_desc_fetch=$db->fetch_table(" SELECT description FROM prd_ifms_response_code_master WHERE code='".$code."' ");
	return $err_desc_fetch[0]['description'];

}

?>





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
<script>
	$(document).ready(function(){
	$( "tr:odd" ).css( "background-color", "#CCE6FF" );
	$( "tr:even" ).css( "background-color", "#DDF7FF" );
	//$( ".modal fade in" ).css( "height", "1000px" );
	});
</script>

<?php

$crypto = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
function date_frmt_change($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") {
		return NULL;
	}
	else{
		return $newDate = date("Y-m-d", strtotime($original_date));
	}
}

	
$monthyear = $crypto->decode($_REQUEST['bill_report_year'],4).$crypto->decode($_REQUEST['bill_report_month'],4);
	
$db = new database();

$ps_profile_fetch=$db->fetch_table("
										SELECT ddo_code,treasury_code FROM psemp_ps_profile WHERE ps_id_fk='".$_SESSION['location']['ps_id']."'
								");
								
$pl_operator_code=$ps_profile_fetch[0]['ddo_code'];
$treasury_code=substr($ps_profile_fetch[0]['treasury_code'],0,3);


if(!ctype_alpha($treasury_code) || !ctype_digit($pl_operator_code)) 
{
	echo '<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Wrong PL Operator Code or Wrong Treasury Code. Please update Panchayat Samiti Profile.</strong></div>';
}
else if($treasury_code=='' || $pl_operator_code=='') 
{
	echo '<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Wrong PL Operator Code or Wrong Treasury Code. Please update Panchayat Samiti Profile.</strong></div>';
}
else
{

	$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
	$requisition_type=$requisition[0]['code'];
	
	$drn_checking = $db->fetch_table("
		SELECT * FROM prd_block_bill_details 
		WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
		AND bill_no = '".$_POST['bill']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."' AND status='1'
		");	
	
	if($drn_checking[0]['drn_number']=="")
	{
		$drn_number=$fun_store->drn_generation($party_code);
	}
	else {
		
		$sftp_details_fetch = $db->fetch_table(" SELECT sftp_benf_id_pk,sftp_benf_sending_status,sftp_benf_response_status,active_status FROM prd_sftp_benf_upload_response WHERE bill_id_fk='".$drn_checking[0]['block_bill_pk']."' AND active_status in ('1','2') ");
		
		$payment_failure_details=$db->fetch_table(" SELECT count(*) as total_count_fail
													FROM prd_sftp_benf_failure_details fail 
													INNER JOIN prd_sftp_benf_upload_response benf
													ON fail.sftp_benf_id_fk=benf.sftp_benf_id_pk
													WHERE benf.bill_id_fk='" . $drn_checking[0]['block_bill_pk'] . "' AND benf.active_status='1'
													AND fail.response_from='10' AND fail.active_status in ('1','2','3')");
		$drn_number=$drn_checking[0]['drn_number'];
	}
	//if($monthyear!=date('Ym'))
	//{
	//?>
	<!--	<div class="alert alert-danger" style="width: 28%;margin-left: 43%;text-align: center;"><strong>Please Select Current Month and Year</strong></div>-->
	<?php
	//}
	
	
		$check_ps_bill = $db->fetch_table("
		
		SELECT count(*) FROM prd_block_bill_details 
		WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."' AND status='1'
		");
		$check_ps_bill_exist = $db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
		AND bill_no = '".$_POST['bill']."'
		AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1'
		");
			
			
		$total_benf=$db->fetch_table("SELECT count(distinct(emp.emp_id_pk)) as total_benf
		FROM prd_location_master_panchayat_samiti ps     
		INNER JOIN prd_employee_master emp on ps.ps_id_pk=emp.ps_id_fk
		LEFT JOIN prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk)
		WHERE 
		trim(sal.salary_monthyear)='".date('Ym')."'
		AND emp.emp_status in('1','9') 
		AND sal.delete_status='1' 
		AND sal.status_flag='3'
		AND sal.is_saved='1'
		AND sal.ps_id_fk='".$_SESSION['location']['ps_id']."' 
		AND sal.requisition_type='".$requisition_type."'
		");
		
		//$encode_drn=$crypto->encode($drn_checking[0]['drn_number'],4); 29-10-2018
		$encode_drn=$crypto->encode($drn_number,4);
		$encode_requsition=$crypto->encode($requisition_type,4);
	if($check_ps_bill[0]['count'] == 0)
	{
			
		pg_query("begin");
	
		$prd_monthly_salary_archive_final = $db->insert("
					INSERT INTO prd_monthly_salary_archive_final
						(
						
											  slno ,
											  ps_id_fk,
											  empcd,
											  bankname,
											  accountno ,
											  basic ,
											  da,
											  hra,
											  ma ,
											  pf_loan,
											  p_tax ,
											  i_tax ,
											  net ,
											  bank_ifsc ,
											  sal_source,
											  spl_pay ,
											  pf_deduct ,
											  code,
											  spl_alo,
											  status_flag,
											  salary_monthyear,
											  category_id,
											  block_code,
											  emp_id_fk,
											  pay_payband ,
											  tch_grade_pay,
											  hill_allowance,
											  gpf,
											  gross_salary ,
											  is_saved,
											  conv_allow,
											  overdrawn,
											  salary_type,
											  cause,
											  gsli,
											  delete_status,
											  consolidated_pay,
											  other_deduction_cause ,
											  other_deduction,
											  festival_loan ,
											  festival_loan_cause,
											  part_salary_cause,
											  no_salary_cause,
											  interim_relief,
											  lock_status,
											  archive_final_entry_time,
											  archive_final_entry_ip,
									  gp_id_fk,
											  requisition_type,
											  hra_deduction,
											  part_day,
											  emp_desig,
											emp_group,
											emp_pay_scale,
											emp_pay_band
								)
						SELECT 
							  slno,
							  ps_id_fk,
							  empcd,
							  bankname,
							  accountno ,
							  basic ,
							  da,
							  hra,
							  ma ,
							  pf_loan,
							  p_tax ,
							  i_tax ,
							  net ,
							  bank_ifsc ,
							  sal_source,
							  spl_pay ,
							  pf_deduct ,
							  code,
							  spl_alo,
							  status_flag,
							  salary_monthyear,
							  category_id,
							  block_code,
							  emp_id_fk,
							  pay_payband ,
							  tch_grade_pay,
							  hill_allowance,
							  gpf,
							  gross_salary ,
							  is_saved,
							  conv_allow,
							  overdrawn,
							  salary_type,
							  cause,
							  gsli,
							  delete_status,
							  consolidated_pay,
							  other_deduction_cause ,
							  other_deduction,
							  festival_loan ,
							  festival_loan_cause,
							  part_salary_cause,
							  no_salary_cause,
							  interim_relief,
							  lock_status,
							now(),
							'".$_SESSION['user_agent']['USER_IP']."',
								gp_id_fk,
							'".$requisition_type."',
								hra_deduction,
								part_day,
								emp_desig,
								emp_group,
								emp_pay_scale,
								emp_pay_band
						   FROM prd_employee_salary_save
						WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
						AND salary_monthyear ='".$monthyear."'
						AND status_flag = 3 and delete_status='1' and is_saved='1'
						AND requisition_type='".$requisition_type."'
			");
	
			
			
			 $prd_monthly_salary_archive_nonfinal=$db->insert("INSERT INTO prd_monthly_salary_archive_nonfinal(
							slno,
							entry_time,
							entry_ip_address,
							ps_id_fk,
							empcd,
							salary_monthyear,
							bankname,
							accountno,
							basic,
							da,
							interim_relief,
							hra,
							ma,
							cpf,
							pf_loan,
							p_tax ,
							i_tax,
							net,
							bank_ifsc,
							sal_source,
							spl_pay,
							pf_deduct,
							code,
							spl_alo,
							status_flag,
							delete_status,
							emp_id_fk,
							block_code,
							festival_loan,
							festival_loan_cause,
							consolidated_pay,
							conv_allow,
							hill_allowance,
							gpf,
							overdrawn,
							gsli,
							pay_payband,
							tch_grade_pay,
							gross_salary, 
							archive_nonfinal_entry_time,
							archive_nonfinal_entry_ip,
							requisition_type,
							gp_id_fk,
							hra_deduction,
							part_day
								)
						SELECT 
							slno,
							latestupdate_time,
							latestupdate_ip_address,
							ps_id_fk,
							empcd,
							salary_monthyear,
							bankname,
							accountno,
							basic,
							da,
							interim_relief,
							hra,
							ma,
							cpf,
							pf_loan,
							p_tax,
							i_tax,
							net,
							bank_ifsc,
							sal_source,
							spl_pay,
							pf_deduct,
							code,
							spl_alo,
							status_flag,
							delete_status,
							emp_id_fk,
							block_code,
							festival_loan,
							festival_loan_cause,
							consolidated_pay,
							conv_allow,
							hill_allowance,
							gpf,
							overdrawn,
							gsli,
							pay_payband,
							tch_grade_pay,
							gross_salary,
							now(),
							'".$_SESSION['user_agent']['USER_IP']."',
							'".$requisition_type."',
								gp_id_fk,
								hra_deduction,
								part_day
			from prd_employee_salary_save 
			where salary_monthyear='".date('Ym')."' AND ps_id_fk ='".$_SESSION['location']['ps_id']."'  AND requisition_type='".$requisition_type."' AND status_flag not in('3')");
		
	
			
			   $delete_save=$db->delete("DELETE FROM prd_employee_salary_save
				WHERE salary_monthyear='".$salary_monthyear."'
				   and ps_id_fk ='".$_SESSION['location']['ps_id']."'
					AND requisition_type='".$requisition_type."'
				"); 
				
				
				
				
		if($prd_monthly_salary_archive_nonfinal && $prd_monthly_salary_archive_final)
		{ 
		pg_query("commit");
		
	//$fetch=$db->fetch_table("SELECT now()"); 
	 //$drn_number=drn_generation();
	
	$insert_salary_bill = $db->insert("
												INSERT INTO prd_block_bill_details (
												bill_no,
												status,
												block_code,
												bill_entry_time,
												bill_update_time,
												ip_addres,
												salary_monthyear,
												ps_id_fk,
												requisition_type,
												drn_number
												)
												VALUES(
												'".$_POST['bill']."',
												'1',
												'0',
												'".date_frmt_change($_POST['bill_date'])."',
												now(),
												'".$_SESSION['user_agent']['USER_IP']."',
												'".$monthyear."',
												'".$_SESSION['location']['ps_id']."',
												'".$requisition_type."',
												'".$drn_number."'
												);
												"); 
			
			
			 
			
			 
			
			
			
			
			if($insert_salary_bill == TRUE )
			{
				
				//05_06_2018
				$get_emp_id_loan = $db->fetch_table("SELECT emp_id_fk, salary_type, festival_loan FROM prd_employee_salary_save 
												WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
												AND status_flag = '3' AND salary_monthyear = '".date('Ym')."'
												AND is_saved in ('1') AND delete_status in ('1')");			
												
				foreach($get_emp_id_loan as $emp_id_fk_loan)
				{
					if($emp_id_fk_loan['salary_type'] != '8' && $emp_id_fk_loan['festival_loan'] > 0)
					{
						$get_pre_data = $db->fetch_table("SELECT b.festival_advance_instalment_amount,b.festival_advance_instalment_last_amount,
														b.deduction_counter,b.festival_advance_instalment_no ,
														b.total_amt_given,
														a.festival_advance_total_amount,
														a.festival_advance_id_pk
														FROM prd_festival_advance_employee_details a
														INNER JOIN prd_festival_advance_entry_sal b
														ON a.festival_advance_id_pk=b.festival_advance_id_fk
														WHERE 
														a.festival_advance_status in ('5') AND a.emp_id_fk = '".$emp_id_fk_loan['emp_id_fk']."'  
														");
						$pre_total_amt_given = $get_pre_data[0]['total_amt_given'];
						$new_counter = $get_pre_data[0]['deduction_counter'] +1;
						$new_total_amt_given = $get_pre_data[0]['total_amt_given'] + $emp_id_fk_loan['festival_loan'];
														  
						$update_f_adv_data = $db->update("UPDATE prd_festival_advance_entry_sal 
														SET deduction_counter = '".$new_counter."', total_amt_given = '".$new_total_amt_given."'
														WHERE festival_advance_id_fk='".$get_pre_data[0]['festival_advance_id_pk']."'");	
						if(($get_pre_data[0]['festival_advance_total_amount'] == $new_total_amt_given) && ($get_pre_data[0]['festival_advance_instalment_no'] == $new_counter))
						{
							$deactivate_fa = $db->update("UPDATE prd_festival_advance_employee_details SET 
															festival_advance_status = '6' 
															WHERE festival_advance_id_pk='".$get_pre_data[0]['festival_advance_id_pk']."' 																												
															AND festival_advance_status='5'");	
						}
					}
				}
				
				
				//05_06_2018
			?>
				<div class="col-sm-12">
					<div class="emplist">
						<div class="school">
							<div class="table-responsive">
								<div class="form-group">
								   
									
									<table width="100%">
										<tr style="background-color: rgb(221, 247, 255);">
											<th colspan="6">
											<h5>BILLING STATUS [ DRN No:<?php echo $drn_number; ?> ]</h5>
											</th>
										</tr>
										<tr>
											<th scope="col" width="20%">SL NO</th>
											<th width="20%">BILL PARTICULAR</th>
											<th width="20%">SEND TO IFMS</th>
											<th width="20%">STATUS</th>
											<th width="20%">ACTION</th>
											
										</tr>
										<tr>
											<td width="20%">1.</td>
											<td width="20%" style="color:#6a4f4b;">Upload Bill Summary To IFMS</td>
											<td width="20%">
												<div class="link_p2">
												<?php if($drn_checking[0]['bill_sending_status']=='2' && $drn_checking[0]['response_code']=='0')
													{?>
														<a style="opacity:0.5;"><img src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png" class="img-responsive" style="width:50%; margin:0 auto; opacity:0.3;" /></a>
														 
													<?php }
													else
													{ ?>
														
							 <a id="send_bill_sum_id" style="display:inline-table;" onClick="value_pass('<?php echo $encode_requsition; ?>','<?php echo $encode_drn; ?>','<?php echo $monthyear; ?>');" > <img src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png"  class="img-responsive" style="width:85%; margin:0 auto; cursor:pointer" /></a>
							 
														<a id="send_bill_sum_id_disable"  style="opacity:0.5;display:none;">  <img src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png" class="img-responsive" style="width:50%; margin:0 auto; opacity:0.3;" /></a>
														
													<?php } ?>
												</div>
											</td>
											<td id="status_bill_sum_send" <?php  if(ifms_error_description_generate($drn_checking[0]['response_code'])=='Success'){ ?> style="color:#00b248;" <?php }else{ ?> style="color:#c43e00;" <?php } ?>><?php echo ifms_error_description_generate($drn_checking[0]['response_code']); ?> </td>
											<td><span id="edit_action_first_row" style="display:none;"></span>
											<?php if($drn_checking[0]['bill_sending_status']=='2' && $drn_checking[0]['response_code']!='0')
											{?>
												<span onClick="bill_details_edit('<?php echo $encode_drn; ?>')">
													<img style="padding-bottom: 10px;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/>
												</span>
											<?php } ?>
											</td>
										   
										</tr>
										<?php //if($total_benf[0]['total_benf']>1)
										//{ ?>
										<tr>
											<td width="20%">2. </td>
											<td width="20%" style="color:#6a4f4b;">Upload Beneficiary file To IFMS</td>
											<td width="20%">
												<div class="link_p2">
								<?php if ($drn_checking[0]['response_code']!='0') {
								?>
									<img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5;">
									<a id="send_benf" style="display:none;"  onClick="value_pass_sftp('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $monthyear; ?>');"><img src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer;"></a>
								<?php
								} 
								//else if (count($sftp_details_fetch) == '0')
								else if (count($sftp_details_fetch) == '0' || ($sftp_details_fetch[0]['sftp_benf_response_status']=='6' || $sftp_details_fetch[0]['active_status']=='2'))  
								{
								?>
									<img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5; display:none;">
									<a id="send_benf"  onClick="value_pass_sftp('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $monthyear; ?>');"><img src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a>
								<?php
								} else {
								?>
									<img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5; ">
									<? } ?>
								</div>
								</td>
								 <td id="status1" width="20%" <?php if($sftp_details_fetch[0]['sftp_benf_response_status']=='6' || $sftp_details_fetch[0]['sftp_benf_response_status'] == '7'){ ?> style="color:#ff3d00;" <?php }else{ ?>style="color:#00b248;" <?php } ?>>
								<?php
												if($sftp_details_fetch[0]['sftp_benf_response_status'] == '')
												{
													if ($sftp_details_fetch[0]['sftp_benf_sending_status'] == '3') 
													{
														echo "Upload Confirmation Pending";
													} 
													else if ($sftp_details_fetch[0]['sftp_benf_sending_status'] == '4') 
													{
														echo "File Uploaded Successfully";
													}
												}
												else if($sftp_details_fetch[0]['sftp_benf_response_status']=='5' || $sftp_details_fetch[0]['sftp_benf_response_status']=='8')
												{
													echo "IFMS Reference Number Generated";
												}
												else if($sftp_details_fetch[0]['sftp_benf_response_status']=='6')
												{
													echo "Wrong Format has been returned";
												}
												else if($sftp_details_fetch[0]['sftp_benf_response_status'] == '7')
												{
													echo "Wrong Data has been returned";
												}
											?>
								</td>
										  <td width="20%">
									<div>
										<?php if ($sftp_details_fetch[0]['sftp_benf_sending_status'] == '3') 
										{?>
											<a onClick="done_file_check('<?php echo $crypto->encode($sftp_details_fetch[0]['sftp_benf_id_pk'],4); ?>');"><i style="font-size:24px;color:#266eac;cursor:pointer;" class="fa" id="refresh_icon_static">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;" id="refresh_icon_dynamic"></i></a> &nbsp;&nbsp;&nbsp;
										<?php 
										}
										else if($sftp_details_fetch[0]['sftp_benf_sending_status'] == '4' && $sftp_details_fetch[0]['sftp_benf_response_status'] == '') 
										{
										?>
											<a onClick="benf_status_check();"><i style="font-size:24px;color:#266eac;cursor:pointer;" class="fa" id="refresh_icon_static">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;" id="refresh_icon_dynamic"></i></a> &nbsp;&nbsp;&nbsp;
										<?php }
										else
										{ ?>
											<i style="font-size:24px;color:#266eac; opacity:0.5;" class="fa">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;"></i>
										<?php 
										}
										if($sftp_details_fetch[0]['sftp_benf_response_status']=='7')
										{ 
										?>
										<span id="edit_action_first_row2" onClick="sftp_edit('<?php echo $crypto->encode($sftp_details_fetch[0]['sftp_benf_id_pk'],4); ?>');"><img style="padding-bottom: 10px;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>
										<?php 
										}
										else
										{?>
											<span id="edit_action_first_row2" ><img style="padding-bottom: 10px; opacity:0.5;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>
										<?php } ?>
									
									
									</div>
								
								
								
								</td>
							</tr>
			<?php //} ?>
									
											<td width="20%">3. </td>
											<td width="20%" style="color:#6a4f4b;">Bill Status View</td>
											<td width="20%"><div class="link_p3">
											<a id="view_status"  onClick="value_pass_view('<?php echo $encode_requsition; ?>','<?php echo $encode_drn; ?>');"> <img src="<?php echo $config['base_url']; ?>themes/default/image/status_btn.png" class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a></div></td>
											<td id="view_bill_status" width="20%" > </td>
											<td></td>
										   
										</tr>
									<tr style="background-color: #DCEDC8;">
							<td width="20%" style="background-color: #DCEDC8;color: #FF3D00;">4. </td>
	
							<td width="20%" style="background-color: #DCEDC8; color: #FF3D00;"> payment status view</td>
							<td width="20%" style="background-color: #DCEDC8;"><div class="btn btn-default" style=" float:center; cursor:default; color:#1A237E;width:105px;"> &nbsp;<a href="<?= $config['base_url']?>page/all_moduls/payment_details_view/payment_details.php?monthyear=<?php echo $crypto->encode($monthyear,4);?>&requisition_type=<?php echo $crypto->encode($requisition_type,4); ?>"><i class="fa fa-money fa-2x reason_view" aria-hidden="true" style="color:#33691E;cursor:pointer;" ></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a style="cursor:pointer;"><i class="fa fa-street-view fa-2x eason_view" aria-hidden="true" style="color:#33691E;"></i></a>
							</div></td>
							 <td width="20%" style="background-color: #DCEDC8;color: #FF3D00;" id="status_payment">
							 <?php	if($sftp_details_fetch[0]['sftp_benf_response_status'] == '8')
												{
													echo "Payment File Generated";
												}
										?>
							 </td>
							  <td width="20%" style="background-color: #DCEDC8;">
								<div>
										<?php if($sftp_details_fetch[0]['sftp_benf_response_status'] == '5' || ($sftp_details_fetch[0]['sftp_benf_response_status'] != '8' && $sftp_details_fetch[0]['sftp_benf_response_status'] != '7'))
										{
										?>
											<a onClick="benf_status_check();"><i style="font-size:24px;color:#266eac;cursor:pointer;" class="fa" id="refresh_icon_static">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;" id="refresh_icon_dynamic"></i></a> &nbsp;&nbsp;&nbsp;
										<?php }
										else
										{ ?>
											<i style="font-size:24px;color:#266eac; opacity:0.5;" class="fa">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;"></i> &nbsp;&nbsp;&nbsp;
										<?php 
										}
										if($payment_failure_details[0]['total_count_fail']!='0')
										{ 
										?>
											<!--<span id="edit_action_first_row2" onClick="payment_edit('<?php echo $crypto->encode($sftp_details_fetch[0]['sftp_benf_id_pk'],4); ?>');"><img style="padding-bottom: 10px;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>-->
										<?php 
										}
										else
										{?>
											<!--<span id="edit_action_first_row2" ><img style="padding-bottom: 10px; opacity:0.5;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>-->
										<?php } ?>
									
									</div>
							
							</td>
						</tr>
						<!--<tr>
							<td width="20%" style="background-color: #E0E0E0;">5. </td>
	
	
							<td width="20%" style="background-color: #E0E0E0;">Failed Transaction Correction </td>
							<td width="20%" style="background-color: #E0E0E0;">  <a onClick="value_pass('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_btn_2.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer" /></a> </td>
							<td width="20%" style="background-color: #E0E0E0;"> </td>
							<td width="20%" style="background-color: #E0E0E0;"></td>
						</tr>-->
						</table>
									<div id="emplist"></div>
								</div>
							</div> 
						</div>
					</div> 
				</div>
			  <div class="onclick_loader" id="page-load"><img src="<?php echo $config['base_url']; ?>themes/default/image/page_loader.gif" class="img-responsive onclick_load"/></div>	
			<?php
			
			} 
			else 
			{ ?>
				<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
			<?php
			}
			
		}
		else 
		{
		pg_query("rollback");
		?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
		<?php
		}
			
	}  
	else 
	{ ?>
		
		
		<?php if($check_ps_bill_exist[0]['count'] == 1  && $drn_checking)
		{
		?>
			<!--<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file/personal.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personal Details</a>
			</div>
			</div>
			<br/><br />
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file/salarybill.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summery</a>
			</div>
			</div>
			<br /><br />
			<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
			<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_ps/eo/text_file/xml_file.php?mo=<?php echo date('m'); ?>&ye=<?php echo date('Y'); ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
			</div>-->
			
			<div class="col-sm-12">
					<div class="emplist">
						<div class="school">
							<div class="table-responsive">
								<div class="form-group">
								   
									<table width="100%">
										<tr style="background-color: rgb(221, 247, 255);">
											<th colspan="6">
											<h5>BILLING STATUS [ DRN No:<?php echo $drn_number; ?> ]</h5>
											</th>
										</tr>
										<tr>
											<th scope="col" width="20%">SL NO</th>
											<th width="20%">BILL PARTICULAR</th>
											<th width="20%">SEND TO IFMS</th>
											<th width="20%">STATUS</th>
											<th width="20%">ACTION</th>
											
										</tr>
										<tr>
											<td width="20%">1.</td>
											<td width="20%" style="color:#6a4f4b;">Upload Bill Summary To IFMS</td>
											<td width="20%">
												<div class="link_p2">
												<?php if($drn_checking[0]['bill_sending_status']=='2' && $drn_checking[0]['response_code']=='0')
													{?>
														<a style="opacity:0.5;"><img src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png" class="img-responsive" style="width:50%; margin:0 auto; opacity:0.3;" /></a>
														 
													<?php }
													else
													{ ?>
														
							 <a id="send_bill_sum_id" style="display:inline-table;" onClick="value_pass('<?php echo $encode_requsition; ?>','<?php echo $encode_drn; ?>','<?php echo $monthyear; ?>');" > <img src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png"  class="img-responsive" style="width:85%; margin:0 auto; cursor:pointer" /></a>
							 
														<a id="send_bill_sum_id_disable"  style="opacity:0.5;display:none;">  <img src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png" class="img-responsive" style="width:50%; margin:0 auto; opacity:0.3;" /></a>
														
													<?php } ?>
												</div>
											</td>
											<td id="status_bill_sum_send" <?php  if(ifms_error_description_generate($drn_checking[0]['response_code'])=='Success'){ ?> style="color:#00b248;" <?php }else{ ?> style="color:#c43e00;" <?php } ?>><?php echo ifms_error_description_generate($drn_checking[0]['response_code']); ?> </td>
											<td><span id="edit_action_first_row" style="display:none;"></span>
											<?php if($drn_checking[0]['bill_sending_status']=='2' && $drn_checking[0]['response_code']!='0')
											{?>
												<span onClick="bill_details_edit('<?php echo $encode_drn; ?>')">
													<img style="padding-bottom: 10px;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/>
												</span>
											<?php } ?>
											</td>
										   
										</tr>
										<?php //if($total_benf[0]['total_benf']>1)
										//{ ?>
										<tr>
											<td width="20%">2. </td>
											<td width="20%" style="color:#6a4f4b;">Upload Beneficiary file To IFMS</td>
											<td width="20%">
												<div class="link_p2">
								<?php if ($drn_checking[0]['response_code']!='0') {
								?>
									<img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5;">
									<a id="send_benf" style="display:none;"  onClick="value_pass_sftp('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $monthyear; ?>');"><img src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer;"></a>
								<?php
								} 
								//else if (count($sftp_details_fetch) == '0')
								else if (count($sftp_details_fetch) == '0' || ($sftp_details_fetch[0]['sftp_benf_response_status']=='6' || $sftp_details_fetch[0]['active_status']=='2'))  
								{
								?>
									<img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5; display:none;">
									<a id="send_benf"  onClick="value_pass_sftp('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $monthyear; ?>');"><img src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a>
								<?php
								} else {
								?>
									<img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5; ">
									<? } ?>
								</div>
								</td>
								 <td id="status1" width="20%" <?php if($sftp_details_fetch[0]['sftp_benf_response_status']=='6' || $sftp_details_fetch[0]['sftp_benf_response_status'] == '7'){ ?> style="color:#ff3d00;" <?php }else{ ?>style="color:#00b248;" <?php } ?>>
								<?php
												if($sftp_details_fetch[0]['sftp_benf_response_status'] == '')
												{
													if ($sftp_details_fetch[0]['sftp_benf_sending_status'] == '3') 
													{
														echo "Upload Confirmation Pending";
													} 
													else if ($sftp_details_fetch[0]['sftp_benf_sending_status'] == '4') 
													{
														echo "File Uploaded Successfully";
													}
												}
												else if($sftp_details_fetch[0]['sftp_benf_response_status']=='5' || $sftp_details_fetch[0]['sftp_benf_response_status']=='8')
												{
													echo "IFMS Reference Number Generated";
												}
												else if($sftp_details_fetch[0]['sftp_benf_response_status']=='6')
												{
													echo "Wrong Format has been returned";
												}
												else if($sftp_details_fetch[0]['sftp_benf_response_status'] == '7')
												{
													echo "Wrong Data has been returned";
												}
											?>
								</td>
										  <td width="20%">
									<div>
										<?php if ($sftp_details_fetch[0]['sftp_benf_sending_status'] == '3') 
										{?>
											<a onClick="done_file_check('<?php echo $crypto->encode($sftp_details_fetch[0]['sftp_benf_id_pk'],4); ?>');"><i style="font-size:24px;color:#266eac;cursor:pointer;" class="fa" id="refresh_icon_static">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;" id="refresh_icon_dynamic"></i></a> &nbsp;&nbsp;&nbsp;
										<?php 
										}
										else if($sftp_details_fetch[0]['sftp_benf_sending_status'] == '4' && $sftp_details_fetch[0]['sftp_benf_response_status'] == '') 
										{
										?>
											<a onClick="benf_status_check();"><i style="font-size:24px;color:#266eac;cursor:pointer;" class="fa" id="refresh_icon_static">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;" id="refresh_icon_dynamic"></i></a> &nbsp;&nbsp;&nbsp;
										<?php }
										else
										{ ?>
											<i style="font-size:24px;color:#266eac; opacity:0.5;" class="fa">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;"></i>
										<?php 
										}
										if($sftp_details_fetch[0]['sftp_benf_response_status']=='7')
										{ 
										?>
										<span id="edit_action_first_row2" onClick="sftp_edit('<?php echo $crypto->encode($sftp_details_fetch[0]['sftp_benf_id_pk'],4); ?>');"><img style="padding-bottom: 10px;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>
										<?php 
										}
										else
										{?>
											<span id="edit_action_first_row2" ><img style="padding-bottom: 10px; opacity:0.5;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>
										<?php } ?>
									
									
									</div>
								
								
								
								</td>
							</tr>
			<?php //} ?>
									
											<td width="20%">3. </td>
											<td width="20%" style="color:#6a4f4b;">Bill Status View</td>
											<td width="20%"><div class="link_p3">
											<a id="view_status"  onClick="value_pass_view('<?php echo $encode_requsition; ?>','<?php echo $encode_drn; ?>');"> <img src="<?php echo $config['base_url']; ?>themes/default/image/status_btn.png" class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a></div></td>
											<td id="view_bill_status" width="20%"> </td>
											<td></td>
										   
										</tr>
									<tr style="background-color: #DCEDC8;">
							<td width="20%" style="background-color: #DCEDC8;">4. </td>
	
							<td width="20%" style="background-color: #DCEDC8; color:#6a4f4b;"> payment status view</td>
							<td width="20%" style="background-color: #DCEDC8;"><div class="btn btn-default" style=" float:center; cursor:default; color:#1A237E;width:105px;"> &nbsp;<a href="<?= $config['base_url']?>page/all_moduls/payment_details_view/payment_details.php?monthyear=<?php echo $crypto->encode($monthyear,4);?>&requisition_type=<?php echo $crypto->encode($requisition_type,4); ?>"><i class="fa fa-money fa-2x reason_view" aria-hidden="true" style="color:#33691E;cursor:pointer;" ></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a style="cursor:pointer;"><i class="fa fa-street-view fa-2x eason_view" aria-hidden="true" style="color:#33691E;"></i></a>
							</div></td>
							 <td width="20%" style="background-color: #DCEDC8;color: #FF3D00;" id="status_payment">
							 <?php	if($sftp_details_fetch[0]['sftp_benf_response_status'] == '8')
												{
													echo "Payment File Generated";
												}
										?>
							 </td>
							  <td width="20%" style="background-color: #DCEDC8;">
								<div>
										<?php if($sftp_details_fetch[0]['sftp_benf_response_status'] == '5' || ($sftp_details_fetch[0]['sftp_benf_response_status'] != '8' && $sftp_details_fetch[0]['sftp_benf_response_status'] != '7'))
										{
										?>
											<a onClick="benf_status_check();"><i style="font-size:24px;color:#266eac;cursor:pointer;" class="fa" id="refresh_icon_static">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;" id="refresh_icon_dynamic"></i></a> &nbsp;&nbsp;&nbsp;
										<?php }
										else
										{ ?>
											<i style="font-size:24px;color:#266eac; opacity:0.5;" class="fa">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;"></i> &nbsp;&nbsp;&nbsp;
										<?php 
										}
										if($payment_failure_details[0]['total_count_fail']!='0')
										{ 
										?>
											<!--<span id="edit_action_first_row2" onClick="payment_edit('<?php echo $crypto->encode($sftp_details_fetch[0]['sftp_benf_id_pk'],4); ?>');"><img style="padding-bottom: 10px;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>-->
										<?php 
										}
										else
										{?>
											<!--<span id="edit_action_first_row2" ><img style="padding-bottom: 10px; opacity:0.5;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>-->
										<?php } ?>
									
									</div>
							
							</td>
						</tr>
						<!--<tr>
							<td width="20%" style="background-color: #E0E0E0;">5. </td>
	
	
							<td width="20%" style="background-color: #E0E0E0;">Failed Transaction Correction </td>
							<td width="20%" style="background-color: #E0E0E0;">  <a onClick="value_pass('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_btn_2.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer" /></a> </td>
							<td width="20%" style="background-color: #E0E0E0;"> </td>
							<td width="20%" style="background-color: #E0E0E0;"></td>
						</tr>-->
						</table>
						<div id="emplist"></div>
					</div>
					</div> 
				</div>
				</div> 
			</div>
			  <div class="onclick_loader" id="page-load"><img src="<?php echo $config['base_url']; ?>themes/default/image/page_loader.gif" class="img-responsive onclick_load"/></div>	
			
		<?php 
		}
		else
		{
		?>
			<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is allready inserted for this month</strong></div>
		<?php
		}
	}
}

?>
<script>
//**************************************************************


 $(document).ready( function() {
//     $('#page-load').show();
//        $('#page-load').delay(1000).fadeOut();
//      });
    });


function bill_details_edit(drn)
{
	$.post('<?= $config['base_url'] ?>page/api/ifms/ps/ajax_bill_details_edit.php?drn_no='+drn,function(data){
		//alert(data);
		//location.reload();
		$('#bill_edit').modal('show');
		$('#bill_div_id').html(data);
		/*$( "#bill_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'dd-mm-yy' 
			});*/
		
	});
}


function value_pass(encode_requsition,drn_number,monthyear)
{
    
    	  $('#page-load').show();
        $('#page-load').delay(500).fadeOut();
    

	
	var requsition=encode_requsition;
	var drn_no=drn_number;
	var total_benf='<?php echo $total_benf[0]['total_benf']; ?>';
	//%%%_____%%%
	$.post('<?= $config['base_url'] ?>page/intra_ps/eo/text_file_ifms/xml_file.php?bill_type='+requsition+'&drn_no='+drn_no+'&monthyear='+monthyear,function(data){ 
	  // alert(data);
	    
	});
	//%%%_____%%%
$.post('<?= $config['base_url'] ?>page/api/ifms/ps/index.php?bill_type='+requsition+'&drn_no='+drn_no+'&monthyear='+monthyear,function(data){

		if(data.trim()=='Success')
		{		 $('#status_bill_sum_send').css('color', '#008e76');
				$('#status_bill_sum_send').text(data);
				$('#upload_image').hide();
				$('#send_benf').show();
				$('#send_bill_sum_id_disable').show();
				$('#send_bill_sum_id').hide();
				$('#edit_action_first_row').hide();
				$('#edit_action_first_row2').hide();
			if(total_benf<1)
			{
				$('#bill_status_row').show();
			}
			else
			{
				$('#bill_status_row').hide();
			}
		}
		else
		{	 $('#status_bill_sum_send').css('color', '#c62828');
			$('#status_bill_sum_send').text(data);
			$('#edit_action_first_row').show();
			$('#edit_action_first_row2').hide();
		}
		
	});
	//%%%_____%%%
}
//**************************************************************
function value_pass_sftp(encode_requsition, drn_number,monthyear)
        {
            $('#page-load').show();
            $('#page-load').delay(500).fadeOut();
            var requsition = encode_requsition;
            var drn_no = drn_number;
            $.post('<?= $config['base_url'] ?>page/api/ifms/ps/sftp_index.php?bill_type=' + requsition + '&drn_no=' + drn_no+'&monthyear='+monthyear, function (data) {
            // alert(data);
			if (data.trim() == '3')
			{
				$("#status1").html("File has been sent already. Do not send again.");
			}
			if (data.trim() == '2')
			{
				$("#status1").html("SFTP Connection Fails");
			}
			if (data.trim() == '0')
			{
				$("#status1").html("File Uploading Fails");
			}
			if (data.trim() == '1')
			{
			
				$("#status1").html("Upload Confirmation Pending");
				$('#upload_image').show();
				$('#send_benf').hide();
			}

            });

        }
//**************************************************************
function value_pass_view(encode_requsition,drn_number)
{
    $('#page-load').show();
        $('#page-load').delay(500).fadeOut();
	var requsition=encode_requsition;
	var drn_no=drn_number;
	$.post('<?= $config['base_url'] ?>page/api/ifms/ps/bill_status_check.php?bill_type='+requsition+'&drn_no='+drn_no,function(data){
		//alert(data);
		if(data)
		{	$('#view_bill_status').css('color', '#ff8f00');
			$('#view_bill_status').text(data);
		}
	});	
}
//**************************************************************
/*function benf_status_check(encode_requsition,drn_number)
{
    $('#page-load').show();
        $('#page-load').delay(500).fadeOut();
	var requsition=encode_requsition;
	var drn_no=drn_number;
	$('#refresh_icon_static').hide();
	$('#refresh_icon_dynamic').show();
	$.post('<?= $config['base_url'] ?>page/api/ifms/ps/prd_ifms_cron_job.php?bill_type='+requsition+'&drn_no='+drn_no,function(data){
		//alert(data);
		if(data=='99')
		{
			$('#refresh_icon_static').show();
			$('#refresh_icon_dynamic').hide();
		}
	});	
}*/


function benf_status_check()
{
	//alert(11);
	var user='ps';
	$('#page-load').show();
	$('#page-load').delay(500).fadeOut();
	$('#refresh_icon_static').hide();
	$('#refresh_icon_dynamic').show();
	$.post('<?= $config['base_url'] ?>page/api/ifms/sftp_cron/prd_ifms_cron_job.php?user='+user, function (data) {
		//alert(data);
		location.reload();
	//$('#status_payment').html(data); 
	});
}
//**************************************************************
function done_file_check()
{   var user='ps';
	$('#page-load').show();
	$('#page-load').delay(500).fadeOut();
	$('#refresh_icon_static').hide();
	$('#refresh_icon_dynamic').show();
	$.post('<?= $config['base_url'] ?>page/api/ifms/sftp_cron/dot_done_ifms_cron_job.php?user='+user, function (data) {
	location.reload();
	//$('#status1').html(data); 
	});
}
//**************************************************************

function sftp_edit(benf_id)
{
	$('#page-load').show();
	$('#page-load').delay(500).fadeOut();
	$('#myModal').modal('show');
	$.post('<?= $config['base_url'] ?>page/api/ifms/ps/sftp_wrong_data_edit.php?benf_id=' + benf_id, function (data) {
	//alert(data);
		$("#mbody").html(data);
		$("#empshow").html(data);
		$("#success").hide();
		$("#failed").hide();
	
	});

}
//**************************************************************

function payment_edit(benf_id)
	{
		$('#page-load').show();
		$('#page-load').delay(500).fadeOut();
		$('#myModal').modal('show');
		$.post('<?= $config['base_url'] ?>page/api/ifms/ps/epayment_wrong_data_edit.php?benf_id=' + benf_id, function (data) {
			$("#mbody").html(data);
			$("#empshow").html(data);
			
				/*if($('#msg').html('<div class="alert alert-success" style="text-align:center"><strong> Employee updation has been successfully finalized... </strong></div>'););
			{
				$('#edit_action_first_row4').show();
			}
			else
			{
				$('#edit_action_first_row3').hide();
			}*/
			
		});
	}

//**************************************************************
</script>
<!----------------------------------------------------------------- Employee MODAL Start---------------------------------->

<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
	<div class="modal-content" style="width: 1380px;margin-left: -25.5%;">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onClick="location.reload();">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">Employee Details</h4>
	    </div>
	    <div class="modal-body" > 
		<div id="mbody" style="height:auto;"> 
		</div>
	    </div>
	    <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal" onClick="location.reload();">Close</button>  
	    </div>
	</div>
    </div>
</div>
<!----------------------------------------------------------------- Employee MODAL End---------------------------------->
<!-----------------------------------------------------------------Bank Details MODAL Start---------------------------------->


<div class="modal fade" id="indi_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Bank Details</h4>
            </div>
            <div class="modal-body" style="background-color:#e0decb;"> 
                <div class="empshow" style="height:300px;"> 
                </div>
            </div>
            <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div> 
<!-----------------------------------------------------------------Bank Details MODAL End---------------------------------->

<!------------------------------------------------------------------Bill Edition Modal Start-------------------------------------------------->
<div class="modal fade" id="bill_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Bill Details</h4>
            </div>
            <div class="modal-body" style="background-color:#e0decb;"> 
                <div id="bill_div_id" style="height:230px;"> 
                </div>
            </div>
            <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div> 

<!------------------------------------------------------------------Bill Edition Modal Start-------------------------------------------------->

<!--------------------------------------------------- Bill Submit Confirmation Modal Start-------------------------------------------------------->
    
    <div class="modal fade bs-example-modal-sm" id="submit_confirmation" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            <div class="modal-body"> 
            	<p class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> Are you sure to submit this form???</strong></p> 
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-success" onClick="bill_submit();">YES</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
                </div>
            </div>
            </div>
        </div>
    </div>
    
    
   <!--------------------------------------------------- Bill Submit Confirmation Modal End-------------------------------------------------------->
