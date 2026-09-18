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

//require '../../page_visite.php';


$k = strtotime("first day of last month");
$arr = date("Y-m-d", $k);

/* $month_ini = new DateTime("first day of last month");
  $arr=$month_ini->format('Y-m-d'); // 2012-02-01
  echo $arr; */

$month_arr = explode('-', $arr);
$salary_monthyear = $month_arr[0] . $month_arr[1];
$db = new database();
$fun_store = new zp_ps_gp_class();
$party_code = '008';

function ifms_error_description_generate($code) 
{
    $db = new database();
    $err_desc_fetch = $db->fetch_table(" SELECT description FROM prd_ifms_response_code_master WHERE code='" . $code . "' ");
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
    $(document).ready(function () {
        $("tr:odd").css("background-color", "#CCE6FF");
        $("tr:even").css("background-color", "#DDF7FF");
        //$( ".modal fade in" ).css( "height", "1000px" );
    });
</script>

<?php

$crypto = new cryptography();

if (
	!isset($_SESSION['user_info']['stake_user']) || !isset($_SESSION['user_info']['stake_level']) || !isset($_SESSION['user_info']['flag'])
) {
    header('Location: ' . $config['base_url'] . "page/login.php");
    exit;
}

function date_frmt_change($original_date) 
{
    if ($original_date == "0001-01-01" || $original_date == '1970-01-01' || $original_date == NULL || $original_date == "") 
	{
		return NULL;
    } 
	else 
	{
		return $newDate = date("Y-m-d", strtotime($original_date));
    }
}

$monthyear = $crypto->decode($_REQUEST['bill_report_year'], 4) . $crypto->decode($_REQUEST['bill_report_month'], 4);



$db = new database();

$zp_profile_fetch=$db->fetch_table("
										SELECT pl_code,ddo_code FROM zpemp_zp_profile WHERE district_id_fk='".$_SESSION['location']['district_id']."'
								");
								
$pl_operator_code=$zp_profile_fetch[0]['pl_code'];
$treasury_code=substr($zp_profile_fetch[0]['ddo_code'],0,3);

$bill_type=$crypto->decode($_POST['bill_type'],4);

$requisition = $db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type = $requisition[0]['code'];

$drn_checking = $db->fetch_table("
										SELECT * FROM prd_block_bill_details 
										WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
										AND bill_no = '" . $_POST['bill'] . "'
										AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
										AND status='1' AND zp_emp_type='".$bill_type."'
										");

if ($drn_checking[0]['drn_number'] == "") 
{
   $drn_number = $fun_store->drn_generation($party_code);
} 
else 
{
	$drn_number=$drn_checking[0]['drn_number'];
	
    $sftp_details_fetch = $db->fetch_table(" SELECT sftp_benf_id_pk,sftp_benf_sending_status,sftp_benf_response_status FROM prd_sftp_benf_upload_response WHERE bill_id_fk='" . $drn_checking[0]['block_bill_pk'] . "' AND active_status='1' ");
	
	$payment_failure_details=$db->fetch_table(" SELECT count(*) as total_count_fail
												FROM prd_sftp_benf_failure_details fail 
												INNER JOIN prd_sftp_benf_upload_response benf
												ON fail.sftp_benf_id_fk=benf.sftp_benf_id_pk
												WHERE benf.bill_id_fk='" . $drn_checking[0]['block_bill_pk'] . "' AND benf.active_status='1'
												AND fail.response_from='10' AND fail.active_status in('1','2','3')");

}

if ($monthyear != date('Ym')) 
{
    ?>
    <div class="alert alert-danger" style="width: 28%;margin-left: 43%;text-align: center;"><strong>Please Select Current Month and Year</strong></div>
    <?php
} 

else if(!ctype_alpha($treasury_code) || !ctype_digit($pl_operator_code)) 
{
	echo '<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Wrong PL Opertaor Code or Wrong Treasury Code. Please update Zilla Parishad Profile.</strong></div>';
}

else 
{
    $check_dpsc_bill = $db->fetch_table("
											SELECT count(*) FROM prd_block_bill_details 
											WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
											AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
											AND status='1' AND zp_emp_type='".$bill_type."'
											");

    $check_dpsc_bill_exist = $db->fetch_table("
												SELECT count(*) FROM prd_block_bill_details 
												WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
												AND bill_no = '" . $_POST['bill'] . "'
												AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
												AND status='1' AND zp_emp_type='".$bill_type."'
											");
    




    $total_benf = $db->fetch_table("SELECT count(distinct(emp.emp_id_pk)) as total_benf
									FROM prd_employee_master emp 
									INNER JOIN prd_employee_salary_save sal 
									ON sal.emp_id_fk=emp.emp_id_pk and sal.zp_id_fk=emp.zp_id_fk
									WHERE 
									trim(sal.salary_monthyear)='".date('Ym')."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='4'
									AND sal.is_saved='1'
									AND sal.zp_id_fk='".$_SESSION['location']['district_id']."' 
									AND sal.requisition_type='" . $requisition_type . "'
									AND sal.zp_emp_type='".$bill_type."'
									");


    $encode_drn = $crypto->encode($drn_number, 4);
    $encode_requsition = $crypto->encode($requisition_type, 4);
	$encode_emp_type = $crypto->encode($bill_type, 4);


    if ($check_dpsc_bill[0]['count'] == 0) 
	{
		pg_query("begin");
	
		$prd_monthly_salary_archive_final = $db->insert("
															INSERT INTO prd_monthly_salary_archive_final
															(
																slno ,
																gp_id_fk,
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
																gp_code,
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
																advance_amount,
																requisition_type,
																part_day,
																ps_id_fk,
																hra_deduction,
																payment_status,
																payment_date,
																zp_id_fk,
																zp_emp_type,
																other_loan_deduction,
																total_loan_deduction
															)
																SELECT 
																slno,
																gp_id_fk,
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
																gp_code,
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
																'" . $_SESSION['user_agent']['USER_IP'] . "',
																advance_amount,
																requisition_type,
																part_day,
																ps_id_fk,
																hra_deduction,
																payment_status,
																payment_date,
																zp_id_fk,
																zp_emp_type,
																other_loan_deduction,
																total_loan_deduction
															FROM prd_employee_salary_save
															WHERE zp_id_fk='".$_SESSION['location']['district_id']."'
															AND salary_monthyear ='".$monthyear."'
															AND status_flag = 4 and delete_status='1' and is_saved='1' AND zp_emp_type='".$bill_type."'
															");
		
		
		$prd_monthly_salary_archive_nonfinal = $db->insert("INSERT INTO prd_monthly_salary_archive_nonfinal(
																slno ,
																gp_id_fk,
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
																gp_code,
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
																archive_nonfinal_entry_time, 
																archive_nonfinal_entry_ip, 
																requisition_type, 
																part_day, 
																ps_id_fk, 
																hra_deduction, 
																payment_status, 
																payment_date, 
																zp_id_fk, 
																zp_emp_type,
																other_loan_deduction,
																total_loan_deduction
															)
																SELECT 
																slno,
																gp_id_fk,
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
																gp_code,
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
																'" . $_SESSION['user_agent']['USER_IP'] . "',
																requisition_type,
																part_day,
																ps_id_fk,
																hra_deduction,
																payment_status,
																payment_date,
																zp_id_fk,
																zp_emp_type,
																other_loan_deduction,
																total_loan_deduction
																from prd_employee_salary_save where salary_monthyear='" . date('Ym') . "'
																and  status_flag <>'4' 
																and zp_id_fk='".$_SESSION['location']['district_id']."'
																AND zp_emp_type='".$bill_type."'");
		
		
		$delete_save = $db->delete("DELETE FROM prd_employee_salary_save
									WHERE salary_monthyear='" . $salary_monthyear . "'
									AND zp_id_fk='".$_SESSION['location']['district_id']."' AND requisition_type='" . $requisition_type . "'
									AND zp_emp_type='".$bill_type."'");
		
		
		if ($prd_monthly_salary_archive_final)
		{
		
				
			
			pg_query("commit");
			$fetch = $db->fetch_table("SELECT now()");
			
			
			$insert_salary_bill = $db->insert("
												INSERT INTO prd_block_bill_details (
												block_code,
												bill_no,
												status,
												bill_entry_time,
												bill_update_time,
												ip_addres,
												salary_monthyear,
												requisition_type,
												drn_number,
												ps_id_fk,
												zp_id_fk,
												zp_emp_type
												)
												VALUES(
												'0',
												'" . $_POST['bill'] . "',
												'1',
												'" . date_frmt_change($_POST['bill_date']) . "',
												now(),
												'" . $_SESSION['user_agent']['USER_IP'] . "',
												'" . $monthyear . "',
												'" . $requisition_type . "',
												'" . $drn_number . "',
												0,
												'".$_SESSION['location']['district_id']."',
												'".$bill_type."'
												);
												");
			
			//$insert_salary_bill = FALSE ;
			if ($insert_salary_bill == TRUE) {
			/* 	$gp_info = $db->fetch_table("select
			gp.email_id,
			gp.mobile_no,
			gp.gram_pradhan_name
			from
			prd_gp_profile gp where CAST(gp.gp_code as character varying) like '".$_SESSION['location']['block_code']."%' "
			);
			
			foreach($gp_info as $item){
			
			$mail_body  = 'Dear '.$item['gram_pradhan_name'].' '.'<p>The Salary Bill has been successfully Generated </p> <hr><b>Disclaimer:</b>This is a system generated mail.Please do not reply.';
			$email=$gp_info['0']['email_id'];
			$emp_sub='Employee Salary Status';
			mail_function($mail_body,$email,$emp_sub);
			}
			*/
			
			////////////////////////////////////////////////////////////////LOAN DEDUCTION S//////////////////////////////////////////////////////////////
			
			$get_emp_id_loan = $db->fetch_table("SELECT emp_id_fk, salary_type FROM prd_employee_salary_save 
											WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
											AND status_flag = '4' AND salary_monthyear = '".date('Ym')."'
											AND is_saved=1 AND delete_status=1
											AND zp_emp_type='".$bill_type."'
											AND requisition_type='" . $requisition_type . "'
											AND total_loan_deduction>0");
			
			foreach($get_emp_id_loan as $emp_id_fk_loan)
			{
				if($emp_id_fk_loan['salary_type'] != 8)			
				{
					$emp_id_pk_loan = $emp_id_fk_loan['emp_id_fk'];
					
					$get_loan_data = $db->fetch_table("SELECT * FROM prd_loan_deduction
														WHERE emp_id_fk = '".$emp_id_pk_loan."'
														AND status in (2)
														AND approval_status in (3)
														AND delete_status in (1)");	
					
					
					foreach($get_loan_data as $loan_data)
					{
						// **insert the data in loan archive table
						/*$wef_date11 = $loan_data['wef'];
						$wef_month_year11 = explode("-",$wef_date11);
						$wef_month_year21 = $wef_month_year11[2].$wef_month_year11[1];
						
						if(date("Ym") >= $wef_month_year21)
						{*/
						$loan_id_pk = $loan_data['loan_id_pk'];
						$loan_total_amount = $loan_data['total_amount'];
						$loan_due_amount = $loan_data['due_amount'];
						$loan_counter = $loan_data['counter'];
						$loan_installment_amount = $loan_data['installment_amount'];
						$loan_no_of_installment = $loan_data['no_of_installment'];
						$loan_reminder_amount = $loan_data['reminder_amount'];
						
						//copy data to archive table Start
						$copy_data = $db->insert("INSERT INTO  prd_monthly_loan_deduction_archive (
						loan_id_pk,
						total_amount,
						no_of_installment,
						reminder_amount,
						status,
						approval_status ,
						counter ,
						emp_id_fk,
						zp_id_fk,
						created_by ,
						create_date ,
						who,
						update_time ,
						ip ,
						installment_amount ,
						emp_unique_id ,
						loan_master_id_fk ,
						dise_code_fk,
						deduction_loan_type_variable,
						lock_status,
						edit_status,
						delete_status,
						loan_id_fk,
						due_amount,
						last_deduction_time,
						last_deduction_by,
						loan_name,
						deduction_by_who,
						deduction_date,
						transaction_month_year,
						monthly_deduction_archive_status
						)
						
						SELECT loan_id_pk,
						total_amount,
						no_of_installment,
						reminder_amount,
						status,
						approval_status ,
						counter ,
						emp_id_fk,
						zp_id_fk,
						created_by ,
						create_date ,
						who,
						update_time ,
						ip ,
						installment_amount ,
						emp_unique_id ,
						loan_master_id_fk ,
						dise_code_fk,
						deduction_loan_type_variable,
						lock_status,
						edit_status,
						delete_status,
						loan_id_fk,
						due_amount,
						last_deduction_time,
						last_deduction_by,
						loan_name,
						'".$_SESSION['user_info']['stake_user']."',
						now(),
						'".date('Ym')."',
						'1'
						FROM prd_loan_deduction
						where emp_id_fk='".$emp_id_pk_loan."' 
						AND loan_id_pk ='".$loan_id_pk."'																															
						AND status in (2)
						AND approval_status in (3)
						AND delete_status in (1)");     //Changed date('mY') to date('Ym') on 121217
						//copy data to archive table End
						//monthly_deduction_archive_status will be 1 if first time bill generated, if not first time then it will be 2.
						
						//Checking counter
						if($copy_data)
						{		
							if($loan_counter < $loan_no_of_installment)
							{
								$new_loan_counter = $loan_counter + 1;
								//echo $new_loan_due_amount = $loan_total_amount - ($loan_installment_amount * $new_loan_counter);
								$new_loan_due_amount = ($loan_due_amount - $loan_installment_amount);
								
								//Check the installment if it is last or not
								if($new_loan_due_amount > 0)
								{
									$update_loan_deduction = $db->update("UPDATE prd_loan_deduction
																		SET counter = '".$new_loan_counter."',
																		due_amount = '".$new_loan_due_amount."',
																		last_deduction_time = now(),
																		last_deduction_by = '".$_SESSION['user_info']['stake_user']."'
																		WHERE loan_id_pk = '".$loan_id_pk."'
																		AND emp_id_fk = '".$emp_id_pk_loan."'
																		AND status in (2)
																		AND approval_status in (3)
																		AND delete_status in (1)");
								
								
								}
								//If the installment is the last installment
								else
								{
									$update_loan_deduction = $db->update("UPDATE prd_loan_deduction
																		SET counter = '".$new_loan_counter."',
																		due_amount = '".$new_loan_due_amount."',
																		last_deduction_time = now(),
																		last_deduction_by = '".$_SESSION['user_info']['stake_user']."',
																		status = '0'
																		WHERE loan_id_pk = '".$loan_id_pk."'
																		AND emp_id_fk = '".$emp_id_pk_loan."'
																		AND status in (2)
																		AND approval_status in (3)
																		AND delete_status in (1)");	
								}
							}
							else if($loan_counter == $loan_no_of_installment)
							{
								if($loan_reminder_amount > 0)
								{
									$update_loan_deduction = $db->update("UPDATE prd_loan_deduction
																			SET 
																			due_amount = '0',
																			status = '0',
																			last_deduction_time = now(),
																			last_deduction_by = '".$_SESSION['user_info']['stake_user']."'
																			WHERE loan_id_pk = '".$loan_id_pk."'
																			AND emp_id_fk = '".$emp_id_pk_loan."'
																			AND status in (2)
																			AND approval_status in (3)
																			AND delete_status in (1)");	
								}
							}
						}
						
						//}	
					}	
					
				}
			}
			
			////////////////////////////////////////////////////////////////LOAN DEDUCTION E//////////////////////////////////////////////////////////////			
			
			?>
			<script>
			//	    	$('#send_bill_sum_id').click(function(){
			//	   
			//		$('#page-load').show();
			//
			//   success:function(result){
			//       $('#page-load').hide();  
			//   }
			//});
			</script>
			
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
											<?php if ($drn_checking[0]['bill_sending_status'] == '2' && $drn_checking[0]['response_code'] == '0') 
											{
                                            ?>
                                            	<img src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.3;" />
                                            <?php
                                            } 
											else 
											{
                                            ?>
                                                <a onClick="value_pass('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>', '<?php echo $encode_emp_type; ?>','<?php echo $monthyear; ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer" /></a>
                                                
                                                <img id="send_bill_sum_id_disable" src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png" class="img-responsive" style="width:50%; margin:0 auto; opacity:0.3;display:none;" />
                                            <?php } ?>
                                        </div>
                                    </td>
				    <td id="status_bill_sum_send" <?php  if(ifms_error_description_generate($drn_checking[0]['response_code'])=='Success'){ ?> style="color:#00b248;" <?php }else{ ?> style="color:#c43e00;" <?php } ?> ><?php echo ifms_error_description_generate($drn_checking[0]['response_code']); ?> </td>
                                    <td>
                                        <span id="edit_action_first_row" style="display:none;"></span>
                                        <?php if ($drn_checking[0]['bill_sending_status'] == '2' && $drn_checking[0]['response_code'] != '0') 
										{
                                        ?>
                                        	<span id="edit_action_first_row2"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php if ($total_benf[0]['total_benf']>0) 
								{
                                ?>
                                    <tr>
                                        <td width="20%">2. </td>
                                        <td width="20%" style="color:#6a4f4b;">Upload Beneficiary file To IFMS</td>
                                        <td width="20%">
                                            <div class="link_p2">
												<?php if ($drn_checking[0]['bill_sending_status'] != '2') 
                                                {
                                                ?>
                                                    <img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5;">
                                                    <a id="send_benf"  style="display:none;"  onClick="value_pass_sftp('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $encode_emp_type; ?>','<?php echo $monthyear; ?>');"><img src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer;"></a>
                                                <?php
                                                } 
                                                else if (count($sftp_details_fetch) == '0') 
                                                {
                                                ?>
                                                    <img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5; display:none;">
                                                    <a id="send_benf"  onClick="value_pass_sftp('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $encode_emp_type; ?>','<?php echo $monthyear; ?>');"><img src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a>
                                                <?php
                                                } 
                                                else 
                                                {
                                                ?>
                                                    <img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5; ">
                                                <? } ?>
                                            </div>
                                        </td>
                                        <td id="status1" width="20%"  <?php if($sftp_details_fetch[0]['sftp_benf_response_status']=='6' || $sftp_details_fetch[0]['sftp_benf_response_status'] == '7'){ ?> style="color:#ff3d00;" <?php }else{ ?>style="color:#00b248;" <?php } ?> >
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
                                                	<i style="font-size:24px;color:#266eac; opacity:0.5;" class="fa">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;"></i> &nbsp;&nbsp;&nbsp;
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
                                <?php 
								} ?>
                                <tr>
                                    <td width="20%">3. </td>
                                    <td width="20%" style="color:#6a4f4b;">Bill Status View</td>
                                    <td width="20%"><div class="link_p3">
                                    <a id="view_status"  onClick="value_pass_view('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $monthyear; ?>');"> <img src="<?php echo $config['base_url']; ?>themes/default/image/status_btn.png" class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a></div></td>
                                    <td id="view_bill_status" width="20%"> </td>
                                    <td></td>
                                </tr>
                                <tr style="background-color: #DCEDC8;">
                                    <td width="20%" style="background-color: #DCEDC8;">4. </td>
                                    
                                    <td width="20%" style="background-color: #DCEDC8; color:#6a4f4b;"> Payment Status View</td>
                                    <td width="20%" style="background-color: #DCEDC8;">
                                    	<div class="btn btn-default" style=" float:center; cursor:default; color:#1A237E;width:105px;"> &nbsp;<a href="<?= $config['base_url']?>page/all_moduls/payment_details_view/payment_details.php?monthyear=<?php echo $crypto->encode($monthyear,4);?>&requisition_type=<?php echo $crypto->encode($requisition_type,4); ?>"><i class="fa fa-money fa-2x reason_view" aria-hidden="true" style="color:#33691E;cursor:pointer;" ></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    	<a style="cursor:pointer;"><i class="fa fa-street-view fa-2x eason_view" aria-hidden="true" style="color:#33691E;"></i></a>
                                    	</div>
                                    </td>
                                    <td width="20%" style="background-color: #DCEDC8;color: #FF3D00;" id="status_payment">
                                    <?php	if($sftp_details_fetch[0]['sftp_benf_response_status'] == '8')
											{
												echo "Payment File Generated";
											}
									?>
                                    </td>
                                    <td width="20%" style="background-color: #DCEDC8;">
                                        <div>
                                            <?php if($sftp_details_fetch[0]['sftp_benf_response_status'] == '5' || ($sftp_details_fetch[0]['sftp_benf_response_status'] != '7' && $sftp_details_fetch[0]['sftp_benf_response_status'] != '8')) 
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
                                                <!--<span id="edit_action_first_row3" onClick="payment_edit('<?php echo $crypto->encode($sftp_details_fetch[0]['sftp_benf_id_pk'],4); ?>');"><img style="padding-bottom: 10px;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>-->
                                            <?php 
                                            }
                                            else
                                            {?>
                                                <!--<span id="edit_action_first_row4" ><img style="padding-bottom: 10px; opacity:0.5;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>-->
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <!--<tr>
                                    <td width="20%" style="background-color: #E0E0E0;">5. </td>
                                    <td width="20%" style="background-color: #E0E0E0;">Failed Transaction Correction </td>
                                    <td width="20%" style="background-color: #E0E0E0;">  
                                    	<a onClick="value_pass('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_btn_2.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer" /></a> 
                                    </td>
                                    <td width="20%" style="background-color: #E0E0E0;"> </td>
                                    <td width="20%" style="background-color: #E0E0E0;"> </td>
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
	{
		if ($check_dpsc_bill_exist[0]['count'] == 1 && $drn_checking) 
		{
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
											<?php if ($drn_checking[0]['bill_sending_status'] == '2' && $drn_checking[0]['response_code'] == '0') 
											{
                                            ?>
                                            	<img src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.3;" />
                                            <?php
                                            } 
											else 
											{
                                            ?>
                                                <a onClick="value_pass('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>', '<?php echo $encode_emp_type; ?>','<?php echo $monthyear; ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer" /></a>
                                                
                                                <img id="send_bill_sum_id_disable" src="<?php echo $config['base_url']; ?>themes/default/image/send_btn.png" class="img-responsive" style="width:50%; margin:0 auto; opacity:0.3;display:none;" />
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td id="status_bill_sum_send" <?php  if(ifms_error_description_generate($drn_checking[0]['response_code'])=='Success'){ ?> style="color:#00b248;" <?php }else{ ?> style="color:#c43e00;" <?php } ?>><?php echo ifms_error_description_generate($drn_checking[0]['response_code']); ?> </td>
                                    <td>
                                        <span id="edit_action_first_row" style="display:none;"></span>
                                        <?php if ($drn_checking[0]['bill_sending_status'] == '2' && $drn_checking[0]['response_code'] != '0') 
										{
                                        ?>
                                        	<span id="edit_action_first_row2"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php if ($total_benf[0]['total_benf']>0) 
								{
                                ?>
                                    <tr>
                                        <td width="20%">2. </td>
                                        <td width="20%" style="color:#6a4f4b;">Upload Beneficiary file To IFMS</td>
                                        <td width="20%">
                                            <div class="link_p2">
												<?php if ($drn_checking[0]['bill_sending_status'] != '2') 
                                                {
                                                ?>
                                                    <img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5;">
                                                    <a id="send_benf"  style="display:none;"  onClick="value_pass_sftp('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $encode_emp_type; ?>','<?php echo $monthyear; ?>');"><img src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer;"></a>
                                                <?php
                                                } 
                                                else if (count($sftp_details_fetch) == '0') 
                                                {
                                                ?>
                                                    <img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5; display:none;">
                                                    <a id="send_benf"  onClick="value_pass_sftp('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $encode_emp_type; ?>','<?php echo $monthyear; ?>');"><img src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a>
                                                <?php
                                                } 
                                                else 
                                                {
                                                ?>
                                                    <img id="upload_image" src="<?php echo $config['base_url']; ?>themes/default/image/upload_btn.png"  class="img-responsive" style="width:50%; margin:0 auto; opacity:0.5; ">
                                                <? } ?>
                                            </div>
                                        </td>
                                        <td id="status1" width="20%"  <?php if($sftp_details_fetch[0]['sftp_benf_response_status']=='6' || $sftp_details_fetch[0]['sftp_benf_response_status'] == '7'){ ?> style="color:#ff3d00;" <?php }else{ ?>style="color:#00b248;" <?php } ?> >
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
                                                	<i style="font-size:24px;color:#266eac; opacity:0.5;" class="fa">&#xf046;</i><i class="fa fa-check-square-o" style="font-size:24px;color:#266eac;display:none;"></i> &nbsp;&nbsp;&nbsp;
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
                                <?php 
								} ?>
                                <tr>
                                    <td width="20%">3. </td>
                                    <td width="20%" style="color:#6a4f4b;">Bill Status View</td>
                                    <td width="20%"><div class="link_p3">
                                    <a id="view_status"  onClick="value_pass_view('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>','<?php echo $monthyear; ?>');"> <img src="<?php echo $config['base_url']; ?>themes/default/image/status_btn.png" class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer"></a></div></td>
                                    <td id="view_bill_status" width="20%" > </td>
                                    <td></td>
                                </tr>
                                <tr style="background-color: #DCEDC8;">
                                    <td width="20%" style="background-color: #DCEDC8;">4. </td>
                                    
                                    <td width="20%" style="background-color: #DCEDC8; color:#6a4f4b;"> Payment Status View</td>
                                    <td width="20%" style="background-color: #DCEDC8;">
                                    	<div class="btn btn-default" style=" float:center; cursor:default; color:#1A237E;width:105px;"> &nbsp;<a href="<?= $config['base_url']?>page/all_moduls/payment_details_view/payment_details.php?monthyear=<?php echo $crypto->encode($monthyear,4);?>&requisition_type=<?php echo $crypto->encode($requisition_type,4); ?>"><i class="fa fa-money fa-2x reason_view" aria-hidden="true" style="color:#33691E;cursor:pointer;" ></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    	<a style="cursor:pointer;"><i class="fa fa-street-view fa-2x eason_view" aria-hidden="true" style="color:#33691E;"></i></a>
                                    	</div>
                                    </td>
                                    <td width="20%" style="background-color: #DCEDC8;color: #FF3D00;" id="status_payment">
                                    <?php	if($sftp_details_fetch[0]['sftp_benf_response_status'] == '8')
											{
												echo "Payment File Generated";
											}
									?>
                                    </td>
                                    <td width="20%" style="background-color: #DCEDC8;">
                                        <div>
                                            <?php if($sftp_details_fetch[0]['sftp_benf_response_status'] == '5' || ($sftp_details_fetch[0]['sftp_benf_response_status'] != '7' && $sftp_details_fetch[0]['sftp_benf_response_status'] != '8')) 
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
                                                <!--<span id="edit_action_first_row3" onClick="payment_edit('<?php echo $crypto->encode($sftp_details_fetch[0]['sftp_benf_id_pk'],4); ?>');"><img style="padding-bottom: 10px;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>-->
                                            <?php 
                                            }
                                            else
                                            {?>
                                                <!--<span id="edit_action_first_row4" ><img style="padding-bottom: 10px; opacity:0.5;" width="25" src="<?= $config['base_url']; ?>themes/default/image/edit_icon.png" alt="Edit"/></span>-->
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <!--<tr>
                                    <td width="20%" style="background-color: #E0E0E0;">5. </td>
                                    <td width="20%" style="background-color: #E0E0E0;">Failed Transaction Correction </td>
                                    <td width="20%" style="background-color: #E0E0E0;">  
                                    	<a onClick="value_pass('<?php echo $encode_requsition; ?>', '<?php echo $encode_drn; ?>');" > <img id="send_bill_sum_id" src="<?php echo $config['base_url']; ?>themes/default/image/send_btn_2.png"  class="img-responsive" style="width:50%; margin:0 auto; cursor:pointer" /></a> 
                                    </td>
                                    <td width="20%" style="background-color: #E0E0E0;"> </td>
                                    <td width="20%" style="background-color: #E0E0E0;"> </td>
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


	$(document).ready(function () 
	{
		//     $('#page-load').show();
		//        $('#page-load').delay(1000).fadeOut();
		//      });
	});
	
	function value_pass(encode_requsition, drn_number, emp_type,monthyear)
	{
		$('#page-load').show();
		
		var requsition = encode_requsition;
		var drn_no = drn_number;		
		$.post('<?= $config['base_url'] ?>page/intra_zp/salary_fcncao/text_file/xml_file.php?bill_type='+requsition+'&drn_no='+drn_no+'&emp_type='+emp_type, function (data) {
		
		});
		
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/index.php?bill_type='+requsition+'&drn_no='+drn_no+'&emp_type='+emp_type+'&monthyear='+monthyear, function (data) {
			//alert(data);
			$('#page-load').delay(500).fadeOut();
			if (data.trim() == 'Success')
			{
			        $('#status_bill_sum_send').css('color', '#008e76');
				$('#status_bill_sum_send').text(data);
				$('#upload_image').hide();
				$('#send_benf').show();
				$('#send_bill_sum_id_disable').show();
				$('#send_bill_sum_id').hide();
				$('#edit_action_first_row').hide();
				$('#edit_action_first_row2').hide();
				$('#bill_status_row').show();
			} 
			else
			{
			        $('#status_bill_sum_send').css('color', '#c62828');
				$('#status_bill_sum_send').text(data);
				$('#edit_action_first_row').show();
				$('#edit_action_first_row2').hide();
			}
		});
		
	}
	
	//**************************************************************
	
	function value_pass_sftp(encode_requsition, drn_number, emp_type,monthyear)
	{
		//$('#page-load').show();
		var requsition = encode_requsition;
		var drn_no = drn_number;
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/sftp_index.php?bill_type='+requsition+'&drn_no='+drn_no+'&emp_type='+emp_type+'&monthyear='+monthyear, function (data) {
			//alert(data);
			$("#status1").html(data);
			$('#page-load').delay(500).fadeOut();
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
	
	function value_pass_view(encode_requsition, drn_number,monthyear)
	{
		$('#page-load').show();
		var requsition = encode_requsition;
		var drn_no = drn_number;
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/bill_status_check.php?bill_type=' + requsition + '&drn_no=' + drn_no+'&monthyear='+monthyear, function (data) {
			$('#page-load').delay(500).fadeOut();
			if (data)
			{
			   
			        $('#view_bill_status').css('color', '#ff8f00');
				$('#view_bill_status').text(data);
				
			}
		});
	}
	
	//**************************************************************
	
	function benf_status_check()
	{
		var user='zp';
		$('#page-load').show();
		$('#refresh_icon_static').hide();
		$('#refresh_icon_dynamic').show();
		$.post('<?= $config['base_url'] ?>page/api/ifms/sftp_cron/prd_ifms_cron_job.php?user='+user, function (data) {
			//alert(data);
			$('#page-load').delay(500).fadeOut();
			//$('#status1').html(data); 
			location.reload();
		});
	}
	
	//**************************************************************
	
	function done_file_check()
	{
		var user='zp';
		$('#page-load').show();
		/*$('#refresh_icon_static').hide();
		$('#refresh_icon_dynamic').show();*/
		$.post('<?= $config['base_url'] ?>page/api/ifms/sftp_cron/dot_done_ifms_cron_job.php?user='+user, function (data) {
			//alert(data);
			$('#page-load').delay(500).fadeOut();
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
		
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/sftp_wrong_data_edit.php?benf_id=' + benf_id, function (data) {
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
		$.post('<?= $config['base_url'] ?>page/api/ifms/zp/epayment_wrong_data_edit.php?benf_id=' + benf_id, function (data) {
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
</script>


<!----------------------------------------------------------------- Employee MODAL Start---------------------------------->


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 1380px;margin-left: -25.5%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onClick="location.reload();">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Employee Details</h4>
            </div>
            <div class="modal-body"> 
                <div id="mbody"> 
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
