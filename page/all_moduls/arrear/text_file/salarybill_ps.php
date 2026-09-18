<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();

$dpsc_dies = $_SESSION['user_info']['stake_user'];
$path = '../../../../readwrite/text_file/';
//Permission --------------------------------------------------------------------------
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//////////////////functions////////////////

	function dateshow_slash($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	if($date==''){
	return '';
	}
	if($date=='0001-01-01'){
	return '';
	}
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'/'.$datearr['1'].'/'.$datearr['0'];
	return $dob=='--'?'':$dob;
}


$schcd = $_SESSION['user_info']['stake_user'];
$mo = $_GET['mo'];
$ye = $_GET['ye'];

$month_year=$ye.$mo;
$bill_no=isset($_GET['bill'])?$_GET['bill']:'';

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='406'");
$requisition_type=$requisition[0]['code'];


$code_data = $db->fetch_table("
		SELECT code, description
		FROM prd_dise_code_master;
	
");


$db = new database();
	//$dpsc_sql = $db->fetch_table("Select treasury_block_code,ddo_code from prd_dise_admin where block_code='3299001'");
	// $psemp_ps_profile=$db->fetch_table("select ddo_code from psemp_ps_profile where ps_id_fk='".$_SESSION['location']['ps_id']."'");
 $psemp_ps_profile=$db->fetch_table("select ddo_code,treasury_code from psemp_ps_profile where ps_id_fk='".$_SESSION['location']['ps_id']."'");
	
		$dpsc_code_new=$psemp_ps_profile[0]['ddo_code'];
		$dpsc_code_array=explode("-",$dpsc_code_new);
		foreach($dpsc_code_array as $in=>$data)
		$dpsc_code.=$data;
		$dpsc_code_slash=$dpsc_code;
		
		
	if($psemp_ps_profile[0]['treasury_code']!="")
{
	$treasury_code=substr($psemp_ps_profile[0]['treasury_code'],0,3);
}
else
{
	$treasury_code=$sql_salarymonth[0]['treasury_block_code'];
}
	
	
$db = new database();	

	
	
	
	$sql_query=$db->fetch_table("select block_bill_pk,bill_no,bill_entry_time,salary_monthyear from prd_block_bill_details where ps_id_fk = '".$_SESSION['location']['ps_id']."' and bill_no='".$bill_no."' and salary_monthyear= '".$month_year."' AND requisition_type='".$requisition_type."'");


$db = new database();
	$sql_query_head=$db->fetch_table("select * from psemp_head_details");
  

$db = new database();

		
		$teacher_dtls=$db->fetch_table("select 
		emp.emp_first_name,
		emp.emp_second_name,
		emp.emp_last_name, 
		emp.gp_id_fk ,
		emp.emp_status,
		emp.emp_dob,
		emp.emp_first_join_date,
		emp.emp_sex,
		emp.emp_grade_pay,
		emp.emp_caste,
		arrear.salary_monthyear,
		emp.emp_bank_name,
		emp.emp_acc_no,
		arrear.basic,
		arrear.da,
		arrear.interim_relief,
		arrear.hra,
		arrear.ma, 
		arrear.gpf,
		arrear.pf_loan, 
		arrear.p_tax, 
		arrear.i_tax,
		arrear.net, 
		emp.emp_ifsc_no,
		arrear.conv_allow,
		arrear.consolidated_pay,
        arrear.hill_allowance,
		arrear.overdrawn,
		arrear.gross_salary
		from prd_location_master_panchayat_samiti ps    
	inner join prd_employee_master emp on ps.ps_id_pk=emp.ps_id_fk
	left join prd_employee_arrear arrear ON (arrear.emp_id_fk=emp.emp_id_pk and ps.ps_id_pk=arrear.ps_id_fk)
	where 
		trim(arrear.salary_monthyear)='".$sql_query[0]['salary_monthyear']."'
		AND emp.emp_status in('1') 
		AND arrear.delete_status='1' 
		AND arrear.status_flag='3'
		AND arrear.is_saved='1'
		AND arrear.ps_id_fk='".$_SESSION['location']['ps_id']."' 
		AND arrear.bill_id_fk='".$sql_query[0]['block_bill_pk']."'
		ORDER BY emp.gp_id_fk ASC");		

	foreach($teacher_dtls as $teacher_row){
		
		 $ern_amount_da=($ern_amount_da)+ ($teacher_row['da']);
		 $ern_amount_interim_relief=($ern_amount_interim_relief)+ ($teacher_row['interim_relief']);
		 $ern_amount_hra=($ern_amount_hra)+ ($teacher_row['hra']);
		 $ern_amount_ma=($ern_amount_ma)+ ($teacher_row['ma']);
		 $ern_amount_basic=($ern_amount_basic)+ ($teacher_row['basic']);
		 $ern_amount_spl_pay=($ern_amount_spl_pay)+ ($teacher_row['spl_pay']);
		 $ern_amount_conv_allow=($ern_amount_conv_allow)+ ($teacher_row['conv_allow']);
		 $hill_allowance=($hill_allowance)+ ($teacher_row['hill_allowance']);
		 $consolidated_pay=($consolidated_pay)+ ($teacher_row['consolidated_pay']);
		 $gpf=($gpf)+ ($teacher_row['gpf']);
		 $p_tax=($p_tax)+ ($teacher_row['p_tax']);
		 $i_tax=($i_tax)+ ($teacher_row['i_tax']);
		 //$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
		 $pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
		 $cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
		 $hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
		 $festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
		 $overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
		 $advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);
	     $gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		 $hra_deduction=0;
		  
	 }

$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
$ern_amount1=($gross_salary);


$ag_total=($gpf)+($pf_loan_total);
$deduc_amt=($i_tax)+($p_tax)+($hra_deduction);
$deduc_total= ($deduc_amt)+($ag_total);
$net_amount=($ern_amount)-($deduc_total);
/*$sql_salarymonth=$db->fetch_table("
										select DISTINCT(sal.salary_monthyear), ad.lead_bank, ad.lead_branch,ad.treasury_block_code from prd_employee_master as emp 
										inner join prd_location_master_panchayat_samiti as ps on emp.ps_id_fk=ps.ps_id_pk 
										inner join (select salary_monthyear,status_flag,ps_id_fk,bill_id_fk from prd_employee_arrear where salary_monthyear='".$sql_query[0]['salary_monthyear']."') as sal 
										on  emp.ps_id_fk=sal.ps_id_fk 
										inner join prd_dise_admin ad on ad.block_code=ps.ps_code 
										where sal.ps_id_fk='".$_SESSION['location']['ps_id']."' 
										and sal.status_flag='3' and sal.bill_id_fk='".$sql_query[0]['block_bill_pk']."' and emp.emp_status='1' OR emp.emp_status='9'");*/
						
		


$db = new database();

$sql_query_schtype=$db->fetch_table("select * from prd_head_code where type_status='1'");
		
		
 $schtype_code=$sql_query_schtype[0]['depart_code']."-".$sql_query_schtype[0]['demand_no']."-".$sql_query_schtype[0]['maj_head']."-".$sql_query_schtype[0]['s_maj_head']."-".$sql_query_schtype[0]['minor_head']."-".$sql_query_schtype[0]['plan_head']."-".$sql_query_schtype[0]['schm_head']."-".$sql_query_schtype[0]['vot_ch']."-".$sql_query_schtype[0]['dtl_head']."-".$sql_query_schtype[0]['sdtl_head']; 
		

  



//$net_amount=(($ern_amount)+($cooperative_loan)+($hbl_loan)+($festival_loan)+($overdrawn)+($gsli))-($deduc_total);




////////////////////////////content//////////////////////////////////////////
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				//.$schtype_code."#"
				.str_pad($ern_amount,8,' ',STR_PAD_LEFT)."#"
				."ERN##"."\r\n"; 
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				."Gross Amount#"
				.str_pad($ern_amount,8,' ',STR_PAD_LEFT)."##"."\r\n"; 
	
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				//.$det_head[0]['pf_maj_head']."-".$det_head[0]['pf_s_maj_head']."-".$det_head[0]['pf_minor_head']."-".$det_head[0]['pf_schm_head']."-".$det_head[0]['pf_dtl_head']."#"
				."----#"
				.str_pad(substr($ag_total,0,8),8,' ',STR_PAD_LEFT)."#"
				."TRY##"."\r\n"; 
	
	/*$content.= 	date("M",strtotime(substr($teacher_row['salary_monthyear'],0,4)."-".substr($teacher_row['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($bill_det['bill_no'],0,10)."#"
				.dateshow_slash(substr($bill_det['bill_entry_time'],0,10))."#"
				.$det_head['pf_maj_head']."-".$det_head['pf_s_maj_head']."-".$det_head['pf_minor_head']."-".$det_head['pf_schm_head']."-".$det_head['pf_dtl_head']."#"
				.str_pad(substr(0,0,8),8,' ',STR_PAD_LEFT)."#"
				."AG##"."\r\n"; */
	
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				//.$det_head[0]['it_maj_head']."-".$det_head[0]['it_s_maj_head']."-".$det_head[0]['it_minor_head']."-".$det_head[0]['it_schm_head']."-".$det_head[0]['it_dtl_head']."#"
				."----#"
				.str_pad(substr($i_tax,0,8),8,' ',STR_PAD_LEFT)."#"
				."TRY##"."\r\n"; 
	
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				//.$det_head[0]['pt_maj_head']."-".$det_head[0]['pt_s_maj_head']."-".$det_head[0]['pt_minor_head']."-".$det_head[0]['pt_schm_head']."-".$det_head[0]['pt_dtl_head']."#"
				."----#"
				.str_pad(substr($p_tax,0,8),8,' ',STR_PAD_LEFT)."#"
				."TRY##"."\r\n"; 
	
	/// hra deduction heads are being put in hard code /////////
	
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				//."00-0216-02-101-00-001-0-05-00"."#"
				."----#"
				.str_pad(substr($hra_deduction,0,8),8,' ',STR_PAD_LEFT)."#"
				."TRY##"."\r\n"; 
	
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				//."00-0216-02-101-00-001-0-05-00"."#"
				."----#"
				.str_pad(substr($gsli,0,8),8,' ',STR_PAD_LEFT)."#"
				."TRY##"."\r\n";
	
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				."Net Amount#"

				.str_pad(substr($net_amount,0,10),10,' ',STR_PAD_LEFT)."##"."\r\n"; 
	
	/*$content.= date("M",substr($bill_row['salary_monthyear'],4))."# ".substr($bill_row['salary_monthyear'],0,4)."# ".$dpsc_code."# ".$bill_det['bill_no']."#".dateshow(substr($bill_det['bill_entry_time'],0,10))."#[emp_group]#[Permanent No]#[Permanent No]#[Temporary No]#[total]#ACT## \n";*/
	
	/*$content.= date("M",substr($bill_row['salary_monthyear'],4))."# ".substr($bill_row['salary_monthyear'],0,4)."# ".$dpsc_code."# ".$bill_det['bill_no']."#".dateshow(substr($bill_det['bill_entry_time'],0,10))."#[Head Of Account]#[GIS Group]#[No of Employee in this group]#[Total Amount]#GIS## \n";*/
	
	
	$content.= 	date("M",strtotime(substr($sql_query[0]['salary_monthyear'],0,4)."-".substr($sql_query[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.$treasury_code."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,20)."#"
				//."4371/ES#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				//.$schtype_code."#"
				."----#"
				//.str_pad(substr($sql_salarymonth[0]['lead_bank']." ".$sql_salarymonth[0]['lead_branch'],0,25),25,' ')."#"
				.str_pad(substr($net_amount,0,10),10,' ',STR_PAD_LEFT)."#CHQ##"."\r\n";
				


/////////////////Creatae file name/////////////////////////
$bill_array=explode("/",$sql_query[0]['bill_no']);
	foreach($bill_array as $a=>$i){
	$billno1=$billno1.$i;
	}
	$bill_array=explode("-",$billno1);
	foreach($bill_array as $a=>$i){
	$billno=$billno.$i;
	}
	$bill_date_array=explode("/",dateshow_slash($sql_query[0]['bill_entry_time']));
	foreach($bill_date_array as $a=>$i){
	$billdate=$billdate.$i;
	}
/////////////////Creatae file name/////////////////////////
//File Generate ----------------------------------------------------------------------------------------------------------------------------------------

//MONTH(10)YEAR(2015)TR_TYPE(31)DDO_CODE(NPCEDS002)ANY_SERIAL_NO(132)CREATED_DATE(07102015)_BillSummary

  $filename=substr($sql_query[0]['salary_monthyear'],4).substr($sql_query[0]['salary_monthyear'],0,4).$treasury_code.$dpsc_code.$billno.$billdate."_BillSummary.txt";
  //echo $filename;die;
	/*$handle = fopen("text/".$filename,"w");
	fwrite($handle,$content);
    fclose($handle);*/
	
	
	$file = fopen($path.$filename,"w");
    fwrite($file,$content);
	fclose($file);
//Force Download ---------------------------------------------------------------------------------------------------------------------------------------
	if (file_exists($path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('text/'.$path.$filename));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path.$filename));
    ob_clean();
    flush();
    readfile($path.$filename);
    exit;
}
?>