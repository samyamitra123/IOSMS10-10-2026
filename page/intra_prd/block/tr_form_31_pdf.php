<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
if($_SERVER['HTTP_REFERER']==''){
header("Location:../../dashboard.php");
}

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}
error_reporting(0);

function number_to_words($number)
{
	if ($number > 999999999)
	{
		throw new Exception("Number is out of range");
	}
	$Gn = floor($number / 10000000);  /* Millions (giga) */
	$number -= $Gn * 10000000;
	$Ln = floor($number / 100000);  /* Millions (giga) */
	$number -= $Ln * 100000;
	$kn = floor($number / 1000);     /* Thousands (kilo) */
	$number -= $kn * 1000;
	$Hn = floor($number / 100);      /* Hundreds (hecto) */
	$number -= $Hn * 100;
	$Dn = floor($number / 10);       /* Tens (deca) */
	$n = $number % 10;               /* Ones */
	$cn = round(($number-floor($number))*100); /* Cents */
	$result = ""; 
	if ($Gn)
	{  $result .= (empty($result) ? "" : " ") . number_to_words($Gn) . " Crore";  } 
	if ($Ln)
	{  $result .= (empty($result) ? "" : " ") . number_to_words($Ln) . " Lakh"; } 
	if ($kn)
	{  $result .= (empty($result) ? "" : " ") . number_to_words($kn) . " Thousand"; } 
	if ($Hn)
	{  $result .= (empty($result) ? "" : " ") . number_to_words($Hn) . " Hundred";  } 
	$ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
	"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
	"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
	"Nineteen");
	$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
	"Seventy", "Eigthy", "Ninety"); 
	if ($Dn || $n)
	{
		if (!empty($result))
		{  
			$result .= " and ";
		} 
		if ($Dn < 2)
		{  
			$result .= $ones[$Dn * 10 + $n];
		}
		else
		{  
			$result .= $tens[$Dn];
			if ($n)
			{  
				$result .= "-" . $ones[$n];
			}
		}
	}
	if ($cn)
	{
		if (!empty($result))
		{  
			$result .= ' and ';
		}
		$title = $cn==1 ? 'paisa ': 'paisa';
		$result .= strtolower(number_to_words($cn)).' '.$title;
	}
	if (empty($result))
	{  
		$result = "zero"; 
	} 
	return $result;
}

if(( base64_decode($_REQUEST['report_month'])=="") || ( base64_decode($_REQUEST['report_year'])==""))
{ 
}
else
{
	$cryptograph=new cryptography();
	
	$report_year= $cryptograph->decode($_REQUEST['report_year'], 4);
	$report_month=$cryptograph->decode($_REQUEST['report_month'], 4);
	$bill_no=isset($_GET['bill_no'])?$_GET['bill_no']:'';
	$bill_entry_date=isset($_GET['bill_date'])?$_GET['bill_date']:'';
	$bill_no=$cryptograph->decode($bill_no, 4);
	$bill_entry_date=$cryptograph->decode($bill_entry_date, 4);
	if($report_month=='01'){ $month=January;
	}else if($report_month=='02'){ $month=February;
	}else if($report_month=='03'){ $month=March;     
	}else if($report_month=='04'){ $month=April;     
	}else if($report_month=='05'){ $month=May;     
	}else if($report_month=='06'){ $month=June;     
	}else if($report_month=='07'){ $month=July;     
	}else if($report_month=='08'){ $month=August;     
	}else if($report_month=='09'){ $month=September;     
	}else if($report_month=='10'){ $month=October;     
	}else if($report_month=='11'){ $month=November;     
	}else if($report_month=='12'){ $month=December;     
	}else { $month=All; }
	//-----------------------------------------------query--------------------------------------------------------------------------	  
	$db = new database();
	$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
	$requisition_type=$requisition[0]['code'];	  
	
	if(date("Y")==$report_year && date("m")==$report_month)
	{																
		$data = $db->fetch_table("select sum(basic) as basic,sum(gross_salary) as gross_salary,sum(da) as da, sum(hra) as hra, sum(ma) as ma, sum(cpf) 
		as cpf,sum(gpf) as gpf, sum(pf_loan ) as pf_loan, sum(p_tax) as p_tax, sum(i_tax) as i_tax, sum(spl_pay)as spl_pay,sum(consolidated_pay) as consolidated_pay, 	
		sum(pf_deduct) as pf_deduct,sum(net) as net
		from prd_employee_salary_save as sv 
		inner join prd_employee_master emp on emp.emp_id_pk=sv.emp_id_fk 
		where sv.status_flag='3' and sv.category_id='1' and sv.block_code='".$_SESSION['location']['block_code']."' 
		and sv.salary_monthyear='".$report_year.$report_month."' AND delete_status='1' AND is_saved='1' and 	
		emp.emp_status in('1','9') AND requisition_type='".$requisition_type."'
		
		");
		$district = $db->fetch_table("
		select district_name from prd_location_master_district
		where district_code='".substr($_SESSION['location']['block_code'],0,4)."'
		");
	}
	else 
	{	
		$data = $db->fetch_table("select sum(basic) as basic, sum(da) as da,sum(gross_salary) as gross_salary, sum(hra) as hra, sum(ma) as ma, sum(cpf) 
		as cpf,sum(gpf) as gpf, sum(pf_loan ) as pf_loan, sum(p_tax) as p_tax, sum(i_tax) as i_tax, sum(spl_pay)as spl_pay, sum(consolidated_pay) as consolidated_pay,
		sum(pf_deduct) as pf_deduct,sum(net) as net
		from prd_monthly_salary_archive_final as sv 
		inner join prd_employee_master emp on emp.emp_id_pk=sv.emp_id_fk 
		where sv.status_flag='3'  and sv.block_code='".$_SESSION['location']['block_code']."' 
		and sv.salary_monthyear='".$report_year.$report_month."' AND delete_status='1' and 	
		emp.emp_status in('1','9') AND requisition_type='".$requisition_type."'
		
		");
		
		$district = $db->fetch_table("
		select district_name from prd_location_master_district
		where district_code='".substr($_SESSION['location']['block_code'],0,4)."'
		");
	}	
	
	//----------------------------------------pdf section------------------------------------------------------------------------------------------
	if(!empty($data)) 
	{
		//$gross_total=$data[0]['basic']+$data[0]['da']+$data[0]['hra']+$data[0]['ma']+$data[0]['cpf']+$data[0]['spl_pay']+$data[0]['consolidated_pay'];
		$gross_total=$data[0]['gross_salary'];
		$deduct_total=$data[0]['gpf']+$data[0]['cpf']+$data[0]['pf_loan']+$data[0]['p_tax']+$data[0]['i_tax'];
		$gpf=$data[0]['gpf']+$data[0]['pf_loan'];
		$amt_gpf=number_to_words($gpf);
		$amt_gross_total=number_to_words($gross_total);
		$amt_deduct_total=number_to_words($deduct_total);
		$amt_net=number_to_words($data[0]['net']);
		
		
		/*if($sal_source=='18102'){
		$demand=38;
		$dept_code="MD";}
		else {
		$demand=15;
		$dept_code="ES";}
		
		
		if($sal_source=='18101'){
		$head_ac="2202-02-110-NP-001(ES)-V-31-01"; }
		
		else if($sal_source=='18102'){
		$head_ac="2202-02-110-NP-013(MD)-V-31-01"; }
		
		else if($sal_source=='18201'){
		$head_ac="2202-02-110-NP-001(ES)-V-31-01"; }
		
		else if($sal_source=='18202'){
		$head_ac="2202-02-110-NP-004(ES)-V-31-01"; }
		
		else if($sal_source=='18300'){
		$head_ac="2202-02-110-NP-001(ES)-V-31-GA-01"; }
		
		else if($sal_source=='18400'){
		$head_ac="2202-02-110-NP-006(ES)-V-31-01"; }
		else { }*/
		
		//-------------------------------------------------------------------------------------------------------
		
		define("_MPDF_TEMP_PATH", '../../../locker/temp/');
		include ('../../../includes/third-party/mpdf/mpdf.php');
		$mpdf=new mPDF("en-GB-x","A4","","",10,10,10,10,6,3);
		$stylesheet = file_get_contents($config['base_url'].'themes/default/css/mpdf_bill_extract.css');
		$mpdf->WriteHTML($stylesheet,1);
		
		
		
		$mpdf->WriteHTML('<body>
		<div  style="width:750px; height:auto; font-family:Arial, Helvetica, sans-serif;">
		<div style="background-color:#CCCCCC; text-align:center;"><strong>T.R. FORM NO. 31<br/>
		<i>[See sub-rule(1) of T.R. 4.195]</i><br />
		Grant-in-aid Bill<br/>Simple Receipt Form</strong><br/>
		</div>
		<div class="one" style="padding-left: 15px; padding-right: 10px; font-size: 14px;">
		<table width="699">
		<tr>
		<td width="325">&nbsp;</td>
		<td width="101">&nbsp;</td>
		<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
		<td>D.D.O. Code &nbsp;: &nbsp;</td>
		<td colspan="2">Bill No. &nbsp;: &nbsp;'.$bill_no.'</td>
		<td>Date :'.$bill_entry_date.'</td>
		</tr>
		<tr>
		<td>Grant No. &nbsp;:</td>
		<td>Token/T.V. No. &nbsp;:</td>
		<td width="134">&nbsp;</td>
		<td width="119">Date&nbsp;:&nbsp;</td>
		</tr>
		<tr>
		<td>Head of Account Code&nbsp;:&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
		<td>Office&nbsp;:&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
		<td colspan="4">&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Received the sum of Rs.&nbsp;'.$gross_total.'  (Rupees &nbsp;<b>'.$amt_gross_total.'</b>&nbsp;only) being the grant-in-aid _______________________ For the period from&nbsp;'.$month.'-'.$report_year.'.For the purpose of _________________________________________________________________ sanctioned by ____________________________ in his order no. __________ dated ____________ (copy enclosed) by Account Payee Cheque in favour of ____________________________________________</td>
		</tr>
		<!--<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
		<td>(A)&nbsp;&nbsp;DEDUCTION</td>
		<td>&nbsp;</td>
		<td colspan="2">&nbsp;</td>
		</tr>-->
		</table>
		</div>
		');
		
		
		/*$mpdf->WriteHTML('
		<div class="sig" style="font-family: Arial, Helvetica, sans-serif;  text-align: justify;">
		
		<table width="750" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;">
		<tr>
		<td width="44" rowspan="2" align="center" class="colgray">Sl.No.</td>
		<td colspan="3" align="center"  class="colgray"><strong>Gross Bill Amount</strong></td>
		<td width="114" align="right"  class="colgray">Rs.</td>
		<td width="134" align="right"  class="colgray"><strong>'.$gross_total.'</strong></td>
		</tr>
		<tr>
		<td colspan="3" align="center"  class="colgray" ><strong>Deduction</strong></td>
		<td width="114" align="center"  class="colgray" >Head of Account</td>
		<td width="134" rowspan="4" align="right" valign="bottom"  ><strong>'.$deduct_total.'</strong></td>
		</tr>
		<tr>
		<td width="44" align="right">1.</td>
		<td width="78" align="left"  class="colgray">P.F.</td>
		<td width="50" align="right"> Rs.</td>
		<td width="100" align="left"><strong>'.$gpf.'</strong></td>
		<td width="114" align="center">"8336"</td>
		</tr>
		<tr>
		<td width="44" align="right">2.</td>
		<td width="78" align="left"  class="colgray">P.Tax.</td>
		<td width="50" align="right"> Rs.</td>
		<td width="100" align="left"><strong>'.$data[0]['p_tax'].'</strong></td>
		<td width="114" align="center">"0028"</td>
		</tr>
		<tr>
		<td width="44" align="right">3.</td>
		<td width="78" align="left"  class="colgray">I.Tax.</td>
		<td width="50" align="right"> Rs.</td>
		<td width="100" align="left"><strong>'.$data[0]['i_tax'].'</strong></td>
		<td width="114" align="center">"8658"</td>
		</tr>
		<tr>
		<td colspan="4" align="center"  class="colgray"><strong>NET</strong></td>
		<td width="114" align="right"  class="colgray">Rs.</td>
		<td width="134" align="right"  class="colgray"><strong >'.$data[0]['net'].'</strong></td>
		</tr>
		</table>
		<br/>
		
		</div>');*/
		
		
		
		$mpdf->WriteHTML('
		<div class="one" style="padding-left: 15px; padding-right: 10px;">
		<table width="736" border="0" cellpadding="0" cellspacing="0">
		<!--<tr>
		<td width="103" align="left"> (B)</td>
		<td colspan="6" align="left"><strong><em>Please issue A/C Payee Cheque in favour of <u>'.$data[0]['lead_bank'].' , '.$data[0]['lead_branch'].'</u> for Rs.<u>'.$data[0]['net'].' (Rupees&nbsp; '.$amt_net.' &nbsp;only)</u></em></strong></td>
		</tr>-->
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td width="103" align="left">Certified that</td>
		<td colspan="6" align="left">(a)&nbsp;<em>The amount of this bill was not drawn earlier and it agrees withthat in the office copy of this bill.</em></td>
		</tr>
		<tr>
		<td width="103" align="left">&nbsp;</td>
		<td colspan="6" align="left">(b)&nbsp;<em>The utilization report in respect of the previous grant has been furnished and accepted by the sanctioning authority.</em></td>
		</tr>
		<tr>
		<td width="103" align="left">&nbsp;</td>
		<td colspan="6" align="left">(c)<em>&nbsp;The utilization report in respect of the present amount will be furnished to the sanctioning authority in due course.</em></td>
		</tr>
		<tr>
		<td colspan="7" align="left">Sanction: </td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td width="103" align="left">Dated:</td>
		<td width="391" align="right">Signature of Officer of the grantee organisation</td>
		<!--<td width="242" align="right">'.$data[0]['desig_dpsc'].',<br />'.$data[0]['address_line2'].'&nbsp;</td>-->
		</tr>
		
		</table>
		<table width="736" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td colspan="4" align="left">Counter signed for Rs.<em> <strong>&nbsp;'.$gross_total.'  (Rupees &nbsp;<b>'.$amt_gross_total.'
		</strong>&nbsp;only)/ Pay by transfer to_____________________</em></td>
		</tr>
		<tr>
		<td colspan="4" height="20" align="left" valign="bottom">Station: &nbsp;'.$data[0]['subdiv_name'].'</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td width="131" align="left" height="24" valign="bottom">Dated:</td>
		<td width="191" align="left" height="24" valign="bottom">&nbsp;</td>
		<td width="87" align="center" height="24" valign="bottom">&nbsp;</td>
		<td width="327" align="right" height="24" valign="bottom">Signature of the D.D.O</td>
		</tr>
		
		<tr>
		<td width="191" align="left" >Bill Clerk</td>
		<td width="87" align="left" >Accountant</td>
		<td width="327" align="right" colspan="2" >B.D.O '.$_SESSION['location']['block_name'].",".$_SESSION['location']['district_name'].'<br/> '.$district[0]['district_name'].'</td>
		</tr>
		</table>
		
		<table width="740" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td width="660" colspan="3" align="center"><p><strong>-------------------</strong><strong>-------------------------<u>For use in Treasury</u>--------------------------------------------</strong></p></td>
		</tr>
		<tr>
		<td width="660" colspan="3" align="left">Pay Rs. <u>'.$data[0]['net'].' (Rupees&nbsp; '.$amt_net.'&nbsp;only)</u> /by transfer/ credit to _________________</td>
		</tr>
		<tr>
		<td width="660" colspan="3" align="left">Examined and Entered</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td width="220" align="left" height="23" valign="bottom">Accountant / J.A.O.</td>
		<td width="440" colspan="2" align="right" height="23" valign="bottom">T.O. .A.T.O./P.A.O./A.P.A.O.</td>
		</tr>
		
		<tr> 
		<td width="660" colspan="3" align="center"><p>&nbsp;</p>
		<p><strong>---------<u>For use in the Office of the Accountant General (Audit), West Bengal</u>----------------------</strong></p></td>
		</tr>
		<tr>
		
		<td width="220" align="left">Admitted for Rs.</td>
		<td width="220" align="center">&nbsp;</td>
		</tr>
		<tr>
		
		<td width="220" align="left">Objected to Rs.</td>
		<td width="220" align="center">&nbsp;</td>
		</tr>
		<tr>
		
		<td width="220" align="left">Reason of Objection:</td>
		<td width="220" align="center">&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		
		<tr>
		<td width="220" height="19" align="left" valign="bottom">Auditor</td>
		<td width="220" height="19" align="center" valign="bottom">S.O. / A.A.O.</td>
		<td width="220" height="19" align="right" valign="bottom">Audit Officer</td>
		</tr>
		</table>
		</div></div></body>');
		
		
		
		
		/*$mpdf->WriteHTML('<div class="sig" >
		<table width="750" border="1" cellpadding="0" cellspacing="0" >
		<tr>
		<td width="60" align="center"  class="heading">Sl.No.</td>
		<td width="200"  align="center"  class="heading">Name of the Officer with <br/>Designation</td>
		<td width="180"  align="center"  class="heading">Amount Deducted <br/>(Rs)</td>
		<td width="120" align="center"  class="heading">PAN No.</td>
		<td width="120" align="center"  class="heading">REMARKS</td>
		</tr>
		<tr>
		<td width="60" align="center">&nbsp;</td>
		<td width="200"  align="center">&nbsp;BLOCK,<br/>'.$district[0]['district_name'].'</td>
		<td width="180" align="center">'.$data[0]['i_tax'].'</td>
		<td width="120" align="center">&nbsp;</td>
		<td width="120" align="center">&nbsp;</td>
		</tr>
		</table></div>');
		
		
		
		$mpdf->WriteHTML('<div class="three" style="margin-top: 80px; font-family: Arial, Helvetica, sans-serif; font-size:14px;">
		<table width="750">
		<tr>
		<td width="425">&nbsp;</td>
		<td width="323">&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td align="right">_______________________________________________</td>
		</tr>
		<!--<tr>
		<td valign="top">Salary Head Code:  &nbsp;</td>
		<td align="right">'.$data[0]['desig_block'].',<br />'.$data[0]['address_line2'].'
		</tr>-->
		<tr>
		<td>&nbsp;</td>
		<td align="right">&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td align="right">&nbsp;</td>
		</tr>
		<tr>
		<td>Bill Clerk/ Accountant</td>
		<td align="right">&nbsp;</td>
		</tr>
		</table>
		</div>');*/
		
		
		$mpdf->Output('T R From No. 31 of '.ucwords(strtolower($_SESSION['location']['district_name'])).'.pdf', 'D');
		
	}
	else
	{
	header('Location: '. $config['base_url'] ."page/intra_prd/block/download_treasury_bill.php?msg=1");
	exit;}	
}
?>