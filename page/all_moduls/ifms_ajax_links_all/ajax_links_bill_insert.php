<?php
//print("test"); exit;
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
error_reporting(0);
//header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
//header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once'../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../all_function/fun_store/zp_ps_gp_function.php';    
//require '../../page_visite.php';
$current_year = date("Y");
$next_year=$current_year+1;
$prev_yrr=$current_year-1;

/*$current_month_first_date=date("Y-m-01");
$next_month_date =date("Y-m-d", strtotime("$current_month_first_date +1 month")); 
$after_ten_month_date=date("Y-m-d", strtotime("$current_month_first_date +10 month"));*/

//$next_monthyear_of_current_month=substr($next_month_date,0,4).substr($next_month_date,5,2);
//$next_ten_monthyear_of_current_month=substr($after_ten_month_date,0,4).substr($after_ten_month_date,5,2);
//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////
$fun_store = new zp_ps_gp_class();
if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


  $k=strtotime("first day of last month");
  $arr = date("Y-m-d",$k); 


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

	//$monthyear = $crypto->decode($_REQUEST['bill_report_year'],4).$crypto->decode($_REQUEST['bill_report_month'],4);
	$monthyear=date('Ym');
	$current_year = substr($monthyear, 0, -2);
	if(($monthyear == $current_year.'01') || ($monthyear == $current_year.'02') || ($monthyear == $current_year.'03')){
		
		$prev_yrr=$current_year-1;
		$fin_prev_yrr=$prev_yrr.'04';
		$fin_yr_end=$current_year.'03';
	}
	else{
	
		$next_year=$current_year+1;
		$fin_prev_yrr=$current_year.'04';
		$fin_yr_end=$next_year.'03';
	}
	
$db = new database();

/*$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='428'");
$requisition_type=$requisition[0]['code'];*/
   $bill_type=$crypto->decode($_POST['emp_type'],4); 
   $requisition_type=$crypto->decode($_POST['requisition_type'],4); 
   $monthyear = $crypto->decode($_POST['bill_report_year'], 4); 
   $bill_serial_no=$_POST['bill_serial_no']; 
   $ropa_status=$crypto->decode($_POST['ropa_status'],4); 
   $_POST['bill'] = 'PNRDIOSMS'.$_POST['bill'];
   
   //var_dump($logged_user); die;
// echo $_POST['bill']; die;



if($logged_user=='zpddo')
{
	$party_code = '008'; 
	$check_bonus_bill = $db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE zp_id_fk ='".$_SESSION['location']['district_id']."'
	AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1' AND ropa_status='".$ropa_status."' 
	");
	
	$check_bonus_bill_exist = $db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE zp_id_fk ='".$_SESSION['location']['district_id']."'
	AND bill_no = '".$_POST['bill']."'
	AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1' AND ropa_status='".$ropa_status."' 
	");
	
	
	if($requisition_type == '1002'|| $requisition_type == '1005' || $requisition_type == '1004' || $requisition_type == '1003' || $requisition_type == '1001')
	{
		
		$check_duplicate_bill_no_check=$db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE zp_id_fk ='".$_SESSION['location']['district_id']."'
		AND bill_no = '".$_POST['bill']."'
		AND CAST(salary_monthyear as INTEGER) BETWEEN $fin_prev_yrr AND $fin_yr_end
		AND status in('1','0')  AND ropa_status='".$ropa_status."' 
		");
	}
	else
	{
		$check_duplicate_bill_no_check=$db->fetch_table("
		SELECT count(*) FROM prd_block_bill_details 
		WHERE zp_id_fk ='".$_SESSION['location']['district_id']."'
		AND bill_no = '".$_POST['bill']."'
		AND salary_monthyear = '".$monthyear."'
		AND requisition_type='".$requisition_type."'
		AND status in('1','0')  AND ropa_status='".$ropa_status."' 
		");	
		
	}
	$drn_checking = $db->fetch_table("
		SELECT * FROM prd_block_bill_details 
		WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
		AND bill_no = '" . $_POST['bill'] . "'
		AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
		AND status='1'  AND bill_serial_no='". $bill_serial_no."' AND ropa_status='".$ropa_status."' 
		");
		
		if ($drn_checking[0]['drn_number'] == "") 
		{
		$drn_number = $fun_store->drn_generation($party_code);
		} 
		else 
		{
		$drn_number=$drn_checking[0]['drn_number'];
		}
		
		if($check_duplicate_bill_no_check[0]['count']=='0' || $check_duplicate_bill_no_check[0]['count']== NULL)		
		{
			pg_query('BEGIN');
		$fetch=$db->fetch_table("SELECT now()");  
		
		//print($requisition_type); exit;
		if($requisition_type == 1003 || $requisition_type == 1001)
		{
			$query = "
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
		total_loan_deduction,
		ropa_status,
		ropa_level,
		allowance
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
		total_loan_deduction,
		ropa_status,
		ropa_level,
		allowance
		FROM prd_employee_salary_save
		WHERE zp_id_fk='".$_SESSION['location']['district_id']."'
		AND salary_monthyear ='".$monthyear."'
		AND status_flag = 4 and delete_status='1' and is_saved='1' AND zp_emp_type='".$bill_type."' AND ropa_status='".$ropa_status."'
		";
		//print("subikar"); exit;
		$prd_monthly_salary_archive_final = $db->insert($query);
		
		
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
		from prd_employee_salary_save where salary_monthyear ='".$monthyear."'
		AND status_flag not in('4') 
		and zp_id_fk='".$_SESSION['location']['district_id']."'
		AND zp_emp_type='".$bill_type."'");
			
	if($prd_monthly_salary_archive_final==TRUE && $prd_monthly_salary_archive_nonfinal==TRUE)
		{ 
		//var_dump($prd_monthly_salary_archive_nonfinal); die;
		//$insert_bonus_bill=TRUE;
		pg_query("commit");
		$Query ="INSERT INTO prd_block_bill_details ( bill_no, status, block_code, bill_entry_time, bill_update_time, ip_addres, salary_monthyear, ps_id_fk, requisition_type, zp_id_fk,bill_serial_no, drn_number, zp_emp_type,
		ropa_status	)
		VALUES( '".$_POST['bill']."', '1', '0', '".date_frmt_change($_POST['bill_date'])."', now(),
		'".$_SESSION['user_agent']['USER_IP']."', '".$monthyear."', '0','".$requisition_type."',
		'".$_SESSION['location']['district_id']."','".$bill_serial_no."','".$drn_number."','".$bill_type."',
		'".$ropa_status."' );
		";
		//print_r($Query); exit;
		$insert_bonus_bill = $db->insert($Query);
		}
		else
		{
			
			$insert_bonus_bill = $db->insert(" INSERT INTO prd_block_bill_details ( bill_no, status, block_code, bill_entry_time, bill_update_time,
		ip_addres, salary_monthyear, ps_id_fk, requisition_type, zp_id_fk,bill_serial_no, drn_number, zp_emp_type, ropa_status )
		VALUES( '".$_POST['bill']."', '1', '0', '".date_frmt_change($_POST['bill_date'])."', now(),
		'".$_SESSION['user_agent']['USER_IP']."', '".$monthyear."', '0','".$requisition_type."',
		'".$_SESSION['location']['district_id']."','".$bill_serial_no."','".$drn_number."','".$bill_type."',
		'".$ropa_status."');
		");
		}
		if($insert_bonus_bill == TRUE)
			{
			
			
						$get_emp_id_loan_fav = $db->fetch_table("SELECT emp_id_fk, salary_type, festival_loan FROM prd_employee_salary_save 
						WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
						AND status_flag = '4' AND salary_monthyear = '".$monthyear."'
						AND is_saved in ('1') AND delete_status in ('1')");			
						
						foreach($get_emp_id_loan_fav as $emp_id_fk_fav)
						{
						if($emp_id_fk_fav['salary_type'] != '8' && $emp_id_fk_fav['festival_loan'] > 0)
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
						a.festival_advance_status in ('5') AND a.emp_id_fk = '".$emp_id_fk_fav['emp_id_fk']."'  
						order by a.festival_advance_id_pk DESC LIMIT 1
						");
						$pre_total_amt_given = $get_pre_data[0]['total_amt_given'];
						$new_counter = $get_pre_data[0]['deduction_counter'] +1;
						$new_total_amt_given = $get_pre_data[0]['total_amt_given'] + $emp_id_fk_fav['festival_loan'];
						
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
						
						
			$get_emp_id_loan = $db->fetch_table("SELECT emp_id_fk, salary_type FROM prd_employee_salary_save 
		WHERE zp_id_fk = '".$_SESSION['location']['district_id']."'
		AND status_flag = '4'  AND salary_monthyear = '".$monthyear."'
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
			
			$loan_id_pk = $loan_data['loan_id_pk'];
			$loan_total_amount = $loan_data['total_amount'];
			$loan_due_amount = $loan_data['due_amount'];
			$loan_counter = $loan_data['counter'];
			$loan_installment_amount = $loan_data['installment_amount'];
			$loan_no_of_installment = $loan_data['no_of_installment'];
			$loan_reminder_amount = $loan_data['reminder_amount'];
			
			
			
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
			AND delete_status in (1)"); 
			
			if($copy_data)
			{		
			if($loan_counter < $loan_no_of_installment)
			{
			$new_loan_counter = $loan_counter + 1;
			
			$new_loan_due_amount = ($loan_due_amount - $loan_installment_amount);
			
			
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
			}
			
			
			}
			
			
			
			
			}
		
		//pg_query('COMMIT');
		?>
		<div  style="width: 28%;margin-left: 40%;text-align: center;">	<a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php' class="btn btn-success">IFMS UPLOAD DETAILS</a></div>
		<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is inserted for this month</strong></div>
						
		
		<?php }/// bill insert
		
				else 
				{ 
				pg_query('ROLLBACK');?>
				<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
				<?php
				}
		    }/// reqution type
		
		}/// duplicate bill number
		
		
		else
		{
		?>
		<div  style="width: 28%;margin-left: 40%;text-align: center;">  <a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php' class="btn btn-success">IFMS UPLOAD DETAILS</a></div>
		<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is allready inserted </strong></div>
		<?php
		}
	
	
	
	
	
}



else if($logged_user=='EO')
	{
	//echo 11; die;
	
	
	$party_code = '007'; 
	
	$check_bonus_bill = $db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
	AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1' 
	AND ropa_status='".$ropa_status."'
	");
	
	$check_bonus_bill_exist = $db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
	AND bill_no = '".$_POST['bill']."'
	AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1'
	AND ropa_status='".$ropa_status."'
	");
	
	
	
	if($requisition_type == '1002'|| $requisition_type == '1005' || $requisition_type == '1004' ||  $requisition_type == '1003' || $requisition_type == '1001'){
	//$check_duplicate_bill_no_check[0]['count']== 0;
	
	$check_duplicate_bill_no_check=$db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
	AND bill_no = '" . $_POST['bill'] . "'
	AND CAST(salary_monthyear as INTEGER) BETWEEN $fin_prev_yrr AND $fin_yr_end
	AND status in('1','0') AND ropa_status='".$ropa_status."'  
	");	
	
	}
	else if($requisition_type != '1002'|| $requisition_type != '1005' || $requisition_type != '1004' ||  $requisition_type != '1003' || $requisition_type == '1001')
	{
	$check_duplicate_bill_no_check=$db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
	AND requisition_type='".$requisition_type."'
	AND salary_monthyear = '".$monthyear."'
	AND status in('1','0') AND ropa_status='".$ropa_status."'  
	");	
	
	}   
	
	//print_r($check_duplicate_bill_no_check); die();
	
	$drn_checking = $db->fetch_table("
	SELECT * FROM prd_block_bill_details 
	WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
	AND bill_no = '" . $_POST['bill'] . "'
	AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
	AND status='1'  AND bill_serial_no='". $bill_serial_no."' AND ropa_status='".$ropa_status."'
	");	
	//print_r($drn_checking); exit;
	if ($drn_checking[0]['drn_number'] == "" || $drn_checking[0]['drn_number'] == NULL) 
	{
	$drn_number = $fun_store->drn_generation($party_code);
	} 
	else 
	{
	$drn_number=$drn_checking[0]['drn_number'];
	}
	
	if($check_duplicate_bill_no_check[0]['count']=='0' || $check_duplicate_bill_no_check[0]['count']== NULL)		
	{
	
	
	$fetch=$db->fetch_table("SELECT now()");  
	
	pg_query('BEGIN');
	
	
	
	if($requisition_type == 1003 || $requisition_type == 1001){
	
	$Query = "
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
	emp_pay_band,
	ropa_status,
	ropa_level
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
	emp_pay_band,
	ropa_status,
	ropa_level
	FROM prd_employee_salary_save
	WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
	AND salary_monthyear ='".$monthyear."'
	AND status_flag = 3 and delete_status='1' and is_saved='1'
	AND requisition_type='".$requisition_type."'
	AND ropa_status='".$ropa_status."'
	";
	//print($Query); exit;
	$prd_monthly_salary_archive_final = $db->insert($Query);
	
  
	
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
	where salary_monthyear='".$monthyear."' AND ps_id_fk ='".$_SESSION['location']['ps_id']."'  AND requisition_type='".$requisition_type."' AND status_flag not in('3')");
	
	}
	
	if($requisition_type == 1003 || $requisition_type == 1001){
		if($prd_monthly_salary_archive_final==TRUE && $prd_monthly_salary_archive_nonfinal==TRUE)
		{

		//$insert_bonus_bill=TRUE;
			      $Query = "
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
					zp_id_fk,bill_serial_no,drn_number, ropa_status
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
					0,'".$bill_serial_no."','" . $drn_number . "', '" . $ropa_status . "'
					);
					";
					//print($Query); exit;
					$insert_bonus_bill = $db->insert($Query);
		}
	}
	else
	{
	
				$insert_bonus_bill = $db->insert("
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
				zp_id_fk,bill_serial_no,drn_number, ropa_status
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
				0,'".$bill_serial_no."','" . $drn_number . "', '" . $ropa_status . "'
				);
				");
	}
	
	//print_r($insert_bonus_bill); exit;
	
	$bill_details_fetch=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details 
	WHERE bill_no='".$_POST['bill']."'
	AND ps_id_fk='".$_SESSION['location']['ps_id']."'
	AND requisition_type='".$requisition_type."'
	AND salary_monthyear='".$monthyear."' AND ropa_status='".$ropa_status."'
	");
	
	if($requisition_type == 1003 || $requisition_type == 1001){
	$update_arrear_salary = TRUE;
	
	}
	else if($requisition_type == 1002){
	
	$update_arrear_salary=$db->update("UPDATE prd_employee_arrear arr
	SET 
	salary_monthyear='".$monthyear."',
	status_flag='3',
	bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."',
	bill_serial_no='".$bill_serial_no."'
	WHERE arr.delete_status=1 AND arr.is_saved=1  AND arr.status_flag='2' 
	AND arr.ps_id_fk = '".$_SESSION['location']['ps_id']."' 
	");
	}									
	else if($requisition_type == 1005){
	
	$update_arrear_salary=$db->update("UPDATE prd_festival_advance_employee_details fav
	SET 
	fad_monthyear='".$monthyear."',
	festival_advance_status=5,
	bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."',
	bill_serial_no='".$bill_serial_no."'
	WHERE fav.ps_id_fk = '".$_SESSION['location']['ps_id']."' 
	AND fav.festival_advance_status = '4'
	");
	/********************************** Changed By ANJAN - 11-09-2019 ***********************************************/
	
	$fav_bill_fetch = $db->fetch_table("SELECT detail.emp_id_fk, detail.festival_advance_id_pk, sal.festival_advance_instalment_no FROM 
	prd_festival_advance_employee_details as detail
	INNER JOIN prd_block_bill_details as bill ON detail.bill_id_fk = bill.block_bill_pk
	INNER JOIN prd_festival_advance_entry_sal as sal ON detail.festival_advance_id_pk = sal.festival_advance_id_fk
	WHERE bill.bill_no='".$_POST['bill']."'
	AND bill.ps_id_fk='".$_SESSION['location']['ps_id']."'
	AND bill.salary_monthyear='".$monthyear."'
	");
	
	
	foreach($fav_bill_fetch as $fav_bill){
	
	$current_month_first_date=date("Y-m-01");
	$next_month_date =date("Ym", strtotime("$current_month_first_date +1 month")); 
	$after_instl_month_date=date("Ym", strtotime("$current_month_first_date +$fav_bill[festival_advance_instalment_no] month"));
	
	$fad_instalment_details_update=$db->update(" UPDATE prd_festival_advance_entry_sal sal
	SET deduction_start_monthyear='".$next_month_date."',
	deduction_end_monthyear='".$after_instl_month_date."' 
	FROM prd_festival_advance_employee_details fad
	WHERE fad.festival_advance_id_pk=sal.festival_advance_id_fk
	AND fad.bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."' ");
	
	//var_dump($fad_instalment_details_update);
	}
	
	/************************************************** END *******************************************************************/
	
	
	
	}
	else if($requisition_type == 1004){	
	
	
	$update_arrear_salary=$db->update("UPDATE prd_employee_bonus_details bon SET 
	bonus_status=5,
	bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."',
	bill_serial_no='".$bill_serial_no."'
	WHERE bon.bonus_status=4 
	AND bon.monthyear='".$prev_yrr.$current_year."' 
	AND bon.ps_id_fk='".$_SESSION['location']['ps_id']."'");
	}
	//var_dump($insert_bonus_bill);
	//var_dump($update_arrear_salary); die;
	if($insert_bonus_bill == TRUE && $update_arrear_salary == TRUE)
	{
	
			$get_emp_id_loan = $db->fetch_table("SELECT emp_id_fk, salary_type, festival_loan FROM prd_employee_salary_save 
			WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
			AND status_flag = '3' AND salary_monthyear = '".$monthyear."'
			AND requisition_type='".$requisition_type."' AND ropa_status='".$ropa_status."'
			AND is_saved in ('1') AND delete_status in ('1')");			
			//print_r($get_emp_id_loan); exit;
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
							WHERE festival_advance_id_pk='".$get_pre_data[0]['festival_advance_id_pk']."' AND festival_advance_status='5'");	
							}
					}
			}
			pg_query('COMMIT');
	?>
	
	<div  style="width: 28%;margin-left: 40%;text-align: center;">	<a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php' class="btn btn-success">IFMS Upload</a></div>
	<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is inserted for this month</strong></div>
	
	<?php
	
	} 
	else 
	{ 
	pg_query('ROLLBACK');?>
	<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
	<?php
	} 
	}
	else
	{
	?>
	<div  style="width: 28%;margin-left: 40%;text-align: center;">  <a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php' class="btn btn-success">IFMS Upload</a></div>
	<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is allready inserted for this month</strong></div>
	<?php
	}
}



else if($logged_user=='BDO')
	{
	
	
	$party_code = '006'; 
	
	$check_bonus_bill = $db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE block_code = '".$_SESSION['location']['block_code']."'
	AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1' AND ropa_status='".$ropa_status."'
	");
	
	$check_bonus_bill_exist = $db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE block_code = '".$_SESSION['location']['block_code']."'
	AND salary_monthyear = '".$monthyear."' AND requisition_type='".$requisition_type."'  AND status='1' AND ropa_status='".$ropa_status."'
	");
	
	
	if($requisition_type == '1002' || $requisition_type == '1005' || $requisition_type == '1004' || $requisition_type == '1003'  || $requisition_type == '1001'){
	//$check_duplicate_bill_no_check[0]['count']== 0;
	$check_duplicate_bill_no_check=$db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE block_code = '".$_SESSION['location']['block_code']."'
	AND bill_no = '" . $_POST['bill'] . "'
	AND CAST(salary_monthyear as INTEGER) BETWEEN $fin_prev_yrr AND $fin_yr_end
	AND status in('1','0')
	AND ropa_status='".$ropa_status."'
	");
	}	
	else if($requisition_type != '1002' || $requisition_type != '1005' || $requisition_type != '1004' || $requisition_type != '1003' ||$requisition_type == '1001'){
	$check_duplicate_bill_no_check=$db->fetch_table("
	SELECT count(*) FROM prd_block_bill_details 
	WHERE block_code = '".$_SESSION['location']['block_code']."'
	AND requisition_type='".$requisition_type."'
	AND salary_monthyear = '".$monthyear."'
	AND status in('1','0') 
	AND ropa_status='".$ropa_status."'
	");	
	}			
	
	//var_dump($check_duplicate_bill_no_check[0]['count']); die;
	
	
	$drn_checking = $db->fetch_table("
	SELECT * FROM prd_block_bill_details 
	WHERE block_code = '" . $_SESSION['location']['block_code'] . "'
	AND bill_no = '" . $_POST['bill'] . "'
	AND salary_monthyear = '" . $monthyear . "' AND requisition_type='" . $requisition_type . "' 
	AND status='1'  AND bill_serial_no='". $bill_serial_no."' AND ropa_status='".$ropa_status."'
	");
	//var_dump($drn_checking[0]['drn_number']); die;
	
	if ($drn_checking[0]['drn_number'] == "" || $drn_checking[0]['drn_number'] == NULL) 
	{
	
	$drn_number = $fun_store->drn_generation($party_code); 
	} 
	else 
	{ 
	$drn_number=$drn_checking[0]['drn_number']; 
	}
	
	//var_dump($drn_number); die;
	
	if($check_duplicate_bill_no_check[0]['count']=='0' || $check_duplicate_bill_no_check[0]['count']== NULL)		
	{ 
	
	$fetch=$db->fetch_table("SELECT now()");  
	
	pg_query('BEGIN');
	
	if($requisition_type == 1003 || $requisition_type == 1001)
	{
	
	
	
	$pmsaQuery = "INSERT INTO prd_monthly_salary_archive_final
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
	emp_desig,
	emp_group,
	emp_pay_scale,
	emp_pay_band,
	ropa_status,
	ropa_level
	
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
	emp_desig,
	emp_group,
	emp_pay_scale,
	emp_pay_band,
	ropa_status,
	ropa_level
	FROM prd_employee_salary_save
	WHERE block_code ='" . $_SESSION['location']['block_code'] . "'
	AND salary_monthyear ='" . $monthyear . "'
	AND status_flag = 3 and delete_status='1' and is_saved='1'
	AND ropa_status='".$ropa_status."'
	";
	
	$prd_monthly_salary_archive_final = $db->insert($pmsaQuery);
	//print_r($pmsaQuery); exit;
	
	$pmsanQuery = "INSERT INTO prd_monthly_salary_archive_nonfinal(
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
	'" . $_SESSION['user_agent']['USER_IP'] . "',
	requisition_type,
	part_day
	from prd_employee_salary_save where salary_monthyear='".$monthyear."' AND block_code ='" . $_SESSION['location']['block_code'] . "'  AND requisition_type='".$requisition_type."' AND status_flag not in('3')";

	$prd_monthly_salary_archive_nonfinal = $db->insert($pmsanQuery);
	
	
	
	
	//$prd_monthly_salary_archive_nonfinal=TRUE;
	
	
	
	}
	
	// Subikar Node..
	if($requisition_type == 1003 || $requisition_type == 1001){
	if($prd_monthly_salary_archive_final==TRUE && $prd_monthly_salary_archive_nonfinal==TRUE)
	{
	//$insert_bonus_bill=TRUE;
	$pbbdQuery = "INSERT INTO prd_block_bill_details (
	bill_no, status, block_code, bill_entry_time, bill_update_time, ip_addres, salary_monthyear, ps_id_fk,
	requisition_type, zp_id_fk, bill_serial_no, drn_number, ropa_status )
	VALUES( '".$_POST['bill']."', '1', '".$_SESSION['location']['block_code']."', '".date_frmt_change($_POST['bill_date'])."',
	now(), '".$_SESSION['user_agent']['USER_IP']."', '".$monthyear."', '0', '".$requisition_type."', 0,'".$bill_serial_no."',
	'" . $drn_number . "', '" . $ropa_status . "' );
	";
	//print_r($pbbdQuery); exit;
	$insert_bonus_bill = $db->insert($pbbdQuery);
	
	}
	}
	
	else
	{
	//$insert_bonus_bill=TRUE;
	
	$insert_bonus_bill = $db->insert("INSERT INTO prd_block_bill_details ( bill_no, status, block_code, bill_entry_time, bill_update_time,
	ip_addres, salary_monthyear, ps_id_fk, requisition_type, zp_id_fk, bill_serial_no, drn_number, ropa_status )
	VALUES( '".$_POST['bill']."', '1', '".$_SESSION['location']['block_code']."', '".date_frmt_change($_POST['bill_date'])."',
	now(), '".$_SESSION['user_agent']['USER_IP']."', '".$monthyear."', '0', '".$requisition_type."', 0,'".$bill_serial_no."',
	'" . $drn_number . "', '" . $ropa_status . "' );
	");
	}
	
	
	$bill_details_fetch=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details 
	WHERE bill_no='".$_POST['bill']."'
	AND block_code='".$_SESSION['location']['block_code']."'
	AND requisition_type='".$requisition_type."'
	AND salary_monthyear='".$monthyear."'
	AND ropa_status='".$ropa_status."'
	");
	if($requisition_type == 1003 || $requisition_type == 1001){
	$update_arrear_salary = TRUE;
	
	}
	else if($requisition_type == 1002){			
	$update_arrear_salary=$db->update("UPDATE prd_employee_arrear arr
	SET 
	salary_monthyear='".$monthyear."',
	status_flag='3',
	bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."',
	bill_serial_no='".$bill_serial_no."'
	
	WHERE arr.delete_status=1 AND arr.is_saved=1  AND arr.status_flag='2' 
	AND arr.block_code = '".$_SESSION['location']['block_code']."' 
	");
	
	}
	else if($requisition_type == 1005){
	
	$update_arrear_salary=$db->update("UPDATE prd_festival_advance_employee_details fav
	SET 
	fad_monthyear='".$monthyear."',
	festival_advance_status=5,
	bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."',
	bill_serial_no='".$bill_serial_no."'
	
	WHERE fav.block_code = '".$_SESSION['location']['block_code']."'
	AND fav.festival_advance_status = '4'
	");
	
	/********************************** Changed By ANJAN - 11-09-2019 ***********************************************/	
	
	$fav_bill_fetch = $db->fetch_table("SELECT detail.emp_id_fk, detail.festival_advance_id_pk, sal.festival_advance_instalment_no FROM 
	prd_festival_advance_employee_details as detail
	INNER JOIN prd_block_bill_details as bill ON detail.bill_id_fk = bill.block_bill_pk
	INNER JOIN prd_festival_advance_entry_sal as sal ON detail.festival_advance_id_pk = sal.festival_advance_id_fk
	WHERE bill.bill_no='".$_POST['bill']."'
	AND bill.block_code='".$_SESSION['location']['block_code']."'
	AND bill.salary_monthyear='".$monthyear."'
	");
	
	
	foreach($fav_bill_fetch as $fav_bill){
	
	$current_month_first_date=date("Y-m-01");
	$next_month_date =date("Ym", strtotime("$current_month_first_date +1 month")); 
	$after_instl_month_date=date("Ym", strtotime("$current_month_first_date +$fav_bill[festival_advance_instalment_no] month"));
	
	$fad_instalment_details_update=$db->update(" UPDATE prd_festival_advance_entry_sal sal
	SET deduction_start_monthyear='".$next_month_date."',
	deduction_end_monthyear='".$after_instl_month_date."' 
	FROM prd_festival_advance_employee_details fad
	WHERE fad.festival_advance_id_pk=sal.festival_advance_id_fk
	AND fad.bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."' ");
	
	//var_dump($fad_instalment_details_update);
	}
	
	/************************************************* END ********************************************************************/
	
	
	}
	else if($requisition_type == 1004){
	$update_arrear_salary=$db->update("UPDATE prd_employee_bonus_details bon SET 
	bonus_status=5,
	bill_id_fk='".$bill_details_fetch[0]['block_bill_pk']."',
	bill_serial_no='".$bill_serial_no."'
	
	WHERE bon.bonus_status=4 
	AND bon.monthyear='".$prev_yrr.$current_year."' 
	AND bon.block_code='".$_SESSION['location']['block_code']."'");
	}
	
	
	/*
	$bill_details_fetch=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details 
	WHERE bill_no='".$_POST['bill']."'
	AND block_code = '".$_SESSION['user_info']['stake_user']."'
	AND requisition_type='".$requisition_type."'
	AND salary_monthyear='".$monthyear."'
	");
	
	*/
	//var_dump($update_arrear_salary); die;
	if($insert_bonus_bill == TRUE && $update_arrear_salary == TRUE )
	{
	//print("Test"); exit;
	
	$get_emp_id_loan = $db->fetch_table("SELECT emp_id_fk, salary_type, festival_loan FROM prd_employee_salary_save 
	WHERE block_code = '".$_SESSION['location']['block_code']."'
	AND status_flag in( '3') AND salary_monthyear = '".$monthyear."'
	AND requisition_type='".$requisition_type."'
	AND is_saved in ('1') AND delete_status in ('1') AND ropa_status='".$ropa_status."'");		
	
	/*else if($logged_user=='EO')
	{
		$get_emp_id_loan = $db->fetch_table("SELECT emp_id_fk, salary_type, festival_loan FROM prd_employee_salary_save 
	WHERE ps_id_fk = '".$_SESSION['location']['ps_id']."'
	AND status_flag in( '3') AND salary_monthyear = '".$monthyear."'
	AND requisition_type='".$requisition_type."'
	AND is_saved in ('1') AND delete_status in ('1') AND ropa_status='".$ropa_status."'");	
	
	}
	else
	{
		$get_emp_id_loan = $db->fetch_table("SELECT emp_id_fk, salary_type, festival_loan FROM prd_employee_salary_save 
	WHERE  zp_id_fk = '".$_SESSION['location']['district_id']."'
	AND status_flag in( '4') AND salary_monthyear = '".$monthyear."'
	AND requisition_type='".$requisition_type."'
	AND is_saved in ('1') AND delete_status in ('1') AND ropa_status='".$ropa_status."'");	
	
	
	
	}*/
	
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
	
	
	
	pg_query('COMMIT');
	?>
	<?php //Subikar Block Bill Message ... ?>
	<div  style="width: 28%;margin-left: 40%;text-align: center;">	<a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php' class="btn btn-success">IFMS Upload</a></div>
	<div class="alert alert-success" style="width: 23%;margin-left: 43%;text-align: center;"><strong><?php //echo $pbbdQuery; ?>Bill no. is inserted for this month</strong></div>
	
	<?php
	
	} 
	else 
	{ 
	pg_query('ROLLBACK');?>
	<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is Not inserted </strong></div>
	<?php
	} 
	}
	else
	{ 
	?>
	<div  style="width: 28%;margin-left: 40%;text-align: center;">  <a href='<?php echo $config['base_url'] ?>page/all_moduls/ifms_ajax_links_all/ajax_links_ifms_upload_details.php' class="btn btn-success">IFMS Upload</a></div>
	<div class="alert alert-danger" style="width: 23%;margin-left: 43%;text-align: center;"><strong>Bill no. is allready inserted for this month</strong></div>
	<?php
	}
	
	
}?>
