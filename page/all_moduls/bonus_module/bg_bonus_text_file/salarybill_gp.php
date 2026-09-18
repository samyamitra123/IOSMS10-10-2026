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

$current_year=date('Y');
$next_year=$current_year+1;

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

/////////////content///////////////////////
//print_r($_REQUEST);
$schcd = $_SESSION['user_info']['stake_user'];
$mo = $_GET['mo'];
$ye = $_GET['ye'];
//$bill_no =  $crypto->decode($_GET['bill'], 4);
//$bill_date=$crypto->decode($_GET['bill_date'], 4);
//echo last_day($ye, $mo);
//echo "<br />".$mo ."<br />". $ye."<br />". $bill_no."<br />". $bill_date; exit ;
$month_year=$ye.$mo;
$bill_no=isset($_GET['bill'])?$_GET['bill']:'';

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='428'");
$requisition_type=$requisition[0]['code'];


$code_data = $db->fetch_table("
		SELECT code, description
		FROM prd_dise_code_master;
	
");

/*echo "SELECT code, description
		FROM ehrms_dise_code_master";exit;*/
		
		//echo "Select treasury_dpsc_code from ehrms_dise_admin_primary where dpsc_code = '".$_SESSION['user_info']['stake_user']."'";exit;
$db = new database();
	$dpsc_sql = $db->fetch_table("Select treasury_block_code,ddo_code from prd_dise_admin where block_code = '".$_SESSION['user_info']['stake_user']."'");
	//$row_dpsc_sql = pg_query($conn,$dpsc_sql);
//	$rs_dpsc_sql=pg_fetch_array($row_dpsc_sql);
	//foreach($dpsc_sql as $rs_dpsc_sql){
		
		//$dpsc_code_new=$dpsc_sql[0]['treasury_block_code'];
	//	$dpsc_code_array=explode("-",$dpsc_code_new);
		//$dpsc_ddo_code_new=$dpsc_sql[0]['ddo_code'];
	//	$dpsc_ddo_code_array=explode("-",$dpsc_ddo_code_new);
		
		/*foreach($dpsc_code_array as $in=>$data)
		$dpsc_code.=$data;
		$dpsc_code_slash=$dpsc_code;*/
		
		$dpsc_code_new=$dpsc_sql[0]['ddo_code'];
		$dpsc_code_array=explode("-",$dpsc_code_new);
		foreach($dpsc_code_array as $in=>$data)
		$dpsc_code.=$data;
		$dpsc_code_slash=$dpsc_code;
		
		
		
	//}

/*echo "select bill_no,bill_entry_time,salary_monthyear from ehrms_dpsc_bill_details where dpsc_code='".$_SESSION['user_info']['stake_user']."' and bill_no='".$bill_no."' and salary_monthyear= '".$month_year."'";exit;*/

$db = new database();	

		
	$sql_query=$db->fetch_table("
									SELECT 
											bill.block_bill_pk,
											bill.bill_no,
											bill.bill_entry_time,
											bill.salary_monthyear 
									FROM prd_block_bill_details bill
									WHERE bill.block_code='".$_SESSION['location']['block_code']."' and bill.bill_no='".$bill_no."' 
									AND bill.salary_monthyear= '".$month_year."' AND bill.requisition_type='".$requisition_type."' 
									AND bill.status='1'
	");
	
	
	
	
	/*$row_data=pg_query($conn,$sql_query);
	$bill_det=pg_fetch_array($row_data);*/
	//print_r($sql_query);
//	echo $_SESSION['user_info']['stake_user'].'<br>';
//	echo $sql_query[0]['salary_monthyear']."<br>";
//	echo $sql_query[0]['salary_source'];
//echo "select * from ehrms_dise_head_details_dpsc where dpsc_code='".$_SESSION['user_info']['stake_user']."'";exit;

$db = new database();
	$sql_query_head=$db->fetch_table("select * from prd_head_details_block where block_code='".$_SESSION['user_info']['stake_user']."'");
   

$db = new database();
/*$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
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
		sal.salary_monthyear,
		sal.sal_source,
		emp.emp_bank_name,
		emp.emp_acc_no,
		sal.basic,
		sal.da,
		sal.hra,
		sal.ma, 
		sal.gpf,
		sal.pf_loan, 
		sal.p_tax, 
		sal.i_tax,
		sal.net, 
		emp.emp_ifsc_no, 
		sal.spl_pay,
		sal.pf_deduct,
		sal.conv_allow,
		sal.consolidated_pay,
        sal.hill_allowance,
		sal.cooperative_loan,
        sal.hbl_loan,
		sal.festival_loan,
		sal.overdrawn,
		sal.gsli
		from prd_employee_master as emp 
		inner join prd_location_master_gp as gp on gp.gp_id_pk=emp.gp_id_fk  
		inner join prd_monthly_salary_archive_final as sal on CAST(emp.empcd AS text)=sal.empcd and CAST(emp.gp_id_fk AS text)=sal.gp_id_fk 
		
		where sal.block_code like '".$_SESSION['location']['block_code']."' 
					
					and
						sal.salary_monthyear='".$sql_query[0]['salary_monthyear']."'
					and 
						sal.status_flag='3'
					and 
						emp.emp_status='1'");
						*/
		
		
		/*$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
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
		sal.salary_monthyear,
		sal.sal_source,
		emp.emp_bank_name,
		emp.emp_acc_no,
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
		sal.spl_pay,
		sal.pf_deduct,
		sal.conv_allow,
		sal.consolidated_pay,
        sal.hill_allowance,
		sal.cooperative_loan,
        sal.hbl_loan,
		sal.festival_loan,
		sal.overdrawn,
		sal.gsli,
		sal.gross_salary,
		sal.advance_amount
		from prd_employee_master emp
        left join prd_employee_salary_save sal
         ON sal.emp_id_fk=emp.emp_id_pk 
	where 
		trim(sal.salary_monthyear)='".$sql_query[0]['salary_monthyear']."'
		AND emp.emp_status in('1','9') 
		AND sal.delete_status='1' 
		AND sal.status_flag='3'
		AND sal.is_saved='1'
		AND sal.block_code='".$_SESSION['location']['block_code']."' 
		ORDER BY emp.gp_id_fk ASC");	*/
	
	
		$teacher_dtls=$db->fetch_table("SELECT  
		emp.emp_first_name,
		emp.emp_second_name,
		emp.emp_last_name, 
		emp.ps_id_fk ,
		emp.emp_status,
		emp.emp_dob,
		emp.emp_first_join_date,
		emp.emp_sex,
		emp.emp_grade_pay,
		emp.emp_caste,
		emp.emp_bank_name,
		emp.emp_acc_no,
		bonus.bonus_amount, 
		emp.emp_ifsc_no
		FROM prd_location_master_gp gp          
		INNER JOIN prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
		LEFT JOIN prd_employee_bonus_details bonus 
		ON bonus.emp_id_fk=emp.emp_id_pk AND bonus.gp_id_fk=emp.gp_id_fk
		WHERE 
		emp.emp_status in('1','9') 
		AND bonus.delete_status='1' 
		AND bonus.bonus_status=5
		AND gp.block_id_fk='".$_SESSION['location']['block_id']."'
		AND bonus.bill_id_fk='".$sql_query[0]['block_bill_pk']."'
		ORDER BY emp.gp_id_fk ASC");		
/************************************************************************************/											
						
//$teacher_dtls=$db->fetch_table("select tch.tchcd, 
//		tch.tchname, 
//		tch.schcd ,
//		tch.status,
//		tch.code,
//		tch.category,
//		tch.dob,
//		tch.tch_date_joining,
//		tch.sex,
//		tch.tch_grade_pay,
//		tch.caste,
//		tch.tch_approval_no,
//		sal.salary_monthyear,
//		sal.sal_source,
//		tch.bankname,
//		tch.accountno,
//		sal.basic,
//		sal.da,
//		sal.hra,
//		sal.ma, 
//		sal.cpf,
//		sal.pf_loan, 
//		sal.p_tax, 
//		sal.i_tax, 
//		sal.net, 
//		tch.bank_ifsc, 
//		sal.sal_source, 
//		sal.spl_pay,
//		sal.pf_deduct, 
//		sal.status_flag,
//		sdiv.subdiv_name,
//		tch.tch_approval_date 
//		from ehrms_dise_teacher as tch
//		inner join ehrms_dise_location_master_school as sch
//			on sch.school_dise_code=tch.schcd
//		inner join ehrms_teacher_salary_save as sal
//			on tch.tchcd=sal.tchcd and tch.schcd=sal.schcd
//		inner join ehrms_dise_location_master_subdiv as sdiv
//			on sdiv.subdiv_id_pk=sch.subdiv_id_fk
//		inner join ehrms_dise_admin ad
//			on ad.dpsc_code=sdiv.subdiv_code
//		inner join sed_dise_teacher_salary_finalize sf
//			on sf.schcd=sal.schcd
//		
//		where sdiv.subdiv_code = '".$_SESSION['user_info']['stake_user']."' 
//					and 
//						ad.dpsc_code='".$_SESSION['user_info']['stake_user']."'
//					and
//						sal.salary_monthyear='".$sql_query[0]['salary_monthyear']."'
//					and 
//						sal.status_flag!='0' 
//					and 
//						sal.category_id='1' 
//					and
//						sal.sal_source='".$so."'
//					and 
//						tch.status in('1','8')
//					and 
//						sf.status='1' 
//					and 
//						sf.category_id='1'
//					and 
//						sf.salary_monthyear='".$sql_query[0]['salary_monthyear']."'");

/************************************************************************************/	

/*echo "<pre>";
print_r($teacher_dtls);*/
//$count_arr=count($teacher_dtls);
//for($i=0; $i<$count_arr; $i++){
	/*print_r($teacher_dtls);
	exit;*/
	foreach($teacher_dtls as $teacher_row){
		//print_r($teacher_row);
		
		 //$teacher_row['basic'];
		 //$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
		 //$ern_amount_da=($ern_amount_da)+ ($teacher_row['da']);
//		 $ern_amount_interim_relief=($ern_amount_interim_relief)+ ($teacher_row['interim_relief']);
//		 $ern_amount_hra=($ern_amount_hra)+ ($teacher_row['hra']);
//		 $ern_amount_ma=($ern_amount_ma)+ ($teacher_row['ma']);
//		 $ern_amount_basic=($ern_amount_basic)+ ($teacher_row['basic']);
//		 $ern_amount_spl_pay=($ern_amount_spl_pay)+ ($teacher_row['spl_pay']);
//		 $ern_amount_conv_allow=($ern_amount_conv_allow)+ ($teacher_row['conv_allow']);
//		 $hill_allowance=($hill_allowance)+ ($teacher_row['hill_allowance']);
//		 $consolidated_pay=($consolidated_pay)+ ($teacher_row['consolidated_pay']);
//		 $gpf=($gpf)+ ($teacher_row['gpf']);
//		 $p_tax=($p_tax)+ ($teacher_row['p_tax']);
//		 $i_tax=($i_tax)+ ($teacher_row['i_tax']);
//		 //$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
//		 $pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
//		 $cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
//		 $hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
//		 $festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
//		 $overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
//		 $advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);
//	     $gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		  
		 $total_bonus_amount=($total_bonus_amount)+ ($teacher_row['bonus_amount']); 
	 }
/*	 echo $ern_amount_basic."<br>";
	 echo $ern_amount_da."<br>";
	 echo $ern_amount_hra."<br>";
	 echo $ern_amount_ma."<br>";
	 echo $ern_amount_spl_pay."<br>";
	 echo $ern_amount_conv_allow."<br>";
	 echo $gpf."<br>";
	 echo $p_tax."<br>";
	 echo $i_tax."<br>";
	 print_r($teacher_dtls);
	exit;*/
	 
//echo $total_price=$ern_amount_da+$ern_amount_hra+$ern_amount_ma+$ern_amount_basic+$ern_amount_spl_pay+$ern_amount_cpf+$p_tax+$i_tax+$pf_total+$pf_loan_total;
//exit;

	//foreach($teacher_dtls as $teacher_row){
		 //$teacher_row['basic'];
		 //$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
//		 $ern_amount_da=($ern_amount_da)+ ($teacher_dtls[0]['da']);
//		 $ern_amount_hra=($ern_amount_hra)+ ($teacher_dtls[0]['hra']);
//		 $ern_amount_ma=($ern_amount_ma)+ ($teacher_dtls[0]['ma']);
//		 $ern_amount_basic=($ern_amount_basic)+ ($teacher_dtls[0]['basic']);
//		 $ern_amount_spl_pay=($ern_amount_spl_pay)+ ($teacher_dtls[0]['spl_pay']);
//		 $ern_amount_cpf=($ern_amount_cpf)+ ($teacher_dtls[0]['cpf']);
//		 $p_tax=($p_tax)+ ($teacher_dtls[0]['p_tax']);
//		 $i_tax=($i_tax)+ ($teacher_dtls[0]['i_tax']);
//		 $pf_total=($pf_total)+ ($teacher_dtls[0]['pf_deduct']);
//		 $pf_loan_total=($pf_loan_total)+ ($teacher_dtls[0]['pf_loan']);
	//}
//}

/**************************************************************************/
/*echo $test_sql="select sal.salary_monthyear,
		   ad.lead_bank,
		   ad.lead_branch 
			 from ehrms_dise_teacher as tch
		inner join ehrms_dise_location_master_school as sch
			on sch.school_dise_code=tch.schcd
		inner join ehrms_monthly_salary as sal
			on tch.tchcd=sal.tchcd and tch.schcd=sal.schcd
		inner join ehrms_dise_location_master_subdiv as sdiv
			on sdiv.subdiv_id_pk=sch.subdiv_id_fk
		inner join ehrms_dise_admin ad
			on ad.dpsc_code=sdiv.subdiv_code
		inner join sed_dise_teacher_salary_finalize sf
			on sf.schcd=sal.schcd
		
		where sdiv.subdiv_code = '19111' 
					and 
						ad.dpsc_code='".$_SESSION['user_info']['stake_user']."'
					and
						trim(sal.salary_monthyear)=trim('".$sql_query[0]['salary_monthyear']."')
					and 
						sal.delete_status='1' 
					and 
						sal.category_id='1' 
					and
						sal.sal_source='".$sql_query[0]['salary_source']."'
					and 
						tch.status in('1','8') 
					and 
						sf.status='1' 
					and 
						sf.category_id='1'
					and 
						trim(sf.salary_monthyear)=trim('".$sql_query[0]['salary_monthyear']."')";
exit;*/
/**************************************************************************/
/*$db = new database();
$sql_salarymonth=$db->fetch_table("
		select sal.salary_monthyear,
		   ad.lead_bank,
		   ad.lead_branch 
			 from ehrms_dise_teacher as tch
		inner join ehrms_dise_location_master_school as sch
			on sch.school_dise_code=tch.schcd
		inner join ehrms_teacher_salary_save as sal
			on tch.tchcd=sal.tchcd and tch.schcd=sal.schcd
		inner join ehrms_dise_location_master_subdiv as sdiv
			on sdiv.subdiv_id_pk=sch.subdiv_id_fk
		inner join ehrms_dise_admin ad
			on ad.dpsc_code=sdiv.subdiv_code
		inner join sed_dise_teacher_salary_finalize sf
			on sf.schcd=sal.schcd
		
		where sdiv.subdiv_code = '19111' 
					and 
						ad.dpsc_code='".$_SESSION['user_info']['stake_user']."'
					and
						trim(sal.salary_monthyear)=trim('".$sql_query[0]['salary_monthyear']."')
					and 
						sal.status_flag!='0' 
					and 
						sal.category_id='1' 
					and
						sal.sal_source='".$sql_query[0]['salary_source']."'
					and 
						tch.status in('1','8') 
					and 
						sf.status='1' 
					and 
						sf.category_id='1'
					and 
						trim(sf.salary_monthyear)=trim('".$sql_query[0]['salary_monthyear']."')	
");*/


/********************************************************************************************/

$sql_salarymonth=$db->fetch_table("
select DISTINCT(bol.salary_monthyear), ad.lead_bank, ad.lead_branch from prd_block_bill_details as bol inner join prd_dise_admin as ad on bol.block_code=ad.block_code
 where ad.block_code='".$_SESSION['user_info']['stake_user']."' 
					and
						bol.salary_monthyear='".$sql_query[0]['salary_monthyear']."'
					");
						


//$sql_salarymonth=$db->fetch_table("
//select DISTINCT(bonus.monthyear), ad.lead_bank, ad.lead_branch from prd_employee_master as emp 
//inner join prd_location_master_gp as gp on emp.gp_id_fk=gp.gp_id_pk 
//inner join (select * from prd_employee_bonus_details where salary_monthyear='".$sql_query[0]['salary_monthyear']."') as bonus 
//on emp.emp_id_pk=bonus.emp_id_pk 
//and emp.gp_id_fk=bonus.gp_id_fk 
//inner join prd_dise_admin ad on ad.block_code=sal.block_code 
//where sal.block_code='".$_SESSION['user_info']['stake_user']."' 
//and sal.status_flag='3' and (emp.emp_status='1' OR emp.emp_status='9') AND sal.requisition_type='".$requisition_type."'");
						
		
					
						
/*echo "<pre>";
print_r($sql_salarymonth);*/

$db = new database();

$sql_query_schtype=$db->fetch_table("select * from prd_head_code where type_status='1'");
		
		
 $schtype_code=$sql_query_schtype[0]['depart_code']."-".$sql_query_schtype[0]['demand_no']."-".$sql_query_schtype[0]['maj_head']."-".$sql_query_schtype[0]['s_maj_head']."-".$sql_query_schtype[0]['minor_head']."-".$sql_query_schtype[0]['plan_head']."-".$sql_query_schtype[0]['schm_head']."-".$sql_query_schtype[0]['vot_ch']."-".$sql_query_schtype[0]['dtl_head']."-".$sql_query_schtype[0]['sdtl_head']; 
		
	$det_head=$db->fetch_table("select * from prd_head_details where block_code='".$_SESSION['location']['block_code']."'");	
   // $sql_query_head="select * from prd_head_details where block_code='".$_SESSION['location']['block_code']."'"; 
   // $row_data_head=pg_query($conn,$sql_query_head);
//	$det_head=pg_fetch_array($row_data_head);		
		
//	$sql_query_head1=$sql_query_head[0]['pf_maj_head']; 
	
	// $det_head_data=$det_head[0]['pf_maj_head']."-".$det_head[0]['pf_s_maj_head']."-".$det_head[0]['pf_minor_head']."-".$det_head[0]['pf_schm_head']."-".$det_head[0]['pf_dtl_head']; 
		
		
	/*	$row_data_schtype=pg_query($conn,$sql_query_schtype);
		 $det_schtype=pg_fetch_array($row_data_schtype); 
		
		
foreach($sql_query_schtyp as $det_schtype){		
	 $schtype_code=$sql_query_schtype[0]['depart_code']."-".$sql_query_schtype[0]['demand_no']."-".$sql_query_schtype[0]['maj_head']."-".$sql_query_schtype[0]['s_maj_head']."-".$sql_query_schtype[0]['minor_head']."-".$sql_query_schtype[0]['plan_head']."-".$sql_query_schtype[0]['schm_head']."-".$sql_query_schtype[0]['vot_ch']."-".$sql_query_schtype[0]['dtl_head']."-".$sql_query_schtype[0]['sdtl_head']; 
}*/

//echo $ern_amount;exit;sal.
       
//ern_amount=($ern_amount)+($ern_amount_da)+($ern_amount_interim_relief)+($ern_amount_hra)+($ern_amount_ma)+($ern_amount_basic)+($ern_amount_spl_pay)+($ern_amount_conv_allow)+($hill_allowance)+($consolidated_pay);


//$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
//$ern_amount1=($gross_salary);
//
//$ag_total=($gpf)+($pf_loan_total);
//$deduc_amt=($i_tax)+($p_tax);
//$deduc_total= ($deduc_amt)+($ag_total);
//$net_amount=($ern_amount)-($deduc_total);

//$net_amount=(($ern_amount)+($cooperative_loan)+($hbl_loan)+($festival_loan)+($overdrawn)+($gsli))-($deduc_total);

$ern_amount=$total_bonus_amount;
$net_amount=$total_bonus_amount;
$ag_total=0;
$i_tax=0;
$p_tax=0;
////////////////////////////content//////////////////////////////////////////
	$content.= 	date("M",strtotime(substr($sql_salarymonth[0]['salary_monthyear'],0,4)."-".substr($sql_salarymonth[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,10)."#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				.$schtype_code."#"
				.str_pad($ern_amount,8,' ',STR_PAD_LEFT)."#"
				."ERN##"."\r\n"; 
	$content.= 	date("M",strtotime(substr($sql_salarymonth[0]['salary_monthyear'],0,4)."-".substr($sql_salarymonth[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,10)."#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				."Gross Amount#"
				.str_pad($ern_amount,8,' ',STR_PAD_LEFT)."##"."\r\n"; 
	
	$content.= 	date("M",strtotime(substr($sql_salarymonth[0]['salary_monthyear'],0,4)."-".substr($sql_salarymonth[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,10)."#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				.$det_head[0]['pf_maj_head']."-".$det_head[0]['pf_s_maj_head']."-".$det_head[0]['pf_minor_head']."-".$det_head[0]['pf_schm_head']."-".$det_head[0]['pf_dtl_head']."#"
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
	
	$content.= 	date("M",strtotime(substr($sql_salarymonth[0]['salary_monthyear'],0,4)."-".substr($sql_salarymonth[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,10)."#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				.$det_head[0]['it_maj_head']."-".$det_head[0]['it_s_maj_head']."-".$det_head[0]['it_minor_head']."-".$det_head[0]['it_schm_head']."-".$det_head[0]['it_dtl_head']."#"
				.str_pad(substr($i_tax,0,8),8,' ',STR_PAD_LEFT)."#"
				."TRY##"."\r\n"; 
	
	$content.= 	date("M",strtotime(substr($sql_salarymonth[0]['salary_monthyear'],0,4)."-".substr($sql_salarymonth[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,10)."#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				.$det_head[0]['pt_maj_head']."-".$det_head[0]['pt_s_maj_head']."-".$det_head[0]['pt_minor_head']."-".$det_head[0]['pt_schm_head']."-".$det_head[0]['pt_dtl_head']."#"
				.str_pad(substr($p_tax,0,8),8,' ',STR_PAD_LEFT)."#"
				."TRY##"."\r\n"; 
	
	
	$content.= 	date("M",strtotime(substr($sql_salarymonth[0]['salary_monthyear'],0,4)."-".substr($sql_salarymonth[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,10)."#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				."Net Amount#"

				.str_pad(substr($net_amount,0,10),10,' ',STR_PAD_LEFT)."##"."\r\n"; 
	
	/*$content.= date("M",substr($bill_row['salary_monthyear'],4))."# ".substr($bill_row['salary_monthyear'],0,4)."# ".$dpsc_code."# ".$bill_det['bill_no']."#".dateshow(substr($bill_det['bill_entry_time'],0,10))."#[emp_group]#[Permanent No]#[Permanent No]#[Temporary No]#[total]#ACT## \n";*/
	
	/*$content.= date("M",substr($bill_row['salary_monthyear'],4))."# ".substr($bill_row['salary_monthyear'],0,4)."# ".$dpsc_code."# ".$bill_det['bill_no']."#".dateshow(substr($bill_det['bill_entry_time'],0,10))."#[Head Of Account]#[GIS Group]#[No of Employee in this group]#[Total Amount]#GIS## \n";*/
	
	
	$content.= 	date("M",strtotime(substr($sql_salarymonth[0]['salary_monthyear'],0,4)."-".substr($sql_salarymonth[0]['salary_monthyear'],4)."-"."01"))."#"
				.date('Y')."#"
				.substr($dpsc_code_slash,0,11)."#"
				.substr($sql_query[0]['bill_no'],0,10)."#"
				.dateshow_slash(substr($sql_query[0]['bill_entry_time'],0,10))."#"
				.$schtype_code."#"
				.str_pad(substr($sql_salarymonth[0]['lead_bank']." ".$sql_salarymonth[0]['lead_branch'],0,25),25,' ')."#"
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

    $filename=substr($sql_query[0]['salary_monthyear'],4).substr($sql_query[0]['salary_monthyear'],0,4)."31".$dpsc_code.$billno.$billdate."_BillSummary.txt";
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