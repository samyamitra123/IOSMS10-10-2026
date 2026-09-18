<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
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

function seq_number_generate()
{
	$fileSystemIterator = new FilesystemIterator($_SERVER['DOCUMENT_ROOT'].'/prd/readwrite/xml_file');

	$entries = array();
	foreach ($fileSystemIterator as $fileInfo){
		$entries[] = $fileInfo->getFilename();
	}
	
	$seq_arr = array();
	foreach($entries as $key=>$value)
	{
		if(strlen($value)=='32')
		{
			$seq_arr[]=substr($value,20,8);
		}
	}
	
	
	$digit=max($seq_arr);
	
	$sum=$digit+1;
	$inc=str_pad($sum,8,'0',STR_PAD_LEFT);
	
	if (in_array($inc, $seq_arr))
	{
		seq_number_generate();
	}
	else
	{
		return $inc;
	}
}


$path = '../../../../readwrite/xml_file/';

$crypto = new cryptography();
//header("Contet-type : text/xml");
$schcd = $_SESSION['user_info']['stake_user'];
$mo = date('m');
$ye = date('Y');
$bill =$crypto->decode($_GET['bill'],4);
$drn_number =$crypto->decode($_GET['drn'],4);
$bill_type =$crypto->decode($_GET['bill_type'],4);

$yemo = $ye.$mo;
$db = new database();

/*$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];*/

$party_code='001';

$det_head=$db->fetch_table("select * from prd_head_details where block_code='".$schcd."'");

$ptax_head=$det_head[0]['pt_maj_head']."-".$det_head[0]['pt_s_maj_head']."-".$det_head[0]['pt_minor_head']."-".$det_head[0]['pt_schm_head']."-".$det_head[0]['pt_dtl_head'];

$i_tax_head=$det_head[0]['it_maj_head']."-".$det_head[0]['it_s_maj_head']."-".$det_head[0]['it_minor_head']."-".$det_head[0]['it_schm_head']."-".$det_head[0]['it_dtl_head'];

$ag_head=$det_head[0]['pf_maj_head']."-".$det_head[0]['pf_s_maj_head']."-".$det_head[0]['pf_minor_head']."-".$det_head[0]['pf_schm_head']."-".$det_head[0]['pf_dtl_head'];

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

$ddo_sql = $db->fetch_table("Select treasury_block_code,ddo_code from prd_dise_admin where block_code = '".$schcd."'");

$ddo_code=$ddo_sql[0]['ddo_code'];
$ddo_code_array=explode("-",$ddo_code);
$ddo_code_name=$ddo_code_array[0].$ddo_code_array[1].$ddo_code_array[2];
$ddo_code_slash=$ddo_code_array[0]."/".$ddo_code_array[1]."/".$ddo_code_array[2];
$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));

$bill_det=$db->fetch_table("select bill_no,bill_entry_time,salary_monthyear from prd_block_bill_details where block_code='".$schcd."' and bill_no='".$bill."' and salary_monthyear= '".$yemo."' AND requisition_type='".$bill_type."' AND status='1'");

$det_head=$db->fetch_table("select *from prd_head_details where block_code='".$schcd."'");

$no_of_row=count($det_head);   

/*$teacher_sql="select  distinct(emp.emp_id_pk),
emp.empcd, 
emp.emp_first_name,
emp.emp_second_name,
emp.emp_last_name, 
emp.gp_id_fk, 
emp.emp_status, 
emp.emp_branch_code, 
emp.emp_system_code,  
save.empcd, 
save.bankname, 
emp.emp_micr_no, 
emp.emp_pan_no, 
emp.emp_desig, 
emp.emp_acc_no, 
save.basic,
save.bank_ifsc,
save.net,
emp.emp_group,
emp.emp_mail_id,
emp.emp_aadhar_no,
emp.emp_mobile_no,
emp.emp_pre_dist,
emp.emp_per_dist,
save.delete_status
FROM 
prd_employee_master emp
left join prd_employee_salary_save save 
ON save.emp_id_fk=emp.emp_id_pk
where
trim(save.salary_monthyear)='".$yemo."'
AND save.delete_status='1' 
AND save.status_flag='3'
AND emp.emp_status in('1','9')
AND save.block_code='".$schcd."'
AND save.requisition_type='".$requisition_type."'
ORDER BY emp.gp_id_fk ASC
";*/

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
								trim(sal.salary_monthyear)='".$yemo."'
								AND emp.emp_status in('1','9') 
								AND sal.delete_status='1' 
								AND sal.status_flag='3'
								AND sal.is_saved='1'
								AND sal.block_code='".$schcd."' 
								AND sal.requisition_type='".$bill_type."'
								ORDER BY emp.gp_id_fk ASC");



//$sql_teacher=$db->fetch_table($teacher_sql);

$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");



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
	$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
	$net_salary=($net_salary)+ ($net_salary['net']);
	$ag_total=$gpf+$pf_loan_total;

}
$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
$ern_amount1=($gross_salary);

$ag_total=($gpf)+($pf_loan_total);
$deduc_amt=($i_tax)+($p_tax);
$deduc_total= ($deduc_amt)+($ag_total);
$net_amount=($ern_amount)-($deduc_total);

$total_beneficiary=count($teacher_dtls);

$xml =  new DOMDocument("1.0","UTF-8");

$bulk_det = $xml->createElement('bulkecs');
$bulk_det->setAttribute("totalamount",$net_amount);
$bulk_det->setAttribute("benfcount",$total_beneficiary);
$bulk_det = $xml->appendChild($bulk_det);

$container = $xml->createElement('BENEFICIARY');
$container = $xml->appendChild($container);

foreach($teacher_dtls as $teacher_row)
{
	/*$bank = $xml->createElement('BANK_DETAIL');
	$bank = $container->appendChild($bank);*/
	
	if (trim($teacher_row['emp_second_name']) == '') 
	{
		$last_name = preg_replace('/\s+/', ' ', trim($teacher_row['emp_last_name']));
		$tch_name = trim($teacher_row['emp_first_name']) . " " . trim($last_name);
	} 
	else 
	{
		$last_name = preg_replace('/\s+/', ' ', trim($teacher_row['emp_last_name']));
		$tch_name = trim($teacher_row['emp_first_name']) . " " . trim($teacher_row['emp_second_name']) . " " . trim($last_name);
	}

	
	$benf_name = $xml->createElement('BENF_NAME',$tch_name);
	$benf_name = $container->appendChild($benf_name);
	
	$ano = $xml->createElement('ACCOUNT_NO',$teacher_row['accountno']);
	$ano = $container->appendChild($ano);
	
	$ifsc = $xml->createElement('IFSC_CODE',$teacher_row['bank_ifsc']);
	$ifsc = $container->appendChild($ifsc);

	$mob_no = $xml->createElement('MOBILE_NO',$teacher_row['net']);
	$mob_no = $container->appendChild($mob_no);
	
	$amount = $xml->createElement('AMOUNT',$teacher_row['net']);
	$amount = $container->appendChild($amount);
	
	$u_id = $xml->createElement('UNIQUE_ID',$teacher_row['emp_id_const']);
	$u_id = $container->appendChild($u_id);

	$on = $xml->createElement('ORDER_NO','E');
	$on = $container->appendChild($on);
	
	$u_id = $xml->createElement('UNIQUE_ID');
	$u_id = $container->appendChild($u_id);
	
}




//find_dist(substr($_SESSION['schcd'],0,4))
//$xml->formatOutput = TRUE;

$billno1='';$billno='';$billdate='';
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
	$billdate=$billdate.$i;
}
//echo substr($bill_det[0]['salary_monthyear'],4).substr($bill_det[0]['salary_monthyear'],0,4)."31".$ddo_code_name.$billno.$billdate."_Benf.xml";exit;

$seq_number=seq_number_generate();

if($xml->formatOutput = TRUE)
{
	//$string_value = $xml->saveXML();
	//$xml->save('../readwrite/xml2/'.$_SESSION['schcd'].'_'.$_SESSION['schname'].'_beneficiary_salary.xml');
	//	MONTH(10)YEAR(2015)TR_TYPE(31)DDO_CODE(NPCEDS002)ANY_SERIAL_NO(132)CREATED_DATE(07102015)_Benf(1)
	
	$filename = $ddo_code_name.$party_code.$billdate.$seq_number.".xml";
	$name = strftime($filename);
	header('Content-Disposition: attachment;filename=' . $name);
	header('Content-Type: text/xml');
	echo $xml->saveXML();
	$xml->save($path.$name);
}


?>
