<?
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
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
$gp_code=$cryptoGraph->decode($_REQUEST['gp_code'],4);
$db = new database();
//echo $config['base_url'].'page/intra_prd/block/salary_finalize.php';exit;
$arr=$db->fetch_table(" SELECT  emp_id_fk,gp_id_fk from prd_employee_salary_save where gp_code='$gp_code' AND ropa_status='2'");

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

//echo $update." ".$circle_code ;

	/*if($gp_code !=''){
	
	//for insert into ehrms_monthly_salary_archive_nonfinal
	$archive_not_final=$db->fetch_table("INSERT INTO prd_monthly_salary_archive_nonfinal(
            slno, latestupdate_time, latestupdate_ip_address, gp_id_fk, empcd, 
            bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
            i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
            emp_salary_id_fk, spl_alo, status_flag, salary_monthyear, category_id, 
            block_code, emp_id_fk, pay_payband, tch_grade_pay, hill_allowance, 
            gpf, cpf_deduct, gross_salary, is_saved, conv_allow, overdrawn, 
            salary_type, cause, gp_code, part_day, gsli, delete_status,consolidated_pay,entry_time,entry_ip)
     SELECT slno, latestupdate_time, latestupdate_ip_address, gp_id_fk, empcd, 
       bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, category_id, 
       block_code, emp_id_fk, pay_payband, tch_grade_pay, hill_allowance, 
       gpf, cpf_deduct, gross_salary, is_saved, conv_allow, overdrawn, 
       salary_type, cause, gp_code, part_day, gsli, delete_status,consolidated_pay,now(),'".$_SESSION['user_agent']['USER_IP']."'
	    from prd_employee_salary_save where gp_code='$gp_code' AND salary_monthyear='".date('Ym')."' and is_saved='1' and delete_status='1' and status_flag='2'");
	}
	  */
	 
	if($gp_code !=''){
		

	
	
	
$update = $db->update("
		UPDATE prd_employee_salary_save
		SET status_flag ='1'
		WHERE status_flag ='2' AND gp_code = '".$gp_code."' AND salary_monthyear='".date('Ym')."' and delete_status='1' and is_saved='1' AND requisition_type='".$requisition_type."' AND ropa_status='2'");
}
	
	if($update>0 && $gp_code !='' ){
	$query = $db->fetch_table("select now() now") ;
	$now = $query[0]['now'] ;
	
	$promotion_eff_chk_yr_mnth = date("Y-m");
	
	/*$promotion_data_cal = $db->fetch_table("SELECT emp_id_fk
												FROM prd_employee_promotion_details
												WHERE promotion_effective_status in('2') 
												AND approval_status in(5,6)
												and gp_id_fk='".$arr[0]['gp_id_fk']."'
												AND effective_date LIKE '".$promotion_eff_chk_yr_mnth."%'
	");*/
	$promotion_data_cal = $db->fetch_table("SELECT emp_id_fk
												FROM prd_employee_promotion_details
												WHERE promotion_effective_status in('2') 
												AND approval_status in(88)
												and gp_id_fk='".$arr[0]['gp_id_fk']."'
												AND effective_date LIKE '".$promotion_eff_chk_yr_mnth."%'
	");
	
	
	//print_r($promotion_data_cal); die;
	 
	//echo date("m"); die;
	foreach ($promotion_data_cal as $promo_cal) {
		
	//$emp_effective_date_for_promotion_cal =  $promo_cal['effective_date'];
	//$emp_effective_month=substr ($emp_effective_date_for_promotion_cal,5,2);
	//$emp_effective_year=substr ($emp_effective_date_for_promotion_cal,0,4);
	$promo_emp_id_fk=  $promo_cal['emp_id_fk'];
		
		$update_promotion_table = $db->update("UPDATE prd_employee_promotion_details
														SET promotion_effective_status = '1'
														WHERE promotion_effective_status in('2')
														AND emp_id_fk='".$promo_emp_id_fk."'");
														
	}
	$security = $db->insert("
							INSERT INTO
										prd_salary_log (
															ip,
															date,
															browser,
															os,
															salary_status_id_fk,
															created_by,
															created_by_stake,
															gp_id_fk,
															emp_id_fk
															
														)
										VALUES 			(
															'".$_SESSION['user_agent']['USER_IP']."',
															now(),
															'".$_SESSION['user_agent']['BROWSER']."',
															'".$_SESSION['user_agent']['OS']."',
															1,
															'".$_SESSION['user_info']['stake_user']."',
															'".$_SESSION['user_info']['stake_level']."',
															'".$arr[0]['gp_id_fk']."',
															'".$arr[0]['emp_id_fk']."'
														)
							
									");
									
									/*echo "INSERT INTO
										prd_salary_log (
															ip,
															date,
															browser,
															os,
															salary_status_id_fk,
															created_by,
															created_by_stake,
															gp_id_fk,
															emp_id_fk
															
														)
										VALUES 			(
															'".$_SESSION['user_agent']['USER_IP']."',
															now(),
															'".$_SESSION['user_agent']['BROWSER']."',
															'".$_SESSION['user_agent']['OS']."',
															1,
															'".$_SESSION['user_info']['stake_user']."',
															'".$_SESSION['user_info']['stake_level']."',
															'".$arr[0]['gp_id_fk']."',
															'".$arr[0]['emp_id_fk']."'
														)";exit;*/
									
}

if($security)
{
	$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>GP successfully unlocked.</strong></div>';
	 //echo $cryptoGraph->encode('<div class="alert alert-success" style="text-align:center"><strong>GP successfully unlocked.</strong></div>',4);
	 header('Location:'.$config['base_url'].'page/intra_prd/block/salary_finalize.php');
	 exit(0);
	//header('Location:'.$config['base_url'].'page/intra_prd/block/salary_finalize.php');
	//echo "GP successfully unlocked.";	
}else {
	 $_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Unlock failed. Please try again.</strong></div>';
	  header('Location:'.$config['base_url'].'page/intra_prd/block/salary_finalize.php');
	 exit(0);
	//header('Location:'.$config['base_url'].'page/intra_prd/block/salary_finalize.php');
	//echo "Unlock failed. Please try again.";	
}

?>