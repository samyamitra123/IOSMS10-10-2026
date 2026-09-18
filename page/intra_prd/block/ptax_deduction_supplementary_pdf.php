<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
error_reporting(0);
if($_SERVER['HTTP_REFERER']=='')
{
	header("Location:../../dashboard.php");
}
?>

<?
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])

)
{
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
$cryptograph=new cryptography();
$fiyear=$cryptograph->decode($_GET['report_year'],4);
$fimonth=$cryptograph->decode($_GET['report_month'],4);
$report_month=$fimonth;
$fiyear2=$fiyear+1;
$c_year=date("Y");
$c_month=date("m");
$sal_momth_year=$fiyear.$fimonth;
$district_sub=explode('-',$_SESSION['location']['district_name']);
$sub=explode('(',$district_sub['1']);

$bill_no=isset($_GET['bill_no'])?$_GET['bill_no']:'';
$bill_entry_date=isset($_GET['bill_date'])?$_GET['bill_date']:'';
$bill_no=$cryptograph->decode($bill_no, 4);
$bill_entry_date=$cryptograph->decode($bill_entry_date, 4);

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


if(($fimonth!="") || ($fimonth!="")|| ($fimonth!=""))
{ 
	$report_year=$fiyear;
	$report_month=$fimonth;
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
}

$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='407'");
$requisition_type=$requisition[0]['code'];

$prd_dise_admin = $db->fetch_table("select * from prd_dise_admin where block_code = '".$_SESSION['location']['block_code']."'		
");


if($fiyear==$c_year && $fimonth==$c_month)
{
	$salary_total_sql = $db->fetch_table("
											SELECT SUM(p_tax) AS p_tax FROM prd_employee_salary_save sal
											inner join prd_employee_master emp on emp.emp_id_pk=sal.emp_id_fk 
											WHERE salary_monthyear='".$sal_momth_year."' AND 
											block_code='".$_SESSION['location']['block_code']."' AND status_flag='3' AND 		
											category_id='1' AND delete_status='1' AND is_saved='1' AND emp.emp_status in ('1','9')
											AND requisition_type='".$requisition_type."'
	");
}
else
{
	$salary_total_sql = $db->fetch_table("
											SELECT SUM(p_tax) AS p_tax FROM prd_monthly_salary_archive_final sal
											inner join prd_employee_master emp on emp.emp_id_pk=sal.emp_id_fk 
											WHERE salary_monthyear='".$sal_momth_year."' AND
											block_code='".$_SESSION['location']['block_code']."' AND status_flag='3' 
											AND delete_status='1' AND emp.emp_status in ('1','9')
											AND requisition_type='".$requisition_type."'
	
	");
}



if(count($salary_total_sql)>0 && !empty($salary_total_sql[0]['p_tax']))
{
	unset($_SESSION['nodata']);					
	
	$date_array=explode('+', $data1[0]['latestupdate_time']);
	$date_time=$date_array[0];
	
	//-----------------------------------------------------------------------------------------------------------------------------
	define("_MPDF_TEMP_PATH", '../../../locker/temp/');
	include ('../../../includes/third-party/mpdf/mpdf.php');
	$mpdf=new mPDF("en-GB-x","A4","","",10,10,10,10,6,3);
	
	
	$stylesheet = file_get_contents($config['base_url'].'themes/default/css/mpdf_bill_tr31.css');
	$mpdf->WriteHTML($stylesheet,1);
	
	
	
	$mpdf->WriteHTML('
	<table width="700" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="7" align="center"><strong>FORM IV OF W.B. STATE TAX ON PROFESSIONS etc Act,1979</strong></td>
		</tr>
		<tr>
			<td colspan="7" align="center">(Sec. Sub-Rule(I) of T.R. 4.080)</td>
		</tr>
		<tr>
			<td colspan="7" align="center">Statement of recovery under the West Bengal State Tax on Professions,Trades,Callings and Employments Act, 1979</td>
		</tr>
		<tr>
			<td colspan="7"  align="center">(West Bengal Act,VI of 1979)</td>
		</tr>
		<tr>
			<td colspan="7" align="center">'.$_SESSION['location']['block_name'].",".$_SESSION['location']['district_name'].','.$month."'".$fiyear.'</td>
		</tr>
		<tr>
			<td width="200" height="40" align="left" valign="bottom">BLOCK CODE&nbsp;:-'.$prd_dise_admin['0']['treasury_block_code'].'</td>
			<td width="70" colspan="2" height="40" align="left" valign="bottom">&nbsp;</td>
			<td width="70" height="40" align="right" valign="bottom">Bill No. &nbsp;:&nbsp;'.$bill_no.'</td>
			<td width="50" height="40" align="left" valign="bottom">&nbsp;</td>
			<td width="150" height="40" align="right" valign="bottom">Date :&nbsp;'.$bill_entry_date.'</td>
			<td width="100" align="left">&nbsp;</td>
		</tr>
		<tr>
			<td width="120" align="left">Grant No. &nbsp;:</td>
			<td width="100" colspan="2" align="left">&nbsp;</td>
			<td width="100" align="right">Token/T.V. No.:</td>
			<td width="100" align="left">&nbsp;</td>
			<td width="150" align="right" valign="bottom">Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td width="100" align="left">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="7" align="left">Head of Account Code :  0028-00-107-001-03&nbsp;</td>
		</tr>
	</table>
	<div class="sig">
		<table width="700"  cellpadding="0" cellspacing="0" border="1">
			<tr>
				<td width="100" align="center">Name of Deptt.</td>
				<td width="140" align="center">Name of Account under which salaries are drawn</td>
				<td width="120" align="center">Period of Salary bill</td>
				<td width="100" align="center">Amount recovered (Rs.)</td>
				<td width="200" align="center">To be credited to 0028-Other taxes on income and expenditure-00-107-Taxes on Prefessions, Trades, Callings &amp; Employments</td>
			</tr>
			<tr>
				<td width="100" align="center">1</td>
				<td width="140" align="center">2</td>
				<td width="120" align="center">3</td>
				<td width="100" align="center">4</td>
				<td width="200" align="center">5</td>
			</tr>
			<tr>
				<td width="100" height="30" valign="bottom" align="center"><strong>Panchayat &amp; Rural Development</strong></td>
				<td width="140" height="30" valign="bottom" align="center">&nbsp;</td>
				<td width="120" height="30" valign="bottom" align="center"><strong>'.$month."'".$fiyear.'</strong></td>
				<td width="100" height="30" valign="bottom" align="center"><strong>'.$salary_total_sql[0]['p_tax'].'</strong></td>
				<td width="200" height="30" valign="bottom" align="center"><strong>&nbsp;</strong></td>
			</tr>
		</table>
	</div>
	<table width="660" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" align="left" height="40" valign="bottom">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="left">Rs.&nbsp;'.$salary_total_sql[0]['p_tax'].'</td>
		</tr>
		<tr>
			<td colspan="2" align="left">Rupees (in words) :&nbsp;'.number_to_words($salary_total_sql[0]['p_tax']).'&nbsp;only</td>
		</tr> 
		<tr>
			<td colspan="2" align="left">Signature:&nbsp;</td>
		</tr>
		<tr>
			<td width="330" align="right" height="35" valign="bottom">_________________________</td>
			<td width="330" align="right" height="35" valign="bottom">_________________________</td>
		</tr> 
		<tr>
			<td width="330" align="right">Bill Clerk /Accountant</td>
			<td width="330" align="right">' .$prd_dise_admin['0']['block_dpsc'].'&nbsp;</td>
		</tr>
		<tr>
			<td width="330" align="right">&nbsp;</td>
			<td width="330" align="right">
			'.$prd_dise_admin['0']['address_line2'].'
			</td>
		</tr>
	</table>');				
}
else
{
	define("_MPDF_TEMP_PATH", '../../../locker/temp/');
	include ('../../../includes/third-party/mpdf/mpdf.php');
	$mpdf=new mPDF("en-GB-x","A4","","",10,10,10,10,6,3);
	$mpdf->WriteHTML('NO DATA FOUND');
}		

$mpdf->SetFooter('www.wbprd.gov.in |{PAGENO}');

$mpdf->Output('P Tax for '.ucwords(strtolower($_SESSION['location']['block_name'])).'.pdf', 'D');