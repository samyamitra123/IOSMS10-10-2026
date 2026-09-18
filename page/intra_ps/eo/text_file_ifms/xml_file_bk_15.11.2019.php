<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
/*require '../../../page_visite.php';
*/
if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])
|| isset($_SESSION['blocked_privilege']['0801'])
){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}

$path = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/webservice/';

$crypto = new cryptography();
//header("Contet-type : text/xml");
$schcd = $_SESSION['user_info']['stake_user'];
$mo = date('m');
$ye = date('Y');


if ($_SESSION['user_info']['stake_abbr']=='BDO' )
{
    $ebop_flag='B';
}
else if($_SESSION['user_info']['stake_abbr']=='EO')
{
    $ebop_flag='P';
}
//$bill =$crypto->decode($_GET['bill'],4);
//$bill='44';
//$bill_type=$crypto->decode($_GET['bill_type'],4);
//$drn_number=$crypto->decode($_GET['drn_no'],4);

$bill_type=$crypto->decode($_GET['bill_type'],4);
$drn_number=$crypto->decode($_GET['drn_no'],4);
$emp_type=$crypto->decode($_GET['emp_type'],4);
$bill_serial_no=$crypto->decode($_GET['bill_serial_no'],4);
$monthyear=$_GET['monthyear'];
//$drn_number =$crypto->decode($_GET['drn'],4); die;
//$drn_number='201805001000001';
//$bill_type =$crypto->decode($_GET['bill_type'],4);
//$bill_type='1001';
$yemo = $ye.$mo;
$db = new database();

$schtype_code='0000000000000000000';
$ptax_head='0000000000000000000';
$i_tax_head='0000000000000000000';
$ag_head='0000000000000000000';
$out_of_acc_head='0000000000000000000';
$hra_head='0000000000000000000';
$total_loan_head='0000000000000000000';
$gsli_head='0000000000000000000';

function dateshow_slash($dateval)
{	
	$date=substr($dateval,0,10);
	if($date=='')
	{
		return '01/01/1900';
	}
	if($date=='0001-01-01')
	{
		return '01/01/1900';
	}
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'/'.$datearr['1'].'/'.$datearr['0'];
	return $dob=='01/01/1900'?'':$dob;
}


$ddo_sql = $db->fetch_table("Select ddo_code,treasury_code from psemp_ps_profile where ps_id_fk = '".$_SESSION['location']['ps_id']."'");



$treasury_code=substr($ddo_sql[0]['treasury_code'],0,3);
$pl_code=str_pad($ddo_sql[0]['ddo_code'],6,'0',STR_PAD_LEFT);
$schm_code='999998';


if($bill_type == '1001')
{
$bill_det=$db->fetch_table("select bill_no,bill_entry_time,salary_monthyear, block_bill_pk from prd_block_bill_details where ps_id_fk='" . $_SESSION['location']['ps_id'] . "' and salary_monthyear= '".date('Ym')."' AND requisition_type='".$bill_type."' AND status='1' AND drn_number='".$drn_number."'");

}
else if($bill_type == '1002' || $bill_type == '1004' || $bill_type == '1005')
{
$bill_det=$db->fetch_table("select bill_no,bill_entry_time,salary_monthyear, block_bill_pk from prd_block_bill_details where ps_id_fk='" . $_SESSION['location']['ps_id'] . "' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' AND drn_number='".$drn_number."'");

}


$billno1='';$billno='';$billdate='';$bill_date_array='';

$bill_array=explode("/",$bill_det[0]['bill_no']); 

foreach($bill_array as $a=>$i)
{
	$billno1=$billno1.$i;
}

 $bill_array=explode("-",$billno1);

foreach($bill_array as $a=>$i)
{
	$billno=$billno.$i; 
}


$bill_date_array=explode("/",dateshow_slash($bill_det[0]['bill_entry_time'])); 
//$bill_n=$bill_det[0]['bill_no'];

/*foreach($bill_date_array as $a=>$i)
{ 
	$billdate=$billdate.$i;
}*/
$billdate=dateshow_slash($bill_det[0]['bill_entry_time']);



//$det_head=$db->fetch_table("select * from prd_head_code where type_status='1'");

//$no_of_row=count($det_head);   
if($bill_type == '1001')
{
$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
								emp.emp_first_name,
								emp.emp_second_name,
								emp.emp_last_name, 
								emp.ps_id_fk ,
								emp.emp_status,
								emp.emp_sex,
								emp.emp_grade_pay,
								emp.emp_caste,
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
								sal.gross_salary,
								sal.hra_deduction,
								sal.total_loan_deduction,
								sal.other_loan_deduction
								FROM prd_employee_master emp
								INNER JOIN prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.ps_id_fk=emp.ps_id_fk)
							where 
							trim(sal.salary_monthyear)='".date('Ym')."'
								AND emp.emp_status in('1','9') 
								AND sal.delete_status='1' 
								AND sal.status_flag='3'
								AND sal.is_saved='1'
								AND sal.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
								AND sal.requisition_type='".$bill_type."'
								
								ORDER BY emp.ps_id_fk ASC");


foreach($teacher_dtls as $teacher_row)
{
	//print_r($teacher_row);
	//$teacher_row['basic'];
	//$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
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
	$gsli_deduction=($gsli_deduction)+ ($teacher_row['gsli']);
	$hra_deduction=($hra_deduction)+ ($teacher_row['hra_deduction']);
	$out_acc_deduction=($out_acc_deduction)+ ($teacher_row['other_loan_deduction']);
	$total_loan_deduction=($total_loan_deduction)+ ($teacher_row['total_loan_deduction']);
	$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
	$net_salary=($net_salary)+ ($net_salary['net']);
	$ag_total=$gpf+$pf_loan_total;
}




$ern_amount=($gross_salary)-($overdrawn)-($festival_loan);
$ern_amount1=($gross_salary);

$ag_total=($gpf)+($pf_loan_total);
$deduc_amt=($i_tax)+($p_tax);
$deduc_total= ($deduc_amt)+($ag_total)+($hra_deduction)+($gsli_deduction)+($out_acc_deduction)+($total_loan_deduction);
$net_amount=($ern_amount)-($deduc_total);
}


	else if($bill_type=='1005')
	{
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
	salary_monthyear = '" . $monthyear . "' AND  ps_id_fk = '".$_SESSION['location']['ps_id']."'
	AND bill_serial_no='".$bill_serial_no."'");
	
	
	
	$teacher_dtls=$db->fetch_table("select distinct(emp.emp_id_pk), 
	emp.emp_id_const,
	emp.emp_first_name,
	emp.emp_second_name,
	emp.emp_last_name, 
	emp.ps_id_fk, 
	emp.gp_id_fk,
	emp.zp_id_fk,
	emp.emp_status, 
	emp.emp_acc_no,
	emp.emp_ifsc_no,
	emp.emp_branch_code,  
	emp.emp_bank_name, 
	emp.emp_micr_no, 
	emp.emp_pan_no, 
	emp.emp_desig, 
	emp.emp_acc_no, 
	emp.emp_ifsc_no,
	fav.festival_advance_total_amount,
	emp.emp_mail_id
	
	FROM 
	prd_employee_master emp
	inner join prd_festival_advance_employee_details fav 
	ON fav.emp_id_fk=emp.emp_id_pk AND fav.ps_id_fk=emp.ps_id_fk
	where
	
	fav.festival_advance_status='5'
	AND emp.emp_status in('1','9')
	AND fav .bill_serial_no='".$bill_serial_no."'
	AND emp.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
	AND fav.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
	");
	
	foreach($teacher_dtls as $teacher_row)
	{					
	$ern_amount_da=0;
	$ern_amount_interim_relief=0;
	$ern_amount_hra=0;
	$ern_amount_ma=0;
	$ern_amount_basic=0;
	$ern_amount_spl_pay=0;
	$ern_amount_conv_allow=0;
	$hill_allowance=0;
	$consolidated_pay=0;
	$gpf=0;
	$p_tax=0;
	$i_tax=0;
	//$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
	$pf_loan_total=0;
	$cooperative_loan=0; 
	$hbl_loan=0; 
	$festival_loan=0; 
	$overdrawn=0;
	$advance_amount=0;
	$gsli_deduction=0;
	$hra_deduction=0;
	$out_acc_deduction=0;
	$total_loan_deduction=0;
	$gross_salary=($gross_salary)+ ($teacher_row['festival_advance_total_amount']);
	$net_salary=($net_salary)+ ($net_salary['festival_advance_total_amount']);
	$ag_total=0;
	$ern_amount1=($ern_amount1)+ ($teacher_row['festival_advance_total_amount']);
	$net_amount=($net_amount)+ ($teacher_row['festival_advance_total_amount']);
	}			
			
}
else if($bill_type == '1002')
{
	
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  ps_id_fk = '".$_SESSION['location']['ps_id']."'
				AND bill_serial_no='".$bill_serial_no."'");
				

	$teacher_dtls=$db->fetch_table("select 
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.gp_id_fk ,
									emp.emp_status,
									emp.emp_sex,
									emp.emp_grade_pay,
									emp.emp_caste,
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
									sal.conv_allow,
									sal.consolidated_pay,
									sal.hill_allowance,
									sal.gross_salary
									FROM prd_employee_master emp
									INNER JOIN prd_employee_arrear sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.ps_id_fk=emp.ps_id_fk)
								where 
								trim(sal.salary_monthyear)='".$monthyear."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='3'
									AND sal.is_saved='1'
									AND sal.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
									
									AND sal.bill_serial_no='".$bill_serial_no."'
									AND sal.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
									");
									
foreach($teacher_dtls as $teacher_row)
{					
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
		//$pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
		//$cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
		//$hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
		//$festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
		//$overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
		//$advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);
		//$gsli_deduction=($gsli_deduction)+ ($teacher_row['gsli']);
		$gsli_deduction=0;
		//$hra_deduction=($hra_deduction)+ ($teacher_row['hra_deduction']);
		$hra_deduction=0;
		//$out_acc_deduction=($out_acc_deduction)+ ($teacher_row['other_loan_deduction']);
		$out_acc_deduction=0;
		//$total_loan_deduction=($total_loan_deduction)+ ($teacher_row['total_loan_deduction']);
		$total_loan_deduction=0;
		$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
}

$ern_amount=($gross_salary)-($overdrawn)-($festival_loan);
$ern_amount1=($gross_salary);

$ag_total=($gpf)+($pf_loan_total);
$deduc_amt=($i_tax)+($p_tax);
$deduc_total= ($deduc_amt)+($ag_total)+($hra_deduction)+($gsli_deduction)+($out_acc_deduction)+($total_loan_deduction);
 $net_amount=($ern_amount)-($deduc_total); 
}
else if($bill_type == '1004')
{
	
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  ps_id_fk = '".$_SESSION['location']['ps_id']."'
				AND bill_serial_no='".$bill_serial_no."'");
				
		$teacher_dtls=$db->fetch_table("select  distinct(emp.emp_id_pk), 
		  emp.emp_id_const,
		  emp.emp_first_name,
		  emp.emp_second_name,
		  emp.emp_last_name, 
		  emp.ps_id_fk, 
		  emp.gp_id_fk,
		  emp.zp_id_fk,
		  emp.emp_status, 
		  emp.emp_acc_no,
		  emp.emp_ifsc_no,
		  emp.emp_branch_code,  
		  emp.emp_bank_name, 
		  emp.emp_micr_no, 
		  emp.emp_pan_no, 
		  emp.emp_desig, 
		  emp.emp_acc_no, 
		  emp.emp_ifsc_no,
		  bonus.bonus_amount,
		  emp.emp_mail_id,
		  bonus.delete_status
		FROM 
		   prd_employee_master emp
		  inner join prd_employee_bonus_details bonus 
		  ON bonus.emp_id_fk=emp.emp_id_pk AND bonus.ps_id_fk=emp.ps_id_fk
		where
			bonus.delete_status='1' 
			AND bonus.bonus_status='5'
			AND emp.emp_status in('1','9')
			AND bonus .bill_serial_no='".$bill_serial_no."'
			AND emp.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
			AND bonus.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
			");
		foreach($teacher_dtls as $teacher_row)
{					
		$ern_amount_da=0;
		$ern_amount_interim_relief=0;
		$ern_amount_hra=0;
		$ern_amount_ma=0;
		$ern_amount_basic=0;
		$ern_amount_spl_pay=0;
		$ern_amount_conv_allow=0;
		$hill_allowance=0;
		$consolidated_pay=0;
		$gpf=0;
		$p_tax=0;
		$i_tax=0;
		//$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
		$pf_loan_total=0;
		$cooperative_loan=0; 
		$hbl_loan=0; 
		$festival_loan=0; 
		$overdrawn=0;
		$advance_amount=0;
		$gsli_deduction=0;
		$hra_deduction=0;
		$out_acc_deduction=0;
		$total_loan_deduction=0;
		$gross_salary=($gross_salary)+ ($teacher_row['bonus_amount']);
		$net_salary=($net_salary)+ ($net_salary['bonus_amount']);
		$ag_total=0;
		$ern_amount1=($ern_amount1)+ ($teacher_row['bonus_amount']);
		$net_amount=($net_amount)+ ($teacher_row['bonus_amount']);
}	
			
			
			
}
 $total_beneficiary=count($teacher_dtls);

	$xml =  new DOMDocument("1.0","UTF-8");
	$container = $xml->createElement('GEN_EPAYMENT');
	$container = $xml->appendChild($container);
	
	$row = $xml->createElement('DRN',$drn_number);
	$row = $container->appendChild($row);
	$sln = $xml->createElement('EBOP_FLAG','P');
	$sln = $container->appendChild($sln);
	
	$name = $xml->createElement('BENF_FLAG','4');
	$name = $container->appendChild($name);
	
	$trsc = $xml->createElement('TREASURY_CODE',$treasury_code);
	$trsc = $container->appendChild($trsc);
	
	$ddc = $xml->createElement('DDO_CODE','');
	$ddc = $container->appendChild($ddc);
	
	$plc= $xml->createElement('PL_CODE',$pl_code);
	$plc = $container->appendChild($plc);
	
	$smc = $xml->createElement('SCHEME_CODE',$schm_code);
	$smc = $container->appendChild($smc);
	
	$hoa = $xml->createElement('HEAD_OF_ACCOUNT',$schtype_code);
	$hoa = $container->appendChild($hoa);
	
	$ga = $xml->createElement('GROSS_AMOUNT',$ern_amount1);
	$ga = $container->appendChild($ga);
	
	$na = $xml->createElement('NET_AMOUNT',$net_amount);
	$na = $container->appendChild($na);
	
	$billnumber = $xml->createElement('BILL_NO',$billno);
	$billnumber = $container->appendChild($billnumber);
	//$billnumber = $xml->createElement('BILL_NO',$bill_n);
	//$billnumber = $container->appendChild($billnumber);
	
	$bill_date = $xml->createElement('BILL_DATE',$billdate);
	$bill_date = $container->appendChild($bill_date);
	
	$billtype = $xml->createElement('BILL_TYPE','0');
	$billtype = $container->appendChild($billtype);
	
	$sacno = $xml->createElement('SANCTION_NO','');
	$sacno = $container->appendChild($sacno);
	
	$sacdate = $xml->createElement('SANCTION_DATE','');
	$sacdate = $container->appendChild($sacdate);
	
	$issu = $xml->createElement('ISSUING_AUTHORITY','');
	$issu = $container->appendChild($issu);
	
	$sa = $xml->createElement('SANCTION_AMOUNT','0');
	$sa = $container->appendChild($sa);
	
	$subdetails = $xml->createElement('SUBDETAIL');
	$subdetails = $container->appendChild($subdetails);
	
	$subdetails_hed = $xml->createElement('SUBDETAIL_HEAD','E');
	$subdetails_hed = $subdetails->appendChild($subdetails_hed);
	
	$subdetails_amount = $xml->createElement('SUBDETAIL_AMOUNT','0');
	$subdetails_amount = $subdetails->appendChild($subdetails_amount);
	
	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$ptax_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$p_tax);
	$bammount = $bynsfer->appendChild($bammount);
	
	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$i_tax_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$i_tax);
	$bammount = $bynsfer->appendChild($bammount);
	
	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$ag_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$ag_total);
	$bammount = $bynsfer->appendChild($bammount);

	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$hra_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$hra_deduction);
	$bammount = $bynsfer->appendChild($bammount);
	
	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$gsli_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$gsli_deduction);
	$bammount = $bynsfer->appendChild($bammount);


//find_dist(substr($_SESSION['schcd'],0,4))
//$xml->formatOutput = TRUE;


//echo substr($bill_det[0]['salary_monthyear'],4).substr($bill_det[0]['salary_monthyear'],0,4)."31".$ddo_code_name.$billno.$billdate."_Benf.xml";exit;

if($xml->formatOutput = TRUE)
{
  
	//$string_value = $xml->saveXML();
	//$xml->save('../readwrite/xml2/'.$_SESSION['schcd'].'_'.$_SESSION['schname'].'_beneficiary_salary.xml');
	//	MONTH(10)YEAR(2015)TR_TYPE(31)DDO_CODE(NPCEDS002)ANY_SERIAL_NO(132)CREATED_DATE(07102015)_Benf(1)
	//31TISTTS00101011900_Benf
  
	$filename = $drn_number."_Benf.xml";
	$name = strftime($filename); 
	header('Content-Disposition: attachment;filename=' . $name);
	header('Content-Type: text/xml');
	echo $xml->saveXML();
	$xml->save($path.$name);
}


?>
