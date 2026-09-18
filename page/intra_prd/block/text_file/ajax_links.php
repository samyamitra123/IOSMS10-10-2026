
<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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
//require '../../page_visite.php';

 $k=strtotime("first day of last month");
$arr = date("Y-m-d",$k);


/*$month_ini = new DateTime("first day of last month");
$arr=$month_ini->format('Y-m-d'); // 2012-02-01
echo $arr;*/
$month_arr=explode('-',$arr);
$salary_monthyear=$month_arr[0].$month_arr[1];

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
</style>

<?php

$crypto = new cryptography();

//$moye = trim($crypto->decode($_GET['ye'], 4)) . trim($crypto->decode($_GET['mo'], 4));
//$salso = trim($crypto->decode($_GET['ss'], 4));
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
	//$salary_source = $crypto->decode($_REQUEST['bill_sal_source'],4);
	//echo $monthyear . "<br />";
	//echo $salary_source . "<br />";
	
	
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

*/

/*if(!isset($_POST['new'])){
?>
	<div class="link_p">
		<a href="<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/personal.php?mo=<?php echo $_POST['bill_report_month']; ?>&ye=<?php echo $_POST['bill_report_year']; ?>&bill=<?php echo $_POST['bill']; ?>">Generate Personal Details</a>
	</div>
	<div class="link_p">
		<a href="<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/salarybill.php?mo=<?php echo $_POST['bill_report_month']; ?>&ye=<?php echo $_POST['bill_report_year']; ?>&bill=<?php echo $_POST['bill']; ?>">Generate Bill Summery</a>
	</div>
<?php }
else {*/
$db = new database();


$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

if($monthyear!=date('Ym'))
{
?>
	<div class="alert alert-danger" style="width: 28%;margin-left: 43%;text-align: center;"><strong>Please Select Current Month and Year!!</strong></div>
<?php
}
else
{
	
	$check_dpsc_bill = $db->fetch_table("
											SELECT count(*) FROM prd_block_bill_details 
											WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
											AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."' AND status='1'
											");
	
	$check_dpsc_bill_exist = $db->fetch_table("
												SELECT count(*) FROM prd_block_bill_details 
												WHERE block_code = '".$_SESSION['user_info']['stake_user']."'
												AND bill_no = '".$_POST['bill']."'
												AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."' AND status='1'
												");
	
	
	if($check_dpsc_bill[0]['count'] == 0)
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
															part_day
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
															'".$_SESSION['user_agent']['USER_IP']."',
															advance_amount,
															requisition_type,
															part_day
															FROM prd_employee_salary_save
															WHERE block_code ='".$_SESSION['location']['block_code']."'
															AND salary_monthyear ='".$monthyear."'
															AND status_flag = 3 and delete_status='1' and is_saved='1'
															AND requisition_type='".$requisition_type."'
															");
		
		
		
		$prd_monthly_salary_archive_nonfinal=$db->insert("INSERT INTO prd_monthly_salary_archive_nonfinal(
														slno,
														entry_time,
														entry_ip_address,
														gp_id_fk,
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
														gp_code,
														pay_payband,
														tch_grade_pay,
														gross_salary, 
														archive_nonfinal_entry_time,
														archive_nonfinal_entry_ip,
														requisition_type,
														part_day
														)
														SELECT 
														slno,
														latestupdate_time,
														latestupdate_ip_address,
														gp_id_fk,
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
														'1' AS delete_status,
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
														gp_code,
														pay_payband,
														tch_grade_pay,
														gross_salary,
														now(),
														'".$_SESSION['user_agent']['USER_IP']."',
														requisition_type,
														part_day
														from prd_employee_salary_save 
														where block_code ='".$_SESSION['location']['block_code']."'
															AND salary_monthyear ='".$monthyear."'
															AND status_flag not in (3) 
															AND requisition_type='".$requisition_type."'");
		
		
		
		$delete_save=$db->delete("DELETE FROM prd_employee_salary_save
									WHERE salary_monthyear='".$salary_monthyear."'
									AND block_code ='".$_SESSION['location']['block_code']."' AND requisition_type='".$requisition_type."'");
		
		if($prd_monthly_salary_archive_nonfinal && $prd_monthly_salary_archive_final)
		{
			pg_query("commit");
			
			$fetch=$db->fetch_table("SELECT now()");
			
			$insert_salary_bill = $db->insert("
												INSERT INTO prd_block_bill_details (
												block_code,
												bill_no,
												status,
												bill_entry_time,
												bill_update_time,
												ip_addres,
												salary_monthyear,
												requisition_type
												)
												VALUES(
												'".$_SESSION['location']['block_code']."',
												'".$_POST['bill']."',
												'1',
												'".date_frmt_change($_POST['bill_date'])."',
												now(),
												'".$_SESSION['user_agent']['USER_IP']."',
												'".$monthyear."',
												'".$requisition_type."'
												);
												");
			
			if($insert_salary_bill == TRUE )
			{
				//05_06_2018
			$get_emp_id_loan = $db->fetch_table("SELECT emp_id_fk, salary_type, festival_loan FROM prd_employee_salary_save 
											WHERE block_code = '".$_SESSION['location']['block_code']."'
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
				
				/*	$gp_info = $db->fetch_table("select
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
				?>
				<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill Number Inserted</strong></div><br />
				<div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                    <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/personal.php?mo=<?php echo $_POST['bill_report_month']; ?>&ye=<?php echo $_POST['bill_report_year']; ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
                    </div>
				</div>
				<br/>
				<div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                    <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/salarybill.php?mo=<?php echo $_POST['bill_report_month']; ?>&ye=<?php echo $_POST['bill_report_year']; ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summery</a>
                    </div>
				</div>
				<br />
				<div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                    <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/xml_file.php?mo=<?php echo $_POST['bill_report_month']; ?>&ye=<?php echo $_POST['bill_report_year']; ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
                    </div>
                </div>
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
		?>
		<div class="form-group">
		<?php if($check_dpsc_bill_exist[0]['count'] == 1)
		{
		?>
            <div class="col-sm-offset-5 col-sm-7">
            	<a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/personal.php?mo=<?php echo $_POST['bill_report_month']; ?>&ye=<?php echo $_POST['bill_report_year']; ?>&bill=<?php echo $_POST['bill']; ?>"><i class="fa fa-file-text"></i> Generate Personnel Details</a>
            </div>
            </div>
            <br/><br />
            <div class="form-group">
            <div class="col-sm-offset-5 col-sm-7">
            <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/salarybill.php?mo=<?php echo $_POST['bill_report_month']; ?>&ye=<?php echo $_POST['bill_report_year']; ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate Bill Summery</a>
            </div>
            </div>
            <br />
            <div class="form-group">
            <div class="col-sm-offset-5 col-sm-7">
            <a class="btn btn-info btn-sm" href="<?php echo $config['base_url'] ?>page/intra_prd/block/text_file/xml_file.php?mo=<?php echo $_POST['bill_report_month']; ?>&ye=<?php echo $_POST['bill_report_year']; ?>&bill=<?php echo $_POST['bill']; ?>" style="width: 29.5%;text-align: left;"><i class="fa fa-file-text"></i> Generate ECS</a>
            </div>
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
//}
?>
