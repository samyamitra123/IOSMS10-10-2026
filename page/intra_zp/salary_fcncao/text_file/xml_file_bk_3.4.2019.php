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
$path = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/webservice/';
//$path = $config['base_url'] .'readwrite/xml_file/ifmsgp/webservice/';
//echo $path ;die;
$crypto = new cryptography();
//header("Contet-type : text/xml");
$schcd = $_SESSION['user_info']['stake_user'];
$mo = date('m');
$ye = date('Y');

$bill_type=$crypto->decode($_GET['bill_type'],4);
$drn_number=$crypto->decode($_GET['drn_no'],4);
$emp_type=$crypto->decode($_GET['emp_type'],4);



$ebop_flag='B';

$yemo = $ye.$mo;
$db = new database();

$zpemp_zp_profile=$db->fetch_table("select pl_code,ddo_code from psemp_ps_profile where district_id_fk='".$_SESSION['location']['district_id']."'");

$pl_code=$zpemp_zp_profile[0]['pl_code'];
$treasury_code=substr($zpemp_zp_profile[0]['ddo_code'],0,3);
$schm_code='999998';


$schtype_code='0000000000000000000';
$ptax_head='0000000000000000000';
$i_tax_head='0000000000000000000';
$ag_head='0000000000000000000';
$out_of_acc_head='0000000000000000000';
$hra_head='0000000000000000000';
$total_loan_head='0000000000000000000';
$gsli_head='0000000000000000000';


/*$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];*/


/*$sql_query_schtype=$db->fetch_table("select * from prd_head_code where type_status='1'");
 
$schtype_code=$sql_query_schtype[0]['depart_code'].$sql_query_schtype[0]['demand_no'].$sql_query_schtype[0]['maj_head'].$sql_query_schtype[0]['s_maj_head'].$sql_query_schtype[0]['minor_head'].$sql_query_schtype[0]['plan_head'].$sql_query_schtype[0]['schm_head'].$sql_query_schtype[0]['vot_ch'].$sql_query_schtype[0]['dtl_head'].$sql_query_schtype[0]['sdtl_head'];  


$det_head=$db->fetch_table("select * from prd_head_details where block_code='3299001'");

$ptax_head=$det_head[0]['pt_maj_head'].$det_head[0]['pt_s_maj_head'].$det_head[0]['pt_minor_head'].$det_head[0]['pt_schm_head'].$det_head[0]['pt_dtl_head'];

$i_tax_head=$det_head[0]['it_maj_head'].$det_head[0]['it_s_maj_head'].$det_head[0]['it_minor_head'].$det_head[0]['it_schm_head'].$det_head[0]['it_dtl_head'];

$ag_head=$det_head[0]['pf_maj_head'].$det_head[0]['pf_s_maj_head'].$det_head[0]['pf_minor_head'].$det_head[0]['pf_schm_head'].$det_head[0]['pf_dtl_head'];
*/
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

/*$ddo_sql = $db->fetch_table("Select treasury_block_code,ddo_code from prd_dise_admin where block_code = '3299001'");

$treasury_code=$treasury_dts[0]['ddo_sql'];
$ddo_code=$ddo_sql[0]['ddo_code'];
$ddo_code_array=explode("-",$ddo_code);
$ddo_code_name=$ddo_code_array[0].$ddo_code_array[1].$ddo_code_array[2];
$ddo_code_slash=$ddo_code_array[0]."/".$ddo_code_array[1]."/".$ddo_code_array[2];*/
$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));

$bill_det=$db->fetch_table("select bill_no,bill_entry_time,salary_monthyear, block_bill_pk from prd_block_bill_details where zp_id_fk='" . $_SESSION['location']['district_id'] . "' and salary_monthyear= '".date('Ym')."' AND requisition_type='".$bill_type."' AND status='1' AND drn_number='".$drn_number."' AND zp_emp_type='".$emp_type."'");

$det_head=$db->fetch_table("select * from prd_head_details where block_code='3299001'");

$no_of_row=count($det_head);   


$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
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
								INNER JOIN prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.zp_id_fk=emp.zp_id_fk)
							where 
							trim(sal.salary_monthyear)='".date('Ym')."'
								AND emp.emp_status in('1','9') 
								AND sal.delete_status='1' 
								AND sal.status_flag='4'
								AND sal.is_saved='1'
								AND sal.zp_id_fk='" . $_SESSION['location']['district_id'] . "' 
								AND sal.requisition_type='".$bill_type."'
								AND sal.zp_emp_type='".$emp_type."'
								ORDER BY emp.gp_id_fk ASC");



//$sql_teacher=$db->fetch_table($teacher_sql);

$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");



$billno1='';$billno='';$billdate='';$billdate_2='';
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
foreach($bill_date_array as $a=>$i)
{
	$billdate_2=$billdate_2.$i;
}


$billdate=dateshow_slash($bill_det[0]['bill_entry_time']);

foreach($teacher_dtls as $teacher_row)
{
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
$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
$ern_amount1=($gross_salary);

$ag_total=($gpf)+($pf_loan_total);
$deduc_amt=($i_tax)+($p_tax);
$deduc_total= ($deduc_amt)+($ag_total)+($hra_deduction)+($gsli_deduction)+($out_acc_deduction)+($total_loan_deduction);
$net_amount=($ern_amount)-($deduc_total);

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
	
	$bthoa = $xml->createElement('BT_HOA',$out_of_acc_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$out_acc_deduction);
	$bammount = $bynsfer->appendChild($bammount);
	
	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$total_loan_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$total_loan_deduction);
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
