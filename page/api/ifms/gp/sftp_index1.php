<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

ob_start();
session_start();

header("Access-Control-Allow-Headers: Origin");
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config_api.php';
require '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
include( '../../../all_function/fun_store/ifms_functions.php');
$path = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/ePayment_Files_006/';

$crypto = new cryptography();
//header("Contet-type : text/xml");
$schcd = $_SESSION['user_info']['stake_user'];
$mo = date('m');
$ye = date('Y');

$drn_number =$crypto->decode($_GET['drn_no'],4);
$bill_type =$crypto->decode($_GET['bill_type'],4);
$bill_serial_no=$crypto->decode($_GET['bill_serial_no'],4);
  $ropa_status=$crypto->decode($_GET['ropa_status_new'],4);

$yemo = $ye.$mo;
$monthyear=$_GET['monthyear'];
$db = new database();

$party_code='006';

$det_head=$db->fetch_table("select * from prd_head_details where block_code='".$schcd."'");

$ptax_head=$det_head[0]['pt_maj_head']."-".$det_head[0]['pt_s_maj_head']."-".$det_head[0]['pt_minor_head']."-".$det_head[0]['pt_schm_head']."-".$det_head[0]['pt_dtl_head'];

$i_tax_head=$det_head[0]['it_maj_head']."-".$det_head[0]['it_s_maj_head']."-".$det_head[0]['it_minor_head']."-".$det_head[0]['it_schm_head']."-".$det_head[0]['it_dtl_head'];

$ag_head=$det_head[0]['pf_maj_head']."-".$det_head[0]['pf_s_maj_head']."-".$det_head[0]['pf_minor_head']."-".$det_head[0]['pf_schm_head']."-".$det_head[0]['pf_dtl_head'];




$ddo_sql = $db->fetch_table("Select treasury_block_code,ddo_code from prd_dise_admin where block_code = '".$schcd."'");

$ddo_code_name=$ddo_sql[0]['ddo_code'];

$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));
if($bill_type=='1001')
{
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE block_code='".$schcd."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1'");

}
else if($bill_type=='1002' || $bill_type=='1003' || $bill_type=='1004' || $bill_type=='1005')
{
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE block_code='".$schcd."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' AND bill_serial_no='".$bill_serial_no."'");

}
$sftp_file_existance_check=$db->fetch_table(" SELECT sftp_benf_id_pk FROM prd_sftp_benf_upload_response WHERE bill_id_fk='".$bill_det[0]['block_bill_pk']."' AND active_status='1' ");

if(count($sftp_file_existance_check)>0)
{
	echo "File has been sent already. Do not send again.";
	exit;
}


$sftp_details_fetch=$db->fetch_table(" SELECT sftp_benf_id_pk,sftp_benf_file_name FROM prd_sftp_benf_upload_response WHERE bill_id_fk='".$bill_det[0]['block_bill_pk']."' AND active_status='2' ");

if(count($sftp_details_fetch)==0)
{
	$file_sequence='01';
}
else
{
	$seq_no=substr($sftp_details_fetch[0]['sftp_benf_file_name'],20,2);
	$sum=$seq_no+1;
	$file_sequence=str_pad($sum,2,'0',STR_PAD_LEFT);
}



$det_head=$db->fetch_table("SELECT * FROM prd_head_details WHERE block_code='".$schcd."'");

$no_of_row=count($det_head); 

if($bill_type=='1001')
{

	if($monthyear!=$yemo)
		{
			
					$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
					emp.emp_first_name,
					emp.emp_second_name,
					emp.emp_last_name, 
					emp.gp_id_fk ,
					emp.emp_status,
					emp.emp_sex,
					emp.emp_grade_pay,
					emp.emp_caste,
					emp.emp_mobile_no,
					emp.emp_id_const,
					sal.salary_monthyear,
					emp.emp_bank_name,
					emp.emp_acc_no,
					sal.accountno,
					sal.bank_ifsc,
					sal.basic,
					sal.da,
					sal.interim_relief,
					sal.hra,
					sal.ma, 
					sal.gpf,
					sal.pf_loan, 
					sal.p_tax, 
					sal.i_tax,
					sal.net, 
					emp.emp_ifsc_no, 
					sal.pf_deduct,
					sal.conv_allow,
					sal.consolidated_pay,
					sal.hill_allowance,
					sal.festival_loan,
					sal.overdrawn,
					sal.gsli,
					sal.gross_salary
					from prd_location_master_block block
					inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
					inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
					left join prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and CAST(block.block_code as character varying)=sal.block_code)
					where 
					trim(sal.salary_monthyear)='".$monthyear."'
					AND emp.emp_status in('1','9','2') 
					AND sal.delete_status='1' 
					AND sal.status_flag='3'
					AND sal.is_saved='1'
					AND sal.block_code='".$schcd."' 
					AND sal.requisition_type='".$bill_type."'
					AND sal.salary_type!='8'
					AND sal.gross_salary!='0'
					AND sal.ropa_status='". $ropa_status."'
					ORDER BY emp.gp_id_fk ASC");
		
	  }
	else
		{
		
				$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
				emp.emp_first_name,
				emp.emp_second_name,
				emp.emp_last_name, 
				emp.gp_id_fk ,
				emp.emp_status,
				emp.emp_sex,
				emp.emp_grade_pay,
				emp.emp_caste,
				emp.emp_mobile_no,
				emp.emp_id_const,
				sal.salary_monthyear,
				emp.emp_bank_name,
				emp.emp_acc_no,
				sal.accountno,
				sal.bank_ifsc,
				sal.basic,
				sal.da,
				sal.interim_relief,
				sal.hra,
				sal.ma, 
				sal.gpf,
				sal.pf_loan, 
				sal.p_tax, 
				sal.i_tax,
				sal.net, 
				emp.emp_ifsc_no, 
				sal.pf_deduct,
				sal.conv_allow,
				sal.consolidated_pay,
				sal.hill_allowance,
				sal.festival_loan,
				sal.overdrawn,
				sal.gsli,
				sal.gross_salary
				from prd_location_master_block block
				inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
				inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
				left join prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and CAST(block.block_code as character varying)=sal.block_code)
				where 
				trim(sal.salary_monthyear)='".$monthyear."'
				AND emp.emp_status in('1','9') 
				AND sal.delete_status='1' 
				AND sal.status_flag='3'
				AND sal.is_saved='1'
				AND sal.block_code='".$schcd."' 
				AND sal.requisition_type='".$bill_type."'
				AND sal.salary_type!='8'
				AND sal.gross_salary!='0'
				AND sal.ropa_status='". $ropa_status."'
				ORDER BY emp.gp_id_fk ASC");
		
	  }

}
else if($bill_type=='1002')
{
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  block_code='".$schcd."' 
				AND bill_serial_no='".$bill_serial_no."' and status='1'");
	
	
	
	$teacher_dtls=$db->fetch_table("select  
	                                emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.gp_id_fk ,
									emp.emp_status,
									emp.emp_sex,
									emp.emp_grade_pay,
									emp.emp_caste,
									emp.emp_mobile_no,
									emp.emp_id_const,									
									sal.salary_monthyear,
									emp.emp_bank_name,
									emp.emp_acc_no,
									sal.accountno,
									sal.bank_ifsc,
									sal.basic,
									sal.da,
									sal.interim_relief,
									sal.hra,
									sal.ma, 
									sal.gpf,
									sal.pf_loan, 
									sal.p_tax, 
									sal.i_tax,
									sal.gsli,
									sal.net, 
									emp.emp_ifsc_no, 
									sal.conv_allow,
									sal.consolidated_pay,
									sal.hill_allowance,
									sal.gross_salary
	from prd_location_master_block block
	inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
	inner join prd_employee_master emp on gp.gp_id_pk